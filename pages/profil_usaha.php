<?php
session_start();
require_once '../config/koneksi.php'; // sesuaikan path-nya jika perlu

// Pastikan user sudah login
if (!isset($_SESSION['id_user'])) {
    header("Location: masuk.php");
    exit;
}

// Ambil id_profil berdasarkan id_user yang sedang login
$id_user = $_SESSION['id_user'];
$query = $conn->prepare("SELECT id_profil FROM profil_usaha WHERE id_user = ?");
$query->bind_param("i", $id_user);
$query->execute();
$result = $query->get_result();
$data_profil = $result->fetch_assoc();
$id_profil = $data_profil['id_profil'];

// Saat menyimpan produk
$stmt = $conn->prepare("INSERT INTO produk (id_profil, nama_produk, deskripsi, harga, gambar) VALUES (?, ?, ?, ?, ?)");
$stmt->bind_param("issds", $id_profil, $nama, $deskripsi, $harga, $gambar);
?>


<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard GoUMKM</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <link rel="stylesheet" href="../css/profil_usaha.css">
    
</head>
<body>
    <header class="header">
        <div class="nav-left" >
            <a href="../pages/beranda.php">
                <img src="../assets/img/image (2).png" alt="logo umkm" class="logo-umkm" >
            </a>     
        </div>
        
        <div class="search-container">
            <input type="text" class="search-input" placeholder="Cari...">
            <button class="search-btn">Search</button>
        </div>
        
        <div class="profile-icon">
            <a href="profil_usaha.php">
                <?php if (!empty($data_profil['gambar'])): ?>
                    <img src="../uploads/<?= htmlspecialchars($data_profil['gambar']) ?>" alt="Akun" class="user-avatar">
                <?php else: ?>
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none">
                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2" stroke="currentColor" stroke-width="2"/>
                        <circle cx="12" cy="7" r="4" stroke="currentColor" stroke-width="2"/>
                    </svg>
                <?php endif; ?>
            </a>
        </div>
    </header>

    <div class="container">
        <div class="main-content">
            <div class="form-section">
                <div class="form-header">
                    <h2 class="form-title">Profil Usaha Anda</h2>
                    <a href="formulir.php?edit=true" class="edit-button">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" width="20px" height="20px" class="edit-icon">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                        </svg>
                    </a>
                </div>
                
                <div class="form-content">
                    <div class="image-placeholder">
                        <?php if (!empty($data_profil['gambar'])): ?>
                            <img src="../uploads/<?= htmlspecialchars($data_profil['gambar']) ?>" alt="Gambar Usaha" class="img-preview">
                        <?php else: ?>
                            <svg class="image-icon" viewBox="0 0 24 24">
                                <rect x="3" y="3" width="18" height="18" rx="2" ry="2"/>
                                <circle cx="8.5" cy="8.5" r="1.5"/>
                                <polyline points="21,15 16,10 5,21"/>
                            </svg>
                        <?php endif; ?>
                    </div>
                    
                    <div class="form-fields">
                        <div class="form-row">
                            <span class="form-label">Nama Usaha</span>
                            <span class="form-value"><?= htmlspecialchars($data_profil['nama_usaha'] ?? '-') ?></span>
                        </div>
                        <div class="form-row">
                            <span class="form-label">Nama Pemilik Usaha</span>
                            <span class="form-value"><?= htmlspecialchars($data_profil['nama_pemilik'] ?? '-') ?></span>
                        </div>
                        <div class="form-row">
                            <span class="form-label">Kategori Usaha</span>
                            <span class="form-value"><?= htmlspecialchars($data_profil['kategori_usaha'] ?? '-') ?></span>
                        </div>
                        <div class="form-row">
                            <span class="form-label">Deskripsi Usaha</span>
                            <span class="form-value"><?= htmlspecialchars($data_profil['deskripsi'] ?? '-') ?></span>
                        </div>
                        <div class="form-row">
                            <span class="form-label">Link Whatsapp</span>
                            <span class="form-value">
                                <?php if (!empty($data_profil['link_whatsapp'])): ?>
                                    <a href="<?= htmlspecialchars($data_profil['link_whatsapp']) ?>" target="_blank"><?= htmlspecialchars($data_profil['link_whatsapp']) ?></a>
                                <?php else: ?>
                                    -
                                <?php endif; ?>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="products-section">
            <div class="products-grid">
                <div class="product-card">
                    <button class="delete-btn">×</button>
                    <div class="product-image">
                        <svg viewBox="0 0 24 24">
                            <rect x="3" y="3" width="18" height="18" rx="2" ry="2"/>
                            <circle cx="8.5" cy="8.5" r="1.5"/>
                            <polyline points="21,15 16,10 5,21"/>
                        </svg>
                    </div>
                    <div class="product-name">Nama Produk</div>
                    <div class="product-price">Rp. -</div>
                </div>
                
                <div class="product-card">
                    <button class="delete-btn">×</button>
                    <div class="product-image">
                        <svg viewBox="0 0 24 24">
                            <rect x="3" y="3" width="18" height="18" rx="2" ry="2"/>
                            <circle cx="8.5" cy="8.5" r="1.5"/>
                            <polyline points="21,15 16,10 5,21"/>
                        </svg>
                    </div>
                    <div class="product-name">Nama Produk</div>
                    <div class="product-price">Rp. -</div>
                </div>
                
                <div class="product-card">
                    <button class="delete-btn">×</button>
                    <div class="product-image">
                        <svg viewBox="0 0 24 24">
                            <rect x="3" y="3" width="18" height="18" rx="2" ry="2"/>
                            <circle cx="8.5" cy="8.5" r="1.5"/>
                            <polyline points="21,15 16,10 5,21"/>
                        </svg>
                    </div>
                    <div class="product-name">Nama Produk</div>
                    <div class="product-price">Rp. -</div>
                </div>
                
                <div class="product-card">
                    <button class="delete-btn">×</button>
                    <div class="product-image">
                        <svg viewBox="0 0 24 24">
                            <rect x="3" y="3" width="18" height="18" rx="2" ry="2"/>
                            <circle cx="8.5" cy="8.5" r="1.5"/>
                            <polyline points="21,15 16,10 5,21"/>
                        </svg>
                    </div>
                    <div class="product-name">Nama Produk</div>
                    <div class="product-price">Rp. -</div>
                </div>
                
                <div class="product-card">
                    <button class="delete-btn">×</button>
                    <div class="product-image">
                        <svg viewBox="0 0 24 24">
                            <rect x="3" y="3" width="18" height="18" rx="2" ry="2"/>
                            <circle cx="8.5" cy="8.5" r="1.5"/>
                            <polyline points="21,15 16,10 5,21"/>
                        </svg>
                    </div>
                    <div class="product-name">Nama Produk</div>
                    <div class="product-price">Rp. -</div>
                </div>
                
                <div class="add-product">
                    <div class="add-icon">+</div>
                    <div class="add-text">Tambah Produk</div>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            // Handle search functionality
            $('.search-btn').click(function() {
                const searchTerm = $('.search-input').val();
                if (searchTerm.trim()) {
                    alert('Mencari: ' + searchTerm);
                }
            });

            // Handle enter key in search
            $('.search-input').keypress(function(e) {
                if (e.which === 13) {
                    $('.search-btn').click();
                }
            });

            // Handle profile icon click
            $('.profile-icon').click(function() {
                alert('Profile menu diklik');
            });

            // // Handle edit icon click
            // $('.edit-icon').click(function() {
            //     alert('Edit form diklik');
            // });

            // Handle delete product buttons
            $('.delete-btn').click(function(e) {
                e.stopPropagation();
                if (confirm('Hapus produk ini?')) {
                    $(this).closest('.product-card').fadeOut(300, function() {
                        $(this).remove();
                    });
                }
            });

            // Handle add product click
            $('.add-product').click(function() {
                alert('Tambah produk baru');
            });

            // Handle product card click
            $('.product-card').click(function() {
                const productName = $(this).find('.product-name').text();
                alert('Produk diklik: ' + productName);
            });
        });
    </script>
</body>
</html>