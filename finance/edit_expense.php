<?php 
//include_once "sql.php";
include_once "DB.php";

//update('daily_account',$_POST);
$Daily->save($_POST);
to("index.php");
