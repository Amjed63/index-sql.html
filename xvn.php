<?php
// الاتصال بقاعدة البيانات
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "product_db"; // اسم قاعدة البيانات في mysql

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// إضافة تعليق جديد
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['username']) && isset($_POST['comment'])) {
    $username = $conn->real_escape_string($_POST['username']);
    $comment = $conn->real_escape_string($_POST['comment']);

    $query = "INSERT INTO Comments (Username, Comment) VALUES ('$username', '$comment')";
    if ($conn->query($query)) {
        // إعادة توجيه لمنع إعادة إرسال النموذج
        header("Location: " . $_SERVER['PHP_SELF']);
        exit;
    } else {
        echo "Error: " . $conn->error;
    }
}

// جلب جميع التعليقات
$query = "SELECT * FROM Comments ORDER BY CreatedAt DESC";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/index-home.css">
    <title>Product Comments</title>
    <style>
     
        .contr {
            width: 80%;
            max-width: 600px;
            background: #ffffff;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            padding: 20px;
        }
        .contr h2 {
            text-align: center;
            color: #333;
        }
        .comment-form {
            margin-bottom: 20px;
        }
        .comment-form input, .comment-form textarea {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 14px;
        }
        .comment-form button {
            width: 100%;
            padding: 10px;
            background-color: #007BFF;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }
        .comment-form button:hover {
            background-color: #0056b3;
        }
        .comments {
            margin-top: 20px;
        }
        .comment {
            background: #f9f9f9;
            padding: 10px 15px;
            margin-bottom: 10px;
            border-radius: 5px;
            border: 1px solid #eee;
        }
        .comment .username {
            font-weight: bold;
            color: #007BFF;
        }
        .comment .time {
            font-size: 12px;
            color: #aaa;
        }
        .comment .text {
            margin-top: 5px;
            color: #555;
        }
    </style>
</head>
<body>
<header>
     <div class="logo">Mony Shop</div> <div class="hamb"> <div class="line"></div> <div class="line"></div> <div class="line"></div> </div> <nav class="nav-bar">
         <ul>
        <li><Arial><a href="index.php" class="sin">تسجيل</a></Arial></li> 
        <li><Arial><a href="index.html">الصفحه الرئيسيه</a></Arial></li>      
          <li><Arial><a href="index-1.html">سجافات</a></Arial></li>
        <li><Arial><a href="index-kusha.html">كوش</a></Arial></li>
     </ul>
     </nav>
    </header>
    <center>
    <div class="contr">
        <h2>اسال سؤال - او اترك تعليق</h2>

       
        <!-- عرض التعليقات -->
        <div class="comments">
            <?php while ($row = $result->fetch_assoc()): ?>
                <div class="comment">
                    <div class="username"><?php echo htmlspecialchars($row['Username']); ?></div>
                    <div class="time"><?php echo $row['CreatedAt']; ?></div>
                    <div class="text"><?php echo nl2br(htmlspecialchars($row['Comment'])); ?></div>
                </div>
            <?php endwhile; ?>
        </div>

         <!-- نموذج إضافة تعليق جديد -->
         <form class="comment-form" method="POST" action="">
            <input type="text" name="username" placeholder="Your name" required>
            <textarea name="comment" rows="4" placeholder="Write your comment..." required></textarea>
            <button type="submit">ارسال</button>
        </form>

    </div>
</center>
</body>
</html>