<html>
<body>
<h1>Test Sound...</h1>
<?php
//echo to show message output
//echo shell_exec('omxplayer -o hdmi ../sound/beepbeep.mp4'); //success
echo shell_exec('omxplayer -o hdmi /var/www/html/pt/sound/beepsolat.mp4'); 
//echo shell_exec('omxplayer -o hdmi /var/www/pt/sound/beepbeep.mp4'); 

?>
</body>
</html>