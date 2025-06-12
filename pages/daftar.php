<?php
// File: daftar.php (gabungan form + proses + login otomatis)
session_start();
include '../config/koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    // Validasi sederhana
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Format email tidak valid.";
    } elseif (strlen($password) < 6) {
        $error = "Password minimal 6 karakter.";
    } else {
        // Cek apakah email sudah terdaftar
        $cek = $conn->prepare("SELECT * FROM users WHERE email = ?");
        $cek->bind_param("s", $email);
        $cek->execute();
        $result = $cek->get_result();

        if ($result->num_rows > 0) {
            $error = "Email sudah terdaftar.";
        } else {
            // Enkripsi dan simpan
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("INSERT INTO users (email, password) VALUES (?, ?)");
            $stmt->bind_param("ss", $email, $hashed_password);

            if ($stmt->execute()) {
                // Login otomatis
                $user_id = $stmt->insert_id;
                $_SESSION['id_user'] = $id_user;
                $_SESSION['email'] = $email;

                // Redirect ke formulir langsung
                header("Location: ../pages/formulir.php");
                exit();
            } else {
                $error = "Gagal mendaftar. Silakan coba lagi.";
            }
        }
    }
}
?>


<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GoUMKM - Daftarkan UMKM Anda</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <link rel="stylesheet" href="../css/daftar.css">

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
                <h2 class="form-title">Daftarkan UMKM Anda</h2>
                <p class="form-subtitle">Sudah punya akun Usaha? <a href="../pages/masuk.php" id="masuk-link">Masuk</a></p>
                
                <?php if (!empty($error)): ?>
                    <div class="error-message" id="error-message" style="display:block;">
                        <i class="fas fa-exclamation-circle"></i> <span id="error-text"><?= htmlspecialchars($error) ?></span>
                    </div>
                <?php endif; ?>

                <form id="registration-form" method="POST" action="">
                    <div class="input-group">
                        <input type="email" class="form-input" name="email" id="email" placeholder="Email" required>
                    </div>

                    <div class="input-group">
                        <input type="password" class="form-input" name="password" id="password" placeholder="Password" required>
                    </div>

                    <button type="submit" class="submit-btn" id="submit-btn">
                        daftar
                    </button>
                </form>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            // Form validation and submission
            // $('#registration-form').on('submit', function(e) {
            //     e.preventDefault();
                
            //     const email = $('#email').val().trim();
            //     const password = $('#password').val().trim();
                
            //      (komentar : Hide previous messages)
            //     $('.success-message, .error-message').hide();
                
            //      (komentar : Basic validation)
            //     if (!email || !password) {
            //         showError('Harap isi semua field yang diperlukan.');
            //         return;
            //     }
                
            //     if (!isValidEmail(email)) {
            //         showError('Format email tidak valid.');
            //         return;
            //     }
                
            //     if (password.length < 6) {
            //         showError('Password minimal 6 karakter.');
            //         return;
            //     }
                
            //      (komentar : Simulate registration process)
            //     showLoading(true);
                
            //      Simulate API call
            //     setTimeout(function() {
            //         showLoading(false);
                    
            //          (komentar : Simulate successful registration)
            //         if (Math.random() > 0.3) {  (komentar : 70% success rate for demo)
            //             showSuccess();
            //             $('#registration-form')[0].reset();
            //         } else {
            //             showError('Email sudah terdaftar. Silakan gunakan email lain.');
            //         }
            //     }, 2000);
            // });
            
            // Google Sign-in simulation
            // $('#google-signin').on('click', function() {
            //     $('.success-message, .error-message').hide();
            //     showLoading(true);
                
            //     setTimeout(function() {
            //         showLoading(false);
            //         showSuccess('Berhasil mendaftar dengan Google!');
            //     }, 1500);
            // });
            
            // Masuk link handler
            // $('#masuk-link').on('click', function(e) {
            //    e.preventDefault();
            //    alert('Redirect ke halaman masuk...');
            // });
            
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
            
            function showLoading(show) {
                if (show) {
                    $('.container').addClass('loading');
                    $('#submit-btn').text('Mendaftar...');
                } else {
                    $('.container').removeClass('loading');
                    $('#submit-btn').text('Daftar');
                }
            }
            
            function showSuccess(message = 'Pendaftaran berhasil! Silakan cek email Anda.') {
                $('#success-message').text(message).fadeIn();
                setTimeout(function() {
                    $('#success-message').fadeOut();
                }, 5000);
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
            
            // Add some interactive hover effects
            // $('.google-btn').hover(
            //     function() {
            //         $(this).css('transform', 'translateY(-1px)');
            //     },
            //     function() {
            //         $(this).css('transform', 'translateY(0)');
            //     }
            // );
        });
    </script>
</body>
</html>