<?php  
include('server.php');
include 'Convert.php';
?>



<!DOCTYPE html>
<html lang="<?php echo $lang; ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <style>
        .lang-switcher {
    position: absolute;
    top: 20px;
    left: 20px;
}

.lang-btn {
    background-color: #007bff;
    color: white;
    padding: 6px 12px;
    margin-right: 5px;
    text-decoration: none;
    border-radius: 5px;
    font-size: 14px;
    transition: background-color 0.3s ease;
}

.lang-btn:hover {
    background-color: #0056b3;
}

        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            font-family: Arial, sans-serif;
            background: url('p2.png') no-repeat center center fixed;
            background-size: cover;
            color: white;
        }
        .container {
            display: flex;
            align-items: center;
            background: rgba(255, 255, 255, 0.9);
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
        }
        .login-form {
            display: flex;
            flex-direction: column;
            align-items: flex-start;
            margin-left: 20px;
        }
        .login-form h2 {
            color: #333;
            margin-bottom: 15px;
        }
        .input-group {
            display: flex;
            align-items: center;
            margin-bottom: 15px;
            width: 100%;
        }
        .input-group span {
            margin-right: 10px;
            font-size: 1.5rem;
            color: #888;
        }
        .input-group input {
            flex: 1;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 1rem;
        }
        .input-group input:focus {
            border-color: #007bff;
            outline: none;
        }
        .auth-button {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 10px 15px;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        .auth-button:hover {
            background-color: #0056b3;
        }
        .auth-switch {
            margin-top: 10px;
            color: black; /* لون النص */
        }
        .auth-switch a {
            color: #007bff; /* أزرق */
            text-decoration: none; /* إزالة الخط السفلي */
        }
        .auth-switch a:hover {
            text-decoration: underline; /* خط سفلي عند التمرير */
        }
        .image-section img {
            width: 250px;
            height: auto;
        }
        .header {
            position: absolute;
            top: 20px;
            text-align: center;
            width: 100%;
            color: white;
        }
        .header h1 {
            font-size: 2.5rem;
            margin: 0;
color: #FFFFFF; 
        }
        .header p {
            font-size: 1.2rem;
                 color: #FFFFFF; 
        }
    </style>
</head>
<body>
<div class="lang-switcher">
    <a href="?lang=en" class="lang-btn">English</a>
    <a href="?lang=ar" class="lang-btn">العربية</a>
</div>



<div class="container">
    <div class="login-form">
        <h2><?php echo $translations['login']; ?></h2>
        <form method="post" action="login.php">
            <?php include('errors.php'); ?>
            <div class="input-group">
                <span class="icon">&#128100;</span>
                <input type="text" name="username" value="<?php echo $username; ?>" required placeholder="<?php echo $translations['student_id']; ?>">
            </div>
            <div class="input-group">
                <span class="icon">&#128274;</span>
                <input type="password" name="password" value="<?php echo $password; ?>" required placeholder="<?php echo $translations['password']; ?>">
            </div>
            <button type="submit" class="auth-button" name="login"><?php echo $translations['login_button']; ?></button>
            <p class="auth-switch"><?php echo $translations['no_account']; ?> <a href="Register.php"><?php echo $translations['register_now']; ?></a></p>
        </form>
    </div>
    <div class="image-section">
        <img src="image.png" alt="Login Illustration">
    </div>
</div>
 
</body>
</html>
