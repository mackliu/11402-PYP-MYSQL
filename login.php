<!DOCTYPE html>
<html lang="zh-Hant">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>會員登入</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="form-container">
        <h2>會員登入</h2>
        <?php if(isset($_GET['error']) && $_GET['error'] == 1): ?>
        <div style='color:red;text-align:center;'>帳號密碼錯誤，請重新輸入</div>
        <?php endif; ?>
        <form action="chk_login.php" method="POST">
            <div class="form-group">
                <label for="account">帳號</label>
                <input type="text" id="account" name="account" placeholder="請輸入帳號" required>
            </div>
            <div class="form-group">
                <label for="password">密碼</label>
                <input type="password" id="password" name="password" placeholder="請輸入密碼" required>
            </div>
            <div class="button-row">
                <input type="submit" value="登入">
                <input type="reset" value="清除">
            </div>
        </form>
        <div class="form-footer">
            <span>還沒有帳號？</span>
            <a href="reg.php">立即註冊</a>
            <span>|</span>
            <a href="forgot.php">忘記密碼？</a>
        </div>
    </div>
</body>
</html>
