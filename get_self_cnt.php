<?php
include 'header.php';
$result = file_get_contents('php://input');
$obj = json_decode($result);
$userID = $obj->userID;

myLOG("Get other notes ".$times);
$host="localhost";
$user="root";
$pass="ludics";
$dbname="Notes";

try {
  $conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $pass);
  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  $sql = "SELECT * FROM Note WHERE userID = $userID";
  $res = $conn->query($sql); 
  $row = $res->fetchALL();
  $cnt = count($row);
  //echo "success"; 
} catch (PDOException $e){
      echo "fail";
      myLOG($sql . PHP_EOL . $e->getMessage());
}
$conn = null;

// $noteAdd = $result["noteAddress"];
// $textAdd = $result["textAddress"];

$obj->selfCounts = $cnt;

echo json_encode($obj);

?>
