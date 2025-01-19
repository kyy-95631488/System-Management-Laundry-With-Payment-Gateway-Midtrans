<?php
    session_start();

    // Check if user is already logged in
    if (isset($_SESSION['user_id'])) { // Sesuaikan dengan session yang digunakan di cek_login.php
        switch ($_SESSION['role']) {
            case 'manage':
                header('location: ../manage/manage/');
                exit();
            case 'owner':
                header('location: ../manage/owner/');
                exit();
            case 'user':
                header('location: ../manage/user/');
                exit();
            default:
                header('location: ../manage/kasir/');
                exit();
        }
    }
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Login - Mikada Laundry</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!--===============================================================================================-->
    <link rel="icon" type="image/png" href="../manage/assets/img/Laundry.png" />
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="../manage/assets/login/vendor/bootstrap/css/bootstrap.min.css">
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="../manage/assets/login/fonts/font-awesome-4.7.0/css/font-awesome.min.css">
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="../manage/assets/login/fonts/Linearicons-Free-v1.0.0/icon-font.min.css">
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="../manage/assets/login/vendor/animate/animate.css">
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="../manage/assets/login/vendor/css-hamburgers/hamburgers.min.css">
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="../manage/assets/login/vendor/animsition/css/animsition.min.css">
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="../manage/assets/login/vendor/select2/select2.min.css">
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="../manage/assets/login/vendor/daterangepicker/daterangepicker.css">
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="../manage/assets/login/css/util.css">
    <link rel="stylesheet" type="text/css" href="../manage/assets/login/css/main.css">
    <!--===============================================================================================-->
    <style>
        .login100-form-btn {
            background: linear-gradient(to right, #00c6ff, #0072ff);
            border-radius: 25px;
            transition: 0.3s ease;
        }

        .login100-form-btn:hover {
            background: linear-gradient(to left, #00c6ff, #0072ff);
            transform: scale(1.05);
        }

        .login100-form-btn:active {
            transform: scale(0.98);
        }

        .container-login100-form-btn {
            margin-bottom: 20px;
        }

        .login100-form-title {
            font-size: 24px;
            color: #333;
            font-weight: 700;
        }

        .login100-form-title.gray-text {
            color: gray;
            font-size: 16px;
            font-weight: normal;
        }

        .login100-form-btn a {
            color: white;
            text-decoration: none;
            display: block;
            text-align: center;
            padding: 12px;
        }

        .login100-form-btn a:hover {
            text-decoration: underline;
        }
        .back-button a {
            display: inline-flex;
            align-items: center;
            background: linear-gradient(to right, #0072ff, #00c6ff);
            color: white;
            padding: 10px 20px;
            border-radius: 4px;
            font-size: 14px;
            font-weight: bold;
            text-decoration: none;
            transition: all 0.3s ease;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .back-button a:hover {
            background: linear-gradient(to left, #0072ff, #00c6ff);
            transform: translateY(-2px);
            box-shadow: 0 6px 8px rgba(0, 0, 0, 0.15);
            text-decoration: none;
        }

        .back-button a i {
            margin-right: 8px;
            font-size: 16px;
        }
        /* Menambahkan gaya untuk ikon show/hide password */
        .password-toggle {
            cursor: pointer;
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            font-size: 18px;
            color: #999; /* Warna ikon default */
            transition: color 0.3s ease, transform 0.3s ease; /* Efek transisi */
        }

        .password-toggle:hover {
            color: #0072ff; /* Ubah warna ikon saat hover */
            transform: scale(1.2); /* Membesarkan ikon saat hover */
        }

        /* Efek transisi saat ikon berubah (fa-eye / fa-eye-slash) */
        .password-toggle.fa-eye {
            color: gray; /* Warna ikon saat password tidak terlihat */
        }

        .password-toggle.fa-eye-slash {
            color: #e74c3c; /* Warna ikon saat password terlihat */
        }
    </style>
    <!--===============================================================================================-->
</head>

<body>
    <div class="limiter">
        <div class="container-login100" style="background-image: url('../manage/assets/login/images/mikada-laundry-bg01.png');">
            <div class="wrap-login100 p-t-30 p-b-50">
                <!-- Back Button -->
                <div class="back-button">
                    <a href="javascript:history.back()" class="btn btn-link">
                        <i class="fa fa-arrow-left"></i> Back
                    </a>
                </div>
                <span class="login100-form-title p-b-41">
                    <img src="../manage/assets/img/Laundry.png" alt="Mikada Laundry Logo" style="max-width: 50%; height: auto;">
                </span>
                <form class="login100-form validate-form p-b-33 p-t-5" action="cek_login.php" method="post">
                    <?php if (isset($_GET['message'])) : ?>
                        <div class="row justify-content-center">
                            <small class="alert alert-danger" role="alert">
                                <?= $_GET['message']; ?>
                            </small>
                        </div>
                    <?php endif ?>
                    <div class="wrap-input100 validate-input" data-validate="Enter username">
                        <input class="input100" type="text" name="username" placeholder="User name">
                        <span class="focus-input100" data-placeholder="&#xe82a;"></span>
                    </div>

                    <div class="wrap-input100 validate-input" data-validate="Enter password">
                        <input id="password" class="input100" type="password" name="password" placeholder="Password">
                        <span class="focus-input100" data-placeholder="&#xe80f;"></span>
                        <i class="fa fa-eye password-toggle" id="togglePassword" style="cursor: pointer; position: absolute; right: 10px; top: 50%; transform: translateY(-50%); font-size: 16px; margin-right: 20px;"></i>
                    </div>


                    <div class="container-login100-form-btn m-t-32">
                        <button class="login100-form-btn" type="submit">
                            Login
                        </button>
                    </div>
                    <div class="container-login100-form-btn">
                        <a href="../register/" class="login100-form-btn">
                            Register</a>
                    </div>

                </form>
            </div>
        </div>
    </div>

    <div id="dropDownSelect1"></div>
    
    <script>
        const togglePassword = document.querySelector("#togglePassword");
        const passwordInput = document.querySelector("#password");

        togglePassword.addEventListener("click", function () {
            // Toggle the type attribute
            const type = passwordInput.getAttribute("type") === "password" ? "text" : "password";
            passwordInput.setAttribute("type", type);

            // Toggle the icon class
            this.classList.toggle("fa-eye");
            this.classList.toggle("fa-eye-slash");
        });
    </script>


    <!--===============================================================================================-->
    <script src="../manage/assets/login/vendor/jquery/jquery-3.2.1.min.js"></script>
    <!--===============================================================================================-->
    <script src="../manage/assets/login/vendor/animsition/js/animsition.min.js"> </script>
    <script src="../manage/assets/login/vendor/bootstrap/js/popper.js"></script>
    <script src="../manage/assets/login/vendor/bootstrap/js/bootstrap.min.js"></script>
    <!--===============================================================================================-->
    <script src="../manage/assets/login/vendor/select2/select2.min.js"></script>
    <!--===============================================================================================-->
    <script src="../manage/assets/login/vendor/daterangepicker/moment.min.js"></script>
    <script src="../manage/assets/login/vendor/daterangepicker/daterangepicker.js"></script>
    <!--===============================================================================================-->
    <script src="../manage/assets/login/vendor/countdowntime/countdowntime.js"></script>
    <!--===============================================================================================-->
    <script src="../manage/assets/login/js/main.js"></script>
</body>

</html>