<?php
// بدء الجلسة
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// تعيين اللغة بناءً على الرابط أو الجلسة
if (isset($_GET['lang'])) {
    $_SESSION['lang'] = $_GET['lang']; // حفظ اللغة في الجلسة
}

// تعيين اللغة الافتراضية (إنجليزية)
$lang = $_SESSION['lang'] ?? 'en';

// تعيين مسار ملف الترجمة
$lang_file = __DIR__ . '/lang_' . $lang . '.php';

// التحقق من وجود ملف الترجمة
if (file_exists($lang_file)) {
    $translations = include($lang_file); // تحميل الترجمة
} else {
    // إذا لم يتم العثور على ملف الترجمة، استخدام الإنجليزية كاحتياطي
    $translations = include(__DIR__ . '/lang_en.php');
}
?>
