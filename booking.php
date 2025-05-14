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

// استلام البيانات من الطلب
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $userId = $_POST['userId'];
    $unitId = $_POST['unitId'];
    $roomNumber = $_POST['roomNumber'];
    $level = $_POST['level'];
    $term = $_POST['term'];
    $bookingDate = date('Y-m-d H:i:s'); // تاريخ الحجز الحالي

    // تحقق من وجود حجز مسبق للمستخدم
    $checkStmt = $conn->prepare("SELECT COUNT(*) AS count FROM bookings WHERE student_id = ?");
    $checkStmt->bind_param("i", $userId);
    $checkStmt->execute();
    $checkResult = $checkStmt->get_result()->fetch_assoc();

    if ($checkResult['count'] > 0) {
        echo json_encode(['success' => false, 'message' => 'لديك حجز واحد بالفعل.']);
        exit();
    }

    // تحقق من توفر غرف بالوحدة
    $unitStmt = $conn->prepare("SELECT total_rooms, booked_rooms FROM housing_units WHERE id = ?");
    $unitStmt->bind_param("i", $unitId);
    $unitStmt->execute();
    $unitData = $unitStmt->get_result()->fetch_assoc();

    if (!$unitData) {
        echo json_encode(['success' => false, 'message' => 'الوحدة غير موجودة.']);
        exit();
    }

    $available = $unitData['total_rooms'] - $unitData['booked_rooms'];
    if ($available <= 0) {
        echo json_encode(['success' => false, 'message' => 'هذه الوحدة ممتلئة ولا تحتوي على غرف شاغرة.']);
        exit();
    }

    // تسجيل الحجز
    $insert = $conn->prepare("INSERT INTO bookings (student_id, unit_id, booking_date, room_number, level, term) 
    VALUES (?, ?, ?, ?, ?, ?)");
    $insert->bind_param("iissss", $userId, $unitId, $bookingDate, $roomNumber, $level, $term);

    if ($insert->execute()) {
        // تحديث عدد الغرف المحجوزة
        $update = $conn->prepare("UPDATE housing_units SET booked_rooms = booked_rooms + 1 WHERE id = ?");
        $update->bind_param("i", $unitId);
        $update->execute();

        echo json_encode(['success' => true, 'message' => 'تم الحجز بنجاح']);
    } else {
        echo json_encode(['success' => false, 'message' => 'فشل في تسجيل الحجز']);
    }

    $conn->close();
}
?>
