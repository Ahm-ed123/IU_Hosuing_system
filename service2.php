<?php
// الاتصال بقاعدة البيانات
$host = 'localhost';  
$user = 'root';  
$password = '';  
$dbname = 'mywebsite';  
$conn = new mysqli($host, $user, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $report_location = $_POST['reportLocation'];
    $transfer = $_POST['transfer'];
    $complaint = $_POST['complaint'];

    // رفع الصورة
    $image_url = '';
    if (isset($_FILES['imageUpload']) && $_FILES['imageUpload']['error'] == 0) {
        // إضافة وقت للصورة لتفادي التعارض في الأسماء
        $image_name = time() . '-' . $_FILES['imageUpload']['name'];
        $image_tmp_name = $_FILES['imageUpload']['tmp_name'];
        $image_url = 'uploads/' . $image_name;

        // التأكد من أن الصورة بصيغة مدعومة (اختياري)
        $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif'];
        $image_extension = pathinfo($image_name, PATHINFO_EXTENSION);
        if (!in_array(strtolower($image_extension), $allowed_extensions)) {
            echo json_encode(["success" => false, "error" => "الملف غير مدعوم"]);
            exit;
        }

        // تأكد من نقل الصورة إلى المجلد المناسب
        if (move_uploaded_file($image_tmp_name, $image_url)) {
            // الصورة تم رفعها بنجاح
        } else {
            echo json_encode(["success" => false, "error" => "فشل في رفع الصورة"]);
            exit;
        }
    }

    // توليد رقم عشوائي للطلب
    $request_number = rand(100000, 999999);

    // إدخال البيانات في قاعدة البيانات
    $sql = "INSERT INTO maintenance_requests (report_location, transfer, complaint, image_url, request_number)
            VALUES ('$report_location', '$transfer', '$complaint', '$image_url', '$request_number')";

    if ($conn->query($sql) === TRUE) {
        echo json_encode(["success" => true, "request_number" => $request_number]);
    } else {
        echo json_encode(["success" => false, "error" => $conn->error]);
    }
    exit;
}
?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>طلب صيانة - الخدمة 2</title>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <style>
        /* إعداد الخلفية بتدرج رمادي */
        body {
            margin: 0;
            font-family: 'Cairo', sans-serif;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            background: linear-gradient(to bottom, #b0b0b0, #ffffff);
        }

        /* شريط التنقل */
        .navbar {
            background: #1e1e2f;
            padding: 15px;
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            position: relative;
            z-index: 1000;
        }

        .navbar h1 {
            margin: 0;
            font-size: 24px;
            color: #ffcc00;
            position: absolute;
            left: 30px;
        }

        .navbar .nav-links {
            display: flex;
            align-items: center;
            margin-left: 80px;
        }

        .navbar a {
            color: white;
            text-decoration: none;
            margin: 0 15px;
            transition: color 0.3s;
            position: relative;
        }

        .navbar a:hover {
            color: #ffcc00;
        }

        /* قسم الخدمات */
        .services {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin: 20px;
        }

        .service-row {
            display: flex;
            justify-content: center;
            margin: 10px;
        }

        .service-option {
            background-color: rgba(255, 255, 255, 0.9);
            border-radius: 12px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            padding: 20px;
            margin: 10px;
            text-align: center;
            width: 100px;
            transition: transform 0.3s, background-color 0.3s;
            cursor: pointer;
        }

        .service-option:hover {
            transform: scale(1.1);
            background-color: #ffcc00;
        }

        .icon {
            font-size: 40px;
            margin-bottom: 10px;
            color: #ffcc00;
        }

        /* قسم طلب الصيانة */
        .container {
            background-color: rgba(255, 255, 255, 0.9);
            border-radius: 12px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            padding: 30px;
            width: 90%;
            max-width: 500px;
            margin: 20px auto;
            text-align: center;
            display: none; /* بداية غير مرئي */
        }

        h2 {
            color: #333;
        }

        label {
            font-weight: bold;
            margin-top: 10px;
            display: block;
            color: #333;
            text-align: right;
        }

        select, textarea, input {
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
            font-size: 16px;
        }

        button:hover {
            background-color: #e6b800;
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
        <a href="#">ROOMS</a>
        <a href="#">PAGES</a>
    </div>
</div>

<!-- قسم الخدمات -->
<div class="services">
    <h2 style="text-align: center; color: #333; margin-bottom: 20px;">⚙️ خدمات الصيانة المتاحة</h2>
    <div class="service-row">
        <div class="service-option" onclick="showForm('نجاره')">
            <span class="material-icons icon">precision_manufacturing</span>
            <span>نجاره</span>
        </div>
        <div class="service-option" onclick="showForm('سباكة')">
            <span class="material-icons icon">water</span>
            <span>سباكة</span>
        </div>
    </div>
    <div class="service-row">
        <div class="service-option" onclick="showForm('كهرباء')">
            <span class="material-icons icon">electric_bolt</span>
            <span>كهرباء</span>
        </div>
        <div class="service-option" onclick="showForm('تكيف وتبريد')">
            <span class="material-icons icon">ac_unit</span>
            <span>تكيف</span>
        </div>
    </div>
    <div class="service-row">
        <div class="service-option" onclick="showForm('أجهزة صوتية')">
            <span class="material-icons icon">volume_up</span>
            <span>أجهزة صوتية</span>
        </div>
        <div class="service-option" onclick="showForm('الحداده')">
            <span class="material-icons icon">build</span>
            <span>الحداده</span>
        </div>
    </div>
</div>

<!-- قسم طلب الصيانة -->
<div class="container" id="requestForm">
    <h2>طلب الصيانة</h2>
    <form id="maintenanceForm" enctype="multipart/form-data">
        <label for="reportLocation">موقع البلاغ:</label>
        <input type="text" id="reportLocation" name="reportLocation" required>

        <label for="transfer">التحويلة:</label>
        <input type="text" id="transfer" name="transfer" required>

        <label for="complaint">تفاصيل الطلب:</label>
        <textarea id="complaint" name="complaint" rows="6" required></textarea>

        <label for="imageUpload">رفع صورة مرفقة:</label>
        <input type="file" id="imageUpload" name="imageUpload" accept="image/*">

        <button type="button" onclick="confirmSubmission()">إرسال الطلب</button>
    </form>
</div>

<script>
    // عند اختيار الخدمة، يظهر نموذج الطلب وتحديد نوع الخدمة في تفاصيل الطلب
    function showForm(service) {
        document.getElementById('requestForm').style.display = 'block'; 
        document.getElementById('complaint').value = `طلب خدمة: ${service}`; 
    }

    function confirmSubmission() {
        const reportLocation = document.getElementById('reportLocation').value;
        const transfer = document.getElementById('transfer').value;
        const complaint = document.getElementById('complaint').value;

        if (!reportLocation || !transfer || !complaint) {
            alert("يرجى تعبئة جميع الحقول قبل الإرسال.");
            return;
        }

        const formData = new FormData(document.getElementById("maintenanceForm"));
        fetch('service2.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert("تم تقديم الطلب بنجاح! رقم الطلب: " + data.request_number);
            } else {
                alert("خطأ: " + data.error);
            }
        })
        .catch(error => {
            alert("حدث خطأ: " + error.message);
        });
    }
</script>

</body>
</html>
