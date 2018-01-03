<?php
include 'header.php';
$result = file_get_contents('php://input');
$obj = json_decode(json_encode($result));
$userID = $obj->{'userID'};
$note = $obj->{"note"};
$text = $obj->{"text"};
$bookname = $obj->{"bookname"};

$obj_re->noteID = 100;
$obj_re->userID = $userID;
$data = json_encode($obj_re);
echo $data; 
myLOG($userID . $note . $text . $bookname);

?>
