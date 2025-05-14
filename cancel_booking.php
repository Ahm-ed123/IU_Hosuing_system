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

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $userId = $_POST['userId'];
    $unitId = $_POST['unitId'];

    // تحقق من وجود الحجز
    $stmt = $conn->prepare("SELECT * FROM bookings WHERE student_id = ? AND unit_id = ?");
    $stmt->bind_param("ii", $userId, $unitId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // حذف الحجز
        $deleteStmt = $conn->prepare("DELETE FROM bookings WHERE student_id = ? AND unit_id = ?");
        $deleteStmt->bind_param("ii", $userId, $unitId);
        if ($deleteStmt->execute()) {
            // تحديث عدد الغرف المحجوزة
            $updateStmt = $conn->prepare("UPDATE housing_units SET booked_rooms = booked_rooms - 1 WHERE id = ?");
            $updateStmt->bind_param("i", $unitId);
            $updateStmt->execute();
            
            echo json_encode(['success' => true, 'message' => 'تم إلغاء الحجز بنجاح']);
        } else {
            echo json_encode(['success' => false, 'message' => 'فشل في إلغاء الحجز']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'لا يوجد حجز لهذا الطالب في هذه الوحدة']);
    }

    $conn->close();
}
?>
