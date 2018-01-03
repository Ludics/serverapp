<?php
$host="localhost";
$user="root";
$pass="ludics";
$dbname="myDB";
$userName = "ludi";
try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    /*$sql = "CREATE TABLE User (
    userID INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    userName VARCHAR(30) NOT NULL,
    userPassword VARCHAR(40) NOT NULL,
    userEmail VARCHAR(50),
    reg_date TIMESTAMP 
)";*/

    $sql = "SELECT * FROM User WHERE userName = '$userName'";

    $res = $conn->query($sql);
    $row = $res->fetchAll();
    echo $row[0]['userPassword'];
    $cnt = count($row);
    if ($cnt){
        echo "yes";
    }
    else{
        echo "no";

    }
    //echo "Table User created successfully." . PHP_EOL;
} catch (PDOException $e){
    echo $sql . PHP_EOL . $e->getMessage() . PHP_EOL;
}

$conn = null;
?>
