<?php
session_start();

if(isset($_POST['var_usn']) AND isset($_POST['var_pwd'])){
    $username = addslashes($_POST['var_usn']);
    $password = addslashes($_POST['var_pwd']);
	
   if( $username !== 'ajk' || $password !== 'ajkajk' ){
        echo 'Username atau Password Salah !';
    }
    else{
        $_SESSION['login']['usn'] = $username;
        $_SESSION['login']['pwd'] = $password;
        echo 'ptime2015';
    }	
	
	
}
?>