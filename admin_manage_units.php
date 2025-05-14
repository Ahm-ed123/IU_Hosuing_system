<?php
session_start();

// الاتصال بقاعدة البيانات
$host = 'localhost';
$user = 'root';
$password = '';
$dbname = 'mywebsite';
$conn = new mysqli($host, $user, $password, $dbname);

// التحقق من الاتصال بقاعدة البيانات
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// إضافة وحدة سكنية جديدة
if (isset($_POST['add'])) {
    $name = $_POST['name'];
    $total_rooms = $_POST['total_rooms'];
    $booked_rooms = $_POST['booked_rooms'];

    $sql_add = "INSERT INTO housing_units (name, total_rooms, booked_rooms) 
                VALUES ('$name', '$total_rooms', '$booked_rooms')";
    $conn->query($sql_add);
}

// تعديل وحدة سكنية
if (isset($_POST['edit'])) {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $total_rooms = $_POST['total_rooms'];
    $booked_rooms = $_POST['booked_rooms'];

    $sql_edit = "UPDATE housing_units SET name='$name', total_rooms='$total_rooms', booked_rooms='$booked_rooms' 
                 WHERE id=$id";
    $conn->query($sql_edit);
}

// حذف وحدة سكنية
if (isset($_POST['delete'])) {
    $id = $_POST['id'];
    $sql_delete = "DELETE FROM housing_units WHERE id=$id";
    $conn->query($sql_delete);
}

// استرجاع البيانات من جدول housing_units
$sql = "SELECT * FROM housing_units";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إدارة الوحدات السكنية - مانج يونت</title>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Cairo', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }

        .container {
            width: 80%;
            margin: 30px auto;
            background-color: white;
            padding: 20px;
            box-shadow: 0px 0px 10px rgba(0,0,0,0.1);
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        table, th, td {
            border: 1px solid #ddd;
        }

        th, td {
            padding: 10px;
            text-align: center;
        }

        th {
            background-color: #f4f4f4;
        }

        .button {
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
            border: none;
            cursor: pointer;
        }

        .button:hover {
            background-color: #0056b3;
        }

        .form-group {
            margin-bottom: 15px;
        }

        input[type="text"], input[type="number"] {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            border: 1px solid #ddd;
        }

        .form-actions {
            display: flex;
            justify-content: space-between;
        }

        .form-actions button {
            padding: 10px;
            background-color: #28a745;
            color: white;
            border: none;
            cursor: pointer;
        }

        .form-actions button:hover {
            background-color: #218838;
        }
    </style>
</head>
<body>

<div class="container">
    <h1>إدارة الوحدات السكنية - مانج يونت</h1>

    <!-- عرض جدول الوحدات السكنية -->
    <table>
        <thead>
            <tr>
                <th>الاسم</th>
                <th>عدد الغرف</th>
                <th>عدد الغرف المحجوزة</th>
                <th>الإجراءات</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()) { ?>
                <tr>
                    <td><?php echo $row['name']; ?></td>
                    <td><?php echo $row['total_rooms']; ?></td>
                    <td><?php echo $row['booked_rooms']; ?></td>
                    <td>
                        <!-- زر التعديل -->
                        <form method="post" style="display:inline;">
                            <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                            <button type="submit" name="edit" class="button">تعديل</button>
                        </form>
                        <!-- زر الحذف -->
                        <form method="post" style="display:inline;">
                            <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                            <button type="submit" name="delete" class="button" style="background-color: #dc3545;">حذف</button>
                        </form>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>

    <!-- نموذج إضافة وحدة سكنية جديدة -->
    <h2>إضافة وحدة سكنية جديدة</h2>
    <form method="post">
        <div class="form-group">
            <label for="name">اسم الوحدة:</label>
            <input type="text" id="name" name="name" required><br>
            
            <label for="total_rooms">عدد الغرف:</label>
            <input type="number" id="total_rooms" name="total_rooms" required><br>
            
            <label for="booked_rooms">عدد الغرف المحجوزة:</label>
            <input type="number" id="booked_rooms" name="booked_rooms" required><br>
        </div>
        <div class="form-actions">
            <button type="submit" name="add" class="button">إضافة وحدة سكنية</button>
        </div>
    </form>
</div>

</body>
</html>

<?php
// إغلاق الاتصال بقاعدة البيانات
$conn->close();
?>
