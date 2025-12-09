<?php 
include_once "sql.php";

$rows=all('daily_account',['payment_method'=>'3']);

//print_r($rows);
foreach($rows as $r){
    echo $r['id'].'. '.$r['item'].'<br>';
}

