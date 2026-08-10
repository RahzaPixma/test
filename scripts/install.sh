#!/bin/sh
set -eu

PROJECT_ROOT=$(CDPATH= cd -- "$(dirname -- "$0")/.." && pwd)
SCHEMA_FILE="$PROJECT_ROOT/database/pt_schema.sql"
CRON_FILE="$PROJECT_ROOT/cron/pt-crontab"
CONN_FILE="$PROJECT_ROOT/pt/controlpanel/panel/panel/conn.php"
CONN_CLI_FILE="$PROJECT_ROOT/pt/controlpanel/panel/panel/conn_cli.php"

DB_HOST=${DB_HOST:-localhost}
DB_NAME=${DB_NAME:-pt}
DB_USER=${DB_USER:-pt_user}
DB_PASS=${DB_PASS:-pt_password}
MYSQL_ADMIN_USER=${MYSQL_ADMIN_USER:-root}
MYSQL_ADMIN_PASS=${MYSQL_ADMIN_PASS:-}
MYSQL_BIN=${MYSQL_BIN:-mysql}
PHP_BIN=${PHP_BIN:-/usr/bin/php}
APP_WEB_ROOT=${APP_WEB_ROOT:-/var/www/html}
INSTALL_CRON=${INSTALL_CRON:-1}
UPDATE_DB_CONFIG=${UPDATE_DB_CONFIG:-1}
IMPORT_SAMPLE_TAQWIM=${IMPORT_SAMPLE_TAQWIM:-0}
SAMPLE_TAQWIM_FILE=${SAMPLE_TAQWIM_FILE:-$PROJECT_ROOT/pt/controlpanel/panel/panel/tmpuploadfile/ext/taqwim.sql}

mysql_admin() {
  if [ -n "$MYSQL_ADMIN_PASS" ]; then
    "$MYSQL_BIN" -h "$DB_HOST" -u "$MYSQL_ADMIN_USER" -p"$MYSQL_ADMIN_PASS" "$@"
  else
    "$MYSQL_BIN" -h "$DB_HOST" -u "$MYSQL_ADMIN_USER" "$@"
  fi
}

sql_quote() {
  printf "%s" "$1" | sed "s/'/''/g"
}

require_file() {
  if [ ! -f "$1" ]; then
    echo "Missing required file: $1" >&2
    exit 1
  fi
}

require_file "$SCHEMA_FILE"
require_file "$CRON_FILE"

command -v "$MYSQL_BIN" >/dev/null 2>&1 || { echo "mysql client not found. Set MYSQL_BIN or install mysql/mariadb client." >&2; exit 1; }
if [ "$UPDATE_DB_CONFIG" = "1" ]; then
  command -v php >/dev/null 2>&1 || { echo "php CLI not found. Install php-cli or run with UPDATE_DB_CONFIG=0." >&2; exit 1; }
fi

printf '==> Importing database schema: %s\n' "$SCHEMA_FILE"
mysql_admin < "$SCHEMA_FILE"

if [ "$DB_USER" != "root" ]; then
  printf '==> Creating/granting application DB user: %s\n' "$DB_USER"
  DB_USER_SQL=$(sql_quote "$DB_USER")
  DB_PASS_SQL=$(sql_quote "$DB_PASS")
  DB_NAME_SQL=$(printf "%s" "$DB_NAME" | sed 's/`/``/g')
  mysql_admin <<SQL
CREATE USER IF NOT EXISTS '$DB_USER_SQL'@'localhost' IDENTIFIED BY '$DB_PASS_SQL';
GRANT ALL PRIVILEGES ON \`$DB_NAME_SQL\`.* TO '$DB_USER_SQL'@'localhost';
FLUSH PRIVILEGES;
SQL
fi

if [ "$UPDATE_DB_CONFIG" = "1" ]; then
  printf '==> Updating PHP DB connection files\n'
  for file in "$CONN_FILE" "$CONN_CLI_FILE"; do
    require_file "$file"
    cp "$file" "$file.bak"
    php -r '
      $file=$argv[1]; $host=$argv[2]; $user=$argv[3]; $pass=$argv[4]; $db=$argv[5];
      $data=file_get_contents($file);
      $replacement="mysqli_connect(".var_export($host,true).",".var_export($user,true).",".var_export($pass,true).",".var_export($db,true).")";
      $data=preg_replace("/mysqli_connect\\s*\\([^;]+\\)/", $replacement, $data, 1);
      file_put_contents($file, $data);
    ' "$file" "$DB_HOST" "$DB_USER" "$DB_PASS" "$DB_NAME"
  done
fi

if [ "$IMPORT_SAMPLE_TAQWIM" = "1" ]; then
  require_file "$SAMPLE_TAQWIM_FILE"
  printf '==> Importing sample taqwim data: %s\n' "$SAMPLE_TAQWIM_FILE"
  mysql_admin "$DB_NAME" < "$SAMPLE_TAQWIM_FILE"
fi

if [ "$INSTALL_CRON" = "1" ]; then
  printf '==> Installing/updating project cron entries\n'
  TMP_CRON=$(mktemp)
  EXISTING_CRON=$(mktemp)
  if crontab -l > "$EXISTING_CRON" 2>/dev/null; then
    sed '/# BEGIN prayer-time-display/,/# END prayer-time-display/d' "$EXISTING_CRON" > "$TMP_CRON"
  else
    : > "$TMP_CRON"
  fi
  {
    printf '\n'
    sed "s#/var/www/html#$APP_WEB_ROOT#g; s#/usr/bin/php#$PHP_BIN#g" "$CRON_FILE"
  } >> "$TMP_CRON"
  crontab "$TMP_CRON"
  rm -f "$TMP_CRON" "$EXISTING_CRON"
fi

printf '\nInstall completed.\n'
printf 'Database: host=%s name=%s user=%s password=%s\n' "$DB_HOST" "$DB_NAME" "$DB_USER" "$DB_PASS"
printf 'Backups of DB connection files use suffix .bak when UPDATE_DB_CONFIG=1.\n'
