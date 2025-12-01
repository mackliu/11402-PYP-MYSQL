<?php 
$dsn='mysql:host=localhost;dbname=finance_db;charset=utf8';
$pdo= new PDO($dsn,'root','');


$sql="SELECT * FROM category";

$rows=$pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);

/* echo "<pre>";
print_r($rows);
echo "</pre>"; */

?>
<style>
table,th,td{
    border:1px solid black;
    border-collapse: collapse;
    padding:5px;
}

body {
    font-family: Arial, sans-serif;
    background-color: #f5f5f5;
    padding: 20px;
}

h2 {
    color: #333;
    text-align: center;
    margin-bottom: 20px;
}

table {
    width: 100%;
    max-width: 800px;
    margin: 0 auto;
    background-color: white;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    border-radius: 8px;
    overflow: hidden;
}

th {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 12px;
    text-align: left;
    font-weight: bold;
}

td {
    padding: 12px;
    border-bottom: 1px solid #e0e0e0;
}

tr:hover {
    background-color: #f9f9f9;
}

tr:nth-child(even) {
    background-color: #f0f0f0;
}
</style>
<h2>category table</h2>
<table>
<tr>
    <th>id</th>
    <th>name</th>
    <th>created_at</th>
    <th>updated_at</th>
<?php 
foreach($rows as $row){
    echo "<tr>";
    foreach($row as $key=>$value){
        echo "<td>$value</td>";
    }
    echo "</tr>";
}


?>
</table>


<?php
$sql="SELECT * FROM category where id=2";
$row=$pdo->query($sql)->fetch(PDO::FETCH_ASSOC);
//echo "<pre>";
//print_r($row);
//echo "</pre>";

echo $row['name'];
?>