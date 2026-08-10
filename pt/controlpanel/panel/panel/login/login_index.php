<!DOCTYPE html>
<html lang="en">
    <head>
		<meta charset="UTF-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"> 
		<meta name="viewport" content="width=device-width, initial-scale=1.0"> 
        <title>Login To IIM 5.5</title>
        <meta name="description" content="Login To IIM 5.5" />
        <link rel="stylesheet" type="text/css" href="css/style.css" />
		<script src="js/modernizr.custom.63321.js"></script>
		<!--[if lte IE 7]><style>.main{display:none;} .support-note .note-ie{display:block;}</style><![endif]-->
	    <script type="text/javascript" src="../../../jquery-1.6.min.js"></script>
        <script type="text/javascript" src="js/login.js"></script>	
		

	
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
				<form class="form-1">
					<p class="field">
						<input type="text" name="login" placeholder="Username" id="txt_username">
						<i class="icon-user icon-large"></i>
					</p>
						<p class="field">
							<input type="password" name="password" placeholder="Password" id="txt_password">
							<i class="icon-lock icon-large"></i>
					</p>
					<p class="submit">
						<button type="text" name="btn" id="btnLogin" onclick="check_login();"><i class="icon-arrow-right icon-large"></i></button>
					</p>
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
    </body>
</html>