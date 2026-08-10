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
    echo "Missing required file: $1"
    exit 1
  fi
}

check_command() {
  label=$1
  bin=$2
  if command -v "$bin" >/dev/null 2>&1; then
    printf '   [OK] %s found: %s\n' "$label" "$(command -v "$bin")"
    return 0
  fi
  printf '   [FAIL] %s not found: %s\n' "$label" "$bin"
  return 1
}

check_any_command() {
  label=$1
  shift
  for bin in "$@"; do
    if command -v "$bin" >/dev/null 2>&1; then
      printf '   [OK] %s found: %s (%s)\n' "$label" "$bin" "$(command -v "$bin")"
      return 0
    fi
  done
  printf '   [FAIL] %s not found. Checked: %s\n' "$label" "$*"
  return 1
}

check_service_hint() {
  service_name=$1
  if command -v systemctl >/dev/null 2>&1; then
    if systemctl list-unit-files "$service_name.service" >/dev/null 2>&1; then
      printf '   [OK] service unit exists: %s.service\n' "$service_name"
      return 0
    fi
  fi
  if command -v service >/dev/null 2>&1; then
    if service "$service_name" status >/dev/null 2>&1; then
      printf '   [OK] service responds: %s\n' "$service_name"
      return 0
    fi
  fi
  return 1
}

check_web_server() {
  if check_any_command 'web server binary' apache2 httpd nginx; then
    return 0
  fi
  if check_service_hint apache2 || check_service_hint httpd || check_service_hint nginx; then
    return 0
  fi
  printf '   [FAIL] Apache/Nginx not detected. Install apache2/httpd or nginx before running this app.\n'
  return 1
}

check_database_server() {
  if check_service_hint mariadb || check_service_hint mysql; then
    return 0
  fi
  if command -v mysqladmin >/dev/null 2>&1; then
    if mysqladmin -h "$DB_HOST" ping >/dev/null 2>&1; then
      printf '   [OK] MySQL/MariaDB server responds on host: %s\n' "$DB_HOST"
      return 0
    fi
  fi
  printf '   [FAIL] MySQL/MariaDB server not detected/responding. Install/start mariadb-server or mysql-server.\n'
  return 1
}

run_preflight_checks() {
  printf '==> Preflight checks (step-by-step)\n'
  errors=0

  printf '1/8 Checking required project files\n'
  if [ -f "$SCHEMA_FILE" ] && [ -f "$CRON_FILE" ]; then
    printf '   [OK] schema and cron files exist\n'
  else
    printf '   [FAIL] required schema or cron file missing\n'
    errors=$((errors + 1))
  fi

  printf '2/8 Checking Apache/Nginx installation\n'
  check_web_server || errors=$((errors + 1))

  printf '3/8 Checking PHP CLI\n'
  if [ "$UPDATE_DB_CONFIG" = "1" ]; then
    check_command 'php CLI' php || errors=$((errors + 1))
  else
    printf '   [SKIP] UPDATE_DB_CONFIG=0, PHP CLI is not required for config rewrite\n'
  fi

  printf '4/8 Checking configured PHP binary for cron\n'
  if [ -x "$PHP_BIN" ]; then
    printf '   [OK] PHP_BIN is executable: %s\n' "$PHP_BIN"
  else
    printf '   [FAIL] PHP_BIN is not executable: %s\n' "$PHP_BIN"
    errors=$((errors + 1))
  fi

  printf '5/8 Checking MySQL/MariaDB client\n'
  check_command 'mysql client' "$MYSQL_BIN" || errors=$((errors + 1))

  printf '6/8 Checking MySQL/MariaDB server\n'
  check_database_server || errors=$((errors + 1))

  printf '7/8 Checking cron/crontab\n'
  if [ "$INSTALL_CRON" = "1" ]; then
    check_command 'crontab' crontab || errors=$((errors + 1))
  else
    printf '   [SKIP] INSTALL_CRON=0, crontab is not required\n'
  fi

  printf '8/8 Checking application connection files\n'
  if [ "$UPDATE_DB_CONFIG" = "1" ]; then
    if [ -f "$CONN_FILE" ] && [ -f "$CONN_CLI_FILE" ]; then
      printf '   [OK] connection files exist\n'
    else
      printf '   [FAIL] conn.php or conn_cli.php missing\n'
      errors=$((errors + 1))
    fi
  else
    printf '   [SKIP] UPDATE_DB_CONFIG=0, connection files will not be modified\n'
  fi

  if [ "$errors" -gt 0 ]; then
    printf '\nPreflight completed with %s problem(s). Fix the items above and run again.\n' "$errors"
    exit 1
  fi
  printf 'Preflight completed successfully.\n\n'
}

run_preflight_checks

require_file "$SCHEMA_FILE"
require_file "$CRON_FILE"

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
