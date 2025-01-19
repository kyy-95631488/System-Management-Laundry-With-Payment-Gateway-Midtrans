<?php
    session_start();

    // Check if user is already logged in
    if (isset($_SESSION['id_user'])) {
        // Redirect to appropriate page based on role
        switch ($_SESSION['role']) {
            case 'manage':
                header('location: manage');
                exit();
            case 'owner':
                header('location: owner');
                exit();
            default:
                header('location: kasir');
                exit();
        }
    }
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Register - Mikada Laundry</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Add custom styles for enhanced button appearance -->
    <link rel="icon" type="image/png" href="../manage/assets/img/Laundry.png" />
    <link rel="stylesheet" type="text/css" href="../manage/assets/login/vendor/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="../manage/assets/login/fonts/font-awesome-4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="../manage/assets/login/fonts/Linearicons-Free-v1.0.0/icon-font.min.css">
    <link rel="stylesheet" type="text/css" href="../manage/assets/login/vendor/animate/animate.css">
    <link rel="stylesheet" type="text/css" href="../manage/assets/login/vendor/css-hamburgers/hamburgers.min.css">
    <link rel="stylesheet" type="text/css" href="../manage/assets/login/vendor/animsition/css/animsition.min.css">
    <link rel="stylesheet" type="text/css" href="../manage/assets/login/vendor/select2/select2.min.css">
    <link rel="stylesheet" type="text/css" href="../manage/assets/login/vendor/daterangepicker/daterangepicker.css">
    <link rel="stylesheet" type="text/css" href="../manage/assets/login/css/util.css">
    <link rel="stylesheet" type="text/css" href="../manage/assets/login/css/main.css">

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

        .wrap-input100 .focus-input100 i {
            position: absolute;
            top: 50%;
            left: 35px; /* Sesuaikan posisi ikon */
            transform: translateY(-50%);
            color: #999;
            pointer-events: none;
        }

        /* Message styles */
        .message {
            text-align: center;
            padding: 10px;
            margin-bottom: 20px;
            font-size: 16px;
            border-radius: 5px;
        }

        .message.success {
            background-color: #28a745;
            color: white;
        }

        .message.error {
            background-color: #dc3545;
            color: white;
        }

        /* Make the message responsive */
        @media (max-width: 768px) {
            .message {
                font-size: 14px;
            }
        }

    </style>
</head>

<body>
    <div class="limiter">
        <div class="container-login100" style="background-image: url('../manage/assets/login/images/mikada-laundry-bg01.png');">
            <div class="wrap-login100 p-t-30 p-b-50">
                <span class="login100-form-title p-b-41">
                    <img src="../manage/assets/img/Laundry.png" alt="Mikada Laundry Logo" style="max-width: 50%; height: auto;">
                </span>
                <form class="login100-form validate-form p-b-33 p-t-5" action="./proses/" method="post">
                    <?php if (isset($_GET['message'])) : ?>
                        <div class="row justify-content-center">
                            <div class="col-12">
                                <?php
                                    $messageClass = 'error'; // Default to error
                                    if (isset($_GET['status']) && $_GET['status'] == 'success') {
                                        $messageClass = 'success'; // Change to success if status is success
                                    }
                                ?>
                                <div class="message <?= $messageClass; ?>" role="alert">
                                    <?= $_GET['message']; ?>
                                </div>
                            </div>
                        </div>
                    <?php endif ?>

                    <div class="wrap-input100 validate-input" data-validate="Enter your name">
                        <input class="input100" type="text" name="full_name" placeholder="Name" required>
                        <span class="focus-input100">
                            <i class="fa fa-user"></i>
                        </span>
                    </div>

                    <div class="wrap-input100 validate-input" data-validate="Enter email">
                        <input class="input100" type="email" name="email" placeholder="Email" required>
                        <span class="focus-input100">
                            <i class="fa fa-envelope"></i>
                        </span>
                    </div>

                    <div class="wrap-input100 validate-input" data-validate="Enter username">
                        <input class="input100" type="text" name="username" placeholder="Username" required>
                        <span class="focus-input100">
                            <i class="fa fa-user-circle"></i>
                        </span>
                    </div>

                    <div class="wrap-input100 validate-input" data-validate="Enter password">
                        <input class="input100" type="password" name="password" placeholder="Password" required>
                        <span class="focus-input100">
                            <i class="fa fa-lock"></i>
                        </span>
                    </div>

                    <div class="container-login100-form-btn m-t-32">
                        <button class="login100-form-btn" type="submit">
                            Register
                        </button>
                    </div>

                    <div class="container-login100-form-btn">
                        <a href="../login/" class="login100-form-btn" style="text-decoration: none;">
                            Login
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div id="dropDownSelect1"></div>

    <script src="../manage/assets/login/vendor/jquery/jquery-3.2.1.min.js"></script>
    <script src="../manage/assets/login/vendor/animsition/js/animsition.min.js"></script>
    <script src="../manage/assets/login/vendor/bootstrap/js/popper.js"></script>
    <script src="../manage/assets/login/vendor/bootstrap/js/bootstrap.min.js"></script>
    <script src="../manage/assets/login/vendor/select2/select2.min.js"></script>
    <script src="../manage/assets/login/vendor/daterangepicker/moment.min.js"></script>
    <script src="../manage/assets/login/vendor/daterangepicker/daterangepicker.js"></script>
    <script src="../manage/assets/login/vendor/countdowntime/countdowntime.js"></script>
    <script src="../manage/assets/login/js/main.js"></script>
</body>
</html>
