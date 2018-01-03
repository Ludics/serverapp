<?php
include 'header.php';

$host="localhost";
$user="root";
$pass="ludics";
$dbName="Notes";
$dir="/var/www/html/app/content/" ;

/*function nameToID($username){
    $conn = mysqli_connect($host, $user, $pass, $dbName);
    if(! $conn )
    {
        myLOG('连接失败: ' . mysqli_error($conn));
    }
    // 设置编码，防止中文乱码
    mysqli_query($conn , "set names utf8");
 
    $sql = "SELECT userID, userName
            FROM User
            WHERE userName == '$username';'
 
    $retval = mysqli_query( $conn, $sql );
    if(! $retval )
    {
        myLOG('无法读取数据: ' . mysqli_error($conn));
    }
    while($row = mysqli_fetch_array($retval, MYSQLI_ASSOC))
    {
        $id = $rows['userID'];
    }
    mysqli_close($conn);

    return $id;
}
 */

// function userExisted($username){
//     // 查询用户名是否已存在，存在则返回True，不存在返回False
// }

// function addUser($username, $password){
//     // 新建用户
// }

// function passwordRight($username, $password){
//     // 查询用户密码是否正确，返回 True, False
// }  

function addNote($userid, $note, $text = NULL, $bookname = NULL){
    // 添加: 返回noteid
    //$ = '$note';
    //$ = '$text';
    $host="localhost";
    $user="root";
    $pass="ludics";
    $dbname="Notes";
    $dir="/var/www/html/app/content/" ;
    $noteID = -1;
    try {
        $conn = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "INSERT INTO Note
                (userID, bookName, User_userID)
                VALUES 
                ('$userid', '$bookname', '$userid')";
        $res = $conn->query($sql);
        $row = $res->fetchAll();
        //获取上一个插入笔记的ID
        if($row){
            $sql = "SELECT last_insert_id();";
            $res = $conn->query($sql);
            $rows = $res->fetchAll();
            $noteID = $rows[0];
            echo "Insertion succeeded.";
            myLOG("Insertion succeeded.");
        }
    } catch (PDOException $e){
        myLOG($sql . PHP_EOL . $e->getMessage());
    }
    
    //将笔记与原文分别保存

    //存笔记
    @$fp=fopen($dir."note/".$userid."_".$noteID.".txt",'a');
    flock($fp,LOCK_EX);
    if(!$fp){
        myLOG("Saving failed.");
        exit;
    }
    fwrite($fp,$note,strlen($note));
    flock($fp,LOCK_UN);
    fclose($fp);

    //存原文
    @$fp=fopen($dir."text/".$userid."_".$noteID.".txt",'ab');
    flock($fp,LOCK_EX);
    if(!$fp){
        myLOG("Saving failed.");
        exit;
    }
    fwrite($fp,$text,strlen($text));
    flock($fp,LOCK_UN);
    fclose($fp);

    try {
        $sql = "UPDATE Note
                SET noteAddress = $dir.'note/'.$userid.'_'.$noteID.'.txt',
                 textAddress = $dir.'text/'.$userid.'_'.$noteID.'.txt',
                WHERE noteID = $noteID";
        $res = $conn->query($sql);
        $row = $res->fetchAll();
        if($row){
            echo "Insertion succeeded.";
            myLOG("Insertion succeeded.");
        }
    } catch (PDOException $e){
        myLOG($sql . PHP_EOL . $e->getMessage());
    }
    $conn = null;

    return $noteID;
}

echo addNote(10, "我的笔记", "原文", "书名");

/*function deleteNote($noteid){ 
    //删除给定id的笔记
    $conn = mysqli_connect($host, $user, $pass, $dbName);
    if(! $conn )
    {
        myLOG('连接失败: ' . mysqli_error($conn));
    }
    // 设置编码，防止中文乱码
    mysqli_query($conn , "set names utf8");
 
    $sql = 'DELETE FROM Note
            WHERE noteID == $noteid;'
 
    $retval = mysqli_query( $conn, $sql );

    mysqli_close($conn);
    
    return $retval;
} 
 
function modifyNote($noteid, $note){ 
    //将noteid对应笔记内容改为$note
    $conn = mysqli_connect($host, $user, $pass, $dbName);
    if(! $conn )
    {
        myLOG('连接失败: ' . mysqli_error($conn));
    }
    // 设置编码，防止中文乱码
    mysqli_query($conn , "set names utf8");
 
    //$newNoteAdd = 

    $sql = 'UPDATE Note
            SET noteAddress = "$note"
            WHERE noteID == $noteid;'
 
    $retval = mysqli_query( $conn, $sql );

    mysqli_close($conn);
    
    return $retval;
}

function getMyNotes($userid){
    // 获取 userid全部的笔记
    $conn = mysqli_connect($host, $user, $pass, $dbName);
    if(! $conn )
    {
        myLOG('连接失败: ' . mysqli_error($conn));
    }
    // 设置编码，防止中文乱码
    mysqli_query($conn , "set names utf8");
 
    $sql = 'SELECT * FROM Note
            WHERE userID == $userid;'
 
    $retval = mysqli_query( $conn, $sql );
    if(! $retval )
    {
        myLOG('无法读取数据: ' . mysqli_error($conn));
    }
    $row = mysqli_fetch_array($retval, MYSQLI_ASSOC);
    mysqli_close($conn);
    
    //返回所有符合条件note的数组
    return $row;
}

function getOtherNotes($times){
    // 获取 10*$times ~ 10*($times+1)-1 项笔记内容
    $conn = mysqli_connect($host, $user, $pass, $dbName);
    if(! $conn )
    {
        myLOG('连接失败: ' . mysqli_error($conn));
    }
    // 设置编码，防止中文乱码
    mysqli_query($conn , "set names utf8");

    $stmt = $conn->prepare('SELECT * FROM Persons ORDER BY noteID LIMIT (?, ?)');
    $stmt->bind_param("ii", $start, $end);

    $start = (int)(10*$times);
    $end = (int)(10*($times+1)-1);
    $retval = $stmt->execute();
    if(! $retval )
    {
        myLOG('无法读取数据: ' . mysqli_error($conn));
    }
    $row = mysqli_fetch_array($retval, MYSQLI_ASSOC))
    
    mysqli_close($conn);
    
    //返回所有符合条件note的数组
    return $row;
}
 */
?>
