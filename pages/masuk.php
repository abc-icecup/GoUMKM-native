<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();
include '../config/koneksi.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Format email tidak valid.";
    } elseif (strlen($password) < 6) {
        $error = "Password minimal 6 karakter.";
    } else {
        $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();
            // PERINTAH DIBAWAH UNTUK MEMPERLIHATKAN ERRORNYA DIMANA
            // echo "<pre>";
            // print_r($user);
            // echo "</pre>";

            if (password_verify($password, $user['password'])) {
                echo "Password cocok<br>";
                $_SESSION['id_user'] = $user['id_user'];
                $_SESSION['email'] = $user['email'];
                $_SESSION['role'] = $user['role']; // Simpan role ke session

                if ($user['role'] === 'admin') {
                    header("Location: ../pages/admin.php");
                    die();
                } else {
                    // Cek apakah user sudah punya profil usaha
                    $checkProfile = $conn->prepare("SELECT id_profil FROM profil_usaha WHERE id_user = ?");
                    $checkProfile->bind_param("i", $user['id_user']);
                    $checkProfile->execute();
                    $profileResult = $checkProfile->get_result();

                    if ($profileResult->num_rows > 0) {
                        header("Location: beranda.php");
                        die();
                    } else {
                        header("Location: formulir.php");
                        die();
                    }
                }
                exit();
            } else {
                // INI JUGA UNTUK MEMPERLIHATKAN ERROR
                // echo "Password TIDAK cocok<br>";
                // echo "Password yang dimasukkan: $password<br>";
                // echo "Password di database: " . $user['password'] . "<br>";
                $error = "Email atau password salah.";
            }
        } else {
            $error = "Email tidak ditemukan.";
        }
    }
}
?>


<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GoUMKM - Login</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <link rel="stylesheet" href="../css/masuk.css">

</head>
<body>
    

    <div class="container">
        <div class="left-section">
            <div class="floating-elements">
                <div class="floating-circle circle-1"></div>
                <div class="floating-circle circle-2"></div>
                <div class="floating-circle circle-3"></div>
            </div>
            
            <div class="illustration">
                <div class="phone-mockup">
                    <div class="phone-screen">
                        <div class="app-item"></div>
                        <div class="app-item" style="background: #34a853;"></div>
                        <div class="app-item" style="background: #fbbc05;"></div>
                        <div class="app-item" style="background: #ea4335;"></div>
                    </div>
                </div>
                <div class="person-icon"></div>
            </div>
        </div>

        <div class="right-section">
            <h1 class="brand-title">GoUMKM</h1>
            
            <div class="form-container">
                <h2 class="form-title">Masuk ke Akun Anda</h2>
                <p class="form-subtitle">Belum punya akun Usaha? <a href="../pages/daftar.php">Daftar</a></p>
                

                <?php if (!empty($error)): ?>
                <div class="error-message" style="color:red;">
                    <i class="fas fa-exclamation-circle"></i> <?= htmlspecialchars($error) ?>
                </div>
                <?php endif; ?>

                <form method="POST" action="" id="registration-form">
                    <div class="input-group">
                        <input type="email" name="email" class="form-input" placeholder="Email" id="email" required>
                    </div>

                    <div class="input-group">
                        <input type="password" name="password" class="form-input" placeholder="Password" id="password" required>
                    </div>

                    <button type="submit" class="submit-btn" id="submit-btn">
                        Masuk
                    </button>
                </form>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            // Input focus effects
            $('.form-input').on('focus', function() {
                $(this).parent().addClass('focused');
            }).on('blur', function() {
                $(this).parent().removeClass('focused');
            });
            
            // Real-time email validation
            $('#email').on('input', function() {
                const email = $(this).val();
                if (email && !isValidEmail(email)) {
                    $(this).css('border-color', '#ea4335');
                } else {
                    $(this).css('border-color', '#e0e0e0');
                }
            });
            
            // Password strength indicator
            $('#password').on('input', function() {
                const password = $(this).val();
                if (password.length > 0 && password.length < 6) {
                    $(this).css('border-color', '#fbbc05');
                } else if (password.length >= 6) {
                    $(this).css('border-color', '#34a853');
                } else {
                    $(this).css('border-color', '#e0e0e0');
                }
            });
            
            // Utility functions
            function isValidEmail(email) {
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                return emailRegex.test(email);
            }
            
            function showError(message) {
                $('#error-text').text(message);
                $('#error-message').fadeIn();
                setTimeout(function() {
                    $('#error-message').fadeOut();
                }, 5000);
            }
            // Smooth animations on load
            $('.container').hide().fadeIn(800);
        
        });
    </script>
</body>
</html>