<?php
//header("Location: /pt/slides/src/slide.php?first=1");
//exit;
?>

<html>
<body>
<div id="timer_div"></div>
<?php
$val = '';
						$flag = trim(file_get_contents('/var/www/html/pt/controlpanel/panel/panel/chktime/flagsync.dat'));

						if (strlen($flag)){
							if( $flag === '1') {
								$val='60000';
							} 
							else {
								$val='1000';
							}
						} else {
							$val='1000';
						}
?>
<script>
			
			setTimeout('move()',<?php echo $val;?>);
			var seconds_left = 60;
			var interval = setInterval(function() {
    				document.getElementById('timer_div').innerHTML = "<h3>Synchronizing time to internet/satellite..... tunggu...." + --seconds_left + "</h3>";
    				if (seconds_left <= 0)
    				{
       					document.getElementById('timer_div').innerHTML = "<h3>Siap!</h3>";
       					clearInterval(interval);
    				}
			}, 1000);


			function move() {
			     window.location = '/pt/slides/src/slide.php?first=1';
			}
			
</script>
</body>
</html>