<?php
session_start();
// متغيرات التهيئة
$name = "";
$username = "";
$password = "";
$email = "";
$errors = array();
$_SESSION['success'] = "";

// الاتصال بقاعدة البيانات
$db = mysqli_connect('localhost', 'abdullah', '12345678', 'mywebsite');

// التحقق من الاتصال
if (!$db) {
    die("Connection failed: " . mysqli_connect_error());
}

// متغيرات للأخطاء
$errors = [];

// كود إضافة مستخدم
if (isset($_POST['add_user'])) {
    $name = mysqli_real_escape_string($db, $_POST['name']);
    $username = mysqli_real_escape_string($db, $_POST['username']);
    $email = mysqli_real_escape_string($db, $_POST['email']);
    $password_1 = mysqli_real_escape_string($db, $_POST['password_1']);
    $password_2 = mysqli_real_escape_string($db, $_POST['password_2']);

    // تحقق من الحقول الفارغة
    if (empty($name)) { array_push($errors, "الاسم مطلوب"); }
    if (empty($username)) { array_push($errors, "اسم المستخدم مطلوب"); }
    if (empty($email)) { array_push($errors, "البريد الإلكتروني مطلوب"); }
    if (empty($password_1)) { array_push($errors, "كلمة المرور مطلوبة"); }
    if ($password_1 != $password_2) { array_push($errors, "كلمات المرور غير متطابقة"); }

    if (count($errors) == 0) {
        $hashed_password = password_hash($password_1, PASSWORD_DEFAULT);
        $query = "INSERT INTO user (name, username, email, password) VALUES ('$name', '$username', '$email', '$hashed_password')";
        if (mysqli_query($db, $query)) {
            $_SESSION['success'] = "تم إضافة المستخدم بنجاح";
        } else {
            array_push($errors, "حدث خطأ أثناء إضافة المستخدم: " . mysqli_error($db));
        }
    }
}

// كود تحديث مستخدم
if (isset($_POST['update_user'])) {
    $id = mysqli_real_escape_string($db, $_POST['id']);
    $name = mysqli_real_escape_string($db, $_POST['name']);
    $username = mysqli_real_escape_string($db, $_POST['username']);
    $email = mysqli_real_escape_string($db, $_POST['email']);

    $query = "UPDATE user SET name='$name', username='$username', email='$email' WHERE id='$id'";
    if (mysqli_query($db, $query)) {
        $_SESSION['success'] = "تم تحديث المستخدم بنجاح";
    } else {
        array_push($errors, "حدث خطأ أثناء تحديث المستخدم: " . mysqli_error($db));
    }
}

// كود حذف مستخدم
if (isset($_POST['delete_user'])) {
    $id = mysqli_real_escape_string($db, $_POST['id']);
    $query = "DELETE FROM user WHERE id='$id'";
    if (mysqli_query($db, $query)) {
        $_SESSION['success'] = "تم حذف المستخدم بنجاح";
    } else {
        array_push($errors, "حدث خطأ أثناء حذف المستخدم: " . mysqli_error($db));
    }
}

// إعادة توجيه إلى صفحة إدارة المستخدمين بعد العمليات
if (count($errors) > 0) {
    $_SESSION['errors'] = $errors;
} 
header('location: admin_user_management.php');
?>