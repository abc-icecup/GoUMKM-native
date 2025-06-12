<?php
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);

// ob_start();
// session_start();
// include '../koneksi.php';

// if (!$conn) {
//     die('Koneksi ke database gagal.');
// }

// if ($_SERVER['REQUEST_METHOD'] === 'POST') {
//     $email = $_POST['email'];
//     $password = $_POST['password'];

    // Validasi input dasar..
    // if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    //     $error = "Format email tidak valid.";
    // } elseif (strlen($password) < 6) {
    //     $error = "Password minimal 6 karakter.";
    // } else {
    //     $stmt = $conn->prepare ("SELECT * FROM users WHERE email = ?");
    //     $stmt->bind_param("s", $email);
    //     $stmt->execute();
    //     $result = $stmt->get_result();

    //     if ($result->num_rows === 1) {
    //         $user = $result->fetch_assoc();
    //         if (password_verify($password, $user['password'])) {
                // Login sukses: set session atau redirect..
                // $_SESSION['id_user'] = $user['id'];
                // $_SESSION['email'] = $user['email'];
                
                // Cek apakah sudah punya profil usaha..
                // $checkProfile = $conn->prepare("SELECT id_profil FROM profil_usaha WHERE id_user = ?");
                // $checkProfile->bind_param("i", $user['id']);
                // $checkProfile->execute();
                // $profileResult = $checkProfile->get_result();
                
                // if ($profileResult->num_rows > 0) {
                //     header("Location: ../beranda.php "); // Sudah isi profil..
                // } else {
                //     header("Location: ../formulir.php"); // Belum isi profil..
//                 }
//                 exit();
//             } else {
//                 $error = "Email atau password salah.";
//             }
//         } else {
//             $error = "Email tidak ditemukan.";
//         }
//     }
// }
// ob_end_flush(); // Selesaikan buffering
?>