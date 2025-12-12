<?php

//include_once "sql.php";
include_once "DB.php";
//delete('daily_account',$_GET['id']);
$Daily->delete($_GET['id']);
//header("location:index.php");
to("index.php");
?>