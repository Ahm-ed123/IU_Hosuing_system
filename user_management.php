<?php
session_start();

// إعدادات الاتصال بقاعدة البيانات
$db = mysqli_connect('localhost', 'abdullah', '12345678', 'mywebsite');

// التحقق من الاتصال
if (!$db) {
    die("فشل الاتصال بقاعدة البيانات: " . mysqli_connect_error());
}

// استعلام للمستخدمين
$query = "SELECT * FROM user";
$result = mysqli_query($db, $query);

// معالجة الحذف
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    $delete_query = "DELETE FROM user WHERE id='$delete_id'";
    mysqli_query($db, $delete_query);
    header("Location: user_management.php");
    exit();
}

// إضافة أو تحديث المستخدم
if (isset($_POST['save_user'])) {
    $name = mysqli_real_escape_string($db, $_POST['name']);
    $username = mysqli_real_escape_string($db, $_POST['username']);
    $email = mysqli_real_escape_string($db, $_POST['email']);
    $college = mysqli_real_escape_string($db, $_POST['college']);
    $region = mysqli_real_escape_string($db, $_POST['region']);
    $term = mysqli_real_escape_string($db, $_POST['term']);
    $level = mysqli_real_escape_string($db, $_POST['level']);
    $password = $_POST['password_1'] ? md5($_POST['password_1']) : null;

    // التحقق إذا كان المستخدم موجودًا
    $check_query = "SELECT * FROM user WHERE username='$username' OR name='$name'";
    $check_result = mysqli_query($db, $check_query);

    if (mysqli_num_rows($check_result) > 0) {
        // تحديث المستخدم إذا كان موجودًا
        $query = "UPDATE user SET email='$email', college='$college', region='$region', term='$term', level='$level'" . ($password ? ", password='$password'" : '') . " WHERE username='$username' OR name='$name'";
    } else {
        // إضافة مستخدم جديد
        $query = "INSERT INTO user (name, username, email, password, college, region, term, level) VALUES ('$name', '$username', '$email', '$password', '$college', '$region', '$term', '$level')";
    }

    if (mysqli_query($db, $query)) {
        header("Location: user_management.php");
        exit();
    } else {
        echo "<script>alert('خطأ في حفظ البيانات: " . mysqli_error($db) . "');</script>";
    }
}

// إذا كان يتم تعديل مستخدم
$user = null;
if (isset($_GET['edit_id'])) {
    $user_id = $_GET['edit_id'];
    $query = "SELECT * FROM user WHERE id='$user_id'";
    $result = mysqli_query($db, $query);
    $user = mysqli_fetch_assoc($result);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Management</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .container {
            margin-top: 50px;
            max-width: 900px;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            background-color: white;
        }
        h2, h3 {
            color: #333;
        }
    .form-control {
    border-radius: 0.5rem;
    width: 100%; /* جعل الحقول بعرض متساوٍ */
    height: 25px; /* تصغير ارتفاع الحقول */
    font-size: 14px; /* تقليل حجم الخط داخل الحقول */
    padding: 4px 8px; /* تقليل الحشوة الداخلية */
}

        .btn {
            border-radius: 0.5rem;
        }
        .table {
            margin-top: 20px;
            width: 100%; /* جعل الجدول يتناسب مع عرض الحاوية */
        }
        .table th, .table td {
            text-align: center;
            overflow: hidden;
            word-wrap: break-word; /* لكسر الكلمات الطويلة */
            max-width: 150px; /* ضبط عرض الخلايا */
            padding: 8px; /* تقليل حشوة الخلايا */
            border: 1px solid black; /* حدود سوداء حول الخلايا */
        }
        .table thead {
            background-color: #007bff; /* لون رأس الجدول */
            color: white; /* لون النص في رأس الجدول */
        }
    </style>
</head>
<body>

<div class="container">
    <h2 class="text-center">User Management</h2>

    <form method="post" action="">
        <div class="form-group">
            <label>Full Name</label>
            <input type="text" name="name" class="form-control" value="<?php echo htmlspecialchars($user['name'] ?? ''); ?>" required>
        </div>
        <div class="form-group">
            <label>University ID</label>
            <input type="text" name="username" class="form-control" value="<?php echo htmlspecialchars($user['username'] ?? ''); ?>" required>
        </div>
        <div class="form-group">
            <label>Email</label>
            <input type="email" name="email" class="form-control" value="<?php echo htmlspecialchars($user['email'] ?? ''); ?>" required>
        </div>
        <div class="form-group">
            <label>College</label>
            <select name="college" class="form-control" required>
                <option value="">Select College</option>
                <option value="computer" <?php if (($user['college'] ?? '') == 'computer') echo 'selected'; ?>>Computer</option>
                <option value="engineering" <?php if (($user['college'] ?? '') == 'engineering') echo 'selected'; ?>>Engineering</option>
                <option value="science" <?php if (($user['college'] ?? '') == 'science') echo 'selected'; ?>>Science</option>
            </select>
        </div>
        <div class="form-group">
            <label>Region</label>
            <input type="text" name="region" class="form-control" value="<?php echo htmlspecialchars($user['region'] ?? ''); ?>" required>
        </div>
        <div class="form-group">
            <label>Phone Number</label>
            <input type="tel" name="term" class="form-control" value="<?php echo htmlspecialchars($user['term'] ?? ''); ?>" required>
        </div>
        <div class="form-group">
            <label>Level</label>
            <input type="text" name="level" class="form-control" value="<?php echo htmlspecialchars($user['level'] ?? ''); ?>" required>
        </div>
        <div class="form-group">
            <label>Password (Leave blank to keep current password)</label>
            <input type="password" name="password_1" class="form-control">
        </div>
        <button type="submit" name="save_user" class="btn btn-primary btn-block">Save User</button>
    </form>

    <h3 class="mt-5">Existing Users</h3>
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>Full Name</th>
                <th>University ID</th>
                <th>Email</th>
                <th>College</th>
                <th>Region</th>
                <th>Phone Number</th>
                <th>Level</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['name'] ?? ''); ?></td>
                    <td><?php echo htmlspecialchars($row['username'] ?? ''); ?></td>
                    <td><?php echo htmlspecialchars($row['email'] ?? ''); ?></td>
                    <td><?php echo htmlspecialchars($row['college'] ?? ''); ?></td>
                    <td><?php echo htmlspecialchars($row['region'] ?? ''); ?></td>
                    <td><?php echo htmlspecialchars($row['term'] ?? ''); ?></td>
                    <td><?php echo htmlspecialchars($row['level'] ?? ''); ?></td>
                    <td>
                        <a href="?edit_id=<?php echo $row['id']; ?>" class="btn btn-warning btn-sm">Edit</a>
                        <a href="?delete_id=<?php echo $row['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this user?');">Delete</a>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>