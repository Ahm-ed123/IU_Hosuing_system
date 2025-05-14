<?php 
session_start(); 
 include 'Convert.php'; 

// بدء الجلسة

// الاتصال بقاعدة البيانات
$host = 'localhost';  // يمكن تغييره إذا كنت تستخدم خادمًا خارجيًا
$user = 'root';  // اسم المستخدم لقاعدة البيانات
$password = '';  // كلمة المرور لقاعدة البيانات
$dbname = 'mywebsite';  // اسم قاعدة البيانات الخاصة بك
$conn = new mysqli($host, $user, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// جلب حالة الحجز للطالب باستخدام student_id
$student_id = $_SESSION['user_id']; // الحصول على student_id من الجلسة
$sql_booking = "SELECT status FROM bookings WHERE student_id = $student_id ORDER BY booking_date DESC LIMIT 1";
$result_booking = $conn->query($sql_booking);

if ($result_booking->num_rows > 0) {
    $row = $result_booking->fetch_assoc();
    $status = $row['status']; // الحالة التي تم جلبها
} else {
    $status = 'empty'; // إذا لم يوجد حجز للطالب
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="<?php echo $lang; ?>">
<head>
<script type="text/javascript">
    function googleTranslateElementInit() {
      var userLanguage = navigator.language || navigator.userLanguage; // تحديد لغة المتصفح
      new google.translate.TranslateElement({
        pageLanguage: 'ar',  // اللغة الأصلية للموقع (هنا العربية)
        includedLanguages: 'ar,en,fr,de',  // اللغات المدعومة
        layout: google.translate.TranslateElement.InlineLayout.SIMPLE
      }, 'google_translate_element');
      
      // تحديد اللغة بناءً على لغة المتصفح
      if (userLanguage.startsWith("en")) {
        google.translate.TranslateElement.prototype.load('en');
      } else if (userLanguage.startsWith("fr")) {
        google.translate.TranslateElement.prototype.load('fr');
      } else {
        google.translate.TranslateElement.prototype.load('ar');
      }
    }
  </script>
  <script type="text/javascript" src="https://translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>نظام السكن - الصفحة الرئيسية</title>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        

.language-buttons {
    position: absolute;
    top: 75px;
    left: 20px;
    display: flex;
    gap: 10px;
}

.language-buttons button {
    background-color: transparent;
    border: 1px solid #ffcc00;
    color: #ffcc00;
    padding: 5px 10px;
    cursor: pointer;
    font-size: 14px;
    transition: background-color 0.3s;
}

.language-buttons button:hover {
    background-color: #ffcc00;
    color: #fff;
}

        body {
            font-family: 'Cairo', sans-serif;
            margin: 0;
            padding: 0;
            background: url('p2.png') no-repeat center center fixed;
            background-size: cover;
            color: #333;
            line-height: 1.6;
        }

        /* شريط التنقل */
        .navbar {
            position: fixed;
            top: 0;
            width: 100%;
            display: flex;
            align-items: center;
            padding: 15px 30px;
            background: #1e1e2f;
            color: white;
            z-index: 1000;
        }

        .navbar h1 {
            font-size: 24px;
            margin: 0;
            color: #ffcc00;
            position: absolute;
            left: 30px;
        }

        .navbar .nav-links {
            display: flex;
            align-items: center;
            margin-left: auto;
            margin-right: auto;
        }

        .navbar a {
            color: white;
            text-decoration: none;
            margin: 0 15px;
            transition: color 0.3s;
            position: relative;
        }

        .navbar a:hover {
            color: #ffcc00;
        }

        /* أيقونة المستخدم */
        .user-icon {
            font-size: 28px;
            cursor: pointer;
            margin-left: 20px;
        }

        /* قائمة منسدلة */
        .dropdown {
            position: relative;
        }

        .dropdown-content {
            display: none;
            position: absolute;
            background-color: #f9f9f9;
            min-width: 160px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            z-index: 1;
        }

        .dropdown-content a {
            color: black;
            padding: 12px 16px;
            text-decoration: none;
            display: block;
            text-align: left;
        }

        .dropdown-content a:hover {
            background-color: #f1f1f1;
        }

        .dropdown:hover .dropdown-content {
            display: block;
        }

        /* الهيدر */
        .header {
            color: white;
            text-align: center;
            height: 100vh;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 20px;
            background: rgba(0, 0, 0, 0.5);
        }

        .header h1 {
            font-size: 3.5em;
            margin-bottom: 20px;
            text-shadow: 2px 2px 8px rgba(0, 0, 0, 0.5);
        }

        .header p {
            font-size: 1.2em;
            max-width: 600px;
            margin: 20px auto;
        }

        /* الأقسام */
        .section {
            padding: 80px 20px;
            text-align: center;
        }

        .section-title {
            font-size: 2.5em;
            color: #1e1e2f;
            margin-bottom: 40px;
        }

        /* الخدمات */
        .services {
            background: #f8f9fa;
        }

        .service-cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
        }

        .service-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            padding: 30px;
            transition: transform 0.3s, box-shadow 0.3s;
        }

        .service-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
            background-color: #ffcc00;
        }

        .service-card h3 {
            font-size: 1.5em;
            color: #0f3460;
            margin-bottom: 15px;
        }

        .service-card p {
            font-size: 1em;
            color: #555;
            margin-bottom: 20px;
        }

        .service-card a {
            display: inline-block;
            background: #ffcc00;
            color: black;
            padding: 10px 20px;
            border-radius: 5px;
            text-decoration: none;
            font-size: 1em;
            transition: background-color 0.3s;
        }

        .service-card a:hover {
            background-color: #e6b800;
        }

        /* الفوتر */
        footer {
            background: #1e1e2f;
            color: white;
            text-align: center;
            padding: 20px;
            margin-top: 40px;
        }

        footer a {
            color: #ffcc00;
            text-decoration: none;
            margin: 0 10px;
            transition: color 0.3s;
        }

        footer a:hover {
            color: white;
        }

        /* القسم السفلي */
        .bottom-section {
            background-color: #222;
            color: white;
            padding: 40px 20px;
        }

        .bottom-section .container {
            display: flex;
            justify-content: space-between;
            flex-wrap: wrap;
        }

        .bottom-section .column {
            width: 30%;
            margin-bottom: 20px;
        }

        .bottom-section h3 {
            margin-bottom: 15px;
        }

        .bottom-section a {
            color: #ffcc00;
            text-decoration: none;
        }

        .bottom-section a:hover {
            text-decoration: underline;
        }

        /* أيقونات وسائل التواصل الاجتماعي */
        .bottom-section a {
            margin-right: 10px;
            font-size: 24px;
            color: #ffcc00;
            transition: color 0.3s;
        }

        .bottom-section a:hover {
            color: white;
        }

        /* نافذة منبثقة */
        .modal {
            display: none;
            position: fixed;
            z-index: 1001;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.7);
        }

        .modal-content {
            background-color: #fefefe;
            margin: 15% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
            max-width: 600px;
            border-radius: 10px;
            position: relative;
        }

        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }

        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }
    </style>
