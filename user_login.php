<?php
include 'header.php';
$host = "localhost";
$user = "root";
$pass = "ludics";
$dbname = "Notes";

$userName = $_POST['userName'];
$userPassword = $_POST['userPassword'];
myLOG("Username: " . $userName );
try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $pass);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "SELECT * FROM User WHERE userName = '$userName'";
 
    $res = $conn->query($sql);
    $row = $res->fetchAll();
    $cnt = count($row);
    if ($cnt) {
        if ($userPassword == $row[0]['userPassword']){
            $userID = $row[0]['userID'];
            $obj->userID = $userID;
            $obj->userName = $userName;
            $data = json_encode($obj);
            //$data = addslashes($data);
            echo $data;
            myLOG($data);
            myLOG("Successfully.");
            myLOG("Login success.");
        } else{
            echo "Password error.";
            myLOG("Password error.");
        }
    } else {
        echo "User not exist.";
        myLOG("USer not exist");
    }
} catch (PDOException $e){
    myLOG($sql . PHP_EOL . $e->getMessage());
}
$conn = null;
?>
