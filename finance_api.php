<?php
/**
 * 記帳系統 API
 * 處理所有消費記錄的 CRUD 操作
 */

// 設定 header 為 JSON
header('Content-Type: application/json; charset=utf-8');

// 連接資料庫
try {
    $dsn = 'mysql:host=localhost;dbname=finance_db;charset=utf8mb4';
    $conn = new PDO($dsn, 'root', '');
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'error' => '資料庫連接失敗: ' . $e->getMessage()]);
    exit();
}

$action = $_REQUEST['action'] ?? '';
$user_account = $_REQUEST['account'] ?? 'default_user';

switch ($action) {
    case 'add_expense':
        addExpense($conn, $user_account);
        break;
    
    case 'get_expenses':
        getExpenses($conn, $user_account);
        break;
    
    case 'get_expense':
        getExpense($conn, $user_account);
        break;
    
    case 'update_expense':
        updateExpense($conn, $user_account);
        break;
    
    case 'delete_expense':
        deleteExpense($conn, $user_account);
        break;
    
    case 'get_report':
        getReport($conn, $user_account);
        break;
    
    default:
        http_response_code(400);
        echo json_encode(['error' => '未知的操作']);
        break;
}

/**
 * 新增消費記錄
 */
function addExpense($conn, $user_account) {
    try {
        $time = $_POST['time'] ?? date('H:i:s');
        $currency = $_POST['currency'] ?? 'TWD';
        $store = $_POST['store'] ?? '';
        $date = $_POST['date'] ?? date('Y-m-d');
        $item = $_POST['item'] ?? '';
        $payment = floatval($_POST['payment'] ?? 0);
        $payment_method = intval($_POST['payment_method'] ?? 1);
        $category = intval($_POST['category'] ?? 1);
        $type = $_POST['type'] ?? '支出';
        $desc = $_POST['desc'] ?? '';
        $account = $_POST['account'] ?? '現金';  // account 是付款帳戶 (現金、信用卡等)

        if (empty($store) || empty($item) || $payment <= 0) {
            echo json_encode(['success' => false, 'error' => '必填欄位不能為空']);
            return;
        }

        $query = "INSERT INTO daily_account (time, currency, store, date, item, payment, payment_method, category, type, desc, account) 
                  VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        
        $stmt = $conn->prepare($query);
        $stmt->execute([$time, $currency, $store, $date, $item, $payment, $payment_method, $category, $type, $desc, $account]);
        
        echo json_encode(['success' => true, 'message' => '消費記錄已新增', 'id' => $conn->lastInsertId()]);
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'error' => '新增失敗: ' . $e->getMessage()]);
    }
}

/**
 * 取得消費記錄清單
 */
function getExpenses($conn, $user_account) {
    try {
        $start_date = $_GET['start_date'] ?? date('Y-m-d', strtotime('-30 days'));
        $end_date = $_GET['end_date'] ?? date('Y-m-d');
        $sort = $_GET['sort'] ?? 'date DESC';

        $query = "SELECT da.*, c.name as category_name, pm.name as payment_method_name 
                  FROM daily_account da
                  LEFT JOIN category c ON da.category = c.id
                  LEFT JOIN payment_method pm ON da.payment_method = pm.id
                  WHERE da.date BETWEEN ? AND ?
                  ORDER BY " . $sort;
        
        $stmt = $conn->prepare($query);
        $stmt->execute([$start_date, $end_date]);
        $expenses = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        echo json_encode(['success' => true, 'data' => $expenses]);
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'error' => '查詢失敗: ' . $e->getMessage()]);
    }
}

/**
 * 取得單筆消費記錄
 */
function getExpense($conn, $user_account) {
    try {
        $id = intval($_GET['id'] ?? 0);

        $query = "SELECT da.*, c.name as category_name, pm.name as payment_method_name 
                  FROM daily_account da
                  LEFT JOIN category c ON da.category = c.id
                  LEFT JOIN payment_method pm ON da.payment_method = pm.id
                  WHERE da.id = ?";
        
        $stmt = $conn->prepare($query);
        $stmt->execute([$id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($result) {
            echo json_encode(['success' => true, 'data' => $result]);
        } else {
            echo json_encode(['success' => false, 'error' => '記錄不存在']);
        }
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'error' => '查詢失敗: ' . $e->getMessage()]);
    }
}

/**
 * 修改消費記錄
 */
function updateExpense($conn, $user_account) {
    try {
        $id = intval($_POST['id'] ?? 0);
        $time = $_POST['time'] ?? date('H:i:s');
        $store = $_POST['store'] ?? '';
        $date = $_POST['date'] ?? date('Y-m-d');
        $item = $_POST['item'] ?? '';
        $payment = floatval($_POST['payment'] ?? 0);
        $payment_method = intval($_POST['payment_method'] ?? 1);
        $category = intval($_POST['category'] ?? 1);
        $desc = $_POST['desc'] ?? '';

        if (empty($store) || empty($item) || $payment <= 0) {
            echo json_encode(['success' => false, 'error' => '必填欄位不能為空']);
            return;
        }

        $query = "UPDATE daily_account SET time = ?, store = ?, date = ?, item = ?, payment = ?, payment_method = ?, category = ?, desc = ? 
                  WHERE id = ?";
        
        $stmt = $conn->prepare($query);
        $stmt->execute([$time, $store, $date, $item, $payment, $payment_method, $category, $desc, $id]);
        
        echo json_encode(['success' => true, 'message' => '消費記錄已更新']);
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'error' => '更新失敗: ' . $e->getMessage()]);
    }
}

/**
 * 刪除消費記錄
 */
function deleteExpense($conn, $user_account) {
    try {
        $id = intval($_GET['id'] ?? 0);

        $query = "DELETE FROM daily_account WHERE id = ?";
        
        $stmt = $conn->prepare($query);
        $stmt->execute([$id]);
        
        echo json_encode(['success' => true, 'message' => '消費記錄已刪除']);
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'error' => '刪除失敗: ' . $e->getMessage()]);
    }
}

/**
 * 取得報表數據
 */
function getReport($conn, $user_account) {
    try {
        $report_type = $_GET['type'] ?? 'month';
        $start_date = $_GET['start_date'] ?? date('Y-m-d', strtotime('-1 month'));
        $end_date = $_GET['end_date'] ?? date('Y-m-d');

        $group_by = '';
        switch ($report_type) {
            case 'day':
                $group_by = 'DATE(da.date)';
                break;
            case 'week':
                $group_by = 'WEEK(da.date)';
                break;
            case 'month':
                $group_by = 'MONTH(da.date)';
                break;
            case 'year':
                $group_by = 'YEAR(da.date)';
                break;
            default:
                $group_by = 'MONTH(da.date)';
        }

        $query = "SELECT c.name as category, SUM(da.payment) as total, COUNT(*) as count, {$group_by} as period
                  FROM daily_account da
                  LEFT JOIN category c ON da.category = c.id
                  WHERE da.date BETWEEN ? AND ? AND da.type = '支出'
                  GROUP BY {$group_by}, c.id
                  ORDER BY period DESC, total DESC";
        
        $stmt = $conn->prepare($query);
        $stmt->execute([$start_date, $end_date]);
        $report_data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        echo json_encode(['success' => true, 'data' => $report_data]);
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'error' => '查詢失敗: ' . $e->getMessage()]);
    }
}
?>

