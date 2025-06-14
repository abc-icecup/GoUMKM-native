<?php
session_start();
require_once '../config/koneksi.php';

if (!isset($_SESSION['id_user'])) {
    http_response_code(401);
    echo json_encode(['status' => 'unauthorized']);
    exit;
}

if (!isset($_GET['id'])) {
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => 'ID tidak ditemukan']);
    exit;
}

$id_produk = (int) $_GET['id'];

// Validasi: apakah produk memang milik user ini?
$stmt = $conn->prepare("
    DELETE FROM produk 
    WHERE id_produk = ? 
    AND id_profil IN (SELECT id_profil FROM profil_usaha WHERE id_user = ?)
");
$stmt->bind_param("ii", $id_produk, $_SESSION['id_user']);

if ($stmt->execute()) {
    echo json_encode(['status' => 'success']);
} else {
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => $stmt->error]);
}
?>
