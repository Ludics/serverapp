<?php
include 'header.php';


$result = file_get_contents('php://input');

myLOG("Add note");
myLOG($result);

$obj = json_decode($result);
$userID = $obj->userID;
$note = $obj->note;
$text = $obj->text;
$bookname = $obj->bookname;

$obj_re->noteID = 100;
$obj_re->userID = $userID;
$data = json_encode($obj_re);
echo $data; 
myLOG($userID . $note . $text . $bookname);

?>
