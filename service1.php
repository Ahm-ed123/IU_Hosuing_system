<?php
session_start();
if (!isset($_SESSION['username'])) {
    header('location: login.php');
    exit();
}

$conn = new mysqli("localhost", "root", "", "mywebsite");
if ($conn->connect_error) {
    die("فشل الاتصال بقاعدة البيانات");
}

$units = [];
$res = $conn->query("SELECT * FROM housing_units");
while ($row = $res->fetch_assoc()) {
    $units[] = $row;
}
?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>نظام الإسكان</title>

    <!-- إضافة مكتبة أيقونات Google -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

    <style>
        body {
            direction: rtl;
            font-family: Arial, sans-serif;
            background-color: #f0f0f0; /* خلفية محايدة */
            margin: 0;
            padding: 30px;
        }
        .navbar {
            background: linear-gradient(135deg, #3a3a3a, #5a5a5a); /* تدرج داكن */
            padding: 15px;
            color: white;
            text-align: center;
            font-size: 24px;
            border-radius: 0 0 10px 10px;
        }
        .unit-cards {
            display: flex;
            flex-wrap: wrap;
            gap: 30px; /* زيادة الفجوة بين الوحدات */
            margin-top: 30px;
            justify-content: center;
        }
        .unit-card {
            background: white;
            padding: 30px; /* زيادة padding */
            width: 280px; /* زيادة العرض */
            border: none; /* إزالة الحدود */
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            text-align: center;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .unit-card:hover {
            transform: scale(1.05);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
        }
        .unit-card h3 {
            color: #333; /* لون نص داكن */
            margin: 10px 0;
        }
        .unit-card p {
            margin: 10px 0;
            color: #555; /* لون نص أخف */
        }
        .unit-card button {
            padding: 12px 20px; /* زيادة حجم الأزرار */
            background-color: gold; /* لون زر ذهبي */
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            width: 100%; /* جعل الزر يأخذ العرض الكامل */
            transition: background-color 0.3s;
        }
        .unit-card button:hover {
            background-color: #ffd700; /* تدرج أفتح عند التحويم */
        }
        .unit-card button:disabled {
            background-color: #aaa;
            cursor: not-allowed;
        }
        .cancel-button {
            margin-top: 15px;
            padding: 12px 20px; /* زيادة حجم زر الإلغاء */
            background-color: #dc3545; /* لون زر الإلغاء */
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            display: none; /* Hidden by default, will be shown when needed */
        }
        .cancel-button:hover {
            background-color: #c82333; /* تدرج أغمق عند التحويم */
        }
        .material-icons {
            vertical-align: middle;
            margin-right: 5px;
        }
    </style>
</head>
<body>

<div class="navbar">الوحدات السكنية</div>

<div class="unit-cards">
    <?php foreach ($units as $unit): 
        $remaining = $unit['total_rooms'] - $unit['booked_rooms'];
    ?>
        <div class="unit-card" id="unit-<?= $unit['id'] ?>">
            <h3><?= htmlspecialchars($unit['name']) ?></h3>
            <p>الغرف المتبقية: <strong><?= $remaining ?></strong></p>
            <button onclick="confirmBooking(<?= $unit['id'] ?>, <?= $remaining ?>)" <?= $remaining <= 0 ? 'disabled' : '' ?>>
                <span class="material-icons">home</span>
                <?= $remaining > 0 ? 'احجز الآن' : 'مكتملة' ?>
            </button>
            <button class="cancel-button" onclick="cancelBooking(<?= $unit['id'] ?>)">إلغاء الحجز</button>
        </div>
    <?php endforeach; ?>
</div>

<script>
    function confirmBooking(unitId, remainingRooms) {
        const userId = <?= json_encode($_SESSION['user_id']) ?>;
        const level = <?= json_encode($_SESSION['level']) ?>;
        const term = <?= json_encode($_SESSION['term']) ?>;

        // اختيار رقم غرفة عشوائي بين الغرف المتبقية
        const roomNumber = Math.floor(Math.random() * remainingRooms) + 1;

        fetch('booking.php', {  // تأكيد الحجز إلى booking.php
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: `userId=${userId}&unitId=${unitId}&roomNumber=${roomNumber}&level=${level}&term=${term}`
        })
        .then(res => res.json())
        .then(data => {
            alert(data.message);
            if (data.success) {
                document.getElementById(`unit-${unitId}`).querySelector('.cancel-button').style.display = 'inline-block'; // إظهار زر إلغاء الحجز
                location.reload();
            }
        })
        .catch(() => alert('حدث خطأ أثناء تنفيذ الطلب.'));
    }

    function cancelBooking(unitId) {
        const userId = <?= json_encode($_SESSION['user_id']) ?>;
        
        if (confirm("هل أنت متأكد أنك تريد إلغاء الحجز؟")) {
            fetch('cancel_booking.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: `userId=${userId}&unitId=${unitId}`
            })
            .then(res => res.json())
            .then(data => {
                alert(data.message);
                if (data.success) {
                    document.getElementById(`unit-${unitId}`).querySelector('.cancel-button').style.display = 'none'; // إخفاء زر إلغاء الحجز
                    location.reload();
                }
            })
            .catch(() => alert('حدث خطأ أثناء إلغاء الحجز.'));
        }
    }
</script>

</body>
</html>