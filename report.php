<?php
// 連接資料庫
try {
    $dsn = 'mysql:host=localhost;dbname=finance_db;charset=utf8mb4';
    $conn = new PDO($dsn, 'root', '');
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("資料庫連接失敗: " . $e->getMessage());
}

$report_type = $_GET['type'] ?? 'month';
$start_date = $_GET['start_date'] ?? date('Y-m-d', strtotime('-3 months'));
$end_date = $_GET['end_date'] ?? date('Y-m-d');

$report_data = [];
$report_title = '';
$total_amount = 0;

// 根據報表類型查詢
try {
    if ($report_type === 'day') {
        $query = "SELECT c.name as category, SUM(da.payment) as total, COUNT(*) as count, da.date
                  FROM daily_account da
                  LEFT JOIN category c ON da.category = c.id
                  WHERE da.type = '支出' AND da.date BETWEEN ? AND ?
                  GROUP BY da.date, c.id, c.name
                  ORDER BY da.date DESC, total DESC";
        $report_title = "日報表 (" . $start_date . " ~ " . $end_date . ")";
    } elseif ($report_type === 'week') {
        $query = "SELECT c.name as category, SUM(da.payment) as total, COUNT(*) as count,
                         WEEK(da.date) as week_num, DATE_FORMAT(da.date, '%Y-W%u') as week_label
                  FROM daily_account da
                  LEFT JOIN category c ON da.category = c.id
                  WHERE da.type = '支出' AND da.date BETWEEN ? AND ?
                  GROUP BY WEEK(da.date), c.id, c.name
                  ORDER BY week_num DESC, total DESC";
        $report_title = "週報表 (" . $start_date . " ~ " . $end_date . ")";
    } elseif ($report_type === 'year') {
        $query = "SELECT c.name as category, SUM(da.payment) as total, COUNT(*) as count,
                         MONTH(da.date) as month_num, DATE_FORMAT(da.date, '%Y-%m') as month_label
                  FROM daily_account da
                  LEFT JOIN category c ON da.category = c.id
                  WHERE da.type = '支出' AND da.date BETWEEN ? AND ?
                  GROUP BY MONTH(da.date), c.id, c.name
                  ORDER BY month_num DESC, total DESC";
        $report_title = "年報表 (" . date('Y', strtotime($start_date)) . ")";
    } else { // month
        $query = "SELECT c.name as category, SUM(da.payment) as total, COUNT(*) as count
                  FROM daily_account da
                  LEFT JOIN category c ON da.category = c.id
                  WHERE da.type = '支出' AND da.date BETWEEN ? AND ?
                  GROUP BY c.id, c.name
                  ORDER BY total DESC";
        $report_title = "月報表 (" . $start_date . " ~ " . $end_date . ")";
    }
    
    $stmt = $conn->prepare($query);
    $stmt->execute([$start_date, $end_date]);
    $report_data = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($report_data as $item) {
        $total_amount += $item['total'];
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
    <title>消費報表</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
    <style>
        .report-container {
            max-width: 1000px;
            margin: 0 auto;
        }

        .report-container h2 {
            color: #333;
            margin: 20px 0 30px 0;
        }

        .report-controls {
            display: flex;
            gap: 15px;
            margin-bottom: 20px;
            flex-wrap: wrap;
            align-items: flex-end;
        }

        .control-group {
            display: flex;
            flex-direction: column;
            gap: 5px;
        }

        .control-group label {
            color: #555;
            font-size: 14px;
            font-weight: 600;
        }

        .control-group select,
        .control-group input {
            padding: 10px 12px;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            font-size: 14px;
            background: #fafafa;
            transition: all 0.3s ease;
        }

        .control-group select:focus,
        .control-group input:focus {
            outline: none;
            background: #ffffff;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        .btn-generate {
            padding: 10px 20px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: #fff;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 14px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-generate:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(102, 126, 234, 0.4);
        }

        .report-summary {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            margin-bottom: 30px;
        }

        .summary-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: #fff;
            padding: 20px;
            border-radius: 8px;
            text-align: center;
        }

        .summary-card h3 {
            margin: 0 0 10px 0;
            font-size: 14px;
            opacity: 0.9;
        }

        .summary-card .value {
            font-size: 28px;
            font-weight: 700;
        }

        .report-content {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-bottom: 30px;
        }

        .chart-container {
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .chart-container h3 {
            color: #333;
            margin: 0 0 15px 0;
        }

        .table-container {
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .table-container h3 {
            color: #333;
            margin: 0 0 15px 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        table th {
            background: #f8f9fa;
            padding: 12px;
            text-align: left;
            border-bottom: 2px solid #e0e0e0;
            color: #555;
            font-weight: 600;
            font-size: 14px;
        }

        table td {
            padding: 12px;
            border-bottom: 1px solid #f0f0f0;
            font-size: 14px;
        }

        table tr:hover {
            background-color: #f8f9fa;
        }

        .back-link {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 20px;
            background: #f0f0f0;
            color: #555;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .back-link:hover {
            background: #e8e8e8;
        }

        .empty-state {
            text-align: center;
            padding: 40px;
            color: #999;
            background: #f8f9fa;
            border-radius: 8px;
        }

        @media (max-width: 768px) {
            .report-content {
                grid-template-columns: 1fr;
            }

            .report-controls {
                flex-direction: column;
            }

            .control-group {
                width: 100%;
            }

            .control-group select,
            .control-group input {
                width: 100%;
            }

            .btn-generate {
                width: 100%;
            }
        }
    </style>
</head>
<body>
    <div class="report-container">
        <h2>📊 消費報表</h2>

        <div class="report-controls">
            <div class="control-group">
                <label for="account">帳號（付款帳戶）：</label>
                <input type="text" id="account" value="" onchange="updateReport()">
            </div>

            <div class="control-group">
                <label for="reportType">報表類型：</label>
                <select id="reportType" name="type" onchange="updateReport()">
                    <option value="day" <?php echo $report_type === 'day' ? 'selected' : ''; ?>>日報表</option>
                    <option value="week" <?php echo $report_type === 'week' ? 'selected' : ''; ?>>週報表</option>
                    <option value="month" <?php echo $report_type === 'month' ? 'selected' : ''; ?>>月報表</option>
                    <option value="year" <?php echo $report_type === 'year' ? 'selected' : ''; ?>>年報表</option>
                </select>
            </div>

            <div class="control-group">
                <label for="reportStart">開始日期：</label>
                <input type="date" id="reportStart" name="start_date" value="<?php echo $start_date; ?>" onchange="updateReport()">
            </div>

            <div class="control-group">
                <label for="reportEnd">結束日期：</label>
                <input type="date" id="reportEnd" name="end_date" value="<?php echo $end_date; ?>" onchange="updateReport()">
            </div>

            <button class="btn-generate" onclick="updateReport()">🔄 產生報表</button>
        </div>

        <?php if ($report_data): ?>
            <div class="report-summary">
                <div class="summary-card">
                    <h3>筆數</h3>
                    <div class="value"><?php echo count($report_data); ?></div>
                </div>
                <div class="summary-card">
                    <h3>總金額</h3>
                    <div class="value">$<?php echo number_format($total_amount, 2); ?></div>
                </div>
            </div>

            <div class="report-content">
                <div class="chart-container">
                    <h3>支出分布圖</h3>
                    <canvas id="reportChart"></canvas>
                </div>

                <div class="table-container">
                    <h3>詳細數據</h3>
                    <table>
                        <thead>
                            <tr>
                                <th>分類</th>
                                <th>金額</th>
                                <th>筆數</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                                $colors = ['#667eea', '#764ba2', '#e74c3c', '#f39c12', '#27ae60', '#2980b9', '#8e44ad', '#c0392b'];
                                $index = 0;
                                foreach ($report_data as $item): 
                            ?>
                                <tr>
                                    <td>
                                        <span style="display: inline-block; width: 10px; height: 10px; background: <?php echo $colors[$index % count($colors)]; ?>; border-radius: 50%; margin-right: 8px;"></span>
                                        <?php echo htmlspecialchars($item['category'] ?? '未分類'); ?>
                                    </td>
                                    <td>$<?php echo number_format($item['total'], 2); ?></td>
                                    <td><?php echo $item['count']; ?></td>
                                </tr>
                            <?php 
                                $index++;
                                endforeach; 
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <script>
                const ctx = document.getElementById('reportChart').getContext('2d');
                const data = {
                    labels: [
                        <?php 
                            $index = 0;
                            foreach ($report_data as $item) {
                                echo "'" . htmlspecialchars(addslashes($item['category'] ?? '未分類')) . "'";
                                if ($index < count($report_data) - 1) echo ", ";
                                $index++;
                            }
                        ?>
                    ],
                    datasets: [{
                        label: '金額',
                        data: [
                            <?php 
                                foreach ($report_data as $item) {
                                    echo $item['total'] . ",";
                                }
                            ?>
                        ],
                        backgroundColor: [
                            '#667eea', '#764ba2', '#e74c3c', '#f39c12', '#27ae60', '#2980b9', '#8e44ad', '#c0392b'
                        ],
                        borderColor: '#fff',
                        borderWidth: 2
                    }]
                };

                const chart = new Chart(ctx, {
                    type: 'doughnut',
                    data: data,
                    options: {
                        responsive: true,
                        maintainAspectRatio: true,
                        plugins: {
                            legend: {
                                position: 'bottom',
                                labels: {
                                    font: {
                                        size: 12
                                    }
                                }
                            }
                        }
                    }
                });
            </script>
        <?php else: ?>
            <div class="empty-state">
                <h3>📭 查無報表數據</h3>
                <p>此期間沒有消費記錄</p>
            </div>
        <?php endif; ?>

        <a href="list_expense.php?account=<?php echo urlencode($user_account); ?>" class="back-link">🔙 返回清單</a>
    </div>

    <script>
        function updateReport() {
            const account = document.getElementById('account').value;
            const type = document.getElementById('reportType').value;
            const start_date = document.getElementById('reportStart').value;
            const end_date = document.getElementById('reportEnd').value;
            window.location.href = 'report.php?account=' + encodeURIComponent(account) + '&type=' + type + '&start_date=' + start_date + '&end_date=' + end_date;
        }
    </script>
</body>
</html>
