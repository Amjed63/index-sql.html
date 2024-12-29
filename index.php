<?php
// الاتصال بقاعدة البيانات
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "test_db";  // اسم قاعدة البيانات في mysql

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// المتغير لعرض الرسائل
$message = "";

// تسجيل مستخدم جديد
if (isset($_POST['register'])) {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $passwordHash = hash('sha256', $password);

    // التحقق من البريد الإلكتروني
    $checkQuery = "SELECT * FROM Users WHERE Email = ?";
    $stmt = $conn->prepare($checkQuery);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $message = "This email is already registered. Please use another email.";
    } else {
        // إدخال البيانات في الجدول
        $insertQuery = "INSERT INTO Users (Username, PasswordHash, Email) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($insertQuery);
        $stmt->bind_param("sss", $username, $passwordHash, $email);

        if ($stmt->execute()) {
            header("Location: index.html");
            exit();
        } else {
            $message = "Error: " . $stmt->error;
        }
    }
}

// تسجيل الدخول
if (isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $passwordHash = hash('sha256', $password);

    $query = "SELECT * FROM Users WHERE Email = ? AND PasswordHash = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ss", $email, $passwordHash);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        header("Location: index.html");
        exit();
    } else {
        $message = "Invalid email or password.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/index-home.css">
    <title>Login and Registration</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background: linear-gradient(135deg, #1e3c72, #2a5298);
            color: #fff;
      
            justify-content: center;
            align-items: center;
            height: 100vh;
            overflow: hidden;
        }
        .contr {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            padding: 30px;
            border-radius: 20px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.2);
            width: 90%;
            max-width: 400px;
            text-align: center;
        }
        h1 {
            font-size: 24px;
            margin-bottom: 20px;
        }
        form {
            margin-bottom: 20px;
        }
        label {
            display: block;
            margin: 10px 0 5px;
            text-align: left;
            font-weight: bold;
            color: #dfe6e9;
        }
        input {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border: none;
            border-radius: 10px;
            background: rgba(255, 255, 255, 0.2);
            color: #fff;
            font-size: 16px;
            outline: none;
        }
        button {
            width: 100%;
            padding: 10px;
            border: none;
            border-radius: 10px;
            background: #6c5ce7;
            color: #fff;
            font-size: 16px;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        button:hover {
            background: #a29bfe;
        }
        .toggle {
            margin-top: 10px;
            font-size: 14px;
            cursor: pointer;
            text-decoration: underline;
            color: #b2bec3;
        }
        .toggle:hover {
            color: #dfe6e9;
        }
        .message {
            text-align: center;
            margin-top: 10px;
            font-size: 16px;
            color: #00cec9;
        }
    </style>
</head>
<body>
<header>
     <div class="logo">Mony Shop</div> <div class="hamb"> <div class="line"></div> <div class="line"></div> <div class="line"></div> </div> <nav class="nav-bar">
         <ul> 
        <li><Arial><a href="index.html" class="active">الصفحه الرئيسيه</a></Arial></li>      
          <li><Arial><a href="index-1.html">سجافات</a></Arial></li>
        <li><Arial><a href="index-kusha.html">كوش</a></Arial></li>
     </ul>
     </nav>
    </header>

<center>

    <div class="contr">
        <h2>تسجيل الدخول</h2>
        <form method="POST">
            <label for="email">البريد الالكتروني:</label>
            <input type="email" name="email" id="email" required>

            <label for="password">كلمة السر:</label>
            <input type="password" name="password" id="password" required>

            <button type="submit" name="login">تسجيل الدخول</button>
        </form>
        <div class="toggle-link">
            ليس لديك حساب قم بانشاء حساب? <a href="#" onclick="toggleForms()" style="  color:#000;
    background:#fff;">انشاء</a>
        </div>

        <div class="message"><?php echo $message; ?></div>
    </div>

    <div class="contr" style="display: none;" id="registerForm">
        <h2>انشاء حساب جديد</h2>
        <form method="POST">
            <label for="username">الاسم:</label>
            <input type="text" name="username" id="username" required>

            <label for="email">البريد الالكتروني:</label>
            <input type="email" name="email" id="email" required>

            <label for="password">كلمة السر:</label>
            <input type="password" name="password" id="password" required>

            <button type="submit" name="register">انشاء</button>
        </form>
        <div class="toggle-link">
             هل لديك حساب مسبقاً قم بتسجيل الدخول? <a href="#" onclick="toggleForms()" style="  color:#000;
    background:#fff;">تسجيل الدخول</a>
        </div>

        <div class="message"><?php echo $message; ?></div>
    </div>
    </center>

    <script>
        function toggleForms() {
            const loginForm = document.querySelector('.contr:nth-of-type(1)');
            const registerForm = document.getElementById('registerForm');
            loginForm.style.display = loginForm.style.display === 'none' ? 'block' : 'none';
            registerForm.style.display = registerForm.style.display === 'none' ? 'block' : 'none';
        }
    </script>
</body>
</html>