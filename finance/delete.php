<?php

include_once "sql.php";
delete('daily_account',$_GET['id']);
header("location:index.php");
?>