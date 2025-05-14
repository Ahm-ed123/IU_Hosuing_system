<?php
session_start();
header('Content-Type: application/json');

// إعداد الاتصال بقاعدة البيانات
$conn = new mysqli("localhost", "abdullah", "12345678", "mywebsite");

// التحقق من الاتصال
if ($conn->connect_error) {
    echo json_encode(['available' => false, 'message' => 'خطأ في الاتصال بقاعدة البيانات.']);
    exit();
}

// الحصول على رقم الغرفة من الطلب
$data = json_decode(file_get_contents("php://input"), true);
$roomNumber = $data['roomNumber'];

// تحقق من توفر الغرفة
$sql = "SELECT availability FROM `units` WHERE room_number = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $roomNumber);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $room = $result->fetch_assoc();
    echo json_encode(['available' => $room['availability']]);
} else {
    echo json_encode(['available' => false, 'message' => 'الغرفة غير موجودة.']);
}

$stmt->close();
$conn->close();
?>