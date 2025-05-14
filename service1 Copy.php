<?php
session_start();

// تحقق من تسجيل الدخول
if (!isset($_SESSION['username'])) {
    header('location: login.php'); // إعادة التوجيه إلى صفحة تسجيل الدخول
    exit();
}

// تحقق من الكلية المخزنة في الجلسة
$college = $_SESSION['college'] ?? '';
?>

<!DOCTYPE html>
<html lang="ar">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Housing System - الصفحة الرئيسية</title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
    <style>
        /* إعداد الخلفية المتدرجة */
        body {
            background: linear-gradient(to bottom, #aeeeee, #7d7d7d);
            margin: 0;
            font-family: Arial, sans-serif;
        }

        /* تنسيق الشريط العلوي */
        .navbar {
            background: linear-gradient(135deg, #0b1a30, #1c3f5a);
            padding: 15px;
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: space-between;
            font-size: 20px;
            box-shadow: 0px 4px 12px rgba(0, 0, 0, 0.2);
            position: relative;
            z-index: 1000;
            border-radius: 0 0 10px 10px;
        }

        .navbar h1 {
            margin: 0;
            font-size: 26px;
            font-family: 'Arial Black', sans-serif;
            flex-grow: 1;
        }

        .navbar .logo {
            width: 80px;
            height: 80px;
            object-fit: contain;
            border-radius: 50%;
            margin-left: 10px;
        }

        /* زر الثلاث خطوط */
        .toggle-btn {
            cursor: pointer;
            font-size: 30px;
            color: white;
        }

        /* تنسيق القائمة الجانبية */
        .sidebar {
            height: 100%;
            width: 250px;
            position: fixed;
            top: 0;
            left: -250px;
            background-color: #1c3f5a;
            overflow-x: hidden;
            transition: 0.5s;
            padding-top: 60px;
            z-index: 1000;
        }

        .sidebar a {
            padding: 10px 15px;
            text-decoration: none;
            font-size: 20px;
            color: white;
            display: block;
            transition: 0.3s;
        }

        .sidebar a:hover {
            background-color: #0b1a30;
        }

        .close-btn {
            font-size: 30px;
            position: absolute;
            top: 10px;
            right: 20px;
            color: white;
            cursor: pointer;
        }

        .container {
            background-color: #fff;
            border-radius: 12px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            padding: 30px;
            width: 90%;
            max-width: 500px;
            margin: 40px auto;
            text-align: center;
        }

        /* تنسيق بروفايل المستخدم */
        .profile {
            background-color: #e7f3fe;
            border: 1px solid #1c3f5a;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            text-align: left;
        }

        .profile img {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            margin-right: 15px;
        }

        .profile h3 {
            margin: 0;
            font-size: 20px;
            color: #1c3f5a;
            font-weight: bold;
        }

        .profile p {
            margin: 5px 0;
            font-size: 16px;
            color: #333;
        }

        .map-title {
            text-align: center;
            font-size: 24px;
            color: #1c3f5a;
            margin: 10px 0;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 1px;
            padding: 10px;
            background-color: rgba(255, 255, 255, 0.8);
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
        }

        /* تنسيق الخريطة */
        #map {
            height: 300px;
            width: 100%;
            border-radius: 12px;
            margin: 20px 0;
        }

        /* تنسيق تفاصيل الحجز */
        .booking-details {
            display: none;
            opacity: 0;
            transition: opacity 0.5s ease-in-out;
            background-color: #e7f3fe;
            border-left: 5px solid #1c3f5a;
            border-radius: 8px;
            padding: 20px;
            margin-top: 20px;
            text-align: right;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .booking-details.show {
            display: block;
            opacity: 1;
        }

        .booking-details h3 {
            margin-top: 0;
            color: #1c3f5a;
        }

        /* تنسيق نموذج الحجز */
        .form-group {
            margin-bottom: 15px;
            text-align: left;
        }

        label {
            display: block;
            font-weight: bold;
            margin-bottom: 5px;
            color: #333;
        }

        select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
            transition: border-color 0.3s;
        }

        select:focus {
            border-color: #1c3f5a;
            outline: none;
        }

        button {
            background-color: #1c3f5a;
            color: #fff;
            border: none;
            padding: 10px 15px;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s;
            width: 100%;
        }

        button:hover {
            background-color: #0b1a30;
        }
    </style>
</head>

<body>

<div class="navbar">
    <span class="toggle-btn" onclick="toggleSidebar()">☰</span>
    <img src="ل.jpg" alt="شعار النظام" class="logo">
    <h1>Housing System</h1>
</div>

<!-- القائمة الجانبية: تبدأ مخفية وتظهر تحت الزر -->
<div class="sidebar" id="sidebar">
    <div class="close-btn" onclick="toggleSidebar()">❌</div>
    <ul>
        <li><a href="index.html">الصفحة الرئيسية</a></li>
        <li><a href="#about">حول النظام</a></li>
        <li><a href="#services">الخدمات</a></li>
        <li>
            <a href="#" onclick="showContactDetails(event)">تواصل معنا</a>
            <div id="contactDetails" class="contact-details" style="display: none;">
                <p>رقم الجوال: 0580282546</p>
                <p>واتساب: <a href="https://wa.me/0580282546" target="_blank">0580282546</a></p>
            </div>
        </li>
    </ul>
</div>

<div class="map-title">خريطة الوحدات السكنية</div>
<div id="map"></div>

<div class="container">
    <div class="profile">
        <img src="prof.png" alt="Profile Picture"> <!-- صورة بروفايل -->
        <div>
            <h3>Profile</h3> <!-- عنوان البروفايل -->
            <p>username: <?php echo $_SESSION['username']; ?></p> <!-- عرض اسم المستخدم المخزن في الجلسة -->
            <p>ID: <?php echo $_SESSION['user_id']; ?></p> <!-- عرض معرف المستخدم -->
            <p>college: <?php echo $_SESSION['college']; ?></p> <!-- عرض اسم الكلية المخزنة في الجلسة -->
            <p>City: <?php echo $_SESSION['region']; ?></p> <!-- عرض المنطقة المخزنة في الجلسة -->
            <p>Term: <?php echo $_SESSION['term']; ?></p> <!-- عرض الفصل الدراسي المخزن في الجلسة -->
            <p>Level: <?php echo $_SESSION['level']; ?></p> <!-- عرض المستوى المخزنة في الجلسة -->
        </div>
    </div>

    <h2>حجز غرفة</h2>
    <form id="bookingForm">
        <div class="form-group">
            <label for="term">اختر الترم:</label>
            <select id="term" name="term" required>
                <option value="">اختر الترم</option>
                <option value="first">الترم الأول</option>
                <option value="second">الترم الثاني</option>
                <option value="therd">الترم الصيفي</option>
            </select>
        </div>

        <div class="form-group">
            <label for="level">المستوى الدراسي:</label>
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
        </div>

        <button type="button" onclick="showBookingDetails()">حجز الآن</button>
    </form>

    <div id="bookingDetails" class="booking-details">
        <h3>تفاصيل الحجز:</h3>
        <p id="detailsText"></p>
        <p id="roomDetailsText"></p> <!-- لعرض تفاصيل الغرفة المختارة -->

        <!-- زر الدفع -->
        <button onclick="window.location.href='Pay.php'">ادفع</button>
    </div>
</div>

<script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>

<script>
    // إعداد الخريطة وتحديد موقع الجامعة الإسلامية
    const map = L.map('map').setView([24.481798, 39.556274], 15);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    }).addTo(map);

    // تحديد الكلية بناءً على معلومات الطالب
    const college = "<?php echo $_SESSION['college']; ?>"; // الحصول على الكلية من الجلسة

    // إضافة دبوس واحد فقط بناءً على الكلية
    let markers = [];
    if (college === 'computer') {
        markers.push({ coords: [24.481300, 39.557000], title: 'Computer Science/unit-10', id: 'computer' });
    } else if (college === 'engineering') {
        markers.push({ coords: [24.481800, 39.556600], title: 'Engineering/unit-11', id: 'Engineering' });
    } else if (college === 'science') {
        markers.push({ coords: [24.482500, 39.556200], title: 'Science/unit-12', id: 'sciences' });
    }

    markers.forEach(markerData => {
        const marker = L.marker(markerData.coords).addTo(map);
        marker.bindTooltip(markerData.title, { permanent: true, direction: 'top' });

        marker.on('click', function() {
            showRoomSelection(markerData.title); // عرض اختيار الوحدة
        });
    });

    // دالة لعرض اختيار الوحدات
    function showRoomSelection(college) {
        let roomNumber; // تعريف متغير رقم الغرفة

        // تحديد رقم الغرفة بناءً على الكلية
        if (college === 'Computer Science/unit-10') {
            roomNumber = Math.floor(Math.random() * 10) + 1; // من 1 إلى 10
        } else if (college === 'Engineering/unit-11') {
            roomNumber = Math.floor(Math.random() * 10) + 11; // من 11 إلى 20
        } else if (college === 'Science/unit-12') {
            roomNumber = Math.floor(Math.random() * 5) + 21; // من 21 إلى 25
        }

        // تحقق مما إذا كانت الغرفة متاحة من خلال استعلام AJAX
        fetch('check_availability.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ roomNumber: roomNumber })
        })
        .then(response => response.json())
        .then(data => {
            if (data.available) {
                const userResponse = confirm(`تم اختيار غرفة ${roomNumber} لك في كلية ${college}.\nهل ترغب في تأكيد الحجز؟`);
                if (userResponse) {
                    const userId = "<?php echo $_SESSION['user_id']; ?>"; // معرف الطالب
                    const level = document.getElementById("level").value; // المستوى الدراسي
                    const term = document.getElementById("term").value; // الترم المحدد

                    // إرسال البيانات إلى booking.php
                    fetch('booking.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({
                            userId: userId,
                            roomNumber: roomNumber,
                            college: college,
                            level: level,
                            term: term
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            document.getElementById("roomDetailsText").innerHTML = `تم تأكيد الحجز لغرفة ${roomNumber} في كلية ${college}!`;
                            alert('تم تأكيد الحجز بنجاح!');
                        } else {
                            alert('فشل في تأكيد الحجز: ' + data.message);
                        }
                    })
                    .catch((error) => {
                        console.error('Error:', error);
                        alert('حدث خطأ أثناء الحجز.');
                    });
                } else {
                    document.getElementById("roomDetailsText").innerHTML = '';
                    alert('تم إلغاء الحجز.');
                }
            } else {
                alert('عذرًا، الغرفة غير متاحة. يرجى اختيار غرفة أخرى.');
            }
        })
        .catch((error) => {
            console.error('Error:', error);
            alert('حدث خطأ أثناء التحقق من التوفر.');
        });
    }

    // دالة لإظهار تفاصيل الحجز
    function showBookingDetails() {
        const level = document.getElementById("level").value;
        const userId = "<?php echo $_SESSION['user_id']; ?>"; // الحصول على معرف المستخدم
        const username = "<?php echo $_SESSION['username']; ?>"; // الحصول على اسم المستخدم
        const region = "<?php echo $_SESSION['region']; ?>"; // الحصول على المنطقة
        const term = document.getElementById("term").value; // الحصول على الترم المحدد

        const detailsText = `
            ID: ${userId} <br>
            username: ${username} <br>
            COLLEGE: ${college} <br>
            region: ${region} <br>
            level: ${level} <br>
            term: ${term} <br>
        `;

        document.getElementById("detailsText").innerHTML = detailsText;
        const bookingDetails = document.getElementById("bookingDetails");
        bookingDetails.classList.add('show');
        alert('الرجاء الضغط على كليتك في الخريطة.');
    }

    // وظيفة لتفعيل القائمة الجانبية
    function toggleSidebar() {
        const sidebar = document.getElementById('sidebar');
        if (sidebar.style.left === '0px') {
            sidebar.style.left = '-250px'; // إخفاء القائمة
        } else {
            sidebar.style.left = '0px'; // إظهار القائمة
        }
    }

    // وظيفة لإظهار تفاصيل الاتصال
    function showContactDetails(event) {
        event.preventDefault();
        const contactDetails = document.getElementById('contactDetails');
        contactDetails.style.display = contactDetails.style.display === 'block' ? 'none' : 'block';
    }
</script>

</body>

</html>