<!DOCTYPE html>
<html lang="zh-Hant">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>會員註冊</title>
    <style>
        /* make sizing predictable and include pseudo elements */
        *, *::before, *::after {
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
            padding: 20px;
        }

        .form-container {
            background: #ffffff;
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
            width: 100%;
            max-width: 480px;
        }

        h2 {
            text-align: center;
            color: #333;
            margin: 0 0 30px 0;
            font-size: 28px;
            font-weight: 600;
            letter-spacing: 0.5px;
        }

        .form-group {
            margin-bottom: 14px;
            display: flex;
            gap: 10px;
            align-items: center;
        }

        label {
            flex: 0 0 100px;
            color: #555;
            font-size: 14px;
            font-weight: 600;
            letter-spacing: 0.3px;
            white-space: nowrap;
        }

        /* inputs: consistent height and spacing, responsive */
        input[type="text"],
        input[type="password"],
        input[type="tel"],
        input[type="email"] {
            flex: 1;
            padding: 10px 12px;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            font-size: 14px;
            background: #fafafa;
            transition: all 0.3s ease;
            font-family: inherit;
        }

        input[type="text"]:focus,
        input[type="password"]:focus,
        input[type="tel"]:focus,
        input[type="email"]:focus {
            outline: none;
            background: #ffffff;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        /* group buttons in a responsive row */
        .button-row {
            display: flex;
            gap: 12px;
            margin-top: 30px;
        }

        .button-row input[type="submit"],
        .button-row input[type="reset"] {
            flex: 1;
            padding: 12px 20px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 15px;
            font-weight: 600;
            transition: all 0.3s ease;
            letter-spacing: 0.5px;
        }

        .button-row input[type="submit"] {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: #fff;
        }

        .button-row input[type="submit"]:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(102, 126, 234, 0.4);
        }

        .button-row input[type="submit"]:active {
            transform: translateY(0);
        }

        .button-row input[type="reset"] {
            background-color: #f0f0f0;
            color: #555;
            border: 2px solid #e0e0e0;
        }

        .button-row input[type="reset"]:hover {
            background-color: #e8e8e8;
            border-color: #d0d0d0;
        }

        /* small adjustments for very small screens */
        @media (max-width: 480px) {
            .form-container {
                padding: 20px;
            }

            h2 {
                font-size: 22px;
                margin-bottom: 20px;
            }

            .form-group {
                flex-direction: column;
                gap: 6px;
                margin-bottom: 12px;
            }

            label {
                flex: none;
            }

            input[type="text"],
            input[type="password"],
            input[type="tel"],
            input[type="email"] {
                padding: 10px 12px;
                font-size: 13px;
            }

            .button-row input[type="submit"],
            .button-row input[type="reset"] {
                padding: 11px 16px;
                font-size: 14px;
            }
        }
    </style>
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