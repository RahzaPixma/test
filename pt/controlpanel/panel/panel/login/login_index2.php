<!DOCTYPE html>
<html lang="en">
    <head>
		<meta charset="UTF-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"> 
		<meta name="viewport" content="width=device-width, initial-scale=1.0"> 
        <title>IIM Welcome Screen</title>
        <meta name="description" content="IIM Welcome Screen" />
        <meta name="keywords" content="css3, login, form, custom, input, submit, button, html5, placeholder" />
        <meta name="author" content="Codrops" />
        <link rel="shortcut icon" href="../favicon.ico"> 
        <link rel="stylesheet" type="text/css" href="css/style.css" />
		<script src="js/modernizr.custom.63321.js"></script>
		<!--[if lte IE 7]><style>.main{display:none;} .support-note .note-ie{display:block;}</style><![endif]-->
		
<!--	    <script type="text/javascript" src="../../../jquery-1.6.min.js"></script>  -->
<!--	    <script type="text/javascript" src="js/login2.js"></script>	-->
	
		
		<style>	
/* latin */
@font-face {
  font-family: 'Montserrat';
  font-style: normal;
  font-weight: 400;
  src: local('Montserrat-Regular'), url(font/zhcz-_WihjSQC0oHJ9TCYPk_vArhqVIZ0nv9q090hN8.woff2) format('woff2');
  unicode-range: U+0000-00FF, U+0131, U+0152-0153, U+02C6, U+02DA, U+02DC, U+2000-206F, U+2074, U+20AC, U+2212, U+2215, U+E0FF, U+EFFD, U+F000;
}
/* latin */
@font-face {
  font-family: 'Montserrat';
  font-style: normal;
  font-weight: 700;
  src: local('Montserrat-Bold'), url(font/IQHow_FEYlDC4Gzy_m8fcoWiMMZ7xLd792ULpGE4W_Y.woff2) format('woff2');
  unicode-range: U+0000-00FF, U+0131, U+0152-0153, U+02C6, U+02DA, U+02DC, U+2000-206F, U+2074, U+20AC, U+2212, U+2215, U+E0FF, U+EFFD, U+F000;
}		
			body {
				background: #eedfcc url(images/bg3.jpg) no-repeat center top;
				-webkit-background-size: cover;
				-moz-background-size: cover;
				background-size: cover;
			}
			.container > header h1,
			.container > header h2 {
				color: #fff;
				text-shadow: 0 1px 1px rgba(0,0,0,0.5);
			}
		</style>
    </head>
    <body>
        <div class="container">
		
			<header>
			
				<h1>IIM 5.5 Admin Control Panel</h1>
				<h2>Perfect Time Solution</h2>
				
				<nav class="codrops-demos">
				</nav>

				<div class="support-note">
					<span class="note-ie">Sorry, only modern browsers.</span>
				</div>

				<h1><strong>Login<strong></h1>
				
			</header>
							
			<section class="main">
				<form class="form-5 clearfix" action="login_biasa.php">
					<p>
						<input type="text" name="login" placeholder="Username" id="txt_username">
						<input type="password" name="password" placeholder="Password" id="txt_password">
					</p>

<!--				    <button type="text" name="btn" id="btnLogin" onclick="check_login();"> -->
				<button type="submit" name="btn" id="btnLogin" value="submit_btn">
				    	<i class="icon-arrow-right"></i>
				    	<span>Sign in</span>
				</button>  										
				</form>				
				
				
				
		<?php 
			if( isset($_GET['error']) ) {
			?>
			<div id="id_error" align="center">
				<div class="error" align="left">
						<strong>Password Salah!</strong>
				</div>
			</div>
			<?php } ?>
			</section>
			
        </div>
		<!-- jQuery if needed -->
        <script type="text/javascript" src="js/jquery.min.js"></script>
        <script type="text/javascript" src="js/jquery.placeholder.min.js"></script>
		<script type="text/javascript">
		$(function(){
			$('input, textarea').placeholder();
		});
		</script>
    </body>
</html>