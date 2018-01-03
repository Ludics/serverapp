<?php
include 'header.php';
$result = file_get_contents('php://input');
$obj = json_decode($result);
$userID = $obj->userID;
$note = $obj->note;
$text = $obj->text;
$bookname = $obj->bookname;
myLOG($userID.$note.$text.$bookname);
$host="localhost";
$user="root";
$pass="ludics";
$dbname="Notes";

try {
  $conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $pass);
  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  $sql = "INSERT INTO Note
          (userID, bookName, User_userID)
          VALUES
          ('$userID', '$bookname', '$userID')";
  $conn->exec($sql);
  $noteID = $conn->lastInsertId();  
} catch (PDOException $e){
      myLOG($sql . PHP_EOL . $e->getMessage());
}
$conn = null;

$noteDir="/var/www/html/app/content/note/";
$textDir="/var/www/html/app/content/text/";

// $notenum = count(scandir($noteDir))-2;
// $textnum = count(scandir($textDir))-2;

$noteAdd = $noteDir.$userID."_".$noteID.".txt";
$textAdd = $textDir.$userID."_".$noteID.".txt";
myLOG($noteAdd);
myLOG($textAdd);
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
$fp=fopen($textAdd, 'w');
flock($fp,LOCK_EX);
if(!$fp){
    myLOG("Saving failed.");
    exit;
}
fwrite($fp,$text);
flock($fp,LOCK_UN);
fclose($fp);

$noteID = intval($noteID);
try {
  $conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $pass);
  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  $sql = "UPDATE Note SET noteAddress = '$noteAdd' WHERE noteID = $noteID";
  $conn->exec($sql);
  $sql = "UPDATE Note SET textAddress = '$textAdd' WHERE noteID = $noteID";
  $conn->exec($sql);
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
