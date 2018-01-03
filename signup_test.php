<?php
include 'header.php';
$host = "localhost";
$user = "root";
$pass = "ludics";
$dbname = "myDB";

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
        echo "Register success.";
        myLOG("Successfully.");
    }
} catch (PDOException $e){
    LOG($sql . PHP_EOL . $e->getMessage());
}
$conn = null;
?>
