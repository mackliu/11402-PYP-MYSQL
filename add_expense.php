<?php
// 連接資料庫
try {
    $dsn = 'mysql:host=localhost;dbname=finance_db;charset=utf8mb4';
    $conn = new PDO($dsn, 'root', '');
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("資料庫連接失敗: " . $e->getMessage());
}

// 取得分類和付款方式
$categories = [];
$payment_methods = [];

try {
    $cat_result = $conn->query("SELECT * FROM category");
    $categories = $cat_result->fetchAll(PDO::FETCH_ASSOC);
    
    $pay_result = $conn->query("SELECT * FROM payment_method");
    $payment_methods = $pay_result->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("查詢失敗: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="zh-Hant">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>記錄消費</title>
    <style>
        .expense-container {
            max-width: 600px;
            margin: 0 auto;
        }

        .expense-container h2 {
            color: #333;
            margin: 20px 0 30px 0;
        }

        .form-section {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        .form-section h3 {
            color: #555;
            font-size: 16px;
            margin-top: 0;
            margin-bottom: 15px;
            border-bottom: 2px solid #667eea;
            padding-bottom: 10px;
        }

        select {
            width: 100%;
            padding: 10px 12px;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            font-size: 14px;
            background: #fafafa;
            transition: all 0.3s ease;
            font-family: inherit;
            margin-bottom: 14px;
        }

        select:focus {
            outline: none;
            background: #ffffff;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        textarea {
            width: 100%;
            padding: 10px 12px;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            font-size: 14px;
            background: #fafafa;
            transition: all 0.3s ease;
            font-family: inherit;
            resize: vertical;
            min-height: 80px;
        }

        textarea:focus {
            outline: none;
            background: #ffffff;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
            margin-bottom: 14px;
        }

        .form-group-inline {
            margin-bottom: 14px;
        }

        .form-group-inline label {
            display: block;
            margin-bottom: 8px;
            color: #555;
            font-size: 14px;
            font-weight: 600;
            letter-spacing: 0.3px;
        }

        .form-group-inline input[type="text"],
        .form-group-inline input[type="number"],
        .form-group-inline input[type="date"],
        .form-group-inline input[type="time"] {
            width: 100%;
            padding: 10px 12px;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            font-size: 14px;
            background: #fafafa;
            transition: all 0.3s ease;
            font-family: inherit;
        }

        .form-group-inline input[type="text"]:focus,
        .form-group-inline input[type="number"]:focus,
        .form-group-inline input[type="date"]:focus,
        .form-group-inline input[type="time"]:focus {
            outline: none;
            background: #ffffff;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        .button-row {
            display: flex;
            gap: 12px;
            margin-top: 30px;
        }

        .button-row button,
        .button-row a {
            flex: 1;
            padding: 12px 20px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 15px;
            font-weight: 600;
            transition: all 0.3s ease;
            letter-spacing: 0.5px;
            text-decoration: none;
            text-align: center;
            display: inline-block;
        }

        .button-row button[type="submit"] {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: #fff;
        }

        .button-row button[type="submit"]:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(102, 126, 234, 0.4);
        }

        .button-row a {
            background-color: #f0f0f0;
            color: #555;
            border: 2px solid #e0e0e0;
        }

        .button-row a:hover {
            background-color: #e8e8e8;
            border-color: #d0d0d0;
        }

        @media (max-width: 480px) {
            .form-row {
                grid-template-columns: 1fr;
            }

            .form-section {
                padding: 15px;
            }

            .button-row button,
            .button-row a {
                padding: 11px 16px;
                font-size: 14px;
            }
        }
    </style>
</head>
<body>
    <div class="form-container expense-container">
        <h2>📝 記錄消費</h2>
        <form action="finance_api.php" method="POST">
            <input type="hidden" name="action" value="add_expense">
            
            <div class="form-section">
                <h3>帳戶信息</h3>
                <div class="form-group-inline">
                    <label for="account">帳號 (必填) *</label>
                    <input type="text" id="account" name="account" placeholder="請輸入帳號" required value="<?php echo htmlspecialchars($_GET['account'] ?? 'default_user'); ?>">
                </div>
            </div>
            
            <div class="form-section">
                <h3>基本資訊</h3>
                <div class="form-row">
                    <div class="form-group-inline">
                        <label for="date">日期 (必填) *</label>
                        <input type="date" id="date" name="date" required value="<?php echo date('Y-m-d'); ?>">
                    </div>
                    <div class="form-group-inline">
                        <label for="time">時間 (必填) *</label>
                        <input type="time" id="time" name="time" required value="<?php echo date('H:i'); ?>">
                    </div>
                </div>
            </div>

            <div class="form-section">
                <h3>消費金額</h3>
                <div class="form-group-inline">
                    <label for="payment">金額 (必填) *</label>
                    <input type="number" id="payment" name="payment" placeholder="請輸入消費金額" step="0.01" min="0" required>
                </div>
            </div>

            <div class="form-section">
                <h3>消費詳情</h3>
                <div class="form-group-inline">
                    <label for="store">店家名稱 (必填) *</label>
                    <input type="text" id="store" name="store" placeholder="例：便利商店、餐廳名稱" required>
                </div>
                
                <div class="form-group-inline">
                    <label for="item">消費項目 (必填) *</label>
                    <input type="text" id="item" name="item" placeholder="例：咖啡、午餐、加油" required>
                </div>
                
                <div class="form-row">
                    <div class="form-group-inline">
                        <label for="category">分類 (必填) *</label>
                        <select id="category" name="category" required>
                            <option value="">-- 請選擇分類 --</option>
                            <?php foreach ($categories as $cat): ?>
                                <option value="<?php echo $cat['id']; ?>"><?php echo htmlspecialchars($cat['name']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div class="form-group-inline">
                        <label for="payment_method">付款方式 (必填) *</label>
                        <select id="payment_method" name="payment_method" required>
                            <option value="">-- 請選擇付款方式 --</option>
                            <?php foreach ($payment_methods as $method): ?>
                                <option value="<?php echo $method['id']; ?>"><?php echo htmlspecialchars($method['name']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
            </div>

            <div class="form-section">
                <h3>備註</h3>
                <div class="form-group-inline">
                    <label for="desc">備註說明</label>
                    <textarea id="desc" name="desc" placeholder="輸入任何備註或說明..."></textarea>
                </div>
            </div>

            <div class="button-row">
                <button type="submit">💾 保存消費記錄</button>
                <a href="list_expense.php">🔙 返回列表</a>
            </div>
        </form>
    </div>
</body>
</html>
