<?php

  $myfile = fopen("userfile.json", "a+"); 
  //echo $_POST["username"];
  //$obj = json_decode($_POST);
  $data = file_get_contents("php://input");
  fwrite($myfile, $data.PHP_EOL);
  //fwrite($myfile, "\n")
  fclose($myfile);
  echo $_GET["name"];
  echo $data; 
?>
