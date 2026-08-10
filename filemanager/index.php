<?php
session_start();
if(isset($_GET['logout']) AND $_GET['logout']=='1'){
    unset($_SESSION['login']);
    session_destroy();
}
if(!isset($_SESSION['login'])){
    header('location: ../pt/controlpanel/panel/panel/login/login_index2.php');
}
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>IIM Ver 5.0 File Manager</title>
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=2" />

		<!-- Section CSS -->
		<!-- jQuery UI (REQUIRED) -->
		<link rel="stylesheet" type="text/css" href="code.jquery.com/ui/1.12.1/themes/smoothness/jquery-ui.css">

		<!-- elFinder CSS (REQUIRED) -->
		<link rel="stylesheet" type="text/css" href="css/elfinder.min.css">
		<link rel="stylesheet" type="text/css" href="css/theme.css">

		<!-- Section JavaScript -->
		<!-- jQuery and jQuery UI (REQUIRED) -->
		<!--[if lt IE 9]>
		<script src="code.jquery.com/jquery-1.12.4.min.js"></script>
		<![endif]-->
		<!--[if gte IE 9]><!-->
		<script src="code.jquery.com/jquery-3.1.1.min.js"></script>
		<!--<![endif]-->
		<script src="code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>

		<!-- elFinder JS (REQUIRED) -->
		<script src="js/elfinder.min.js"></script>

		<!-- GoogleDocs Quicklook plugin for GoogleDrive Volume (OPTIONAL) -->
		<!--<script src="js/extras/quicklook.googledocs.js"></script>-->

		<!-- elFinder translation (OPTIONAL) -->
		<!--<script src="js/i18n/elfinder.ru.js"></script>-->

		<!-- elFinder initialization (REQUIRED) -->
		<script type="text/javascript" charset="utf-8">


		
elFinder.prototype.commands.home = function() {
          this.exec = function(hashes) {
                //implement what the custom command should do here
				//alert('sdjfdsjknsjl');
				window.location = "../admin/";
          }
          this.getstate = function() {
                //return 0 to enable, -1 to disable icon access
                return 0;
          }
}		
		

			// Documentation for client options:
			// https://github.com/Studio-42/elFinder/wiki/Client-configuration-options
			$(document).ready(function() {
				$('#elfinder').elfinder({
					url : 'php/connector.minimal.php',  // connector URL (REQUIRED)

        uiOptions : {
		// toolbar configuration
		toolbar : [
			['home'],
			['back', 'forward'],
			//['netmount'],
			// ['reload'],
			// ['home', 'up'],
			['mkdir', 'mkfile'],
			//['download', 'getfile'],
			['upload','download'],
			//['chmod'],
			//['quicklook'],
			['copy', 'cut', 'paste'],
			['rm'],
			['duplicate', 'rename', 'edit', 'resize'],
			['extract', 'archive'],
			['search'],
			['view', 'sort'],
			['help'],
			['quicklook','fullscreen'],
			// extra options
			{
				// also displays the text label on the button (true / false)
				displayTextLabel: true,
				// Exclude `displayTextLabel` setting UA type
				labelExcludeUA: ['Mobile'],
				// auto hide on initial open
				autoHideUA: ['Mobile']
			}
		],
}


					// , lang: 'ru'                    // language (OPTIONAL)
				});
			});
		</script>
	</head>
	<body>

		<!-- Element where elFinder will be created (REQUIRED) -->
		<div id="elfinder"></div>

	</body>
</html>
