<?php 
include_once "sql.php";
insert('daily_account',$_POST);
header("location:index.php");
