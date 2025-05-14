<?php
// الاتصال بقاعدة البيانات
$host = 'localhost';
$user = 'root';
$password = '';
$dbname = 'mywebsite';  
$conn = new mysqli($host, $user, $password, $dbname);

// التحقق من الاتصال بقاعدة البيانات
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$complaint_number = "";
$complaint_type = "";
$complaint_details = "";
$file_url = "";

// إذا كان النموذج قد تم إرساله
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $complaint_type = $_POST['complaintType'];
    $complaint_details = $_POST['complaintDetails'];

    // رفع الصورة أو الملف
    if (isset($_FILES['fileUpload']) && $_FILES['fileUpload']['error'] == 0) {
        $file_name = time() . '-' . $_FILES['fileUpload']['name'];
        $file_tmp_name = $_FILES['fileUpload']['tmp_name'];
        $file_url = 'uploads/' . $file_name;

        // نقل الملف إلى المجلد المناسب
        if (move_uploaded_file($file_tmp_name, $file_url)) {
            // الملف تم رفعه بنجاح
        } else {
            $error_message = "فشل في رفع الملف";
        }
    }

    // توليد رقم عشوائي للشكوى
    $complaint_number = rand(100000, 999999);

    // إدخال البيانات في قاعدة البيانات
    $sql = "INSERT INTO complaints (complaint_type, complaint_details, file_url, complaint_number)
            VALUES ('$complaint_type', '$complaint_details', '$file_url', '$complaint_number')";

    if ($conn->query($sql) === TRUE) {
        $success_message = "تم تقديم الشكوى بنجاح! رقم الشكوى: " . $complaint_number;
    } else {
        $error_message = "حدث خطأ: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تقديم شكوى</title>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <style>
        body {
            font-family: 'Cairo', sans-serif;
            margin: 0;
            padding: 0;
            background: linear-gradient(to bottom, #b0b0b0, #ffffff);
            color: #333;
            line-height: 1.6;
            padding-bottom: 60px;
        }

        .navbar {
            position: fixed;
            top: 0;
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 15px 30px;
            background: #1e1e2f;
            color: white;
            z-index: 1000;
        }

        .navbar h1 {
            font-size: 24px;
            margin: 0;
            color: #ffcc00;
            position: absolute;
            left: 30px;
        }

        .navbar .nav-links {
            display: flex;
            align-items: center;
            margin-left: 20px;
        }

        .navbar a {
            color: white;
            text-decoration: none;
            margin: 0 15px;
            transition: color 0.3s;
        }

        .navbar a:hover {
            color: #ffcc00;
        }

        .container {
            background-color: rgba(255, 255, 255, 0.9);
            border-radius: 12px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            padding: 30px;
            width: 90%;
            max-width: 600px;
            margin: 100px auto;
            text-align: center;
        }

        h2 {
            text-align: center;
            color: #333;
        }

        .complaint-options {
            display: flex;
            justify-content: space-between;
            margin: 20px 0;
        }

        .option {
            background-color: #f1f1f1;
            border: 1px solid #ccc;
            border-radius: 10px;
            padding: 20px;
            width: 30%;
            text-align: center;
            cursor: pointer;
            transition: transform 0.3s, background-color 0.3s;
        }

        .option:hover {
            transform: scale(1.05);
            background-color: #ffcc00;
        }

        .icon {
            font-size: 40px;
            color: #ffcc00;
            margin-bottom: 10px;
        }

        label {
            font-weight: bold;
            margin-top: 10px;
            display: block;
            color: #333;
            text-align: right;
        }

        select, textarea, input[type="file"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            margin-top: 5px;
            box-sizing: border-box;
            direction: rtl;
        }

        button {
            background-color: #ffcc00;
            color: white;
            border: none;
            padding: 10px 15px;
            border-radius: 4px;
            cursor: pointer;
            width: 100%;
            margin-top: 15px;
        }

        button:hover {
            background-color: #e6b800;
        }

        footer {
            position: fixed;
            left: 0;
            bottom: 0;
            width: 100%;
            background: #1e1e2f;
            color: white;
            text-align: center;
            padding: 20px;
        }

        footer a {
            color: #ffcc00;
            text-decoration: none;
            margin: 0 10px;
            transition: color 0.3s;
        }

        footer a:hover {
            color: white;
        }

        .modal {
            display: none;
            position: fixed;
            z-index: 1001;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.7);
        }

        .modal-content {
            background-color: #fefefe;
            margin: 15% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
            max-width: 600px;
            border-radius: 10px;
            position: relative;
        }

        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }

        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }
    </style>
</head>
<body>

<!-- شريط التنقل -->
<div class="navbar">
    <h1 id="navbar-title">نظام السكن</h1>
    <div class="nav-links">
        <a href="#">HOME</a>
        <a href="#">ABOUT</a>
        <a href="#">SERVICES</a>
        <a href="#">Track Request</a>
        <a href="#">PAGES</a>
    </div>
</div>

<!-- قسم تقديم الشكوى -->
<div class="container">
    <h2><span class="material-icons icon">feedback</span> نموذج الشكوى</h2>

    <!-- خيارات نوع الشكوى -->
    <div class="complaint-options">
        <div class="option" onclick="selectComplaintType('مشرف')">
            <span class="material-icons icon">supervisor_account</span>
            <p>مشرف</p>
        </div>
        <div class="option" onclick="selectComplaintType('طالب')">
            <span class="material-icons icon">school</span>
            <p>طالب</p>
        </div>
        <div class="option" onclick="selectComplaintType('سكن')">
            <span class="material-icons icon">home</span>
            <p>سكن</p>
        </div>
    </div>

    <!-- النموذج -->
    <div id="complaintForm" style="display: none;">
        <form method="POST" enctype="multipart/form-data">
            <label for="complaint">تفاصيل الشكوى:</label>
            <textarea id="complaint" name="complaintDetails" rows="6" required></textarea>

            <label for="fileUpload">إرفاق صورة أو ملف:</label>
            <input type="file" id="fileUpload" name="fileUpload" accept="image/*,application/pdf">

            <input type="hidden" name="complaintType" id="complaintType">
            <button type="submit">إرسال الشكوى</button>
        </form>
    </div>

    <!-- عرض الرسائل -->
    <?php if (isset($success_message)) : ?>
        <p style="color: green;"><?php echo $success_message; ?></p>
    <?php elseif (isset($error_message)) : ?>
        <p style="color: red;"><?php echo $error_message; ?></p>
    <?php endif; ?>
</div>

<script>
    function selectComplaintType(type) {
        document.getElementById('complaintForm').style.display = 'block'; // إظهار النموذج
        document.getElementById('complaintType').value = type; // تعيين نوع الشكوى في الحقل المخفي
    }
</script>

</body>
</html>
