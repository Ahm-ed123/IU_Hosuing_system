<?php
session_start();

// تحقق من تسجيل الدخول
if (!isset($_SESSION['username'])) {
    header('location: login.php'); // إعادة التوجيه إلى صفحة تسجيل الدخول
    exit();
}

// استرجاع تفاصيل الحجز من الجلسة
$username = $_SESSION['username'];
$user_id = $_SESSION['user_id'];
$college = $_SESSION['college'];
$region = $_SESSION['region'];
$room_number = $_SESSION['room_number'] ?? 'غير محدد'; 
$term = $_SESSION['term'] ?? 'غير محدد'; 
$level = $_SESSION['level'] ?? 'غير محدد'; 
?>
<!DOCTYPE html>
<html lang="ar">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Housing System - الدفع</title>
    <style>
        body {
            background: linear-gradient(to bottom, #eef2f3, #8e9eab);
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            color: #333;
        }

        .container {
            max-width: 600px;
            margin: 50px auto;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
            text-align: right;
        }

        h1 {
            color: #1c3f5a;
            margin-bottom: 20px;
        }

        .details {
            background-color: #f7f9fc;
            padding: 15px;
            border: 1px solid #d1e7f0;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        .details p {
            margin: 5px 0;
            font-size: 16px;
        }

        form {
            margin-top: 20px;
        }

        .form-group {
            margin-bottom: 15px;
        }

        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }

        input[type="text"],
        input[type="email"],
        input[type="number"],
        input[type="file"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
            transition: border-color 0.3s;
        }

        input[type="text"]:focus,
        input[type="email"]:focus,
        input[type="number"]:focus {
            border-color: #1c3f5a;
        }

        input[type="button"] {
            background-color: #1c3f5a;
            color: white;
            border: none;
            padding: 15px;
            width: 100%;
            border-radius: 5px;
            font-size: 18px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        input[type="button"]:hover {
            background-color: #0b1a30;
        }

        .footer {
            text-align: center;
            margin-top: 20px;
            font-size: 14px;
            color: #666;
        }

        .payment-method {
            margin-bottom: 20px;
        }

        .hidden {
            display: none;
        }
    </style>
</head>

<body>

<div class="container">
    <h1>صفحة الدفع</h1>
    <div class="details">
        <h3>تفاصيل الحجز:</h3>
        <p><strong>اسم المستخدم:</strong> <?php echo htmlspecialchars($username); ?></p>
        <p><strong>معرف المستخدم:</strong> <?php echo htmlspecialchars($user_id); ?></p>
        <p><strong>الكلية:</strong> <?php echo htmlspecialchars($college); ?></p>
        <p><strong>المنطقة:</strong> <?php echo htmlspecialchars($region); ?></p>
        <p><strong>رقم الغرفة:</strong> <?php echo htmlspecialchars($room_number); ?></p>
        <p><strong>الترم:</strong> <?php echo htmlspecialchars($term); ?></p>
        <p><strong>المستوى:</strong> <?php echo htmlspecialchars($level); ?></p>
    </div>

    <form action="process_payment.php" method="POST" enctype="multipart/form-data">
        <div class="form-group payment-method">
            <label>طريقة الدفع:</label>
            <label><input type="radio" name="paymentMethod" value="card" onchange="togglePaymentFields()" required> بطاقة ائتمان</label>
            <label><input type="radio" name="paymentMethod" value="socialSupport" onchange="togglePaymentFields()"> مستفيد من الضمان الاجتماعي</label>
        </div>

        <!-- حقل الدفع عبر البطاقة -->
        <div id="cardPaymentFields" class="hidden">
            <div class="form-group">
                <label for="cardNumber">رقم البطاقة:</label>
                <input type="text" id="cardNumber" name="cardNumber" maxlength="16" placeholder="أدخل رقم البطاقة" required>
            </div>

            <div class="form-group">
                <label for="cardHolder">اسم حامل البطاقة:</label>
                <input type="text" id="cardHolder" name="cardHolder" placeholder="أدخل اسم حامل البطاقة" required>
            </div>

            <div class="form-group">
                <label for="expiryDate">تاريخ الانتهاء (MM/YY):</label>
                <input type="text" id="expiryDate" name="expiryDate" maxlength="5" placeholder="MM/YY" required>
            </div>

            <div class="form-group">
                <label for="cvv">رمز التحقق (CVV):</label>
                <input type="number" id="cvv" name="cvv" maxlength="3" placeholder="CVV" required>
            </div>
        </div>

        <!-- حقل الدفع عبر الضمان الاجتماعي -->
        <div id="socialSupportFields" class="hidden">
            <div class="form-group">
                <label for="supportFile">مشهد الضمان الاجتماعي (PDF):</label>
                <input type="file" id="supportFile" name="supportFile" accept="application/pdf" required>
            </div>
        </div>

        <input type="button" value="إتمام الدفع" onclick="window.location.href='report.php'">
    </form>

    <div class="footer">
        <p>جميع الحقوق محفوظة &copy; 2025 | Housing System</p>
    </div>
</div>

<script>
    // دالة لإظهار الحقول بناءً على طريقة الدفع
    function togglePaymentFields() {
        const cardFields = document.getElementById('cardPaymentFields');
        const socialFields = document.getElementById('socialSupportFields');
        const paymentMethod = document.querySelector('input[name="paymentMethod"]:checked');

        // إظهار وإخفاء الحقول بناءً على الاختيار
        if (paymentMethod && paymentMethod.value === 'card') {
            cardFields.classList.remove('hidden');
            socialFields.classList.add('hidden');
        } else if (paymentMethod && paymentMethod.value === 'socialSupport') {
            socialFields.classList.remove('hidden');
            cardFields.classList.add('hidden');
        } else {
            cardFields.classList.add('hidden');
            socialFields.classList.add('hidden');
        }
    }
</script>

</body>
</html>