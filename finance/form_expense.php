<?php 
$dsn="mysql:host=localhost;dbname=finance_db;charset=utf8";
$pdo=new PDO($dsn,'root','');

?>

<!DOCTYPE html>
<html lang="zh-Hant">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>💰 新增消費</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <div class="form-container">
            <h1>💰 新增消費</h1>
            <form action="insert_expense.php" method="post">
                
                <div class="form-section">
                    <h3>基本資訊</h3>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="date">日期 (必填) *</label>
                            <input type="date" id="date" name="date" required>
                        </div>
                        <div class="form-group">
                            <label for="time">時間 (必填) *</label>
                            <input type="time" id="time" name="time" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="currency">幣別 (必填) *</label>
                        <div class="radio-group">
                            <div class="radio-item">
                                <input type="radio" name="currency" id="currency_twd" value="TWD" checked>
                                <label for="currency_twd">台幣</label>
                            </div>
                            <div class="radio-item">
                                <input type="radio" name="currency" id="currency_usd" value="USD">
                                <label for="currency_usd">美元</label>
                            </div>
                            <div class="radio-item">
                                <input type="radio" name="currency" id="currency_aud" value="AUD">
                                <label for="currency_aud">澳幣</label>
                            </div>
                            <div class="radio-item">
                                <input type="radio" name="currency" id="currency_jpy" value="JPY">
                                <label for="currency_jpy">日圓</label>
                            </div>
                            <div class="radio-item">
                                <input type="radio" name="currency" id="currency_cny" value="CNY">
                                <label for="currency_cny">人民幣</label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-section">
                    <h3>消費詳情</h3>
                    
                    <div class="form-group">
                        <label for="item">品項 (必填) *</label>
                        <input type="text" id="item" name="item" placeholder="例：咖啡、午餐" required>
                    </div>

                    <div class="form-group">
                        <label for="store">商店 (必填) *</label>
                        <select name="store" id="store" required>
                            <option value="">-- 請選擇或輸入商店 --</option>
                            <?php $stores=$pdo->query("SELECT `id`,`store` FROM `daily_account` GROUP BY `store`")->fetchALL(PDO::FETCH_ASSOC);
                                foreach($stores as $store){
                                    echo "<option value='{$store['store']}'>{$store['store']}</option>";
                            }
                            ?>
                        </select>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="payment">金額 (必填) *</label>
                            <input type="number" id="payment" name="payment" placeholder="0.00" step="0.01" min="0" required>
                        </div>
                        <div class="form-group">
                            <label for="category">類別 (必填) *</label>
                            <select name="category" id="category" required>
                                <option value="">-- 請選擇類別 --</option>
                                <?php $categories=$pdo->query("SELECT `id`,`name` FROM `category`")->fetchALL(PDO::FETCH_ASSOC);
                                    foreach($categories as $cat){
                                        echo "<option value='{$cat['id']}'>{$cat['name']}</option>";
                                    }
                                ?>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="form-section">
                    <h3>付款方式</h3>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="payment_method">付款方式 (必填) *</label>
                            <select name="payment_method" id="payment_method" required>
                                <option value="">-- 請選擇付款方式 --</option>
                                <option value="1">信用卡</option>
                                <option value="2">現金</option>
                                <option value="3">電子支付</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="account">付款帳戶 (必填) *</label>
                            <select name="account" id="account" required>
                                <option value="">-- 請選擇帳戶 --</option>
                                <?php 
                                    $accounts=$pdo->query("SELECT `account` FROM `daily_account` GROUP BY  `account`")->fetchALL(PDO::FETCH_ASSOC);
                                    
                                    foreach($accounts as $acc){
                                        echo "<option value='{$acc['account']}'>{$acc['account']}</option>";
                                    }
                                ?>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="form-section">
                    <h3>交易類型</h3>
                    
                    <div class="form-group">
                        <label>類型 (必填) *</label>
                        <div class="radio-group">
                            <div class="radio-item">
                                <input type="radio" id="type_expense" name="type" value="支出" checked required>
                                <label for="type_expense">支出</label>
                            </div>
                            <div class="radio-item">
                                <input type="radio" id="type_income" name="type" value="收入" required>
                                <label for="type_income">收入</label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-section">
                    <h3>備註</h3>
                    
                    <div class="form-group">
                        <label for="desc">描述說明</label>
                        <textarea id="desc" name="desc" placeholder="輸入任何備註或說明..." rows="3" style="resize: vertical;"></textarea>
                    </div>
                </div>

                <div class="form-buttons">
                    <input type="submit" value="💾 新增消費">
                    <input type="reset" value="🔄 重置">
                </div>
            </form>

            <div class="back-link">
                <a href="index.php">🔙 返回首頁</a>
            </div>
        </div>
    </div>
</body>
</html>