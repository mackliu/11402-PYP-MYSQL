<?php
//all();

function all($table='daily_account',$where=[],$desc=' ORDER BY `id` ASC'){
    $dsn="mysql:host=localhost;charset=utf8;dbname=finance_db";
    $pdo=new PDO($dsn,'root','');
    
    $sql="SELECT * FROM $table ";

    if(is_array($where) && count($where)>0){
        foreach($where as $key => $value){
            $tmp[]="`$key`='$value'";
        }
        $sql .= " WHERE ".join(" && ",$tmp) ;
    }else if(is_string($where) && !empty($where)){
          $sql .= $where  ;
    }

    $sql .= $desc;


   echo $sql;
    echo "<hr>";
    
    $rows=$pdo->query($sql)->fetchALL(PDO::FETCH_ASSOC);
    
    return $rows;
}


?>