<?php
session_start();
//echo "hsghgsdg";

if(isset($_GET['btn'])){
  //  $username = addslashes($_GET['var_usn']);
  //  $password = addslashes($_GET['var_pwd']);
//echo "msk 1";
    $username = $_GET['login'];
    $password = $_GET['password'];
	
   if( $username !== 'ajk' || $password !== 'ajkajk' ){
//        echo 'Username atau Password Salah !';
		header('location: /pt/controlpanel/panel/panel/login/login_index2.php?error=1');
		exit;
    }
    else{
        $_SESSION['login']['usn'] = $username;
        $_SESSION['login']['pwd'] = $password;
        //echo 'aryan2015';
		header('location: /pt/controlpanel/panel/panel/index.php');
		exit;
		//die;
    }	
	
//	echo $username . ",   " . $password;
}
?>