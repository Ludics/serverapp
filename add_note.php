<?php
  $result = file_get_contents('php://input');
  echo $result;
  echo json_decode(json_encode($result));
?>
