<?php
include 'header.php';
$result = file_get_contents('php://input');
$obj = json_decode($result);
$userID = $obj->userID;
$note = $obj->note;
$text = $obj->text;
$bookname = $obj->bookname;

$host="localhost";
$user="root";
$pass="ludics";
$dbName="Notes";
$dir="/var/www/html/app/content/";

$notenum = count(scandir($dir."note/"))-2;
$textnum = count(scandir($dir."text/"))-2;

$noteAdd = $dir."note/".$notenum.".txt";
$textAdd = $dir."note/".$textnum.".txt";
//将笔记与原文分别保存

//存笔记
$fp = fopen($noteAdd,'w');
flock($fp,LOCK_EX);
if(!$fp){
    myLOG("Saving failed.");
    exit;
}
fwrite($fp,$note);
flock($fp,LOCK_UN);
fclose($fp);

//存原文
$fp=fopen($textAdd,'w');
flock($fp,LOCK_EX);
if(!$fp){
    myLOG("Saving failed.");
    exit;
}
fwrite($fp,$text);
flock($fp,LOCK_UN);
fclose($fp);

try {
  $conn = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);
  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  $sql = "INSERT INTO Note
          (userID, bookName, User_userID, noteAddress, textAddress)
          VALUES
          ('$userid', '$bookName', '$userid', '$noteAdd', '$textAdd')";
  $conn->exec($sql);
  $noteID = $conn->lastInsertId();  
} catch (PDOException $e){
      myLOG($sql . PHP_EOL . $e->getMessage());
}


$conn = null;
$obj_re->noteID = $noteID;
$obj_re->userID = $userID;
$data = json_encode($obj_re);
echo $data; 
myLOG($userID . $noteID);

?>
