<?php
//all();
/* echo "<pre>";
print_r(all('daily_account',['id'=>'14']));
echo "</pre>";
echo "<br>";
echo "<pre>";
print_r(find('daily_account', ['id'=>'14']));
echo "</pre>"; */

    $dsn="mysql:host=localhost;charset=utf8;dbname=finance_db";
    $pdo=new PDO($dsn,'root','');

//all();
//insert('category',['name'=>'出差']);
/* $row=find('category','11');
echo "<pre>";
print_r($row);
echo "</pre>";
echo "<hr>";
$row['name']='公益慈善';
echo "<pre>";
print_r($row);
echo "</pre>";
update('category',$row);
 */


//delete('category','11');

function all($table='daily_account',$where=[],$desc=' ORDER BY `id` ASC'){
    
    global $pdo;

    $sql="SELECT * FROM $table ";

    if(is_array($where) && count($where)>0){

        $tmp=array_trans($where);
        $sql .= " WHERE ".join(" && ",$tmp) ;

    }else if(is_string($where) && !empty($where)){
          $sql .= $where  ;
    }

    $sql .= $desc;

    //echo $sql;
    //echo "<hr>";
    
    $rows=$pdo->query($sql)->fetchALL(PDO::FETCH_ASSOC);
    
    return $rows;
}

function find($table,$id){
    global $pdo;

    
    $sql="SELECT * FROM `{$table}` ";
    if(is_array($id)){
        
        $tmp=array_trans($id);
        $sql .= " WHERE ".join(" && ",$tmp) ;
        
    }else{
        $sql .= " WHERE `id`='$id' ";
    }
    //echo $sql;
    //echo "<hr>";
    
    $row=$pdo->query($sql)->fetch(PDO::FETCH_ASSOC);
    
    return $row;

}
// INSERT example:
// INSERT INTO daily_account (`id`, `name`, `amount`) VALUES ('1', 'expense', '100')

function insert($table,$array){
    global $pdo;
    $sql="INSERT INTO `{$table}` ";
    $keys=array_keys($array);
    $sql .="(`". join("`,`",$keys). "`)";
    $sql .=" VALUES ('". join("','",$array). "')";
   // echo $sql;
    //echo "<hr>";
    return $pdo->exec($sql);
}

function update($table,$array,$limit=[]){
    global $pdo;
    
    $sql="UPDATE $table ";
    $tmp=array_trans($array);
    $sql .=" SET ".join(", ",$tmp);
    if(!empty($limit)){
        $tmp=array_trans($limit);
        $sql .=" WHERE ".join(" && ",$tmp2);
    }else{
        $sql .=" WHERE id='{$array['id']}'";
    }

    //echo $sql;
    //echo "<hr>";

    return $pdo->exec($sql);
}
function delete($table,$id){
    global $pdo;
   
    $sql="DELETE FROM `{$table}` ";
    if(is_array($id)){
        $tmp=array_trans($id);
        $sql .= " WHERE ".join(" && ",$tmp) ;
    }else{
        $sql .= " WHERE `id`='$id' ";
    }
    //echo $sql;
    //echo "<hr>";
    
    return $pdo->exec($sql);
}

function q($sql){
    global $pdo;
    return $pdo->query($sql)->fetchALL(PDO::FETCH_ASSOC);
}


function array_trans($array){
    foreach($array as $key => $value){
        $tmp[]="`$key`='$value'";
    }
 return $tmp;          
}

all('category'," order by `id` desc ");
?>