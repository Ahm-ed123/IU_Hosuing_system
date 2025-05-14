<?php
session_start();
$errors = array();

// تحقق مما إذا كان المستخدم مسجلاً الدخول
if (!isset($_SESSION['user_id'])) {
    header('location: login.php');
    exit();
}

// إعداد الاتصال بقاعدة البيانات
$servername = "localhost";
$username = "abdullah";
$password = "12345678"; // الافتراضي في WAMP بدون كلمة مرور
$dbname = "mywebsite"; // اسم قاعدة البيانات

// إنشاء الاتصال
$db = mysqli_connect($servername, $username, $password, $dbname);

// تحقق من الاتصال
if (!$db) {
    die("فشل الاتصال: " . mysqli_connect_error());
}

// معالجة استلام البيانات
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // استلام البيانات
    $room_number = mysqli_real_escape_string($db, $_POST['room_number']);
    $user_id = $_SESSION['user_id'];
    $level = mysqli_real_escape_string($db, $_POST['level']);
    $specialization = mysqli_real_escape_string($db, $_POST['specialization']);
    $bed = mysqli_real_escape_string($db, $_POST['bed']);

    // تحقق من الحقول المطلوبة
    if (empty($room_number)) { array_push($errors, "رقم الغرفة مطلوب"); }
    if (empty($level)) { array_push($errors, "المستوى مطلوب"); }
    if (empty($specialization)) { array_push($errors, "التخصص مطلوب"); }
    if (empty($bed)) { array_push($errors, "نوع السرير مطلوب"); }

    // إذا لم يكن هناك أخطاء، قم بإجراء الحجز
    if (count($errors) == 0) {
        $query = "INSERT INTO room_bookings (room_number, user_id, level, specialization, bed) 
                  VALUES ('$room_number', '$user_id', '$level', '$specialization', '$bed')";
        if (mysqli_query($db, $query)) {
            echo "<div class='booking-details'><p>تم حجز الغرفة بنجاح!</p></div>";
        } else {
            array_push($errors, "خطأ في الحجز: " . mysqli_error($db));
        }
    }
}
?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>حجز غرفة</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f2f2f2;
            display: flex;
        }
        .sidebar {
            width: 250px;
            background: linear-gradient(to bottom, #002147, #004080);
            color: white;
            padding: 15px;
            height: 100vh;
        }
        .sidebar-header h2 {
            text-align: center;
            margin-bottom: 15px;
        }
        .sidebar-menu {
            list-style-type: none;
            padding: 0;
        }
        .sidebar-menu li {
            padding: 10px;
            cursor: pointer;
        }
        .sidebar-menu li:hover {
            background-color: #003366;
        }
        .sidebar-menu i {
            margin-right: 10px;
        }
        .container {
            flex: 1;
            padding: 20px;
            max-width: 400px;
            margin: auto;
            border: 1px solid #ccc;
            border-radius: 8px;
            background-color: white;
            display: flex;
            flex-direction: column;
            justify-content: flex-start;
        }
        h2 {
            text-align: center;
            margin-bottom: 10px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        td {
            padding: 5px;
            vertical-align: top;
            text-align: left;
        }
        label {
            display: block;
            margin-bottom: 5px;
        }
        select, input {
            width: 100%;
            padding: 8px;
            margin: 0;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 14px;
        }
        button {
            width: 100%;
            padding: 10px;
            background-color: #00aaff;
            color: white;
            border: none;
            cursor: pointer;
            margin-top: 10px;
        }
        button:hover {
            background-color: #0099e6;
        }
        .error {
            color: red;
            margin-top: 10px;
            text-align: center;
        }
        .booking-details {
            margin-top: 20px;
            padding: 10px;
            background-color: #e7f3fe;
            border-left: 6px solid #2196F3;
            text-align: center;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>حجز غرفة</h2>

    <form method="post" action="">
        <table>
            <tr>
                <td><label for="room_number">رقم الغرفة:</label></td>
                <td><input type="text" name="room_number" required></td>
            </tr>
            <tr>
                <td><label for="level">المستوى الدراسي:</label></td>
                <td>
                    <select id="level" name="level" required>
                        <option value="1">1</option>
                        <option value="2">2</option>
                        <option value="3">3</option>
                        <option value="4">4</option>
                        <option value="5">5</option>
                        <option value="6">6</option>
                        <option value="7">7</option>
                        <option value="8">8</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td><label for="specialization">تخصص الطالب:</label></td>
                <td>
                    <select id="specialization" name="specialization" required>
                        <option value="IT">تقنية المعلومات</option>
                        <option value="IS">نظم المعلومات</option>
                        <option value="CE">هندسة الحاسوب</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td><label for="bed">رقم السرير:</label></td>
                <td><input type="text" name="bed" required></td>
            </tr>
        </table>
        <button type="submit">احجز الغرفة</button>
    </form>

    <?php if (count($errors) > 0): ?>
        <div class="error">
            <?php foreach ($errors as $error): ?>
                <p><?php echo $error; ?></p>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

</div>

<!-- الشريط الجانبي -->
<aside class="sidebar">
    <div class="sidebar-header">
        <h2>لوحة التحكم</h2>
    </div>
    <ul class="sidebar-menu">
        <li><i class="fas fa-home"></i><a href="admin_dashboard.php" style="color: white; text-decoration: none;"> الصفحة الرئيسية</a></li>
    </ul>
</aside>

</body>
</html>