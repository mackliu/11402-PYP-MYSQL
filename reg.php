<!DOCTYPE html>
<html lang="zh-Hant">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>會員註冊</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="form-container">
        <h2>會員註冊</h2>
        <form action="create.php" method="POST">
            <div class="form-group">
                <label for="account">帳號</label>
                <input type="text" id="account" name="account" placeholder="請輸入帳號" required>
            </div>
            <div class="form-group">
                <label for="password">密碼</label>
                <input type="password" id="password" name="password" placeholder="請輸入密碼" required>
            </div>
            <div class="form-group">
                <label for="name">姓名</label>
                <input type="text" id="name" name="name" placeholder="請輸入姓名" required>
            </div>
            <div class="form-group">
                <label for="tel">電話</label>
                <input type="tel" id="tel" name="tel" placeholder="請輸入電話號碼" required>
            </div>
            <div class="form-group">
                <label for="national_id">國民身份證號</label>
                <input type="text" id="national_id" name="national_id" placeholder="請輸入身份證號" required>
            </div>
            <div class="form-group">
                <label for="address">地址</label>
                <input type="text" id="address" name="address" placeholder="請輸入地址" required>
            </div>
            <div class="form-group">
                <label for="email">電子郵件</label>
                <input type="text" id="email" name="email" placeholder="請輸入電子郵件" >
            </div>
            <div class="form-group">
                <label for="post_code">郵遞區號</label>
                <input type="text" id="post_code" name="post_code" placeholder="請輸入郵遞區號" required>
            </div>
            <div class="button-row">
                <input type="submit" value="註冊">
                <input type="reset" value="重置">
            </div>
        </form>
    </div>
</body>
</html>