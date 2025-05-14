<?php
include('server.php');
include('Convert.php');
?>
<!DOCTYPE html>
<html lang="<?php echo $lang; ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $translations['register_title']; ?></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/css/intlTelInput.css" />
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background: url('p2.png') no-repeat center center fixed;
            background-size: cover;
            font-family: Arial, sans-serif;
        }
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
        .container {
            display: flex;
            align-items: center;
            background: rgba(255, 255, 255, 0.9);
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 80%;
            max-width: 900px;
        }
        .signup-form {
            margin-left: 20px;
            flex: 1;
        }
        .image-section img {
            width: 250px;
            height: auto;
        }
        .input-group {
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            width: 100%;
        }
        .input-group .icon {
            margin-right: 10px;
            font-size: 20px;
            color: #0077b6;
        }
        .input-group label {
            width: 150px;
            margin: 0;
        }
        .input-group input, .input-group select {
            flex: 1;
            padding: 10px;
            border: 1px solid #0077b6;
            border-radius: 4px;
            background-color: #fff;
            color: #333;
            box-sizing: border-box;
        }
        button {
            width: 100%;
            padding: 10px;
            background-color: #0077b6;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            margin-top: 10px;
        }
        button:hover {
            background-color: #005f8a;
        }
        .register-title {
            font-size: 1.5rem;
            color: #FFA500;
            margin-bottom: 20px;
        }
        .auth-switch a {
            color: #FF8C00;
            text-decoration: none;
        }
        .auth-switch a:hover {
            text-decoration: underline;
        }
        .hidden {
            display: none;
        }
    </style>
    <script>
        let isAdditionalFieldsShown = false;

        function showMoreFields() {
            document.getElementById("additional-fields").classList.remove("hidden");
            document.getElementById("show-more-btn").classList.add("hidden");
            isAdditionalFieldsShown = true;
        }

        function validateForm(event) {
            if (!isAdditionalFieldsShown) {
                event.preventDefault();
                alert("<?php echo $translations['complete_form_alert']; ?>");
            }
        }
    </script>
</head>
<body>
    <div class="lang-switcher">
        <a href="?lang=en" class="lang-btn">English</a>
        <a href="?lang=ar" class="lang-btn">العربية</a>
    </div>
    <div class="container">
        <div class="signup-form">
            <h2><?php echo $translations['register_title']; ?></h2>
            <form method="post" action="Register.php" onsubmit="validateForm(event)">
                <?php include('errors.php'); ?>

                <div class="input-group">
                    <span class="icon">&#128100;</span>
                    <label><?php echo $translations['full_name']; ?></label>
                    <input type="text" name="name" value="<?php echo $name; ?>" required>
                </div>

                <div class="input-group">
                    <span class="icon">&#128192;</span>
                    <label><?php echo $translations['university_id']; ?></label>
                    <input type="text" name="username" value="<?php echo $username; ?>" required>
                </div>

                <div class="input-group">
                    <span class="icon">&#9993;</span>
                    <label><?php echo $translations['email']; ?></label>
                    <input type="email" name="email" value="<?php echo $email; ?>" required>
                </div>

                <button type="button" id="show-more-btn" onclick="showMoreFields()"><?php echo $translations['complete_form']; ?></button>

                <div id="additional-fields" class="hidden">
                    <div class="input-group">
                        <span class="icon">&#127891;</span>
                        <label><?php echo $translations['college']; ?></label>
                        <select name="college" required>
                            <option value=""><?php echo $translations['select_college']; ?></option>
                            <option value="computer"><?php echo $translations['college_computer']; ?></option>
                            <option value="engineering"><?php echo $translations['college_engineering']; ?></option>
                            <option value="science"><?php echo $translations['college_science']; ?></option>
                        </select>
                    </div>
                    <div class="input-group">
                        <span class="icon">&#127968;</span>
                        <label><?php echo $translations['region']; ?></label>
                        <input type="text" name="region" value="<?php echo $region; ?>" required>
                    </div>
                    <div class="input-group">
                        <span class="icon">&#128222;</span>
                        <label><?php echo $translations['phone_number']; ?></label>
                        <input type="tel" id="phone" name="term" value="<?php echo $term; ?>" required>
                    </div>
                    <div class="input-group">
                        <span class="icon">&#127937;</span>
                        <label><?php echo $translations['level']; ?></label>
                        <input type="text" name="level" value="<?php echo $level; ?>" required>
                    </div>
                    <div class="input-group">
                        <span class="icon">&#128274;</span>
                        <label><?php echo $translations['password']; ?></label>
                        <input type="password" name="password_1" value="<?php echo $password; ?>" required>
                    </div>
                    <div class="input-group">
                        <span class="icon">&#128273;</span>
                        <label><?php echo $translations['confirm_password']; ?></label>
                        <input type="password" name="password_2" required>
                    </div>
                </div>

                <button type="submit" class="btn" name="reg_user"><?php echo $translations['register']; ?></button>
                <p class="auth-switch">
                    <?php echo $translations['already_have_account']; ?> <a href="login.php"><?php echo $translations['login']; ?></a>
                </p>
            </form>
        </div>

        <div class="image-section">
            <img src="imge1.png" alt="Signup Illustration">
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/intlTelInput.min.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const phoneInputField = document.querySelector("#phone");
            const phoneInput = window.intlTelInput(phoneInputField, {
                preferredCountries: ["us", "co", "in", "de"],
                utilsScript: "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/utils.js",
            });
        });
    </script>
</body>
</html>