</head>
<body>

<!-- أزرار تغيير اللغة -->
<div class="language-buttons">
    <button onclick="changeLanguage('en')">English</button>
    <button onclick="changeLanguage('ar')">العربية</button>
</div>


<!-- شريط التنقل -->
<div class="navbar">
    <h1 id="navbar-title">نظام السكن</h1>
    <div class="nav-links">
        <a href="#"></a>

        <!-- هنا يتم التحقق من حالة الحجز لتحديد عرض الخدمة -->
        <?php if ($status == 'empty'): ?>
            <a href="booking_page.php"> </a> <!-- فقط إذا كانت الحالة "empty" -->
        <?php elseif ($status == 'accepted'): ?>
            <!-- عرض جميع الخدمات إذا كانت الحالة "accepted" -->
            <a href="track_request.php"> </a>
            <a href="monitor_requests.php">متابعة الطلبات</a>
            <a href="#"> </a>
        <?php else: ?>
            <p>حالتك لم يتم قبولها بعد. يرجى الانتظار أو التواصل مع الدعم.</p>
        <?php endif; ?>

        <a href="logout.php">تسجيل الخروج</a> <!-- رابط تسجيل الخروج -->
        <i class="fas fa-user user-icon" id="userIcon"></i> <!-- أيقونة المستخدم -->
    </div>
</div>

