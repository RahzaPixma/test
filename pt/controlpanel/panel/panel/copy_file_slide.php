<?php


$connect = ftp_connect('192.168.0.200');  //connect to server

$login = ftp_login($connect, 'root', 'suhair007'); 

if($login) 
{ 

    if(file_exists('/var/www/html/pt/templateslides/kuliahslides/' . $file_slide)) unlink( '/var/www/html/pt/templateslides/kuliahslides/' . $file_slide);

    if(ftp_put($connect, '/var/www/html/pt/templateslides/kuliahslides/' . $file_slide, $file_slide, FTP_BINARY)) 
    { 
        echo "Success...";
    } 
	 else {
	echo json_encode(array('errorMsg'=>'Some errors occured.'));
	}
} 

ftp_close($connect); 
?> 