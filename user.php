<?php
session_start();

// متغيرات التهيئة
$name = "";
$username = "";
$email = "";
$password = "";
$errors = array();
$_SESSION['success'] = "";

// الاتصال بقاعدة البيانات
$db = mysqli_connect('localhost', 'abdullah', '12345678', 'mywebsite');

// إضافة مستخدم جديد
if (isset($_POST['add_user'])) {
    $name = mysqli_real_escape_string($db, $_POST['name']);
    $username = mysqli_real_escape_string($db, $_POST['username']);
    $email = mysqli_real_escape_string($db, $_POST['email']);
    $password_1 = mysqli_real_escape_string($db, $_POST['password_1']);
    $password_2 = mysqli_real_escape_string($db, $_POST['password_2']);

    // التحقق من الحقول الفارغة
    if (empty($name)) { array_push($errors, "Fullname is required"); }
    if (empty($username)) { array_push($errors, "Username is required"); }
    if (empty($email)) { array_push($errors, "Email is required"); }
    if (empty($password_1)) { array_push($errors, "Password is required"); }
    if ($password_1 != $password_2) {
        array_push($errors, "Passwords do not match");
    }

    if (count($errors) == 0) {
        $password = md5($password_1);
        $query = "INSERT INTO user (name, username, email, password) VALUES ('$name', '$username', '$email', '$password')";
        mysqli_query($db, $query);
        $_SESSION['success'] = "User added successfully";
        header('location: admin_dashboard.php'); // توجيه إلى لوحة التحكم
    }
}

// تحديث معلومات مستخدم
if (isset($_POST['update_user'])) {
    $id = mysqli_real_escape_string($db, $_POST['id']);
    $name = mysqli_real_escape_string($db, $_POST['name']);
    $username = mysqli_real_escape_string($db, $_POST['username']);
    $email = mysqli_real_escape_string($db, $_POST['email']);
    
    $query = "UPDATE user SET name='$name', username='$username', email='$email' WHERE id='$id'";
    mysqli_query($db, $query);
    $_SESSION['success'] = "User updated successfully";
    header('location: admin_dashboard.php'); // توجيه إلى لوحة التحكم
}

// حذف مستخدم
if (isset($_POST['delete_user'])) {
    $id = mysqli_real_escape_string($db, $_POST['id']);
    $query = "DELETE FROM user WHERE id='$id'";
    mysqli_query($db, $query);
    $_SESSION['success'] = "User deleted successfully";
    header('location: admin_dashboard.php'); // توجيه إلى لوحة التحكم
}

// استعلام لجلب جميع المستخدمين
$users = [];
$query = "SELECT * FROM user";
$result = mysqli_query($db, $query);
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $users[] = $row;
    }
}
?>