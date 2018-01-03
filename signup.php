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
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "SELECT 1 FROM User WHERE userName = '$userName' limit 1";
 
    $res = $conn->query($sql);
    $row = $res->fetchAll();
    $cnt = count($row);
    myLOG($cnt); 
    if ($cnt) {
        echo "Username existed, register failed.";
        myLOG("Failed");
    } else {
        $sql = "INSERT INTO User 
            (userName, userPassword) 
            values 
            ('$userName', '$userPassword')"; 
        $conn->exec($sql);
        //echo "Register success.";
        $sql = "SELECT userID, userName
            FROM User
            WHERE userName = '$userName'";
        $res = $conn->query($sql);
        $row = $res->fetchAll();
        $userID = $row[0]['userID'];
        $obj->userID = $userID;
        $obj->userName = $userName;
        $data = json_encode($obj);
        //$data = addslashes($data);
        echo $data;
        myLOG($data);
        myLOG("Successfully.");
    }
} catch (PDOException $e){
    myLOG($sql . PHP_EOL . $e->getMessage());
}
$conn = null;
?>
