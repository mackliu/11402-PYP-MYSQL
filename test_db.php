<?php
// 測試資料庫連接和數據
try {
    $conn = new PDO('mysql:host=localhost;dbname=finance_db;charset=utf8mb4', 'root', '');
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "✓ 資料庫連接成功\n";
    
    // 檢查表是否存在
    $tables = ['daily_account', 'category', 'payment_method'];
    foreach ($tables as $table) {
        $result = $conn->query("SELECT COUNT(*) as cnt FROM $table");
        $row = $result->fetch();
        echo "✓ $table 表有 " . $row['cnt'] . " 筆記錄\n";
    }
    
    // 顯示 daily_account 的前 5 筆數據
    echo "\n=== daily_account 前 5 筆數據 ===\n";
    $result = $conn->query("SELECT * FROM daily_account LIMIT 5");
    $rows = $result->fetchAll(PDO::FETCH_ASSOC);
    if (count($rows) > 0) {
        foreach ($rows as $row) {
            echo "ID: " . $row['id'] . ", 日期: " . $row['date'] . ", 金額: " . $row['payment'] . ", 帳號: " . $row['account'] . "\n";
        }
    } else {
        echo "❌ 無數據\n";
    }
    
    // 檢查 category 表
    echo "\n=== category 分類 ===\n";
    $result = $conn->query("SELECT * FROM category");
    $rows = $result->fetchAll(PDO::FETCH_ASSOC);
    foreach ($rows as $row) {
        echo "ID: " . $row['id'] . ", 名稱: " . $row['name'] . "\n";
    }
    
} catch (PDOException $e) {
    echo "❌ 錯誤: " . $e->getMessage();
}
?>
