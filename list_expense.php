<?php
// 連接資料庫
try {
    $dsn = 'mysql:host=localhost;dbname=finance_db;charset=utf8mb4';
    $conn = new PDO($dsn, 'root', '');
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("資料庫連接失敗: " . $e->getMessage());
}

// 取得消費記錄
$start_date = $_GET['start_date'] ?? date('Y-m-d', strtotime('-30 days'));
$end_date = $_GET['end_date'] ?? date('Y-m-d');

$expenses = [];
$total_amount = 0;

try {
    $query = "SELECT da.*, c.name as category_name, pm.name as payment_method_name 
              FROM daily_account da
              LEFT JOIN category c ON da.category = c.id
              LEFT JOIN payment_method pm ON da.payment_method = pm.id
              WHERE da.date BETWEEN ? AND ?
              ORDER BY da.date DESC, da.time DESC";

    $stmt = $conn->prepare($query);
    $stmt->execute([$start_date, $end_date]);
    $expenses = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($expenses as $expense) {
        if ($expense['type'] === '支出') {
            $total_amount += $expense['payment'];
        }
    }
} catch (PDOException $e) {
    die("查詢失敗: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="zh-Hant">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>消費記錄</title>
    <style>
        .expense-list-container {
            max-width: 1000px;
            margin: 0 auto;
        }

        .expense-list-container h2 {
            color: #333;
            margin: 20px 0 30px 0;
        }

        .action-bar {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
            flex-wrap: wrap;
            align-items: flex-end;
        }

        .filter-group {
            display: flex;
            gap: 10px;
            align-items: flex-end;
            flex: 1;
        }

        .filter-item {
            display: flex;
            flex-direction: column;
            gap: 5px;
        }

        .filter-item label {
            color: #555;
            font-size: 14px;
            font-weight: 600;
        }

        .filter-item input {
            padding: 8px 12px;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            font-size: 14px;
            background: #fafafa;
            transition: all 0.3s ease;
        }

        .filter-item input:focus {
            outline: none;
            background: #ffffff;
            border-color: #667eea;
        }

        .action-bar a,
        .btn-filter {
            padding: 10px 20px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 14px;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .btn-add {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: #fff;
        }

        .btn-add:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(102, 126, 234, 0.4);
        }

        .btn-filter {
            background-color: #f0f0f0;
            color: #555;
            border: 2px solid #e0e0e0;
        }

        .btn-filter:hover {
            background-color: #e8e8e8;
        }

        .btn-report {
            background-color: #667eea;
            color: #fff;
            padding: 10px 20px;
            border-radius: 8px;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .btn-report:hover {
            background-color: #764ba2;
        }

        .summary-box {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: #fff;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .summary-item {
            text-align: center;
        }

        .summary-label {
            font-size: 12px;
            opacity: 0.9;
            margin-bottom: 5px;
        }

        .summary-value {
            font-size: 24px;
            font-weight: 700;
        }

        .table-wrapper {
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        table thead {
            background: #f8f9fa;
            border-bottom: 2px solid #e0e0e0;
        }

        table th {
            padding: 15px;
            text-align: left;
            color: #555;
            font-weight: 600;
            font-size: 14px;
        }

        table td {
            padding: 15px;
            border-bottom: 1px solid #f0f0f0;
            font-size: 14px;
        }

        table tr:hover {
            background-color: #f8f9fa;
        }

        .amount {
            color: #e74c3c;
            font-weight: 600;
        }

        .category-badge {
            display: inline-block;
            background: #e8f0fd;
            color: #667eea;
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }

        .action-buttons {
            display: flex;
            gap: 8px;
        }

        .btn-edit, .btn-delete {
            padding: 6px 12px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 12px;
            text-decoration: none;
            transition: all 0.3s ease;
            display: inline-block;
        }

        .btn-edit {
            background: #667eea;
            color: #fff;
        }

        .btn-edit:hover {
            background: #764ba2;
        }

        .btn-delete {
            background: #f0f0f0;
            color: #555;
            border: 1px solid #e0e0e0;
        }

        .btn-delete:hover {
            background: #e74c3c;
            color: #fff;
            border-color: #e74c3c;
        }

        .empty-state {
            text-align: center;
            padding: 40px;
            color: #999;
        }

        @media (max-width: 768px) {
            .summary-box {
                flex-direction: column;
                gap: 15px;
            }

            .action-bar {
                flex-direction: column;
            }

            .filter-group {
                flex-direction: column;
                width: 100%;
            }

            .filter-item input {
                width: 100%;
            }

            .action-bar a,
            .btn-filter {
                width: 100%;
            }

            table {
                font-size: 12px;
            }

            table th,
            table td {
                padding: 10px;
            }

            .action-buttons {
                flex-direction: column;
            }

            .btn-edit, .btn-delete {
                width: 100%;
            }
        }
    </style>
</head>
<body>
    <div class="expense-list-container">
        <h2>💰 消費記錄</h2>

        <div class="action-bar">
            <div class="filter-group">
                <form method="GET" style="display: flex; gap: 10px; width: 100%;">
                    <div class="filter-item" style="flex: 1;">
                        <label for="account">帳號（付款帳戶）：</label>
                        <input type="text" id="account" name="account" value="<?php echo htmlspecialchars($_GET['account'] ?? ''); ?>">
                    </div>
                    <div class="filter-item" style="flex: 1;">
                        <label for="start_date">開始日期：</label>
                        <input type="date" id="start_date" name="start_date" value="<?php echo $start_date; ?>">
                    </div>
                    <div class="filter-item" style="flex: 1;">
                        <label for="end_date">結束日期：</label>
                        <input type="date" id="end_date" name="end_date" value="<?php echo $end_date; ?>">
                    </div>
                    <button type="submit" class="btn-filter" style="margin-top: auto;">🔍 篩選</button>
                </form>
            </div>
            <a href="add_expense.php" class="btn-add">➕ 新增消費</a>
            <a href="report.php" class="btn-report">📊 報表</a>
        </div>

        <?php if ($expenses): ?>
            <div class="summary-box">
                <div class="summary-item">
                    <div class="summary-label">消費筆數</div>
                    <div class="summary-value"><?php echo count($expenses); ?></div>
                </div>
                <div class="summary-item">
                    <div class="summary-label">總消費金額</div>
                    <div class="summary-value">$<?php echo number_format($total_amount, 2); ?></div>
                </div>
            </div>

            <div class="table-wrapper">
                <table>
                    <thead>
                        <tr>
                            <th>日期</th>
                            <th>時間</th>
                            <th>金額</th>
                            <th>店家</th>
                            <th>項目</th>
                            <th>分類</th>
                            <th>付款方式</th>
                            <th>備註</th>
                            <th>操作</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($expenses as $expense): ?>
                            <tr>
                                <td><?php echo $expense['date']; ?></td>
                                <td><?php echo $expense['time']; ?></td>
                                <td class="amount">$<?php echo number_format($expense['payment'], 2); ?></td>
                                <td><?php echo htmlspecialchars($expense['store']); ?></td>
                                <td><?php echo htmlspecialchars($expense['item']); ?></td>
                                <td><span class="category-badge"><?php echo htmlspecialchars($expense['category_name'] ?? ''); ?></span></td>
                                <td><?php echo htmlspecialchars($expense['payment_method_name'] ?? ''); ?></td>
                                <td><?php echo htmlspecialchars(substr($expense['desc'] ?? '', 0, 20)); ?></td>
                                <td>
                                    <div class="action-buttons">
                                        <a href="edit_expense.php?id=<?php echo $expense['id']; ?>" class="btn-edit">✏️ 編</a>
                                        <a href="finance_api.php?action=delete_expense&id=<?php echo $expense['id']; ?>" class="btn-delete" onclick="return confirm('確定要刪除？');">🗑️ 刪</a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="empty-state">
                <h3>📭 還沒有消費記錄</h3>
                <p>點擊「新增消費」按鈕開始記錄您的消費</p>
                <a href="add_expense.php" style="color: #667eea; text-decoration: none;">➕ 新增消費</a>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>
