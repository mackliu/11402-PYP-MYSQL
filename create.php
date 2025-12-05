<?php
$dsn='mysql:host=localhost;dbname=company;charset=utf8';
$pdo= new PDO($dsn,'root','');

$cols=array_keys($_POST);

$sql="INSERT INTO users ";
$sql.="(`".join("`,`",$cols)."`)";
$sql.=" VALUES ( '".join("','",$_POST)."' )";

echo $sql;

$pdo->exec($sql);
echo "註冊成功";

header("location:login.php");

?>