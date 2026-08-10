<?php
$file_taqwim = $_FILES['file_taqwim']['tmp_name'];
//echo "<p><h3>" . "result " . $file_taqwim . ", filet=" . $filet . "</h3></p>";
//var_dump($_FILES);
// your config

echo  "<div id=\"prgStatusDIV\"><h3 style=\"color:blue;\">Sedang upload data, sila tunggu...</h3></div>";


$targetPath = './tmpuploadfile/toupload.zip';
//echo "target path=" . $targetPath;
//echo "path=" . $path;

unlink("./tmpuploadfile/ext/taqwim.sql_error");
unlink("./tmpuploadfile/ext/taqwim.sql_filepointer");
unlink("./tmpuploadfile/ext/taqwim.sql");


if(move_uploaded_file($file_taqwim,$targetPath)) {
	// get the absolute path to $file
	$zip = new ZipArchive;
	$res = $zip->open($targetPath);
	if ($res === TRUE) {
 	 // extract it to the path we determined above
 	 $zip->extractTo('./tmpuploadfile/ext');
 	 $zip->close();
// 	 echo "WOOT! $targetPath extracted to $path";
	} else {
	  echo "Maaf! Tak boleh buka file $targetPath";
	}
}
else {
   echo  "<div id=\"prgStatusDIV\"><h3 style=\"color:red;\">Gagal copy file.</h3></div>";
   die;
}

$filename = './tmpuploadfile/ext/taqwim.sql';


include 'conn_cli.php';

//testing
//mysql_select_db('test') OR die('select db: '.$dbName.' failed: '.mysql_error());

$maxRuntime = 120; // less then your max script execution limit


$deadline = time()+$maxRuntime; 
$progressFilename = $filename.'_filepointer'; // tmp file for progress
$errorFilename = $filename.'_error'; // tmp file for erro

//mysql_connect($dbHost, $dbUser, $dbPass) OR die('connecting to host: '.$dbHost.' failed: '.mysql_error());
//mysql_select_db($dbName) OR die('select db: '.$dbName.' failed: '.mysql_error());

($fp = fopen($filename, 'r')) OR die('failed to open file:'.$filename);

// check for previous error
if( file_exists($errorFilename) ){
    die('<pre> previous error: '.file_get_contents($errorFilename));
}

// activate automatic reload in browser
echo '<html><head> <meta http-equiv="refresh" content="'.($maxRuntime+2).'"><pre>';

// go to previous file position
$filePosition = 0;
if( file_exists($progressFilename) ){
    $filePosition = file_get_contents($progressFilename);
    fseek($fp, $filePosition);
}

$queryCount = 0;
$query = '';
while( $deadline>time() AND ($line=fgets($fp, 1024000)) ){
    if(substr($line,0,2)=='--' OR trim($line)=='' ){
        continue;
    }

    $query .= $line;
    if( substr(trim($query),-1)==';' ){
        if( !mysqli_query($query) ){
            $error = 'Error performing query \'<strong>' . $query . '\': ' . mysqli_error();
            file_put_contents($errorFilename, $error."\n");
	    echo  "<div id=\"prgStatusDIV\"><h3 style=\"color:red;\">Gagal masukkan data ke database.</h3></div>";
            exit;
        }
        $query = '';
        file_put_contents($progressFilename, ftell($fp)); // save the current file position for 
        $queryCount++;
    }
}

 //  echo  "<h3 style=\"color:red;\">Gagal copy file.</h3>";



if( feof($fp) ){
   if($queryCount > 0)
      echo  "<div id=\"prgStatusDIV\"><h3 style=\"color:green;\">Data taqwim telah berjaya dikemaskini.</h3></div>";
   else 
     echo  "<div id=\"prgStatusDIV\"><h3 style=\"color:red;\">Data taqwim gagal dikemaskini.</h3></div>";

}else{
    echo ftell($fp).'/'.filesize($filename).' '.(round(ftell($fp)/filesize($filename), 2)*100).'%'."\n";
    echo $queryCount.' queries processed! please reload or wait for automatic browser refresh!';
}


?>