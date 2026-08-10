# Panduan Install / Restore Prayer Time Display

Dokumen ini menerangkan cara restore aplikasi termasuk database, username/password MySQL, cron job, dan data waktu solat.

## 1. Keperluan server

Pastikan server ada komponen berikut:

- Web server PHP seperti Apache/Nginx + PHP.
- MySQL atau MariaDB server.
- MySQL/MariaDB client command `mysql`.
- `cron` / `crontab`.
- Untuk Raspberry Pi lama yang guna audio/video, pastikan binary seperti `omxplayer` masih tersedia jika diperlukan oleh fail PHP sedia ada.

## 2. Fail penting yang disediakan

- `database/pt_schema.sql` — create database `pt`, table-table aplikasi, dan seed config asas.
- `cron/pt-crontab` — cron block rasmi project.
- `scripts/install.sh` — auto installer untuk import DB, create/grant DB user, update PHP DB config, dan merge/update cron sedia ada.

## 3. Quick install automatik

Jalankan dari root project:

```sh
./scripts/install.sh
```

Default installer akan guna nilai berikut:

| Setting | Default |
| --- | --- |
| MySQL admin user untuk import | `root` |
| MySQL admin password | kosong / socket auth |
| DB host | `localhost` |
| DB name aplikasi | `pt` |
| DB username aplikasi | `pt_user` |
| DB password aplikasi | `pt_password` |
| Web root server | `/var/www/html` |
| PHP binary untuk cron | `/usr/bin/php` |

> Nota keselamatan: tukar `DB_PASS` kepada password sebenar sebelum production.

Contoh install dengan password custom:

```sh
MYSQL_ADMIN_USER=root \
MYSQL_ADMIN_PASS='password-root-mysql' \
DB_HOST=localhost \
DB_NAME=pt \
DB_USER=pt_user \
DB_PASS='password-kuat-di-sini' \
APP_WEB_ROOT=/var/www/html \
PHP_BIN=/usr/bin/php \
./scripts/install.sh
```

Installer akan:

1. Import `database/pt_schema.sql`.
2. Create user MySQL aplikasi jika `DB_USER` bukan `root`.
3. Grant akses penuh kepada database `pt` untuk user aplikasi.
4. Update `pt/controlpanel/panel/panel/conn.php` dan `pt/controlpanel/panel/panel/conn_cli.php` dengan username/password DB yang dipilih.
5. Simpan backup fail connection dengan suffix `.bak`.
6. Update cron sedia ada secara selamat dengan mengganti block `# BEGIN prayer-time-display` hingga `# END prayer-time-display` sahaja.

## 4. Install tanpa ubah connection file

Jika mahu import DB dan cron sahaja, tanpa ubah `conn.php` / `conn_cli.php`:

```sh
UPDATE_DB_CONFIG=0 ./scripts/install.sh
```

## 5. Install tanpa cron

Jika mahu handle cron manual:

```sh
INSTALL_CRON=0 ./scripts/install.sh
```

## 6. Import data taqwim / waktu solat

Schema baseline create table `taqwim`, tetapi data waktu solat sebenar ikut lokasi/tahun perlu diimport.

Project ini ada contoh dump di:

```text
pt/controlpanel/panel/panel/tmpuploadfile/ext/taqwim.sql
```

Untuk import contoh dump sekali semasa install:

```sh
IMPORT_SAMPLE_TAQWIM=1 ./scripts/install.sh
```

Atau import manual selepas schema siap:

```sh
mysql -u root -p pt < pt/controlpanel/panel/panel/tmpuploadfile/ext/taqwim.sql
```

Jika ada dump terbaru daripada sistem/portal waktu solat, import fail tersebut selepas `database/pt_schema.sql`.

## 7. Manual database restore

Jika tidak mahu guna installer:

```sh
mysql -u root -p < database/pt_schema.sql
```

Create user aplikasi secara manual:

```sql
CREATE USER IF NOT EXISTS 'pt_user'@'localhost' IDENTIFIED BY 'password-kuat-di-sini';
GRANT ALL PRIVILEGES ON `pt`.* TO 'pt_user'@'localhost';
FLUSH PRIVILEGES;
```

Kemudian update dua fail connection supaya sama dengan credential DB:

```text
pt/controlpanel/panel/panel/conn.php
pt/controlpanel/panel/panel/conn_cli.php
```

## 8. Manual cron install / update

Jika tidak guna installer, install cron rasmi project:

```sh
crontab cron/pt-crontab
```

Untuk server yang sudah ada cron lain, jangan overwrite semua cron. Lebih selamat guna installer kerana ia merge cron sedia ada dan hanya replace block project.

Cron project melakukan dua perkara:

- Setiap hari jam `00:05`, jalankan `script_autodelete.php` untuk buang rekod kuliah one-off lama.
- Semasa reboot, pastikan folder `chktime` dan fail `flagsync.dat` wujud.

## 9. Semakan selepas install

Semak table wujud:

```sh
mysql -u pt_user -p pt -e 'SHOW TABLES;'
```

Semak cron project:

```sh
crontab -l | sed -n '/BEGIN prayer-time-display/,/END prayer-time-display/p'
```

Semak connection PHP:

```sh
php -r "include 'pt/controlpanel/panel/panel/conn_cli.php'; echo 'DB OK'.PHP_EOL;"
```
