<?php
$dsn="mysql:host=localhost;dbname=company;charset=utf8";
$pdo=new PDO($dsn,'root','');


$acc=$_POST['account'];
$pw=$_POST['password'];

//$sql="SELECT * FROM `users` WHERE `account`='$acc' && `password`='$pw'";
$sql="SELECT count(`id`) as 'count' FROM `users` WHERE `account`='$acc' && `password`='$pw'";
//$row=$pdo->query($sql)->fetch();
$row=$pdo->query($sql)->fetchColumn();
print_r($row);

//if($row[0]>0){
if($row>0){
    echo "登入成功";
    header("location:result.php?account=$acc");
}else{
    echo "登入失敗";
    header("location:login.php?error=1");
}
