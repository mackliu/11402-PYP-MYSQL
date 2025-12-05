<?php
if(!isset($_GET['account'])){
    header("location:login.php");
    exit();
}


?>

<!DOCTYPE html>
<html lang="zh-Hant">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>登入成功</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="success-icon">✓</div>
            <div class="header-text">
                <h2>登入成功，歡迎<?=$_GET['account'];?></h2>
            </div>
        </div>

        <div class="content">
            <div class="user-info">
                <div class="info-row">
                    <span class="info-label">帳號：</span>
                    <span class="info-value"><?php echo htmlspecialchars($_SESSION['account'] ?? ''); ?></span>
                </div>
                <div class="info-row">
                    <span class="info-label">姓名：</span>
                    <span class="info-value"><?php echo htmlspecialchars($_SESSION['name'] ?? ''); ?></span>
                </div>
                <div class="info-row">
                    <span class="info-label">電子郵件：</span>
                    <span class="info-value"><?php echo htmlspecialchars($_SESSION['email'] ?? ''); ?></span>
                </div>
            </div>

            <p class="message">
                您已成功登入系統。現在可以享受我們提供的各項服務。祝您使用愉快！
            </p>
        </div>

        <div class="button-group">
            <a href="index.php" class="btn btn-primary">返回首頁</a>
            <a href="logout.php" class="btn btn-secondary">登出</a>
        </div>
    </div>
</body>
</html>