<!-- نافذة منبثقة لمعلومات المستخدم -->
<div id="userModal" class="modal">
    <div class="modal-content">
        <span class="close" id="closeUserModal">&times;</span>
        <h2 id="userName"><?php echo isset($_SESSION['name']) ? $_SESSION['name'] : 'المعلومات  الاكاديمية'; ?></h2>
        <p id="userEmail"><?php echo isset($_SESSION['email']) ? $_SESSION['email'] : 'غير متوفر'; ?></p>
        <p>username: <?php echo isset($_SESSION['username']) ? $_SESSION['username'] : 'غير متوفر'; ?></p>
        <p>#ID : <?php echo isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 'غير متوفر'; ?></p>
        <p>college: <?php echo isset($_SESSION['college']) ? $_SESSION['college'] : 'غير متوفر'; ?></p>
        <p>City: <?php echo isset($_SESSION['region']) ? $_SESSION['region'] : 'غير متوفر'; ?></p>
        <p>term: <?php echo isset($_SESSION['term']) ? $_SESSION['term'] : 'غير متوفر'; ?></p>
        <p>level: <?php echo isset($_SESSION['level']) ? $_SESSION['level'] : 'غير متوفر'; ?></p>
    </div>
</div>

<!-- نافذة منبثقة -->
<div id="aboutModal" class="modal">
    <div class="modal-content">
        <span class="close" id="closeModal">&times;</span>
        <h2>معلومات عن موقع سكن الطلاب</h2>
        <p>نظام السكن يقدم خدمات متعددة للطلاب، بما في ذلك حجز الغرف، طلبات الصيانة، وتقديم الشكاوى. هدفنا هو توفير بيئة مريحة وآمنة للطلاب، حيث يمكنهم التركيز على دراستهم وتطوير مهاراتهم.</p>
    </div>
</div>

<!-- الهيدر -->
<header class="header">
    <h1 id="header-title">مرحبًا بك في نظام السكن الجامعي</h1>
    <p id="header-description">نوفر أفضل الخدمات للطلاب بطريقة سهلة وسريعة.</p>
</header>
<div id="google_translate_element"></div>
<!-- قسم الخدمات -->
<section class="section services">
    <h2 id="services-title" class="section-title">الخدمات</h2>
    <div class="service-cards">
        <?php if ($status == 'empty'): ?>
        <!-- إذا كانت الحالة "empty"، عرض خدمة حجز الغرفة فقط -->
        <div class="service-card" id="service1">
            <h3 id="service1-title">حجز غرفة</h3>
            <p id="service1-desc">احجز غرفتك بسهولة عبر موقعنا الإلكتروني.</p>
            <a href="service1.php" id="service1-link">ابدأ الآن</a>
        </div>
        <?php elseif ($status == 'accepted'): ?>
        <!-- إذا كانت الحالة "accepted"، عرض جميع الخدمات -->
       
        <div class="service-card" id="service2">
            <h3 id="service2-title">طلب صيانة</h3>
            <p id="service2-desc">يمكنك إرسال طلبات الصيانة بسهولة وسرعة.</p>
            <a href="service2.php" id="service2-link">اطلب الآن</a>
        </div>
        <div class="service-card" id="service3">
            <h3 id="service3-title">تقديم شكوى</h3>
            <p id="service3-desc">تواصل معنا وقدم شكواك لتحصل على الدعم اللازم.</p>
            <a href="service3.php" id="service3-link">قدم شكوى</a>
        </div>
        <div class="service-card" id="service4">
            <h3 id="service4-title">تجديد عقد / إخلاء طرف</h3>
            <p id="service4-desc">يمكنك تجديد عقدك أو تقديم طلب إخلاء الطرف بسهولة.</p>
            <a href="service4.html" id="service4-link">قم بالتقديم الآن</a>
        </div>
        <?php else: ?>
        <!-- إذا كانت الحالة غير "accepted" أو "empty"، عرض رسالة -->
        <p>حالتك لم تُقبل بعد. يرجى الانتظار أو التواصل مع الدعم.</p>
        <?php endif; ?>
    </div>
</section>

