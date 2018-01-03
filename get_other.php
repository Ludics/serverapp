<?php
include 'header.php';
$result_0 = file_get_contents('php://input');
$obj = json_decode($result_0);
$times = $obj->times;

myLOG("Get other notes ".$times);
$host="localhost";
$user="root";
$pass="ludics";
$dbname="Notes";

try {
  $conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $pass);
  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  $sql = "SELECT * FROM Note ORDER BY createTime DESC LIMIT $times";
  $res = $conn->query($sql); 
  $row = $res->fetchALL();
  $result = $row[$times-1];
  //echo "success"; 
} catch (PDOException $e){
      echo "fail";
      myLOG($sql . PHP_EOL . $e->getMessage());
}
$conn = null;

// $noteAdd = $result["noteAddress"];
// $textAdd = $result["textAddress"];

$note = file_get_contents($result["noteAddress"]);
$text = file_get_contents($result["textAddress"]);

$obj->userID = $result["userID"];
$obj->noteID = $result["noteID"];
$obj->bookName = $result["bookName"];
$obj->createTime = $result["createTime"];
$obj->totalLikes = $result["totalLikes"];
$obj->totalComments = $result["totalComments"];
$obj->note = $note;
$obj->text = $text;

echo json_encode($obj);

?>
