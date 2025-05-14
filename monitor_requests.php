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

// استعلام لعرض طلبات الصيانة
$sql_maintenance = "SELECT * FROM maintenance_requests";
$result_maintenance = $conn->query($sql_maintenance);

// استعلام لعرض الشكاوى
$sql_complaints = "SELECT * FROM complaints";
$result_complaints = $conn->query($sql_complaints);
?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>مراقبة الطلبات</title>
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
            max-width: 1000px;
            margin: 100px auto;
            text-align: center;
        }

        h2 {
            text-align: center;
            color: #333;
        }

        table {
            width: 100%;
            margin-top: 30px;
            border-collapse: collapse;
        }

        th, td {
            padding: 10px;
            text-align: center;
            border: 1px solid #ddd;
        }

        th {
            background-color: #1e1e2f;
            color: white;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
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

<!-- قسم مراقبة الطلبات -->
<div class="container">
    <h2>مراقبة طلبات الصيانة والشكاوى</h2>

    <!-- جدول طلبات الصيانة -->
    <h3>طلبات الصيانة</h3>
    <table>
        <thead>
            <tr>
                <th>رقم الطلب</th>
                <th>موقع البلاغ</th>
                <th>التحويلة</th>
                <th>تفاصيل الطلب</th>
                <th>الصورة المرفقة</th>
                <th>التاريخ</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($result_maintenance->num_rows > 0) {
                while ($row = $result_maintenance->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $row["request_number"] . "</td>";
                    echo "<td>" . $row["report_location"] . "</td>";
                    echo "<td>" . $row["transfer"] . "</td>";
                    echo "<td>" . $row["complaint"] . "</td>";
                    echo "<td><a href='" . $row["image_url"] . "' target='_blank'>عرض الصورة</a></td>";
                    echo "<td>" . (isset($row["created_at"]) ? $row["created_at"] : 'غير محدد') . "</td>";  // التعامل مع الحقل created_at بشكل آمن
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='6'>لا توجد طلبات صيانة حالياً</td></tr>";
            }
            ?>
        </tbody>
    </table>

    <!-- جدول الشكاوى -->
    <h3>الشكاوى</h3>
    <table>
        <thead>
            <tr>
                <th>رقم الشكوى</th>
                <th>نوع الشكوى</th>
                <th>تفاصيل الشكوى</th>
                <th>الصورة المرفقة</th>
                <th>التاريخ</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($result_complaints->num_rows > 0) {
                while ($row = $result_complaints->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $row["complaint_number"] . "</td>";
                    echo "<td>" . $row["complaint_type"] . "</td>";
                    echo "<td>" . $row["complaint_details"] . "</td>";
                    echo "<td><a href='" . $row["file_url"] . "' target='_blank'>عرض الملف</a></td>";
                    echo "<td>" . (isset($row["created_at"]) ? $row["created_at"] : 'غير محدد') . "</td>";  // التعامل مع الحقل created_at بشكل آمن
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='5'>لا توجد شكاوى حالياً</td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>

<!-- الفوتر -->
<footer>
    <p id="footer-text">© 2025 نظام السكن. جميع الحقوق محفوظة.</p>
</footer>

</body>
</html>

<?php
// إغلاق الاتصال بقاعدة البيانات
$conn->close();
?>