<!-- القسم السفلي -->
<div class="bottom-section">
    <div class="container">
        <div class="column">
            <h3>TOOLS</h3>
            <a href="#">All Image Generator</a><br>
            <a href="#">A Video Generator</a><br>
            <a href="#">Background Remover</a><br>
            <a href="#">Photo editor</a><br>
            <a href="#">Video Generator</a><br>
            <a href="#">All Freepik tools</a><br>
        </div>
        <div class="column">
            <h3>INFORMATION</h3>
            <a href="#">Pricing</a><br>
            <a href="#">About us</a><br>
            <a href="#">API</a><br>
            <a href="#">Jobs</a><br>
            <a href="#">Events</a><br>
            <a href="#">Blog</a><br>
        </div>
        <div class="column">
            <h3>LEGAL</h3>
            <a href="#">Terms of use</a><br>
            <a href="#">License agreement</a><br>
            <a href="#">Privacy policy</a><br>
            <a href="#">Copyright information</a><br>
            <a href="#">Cookies Settings</a><br>
        </div>
        <div class="column">
            <h3>SOCIAL MEDIA</h3>
            <a href="#"><i class="fab fa-whatsapp"></i></a>
            <a href="#"><i class="fab fa-twitter"></i></a>
            <a href="#"><i class="fab fa-instagram"></i></a>
        </div>
        <div class="column">
            <h3>SUPPORT</h3>
            <a href="#">FAQ</a><br>
            <a href="team.html">IT Team</a><br> 
        </div>
    </div>
</div>

<!-- الفوتر -->
<footer>
    <p id="footer-text">© 2025 نظام السكن. جميع الحقوق محفوظة.</p>
</footer>

<script>
    // دالة لتغيير اللغة عبر AJAX
function changeLanguage(lang) {
    $.get('Convert.php', { lang: lang }, function(data) {
        location.reload(); // إعادة تحميل الصفحة بعد تغيير اللغة
    });
}

    // فتح وإغلاق نافذة معلومات المستخدم
    var userModal = document.getElementById("userModal");
    var userIcon = document.getElementById("userIcon");
    var closeUserModal = document.getElementById("closeUserModal");

    userIcon.onclick = function() {
        userModal.style.display = "block";
    }

    closeUserModal.onclick = function() {
        userModal.style.display = "none";
    }

    window.onclick = function(event) {
        if (event.target == userModal) {
            userModal.style.display = "none";
        }
    }

    // فتح وإغلاق النافذة المنبثقة
    var modal = document.getElementById("aboutModal");
    var btn = document.getElementById("aboutBtn");
    var span = document.getElementById("closeModal");

    btn.onclick = function() {
        modal.style.display = "block";
    }

    span.onclick = function() {
        modal.style.display = "none";
    }

    window.onclick = function(event) {
        if (event.target == modal) {
            modal.style.display = "none";
        }
    }

    document.querySelectorAll('.dropdown-content a').forEach(anchor => {
        anchor.addEventListener('click', function(e) {
            e.preventDefault();
            const targetId = this.getAttribute('href');
            const targetElement = document.querySelector(targetId);
            
            targetElement.scrollIntoView({
                behavior: 'smooth'
            });
        });
    });
    </script>
    <script>  
(function(){if(!window.chatbase||window.chatbase("getState")!=="initialized"){window.chatbase=(...arguments)=>{if(!window.chatbase.q){window.chatbase.q=[]}window.chatbase.q.push(arguments)};window.chatbase=new Proxy(window.chatbase,{get(target,prop){if(prop==="q"){return target.q}return(...args)=>target(prop,...args)}})}const onLoad=function(){const script=document.createElement("script");script.src="https://www.chatbase.co/embed.min.js";script.id="O9AyqrK5ZZbG-NUeH0dxG";script.domain="www.chatbase.co";document.body.appendChild(script)};if(document.readyState==="complete"){onLoad()}else{window.addEventListener("load",onLoad)}})();
</script>

<script type="text/javascript">
    function googleTranslateElementInit() {
      var userLanguage = navigator.language || navigator.userLanguage; // تحديد لغة المتصفح
      new google.translate.TranslateElement({
        pageLanguage: 'ar',  // اللغة الأصلية للموقع (هنا العربية)
        includedLanguages: 'ar,en,fr,de',  // اللغات المدعومة
        layout: google.translate.TranslateElement.InlineLayout.SIMPLE
      }, 'google_translate_element');
      
      // تحديد اللغة بناءً على لغة المتصفح
      if (userLanguage.startsWith("en")) {
        google.translate.TranslateElement.prototype.load('en');
      } else if (userLanguage.startsWith("fr")) {
        google.translate.TranslateElement.prototype.load('fr');
      } else {
        google.translate.TranslateElement.prototype.load('ar');
      }
    }
  </script>
  <script type="text/javascript" src="https://translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>


</body>
</html>
