<?php
$val_select = $_REQUEST['set_autoreboot'];

if( $val_select === 'Ya')
  shell_exec('sudo echo "1" > /var/www/html/pt/controlpanel/panel/panel/chktime/flagreboot.dat');
else 
  shell_exec('sudo echo "0" > /var/www/html/pt/controlpanel/panel/panel/chktime/flagreboot.dat');

echo 'Autoreboot = ' . $val_select;
?>
