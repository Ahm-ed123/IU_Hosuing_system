<?php

session_start();

// متغيرات التهيئة

$name = "";
$username = "";
$password = "";
$email = "";
$college = ""; // متغير الكلية
$region = "";  // متغير المنطقة
$term = "";    // متغير الفصل الدراسي
$level = "";   // متغير المستوى

$errors = array();

$_SESSION['success'] = "";

$itemname = "";
$price = "";
$amount = "";
$exp_d = "";
$type = "";
$notes = "";

// الاتصال بقاعدة البيانات

$db = mysqli_connect('localhost', 'abdullah', '12345678', 'mywebsite');

// كود التسجيل

if (isset($_POST['reg_user'])) {

    // استلام جميع القيم من النموذج 
    $name = mysqli_real_escape_string($db, $_POST['name']);
    $username = mysqli_real_escape_string($db, $_POST['username']);
    $email = mysqli_real_escape_string($db, $_POST['email']);
    $password_1 = mysqli_real_escape_string($db, $_POST['password_1']);
    $password_2 = mysqli_real_escape_string($db, $_POST['password_2']);
    $college = mysqli_real_escape_string($db, $_POST['college']); //
    $region = mysqli_real_escape_string($db, $_POST['region']);   // 
    $term = mysqli_real_escape_string($db, $_POST['term']);     // 
    $level = mysqli_real_escape_string($db, $_POST['level']);   // 

    // تحقق من الحقول الفارغة
    if (empty($name)) { array_push($errors, "Fullname is required"); }
    if (empty($username)) { array_push($errors, "Username is required"); }
    if (empty($email)) { array_push($errors, "Email is required"); }
    if (empty($password_1)) { array_push($errors, "Password is required"); }
    if (empty($college)) { array_push($errors, "College is required"); } // تحقق من الكلية
    if (empty($region)) { array_push($errors, "Region is required"); }   // تحقق من المنطقة
    if (empty($term)) { array_push($errors, "Term is required"); }       // تحقق من الفصل الدراسي
    if (empty($level)) { array_push($errors, "Level is required"); }     // تحقق من المستوى
    if ($password_1 != $password_2) {
        array_push($errors, "Passwords do not match");
    }

    // إذا لم يكن هناك أخطاء، قم بإدخال البيانات
    if (count($errors) == 0) {
        $password = md5($password_1); // تشفير كلمة المرور
        $query = "INSERT INTO user (name, username, email, password, college, region, term, level) 
                  VALUES ('$name', '$username', '$email', '$password', '$college', '$region', '$term', '$level')";
        mysqli_query($db, $query);

        $_SESSION['username'] = $username;
        $_SESSION['success'] = "Registration successful";
        header('location: home.php');
    }
}

// كود تسجيل الدخول

if (isset($_POST['login'])) {

    $username = mysqli_real_escape_string($db, $_POST['username']);
    $password = mysqli_real_escape_string($db, $_POST['password']);

    if (empty($username)) {
        array_push($errors, "Username is required");
    }

    if (empty($password)) {
        array_push($errors, "Password is required");
    }

    if (count($errors) == 0) {

        $password = md5($password);

        $query = "SELECT * FROM user WHERE username='$username' AND password='$password'";
        $results = mysqli_query($db, $query);

        if (mysqli_num_rows($results) == 1) {
            $user = mysqli_fetch_assoc($results);
            $_SESSION['username'] = $user['username'];
            $_SESSION['user_id'] = $user['id']; // حفظ معرف المستخدم
            $_SESSION['college'] = $user['college']; // تخزين الكلية في الجلسة
            $_SESSION['region'] = $user['region'];   // تخزين المنطقة في الجلسة
            $_SESSION['term'] = $user['term'];       // تخزين الفصل الدراسي في الجلسة
            $_SESSION['level'] = $user['level'];     // تخزين المستوى في الجلسة

            // التحقق مما إذا كان المستخدم هو المشرف
            if ($username == 'admin') {
                $_SESSION['success'] = "Login successful";
                header('location: admin_dashboard.php'); // توجيه المشرف إلى لوحة التحكم
            } else {
                $_SESSION['success'] = "Login successful";
                header('location: index.php'); // توجيه المستخدم العادي
            }
        } else {
            array_push($errors, "Error in username or password");
        }
    }
}

// باقي الأكواد كما هي...

?>