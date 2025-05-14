<?php
session_start();

// تحقق من تسجيل الدخول
if (!isset($_SESSION['username'])) {
    header('location: login.php'); // إعادة التوجيه إلى صفحة تسجيل الدخول
    exit();
}

// استرجاع تفاصيل الحجز من الجلسة
$username = $_SESSION['username'];
$user_id = $_SESSION['user_id'];
$college = $_SESSION['college'];
$region = $_SESSION['region'];
$room_number = 'غير محدد'; // القيمة الافتراضية
$term = $_SESSION['term'] ?? 'غير محدد'; 
$level = $_SESSION['level'] ?? 'غير محدد'; 

// الاتصال بقاعدة البيانات
$conn = new mysqli('localhost', 'root', '', 'mywebsite');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// استرجاع رقم الغرفة بناءً على معرف الطالب
$sql = "SELECT room_number FROM bookings WHERE student_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $user_id);  // ربط المعامل مع user_id
$stmt->execute();
$stmt->bind_result($room_number);
$stmt->fetch();
$stmt->close();
$conn->close();

// رابط لهذه الصفحة لرمز QR
$current_page_url = "http://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>عقد الإيجار الإلكتروني</title>
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Tajawal', sans-serif;
            background: #f5f7fa;
            margin: 0;
            padding: 20px;
            color: #333;
            direction: rtl;
            text-align: right;
        }

        .contract {
            background: #fff;
            max-width: 900px;
            margin: 30px auto;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
            border: 5px solid #2c5364;
            position: relative;
        }

        .contract-header {
            text-align: center;
            margin-bottom: 30px;
        }

        .contract-header img {
            width: 80px;
            margin-bottom: 15px;
        }

        .contract-header h1 {
            font-size: 28px;
            margin: 0;
            color: #1d3557;
            font-weight: bold;
        }

        .contract-content h2 {
            font-size: 24px;
            color: #1d3557;
            margin-bottom: 15px;
            border-bottom: 2px solid #457b9d;
            padding-bottom: 5px;
        }

        .contract-content p {
            font-size: 16px;
            margin: 10px 0;
            line-height: 1.8;
            color: #444;
        }

        .terms {
            margin-top: 30px;
            padding: 20px;
            background: #f1f9ff;
            border: 2px dashed #457b9d;
            border-radius: 10px;
        }

        .terms h3 {
            font-size: 22px;
            color: #457b9d;
            margin-bottom: 15px;
        }

        .action-buttons {
            text-align: center;
            margin-top: 30px;
        }

        button {
            padding: 12px 25px;
            font-size: 16px;
            border-radius: 8px;
            border: none;
            color: #fff;
            background: #1d3557;
            cursor: pointer;
            transition: all 0.3s;
            margin: 5px;
        }

        button:hover {
            background: #457b9d;
        }

        .qr-code {
            margin-top: 20px;
            text-align: center;
        }

        .qr-code img {
            width: 150px;
            height: 150px;
            margin-top: 10px;
        }

        @media print {
            button {
                display: none;
            }
        }
    </style>
</head>
<body>

<div id="contract" class="contract">
    <div class="contract-header">
        <img src="logo.png" alt="شعار الجامعة">
        <h1>عقد الإيجار الإلكتروني</h1>
        <p> نظام إدارة السكن</p>
    </div>

    <div class="contract-content">
        <h2>تفاصيل المستأجر</h2>
        <p><strong>اسم الطالب:</strong> <?= htmlspecialchars($username); ?></p>
        <p><strong>معرف المستخدم:</strong> <?= htmlspecialchars($user_id); ?></p>
        <p><strong>الكلية:</strong> <?= htmlspecialchars($college); ?></p>
        <p><strong>المنطقة:</strong> <?= htmlspecialchars($region); ?></p>
        <p><strong>رقم الغرفة:</strong> <?= htmlspecialchars($room_number); ?></p> <!-- عرض رقم الغرفة -->
        <p><strong>الترم:</strong> <?= htmlspecialchars($term); ?></p>
        <p><strong>المستوى:</strong> <?= htmlspecialchars($level); ?></p>
    </div>

    <div class="terms">
        <h3>شروط العقد</h3>
        <p>1. يؤجر الطرف الأول للطرف الثاني غرفة في الوحدة السكنية.</p>
        <p>2. مدة العقد فصل دراسي واحد وغير قابلة للإلغاء.</p>
        <p>3. يتحمل الطرف الثاني تكاليف أي أضرار.</p>
        <p>4. يلتزم الطالب بعدم تسكين أي شخص آخر.</p>
    </div>

    <div class="action-buttons">
        <button onclick="window.print()">طباعة العقد</button>
    </div>

    <div class="qr-code">
        <h3>مسح رمز QR لعرض العقد على الجوال</h3>
        <!-- عرض رمز QR باستخدام المكتبة -->
        <img src="https://api.qrserver.com/v1/create-qr-code/?data=<?= urlencode($current_page_url) ?>&size=150x150" alt="QR Code">
    </div>
</div>

</body>
</html>
