<?php 
$dsn="mysql:host=localhost;dbname=finance_db;charset=utf8";
$pdo=new PDO($dsn,'root','');

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>新增消費</title>
</head>
<body>
<h2>新增消費</h2>    
<form action="insert_expense.php" method="post">
    <!-- time,
         currency,
         store,
         date,
         item,
         payment,
         payment_method,
         category,
         type,
         desc,
         account -->

    <div>
        <label for="currency">幣別:</label>
        <label><input type="radio" name="currency" id="" value="TWD"> 台幣</label>
        <label><input type="radio" name="currency" id="" value="USD"> 美元</label>
        <label><input type="radio" name="currency" id="" value="AUD"> 澳幣</label>
        <label><input type="radio" name="currency" id="" value="JPY"> 日圓</label>
        <label><input type="radio" name="currency" id="" value="CNY"> 人民幣</label>
        
    </div>
    <div>
        <label for="item">品項:</label>
        <input type="text" id="item" name="item" required>
    </div>    
    <div>
        <label for="store">商店:</label>
        <select name="store" id="store">
            <?php $stores=$pdo->query("SELECT `id`,`store` FROM `daily_account` GROUP BY `store`")->fetchALL(PDO::FETCH_ASSOC);
                foreach($stores as $store){
                    echo "<option value='{$store['store']}'>{$store['store']}</option>";
            }
            ?>
        </select>
    </div>
    <div>
        <label for="date">日期:</label>
        <input type="date" id="date" name="date" required>
    </div>
    <div>
        <label for="time">時間:</label>
        <input type="time" id="time" name="time" required>
    </div>

    <div>
        <label for="payment">金額:</label>
        <input type="number" id="payment" name="payment" required>
    </div>
    <div>
        <label for="payment_method">付款方式:</label>
        <select name="payment_method" id="payment_method">
            <option value="1">信用卡</option>
            <option value="2">現金</option>
            <option value="3">電子支付</option>
        </select>
        
    </div>
    <div>
        <label for="category">類別:</label>
        <select name="category" id="category">
            <?php $categories=$pdo->query("SELECT `id`,`name` FROM `category`")->fetchALL(PDO::FETCH_ASSOC);
                foreach($categories as $cat){
                    echo "<option value='{$cat['id']}'>{$cat['name']}</option>";
                }
            ?>

        </select>
    </div>
    <div>
        <label for="type">類型:</label>
        <label><input type="radio" id="pay" name="type" value="支出"> 支出</label>
        <label><input type="radio" id="income" name="type" value="收入"> 收入</label>
    </div>
    <div>
        <label for="desc">描述:</label>
        <input type="text" id="desc" name="desc">

    </div>
    <div>
        <label for="account">帳戶:</label>
        <select name="account" id="account">
            <?php 
                $accounts=$pdo->query("SELECT `account` FROM `daily_account` GROUP BY  `account`")->fetchALL(PDO::FETCH_ASSOC);
                
                foreach($accounts as $acc){
                    echo "<option value='{$acc['account']}'>{$acc['account']}</option>";
                }
            ?>
        </select>
        
    </div>
    <div>
        <input type="submit" value="新增消費">
        <input type="reset" value="重置">
    </div>
</form>
</body>
</html>