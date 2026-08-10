<?php

$set_tarikh = $_POST["today_date"];

shell_exec('sudo date -s ' . '"' . $set_tarikh . '"');
shell_exec('sudo hwclock -w');
echo shell_exec('date');

?>
