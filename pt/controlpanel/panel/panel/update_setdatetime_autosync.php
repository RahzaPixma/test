<?php
$val_select = $_REQUEST['set_autosync'];

if( $val_select === 'Ya')
  shell_exec('sudo echo "1" > /var/www/html/pt/controlpanel/panel/panel/chktime/flagsync.dat');
else 
  shell_exec('sudo echo "0" > /var/www/html/pt/controlpanel/panel/panel/chktime/flagsync.dat');

echo 'Autosync = ' . $val_select;
?>
