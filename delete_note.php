<?php
include 'header.php';
$result = file_get_contents('php://input');
$obj = json_decode($result);
$noteID = $obj->noteID;

myLOG("Delete ".$noteID);
$host="localhost";
$user="root";
$pass="ludics";
$dbname="Notes";

try {
  $conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $pass);
  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  $sql = "DELETE FROM Note
          WHERE noteID = $noteID";
  $conn->exec($sql); 
  echo "success"; 
} catch (PDOException $e){
      echo "fail";
      myLOG($sql . PHP_EOL . $e->getMessage());
}
$conn = null;

?>
