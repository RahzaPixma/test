# Restore database and cron

This project expects a MySQL/MariaDB database named `pt` and a small maintenance cron.

## Database

Import the baseline schema and seed data:

```sh
mysql -u root -p < database/pt_schema.sql
```

The application connection files currently use database `pt` on `localhost` with user `root`.

If you have a newer prayer-time dump, import it after the schema. The existing dump format under `pt/controlpanel/panel/panel/tmpuploadfile/ext/taqwim.sql` inserts rows into the `taqwim` table.

## Cron

Install the project cron:

```sh
crontab cron/pt-crontab
```

The cron removes expired one-off `template_kuliah` records every day and ensures the `flagsync.dat` file exists after reboot.
