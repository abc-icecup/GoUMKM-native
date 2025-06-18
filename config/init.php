<?php
// Mulai session jika belum dimulai
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Sertakan koneksi ke database
include 'koneksi.php'; // pastikan path ini sesuai

// Fungsi untuk ambil data user dari DB jika session aktif
function getUserIfExists($conn) {
    if (!isset($_SESSION['id_user'])) {
        return null;
    }

    $id_user = $_SESSION['id_user'];

    // Cek apakah user masih ada di DB (ubah nama tabel jika perlu)
    $query = mysqli_query($conn, "SELECT * FROM users WHERE id_user = '$id_user'");

    if ($query && mysqli_num_rows($query) > 0) {
        return mysqli_fetch_assoc($query);
    }

    return null;
}


