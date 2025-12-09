<?php
//all();
echo "<pre>";
print_r(all('daily_account',['id'=>'14']));
echo "</pre>";
echo "<br>";
echo "<pre>";
print_r(find('daily_account', ['id'=>'14']));
echo "</pre>";


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

function find($table,$id){

    $dsn="mysql:host=localhost;charset=utf8;dbname=finance_db";
    $pdo=new PDO($dsn,'root','');
    
    $sql="SELECT * FROM `{$table}` ";
    if(is_array($id)){
        
        foreach($id as $key => $value){
               $tmp[]="`$key`='$value'";
           }
           $sql .= " WHERE ".join(" && ",$tmp) ;
    }else{
        $sql .= " WHERE `id`='$id' ";
    }
    echo $sql;
    echo "<hr>";
    
    $row=$pdo->query($sql)->fetch(PDO::FETCH_ASSOC);
    
    return $row;

}
?>