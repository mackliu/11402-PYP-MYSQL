<?php 
include_once "sql.php";

update('daily_account',$_POST);
header("location:index.php");
