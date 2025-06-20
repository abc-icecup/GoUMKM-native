<?php
require_once '../config/init.php';
include '../includes/header.php';

$query = "
    SELECT 
        produk.nama_produk,
        produk.harga,
        produk.deskripsi,
        produk.gambar_produk,
        kategori.nama_kategori,
        profil_usaha.nama_usaha,
        profil_usaha.gambar_usaha,
        profil_usaha.link_whatsapp,
        users.id_user
    FROM produk
    JOIN kategori ON produk.id_kategori = kategori.id_kategori
    JOIN profil_usaha ON produk.id_profil = profil_usaha.id_profil
    JOIN users ON profil_usaha.id_user = users.id_user
";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Halaman Produk/Jasa</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <link rel="stylesheet" href="../css/katalog_produk.css">
</head>

<body>
    <!-- <div class="header">
        <div class="logo">
            <div class="logo-icon">Go</div>
            UMKM
        </div>
        <div class="search-container">
            <input type="text" class="search-input" placeholder="Cari produk atau jasa...">
            <button class="search-btn">Search</button>
        </div>
        <div class="nav-buttons">
            <button class="nav-btn">Masuk</button>
            <button class="nav-btn primary">Daftar</button>
        </div>
    </div> -->

    <!-- <h1 style="text-align: center;">Katalog Produk</h1> -->
    <div class="produk-grid">
    <?php while ($row = $result->fetch_assoc()): ?>
        <div class="container">
            <div class="image-section">
                <div class="image-placeholder">
                    <?php if (!empty($row['gambar_produk'])): ?>
                        <img src="../user_img/foto_produk/<?= htmlspecialchars($row['gambar_produk']) ?>" alt="Foto Produk">
                    <?php else: ?>
                        <svg class="image-icon" viewBox="0 0 24 24">
                            <path d="M21 19V5c0-1.1-.9-2-2-2H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2zM8.5 13.5l2.5 3.01L14.5 12l4.5 6H5l3.5-4.5z"/>
                        </svg>
                    <?php endif; ?>
                </div>
            </div>

            <div class="product-info">
                <h1 class="product-title" style="text-decoration: none;"><?= htmlspecialchars($row['nama_produk']) ?></h1>
                <div class="product-price">Rp. <?= number_format($row['harga'], 0, ',', '.') ?></div>

                <div class="shop-info">
                    <a href="../pages/toko.php?id_user=<?= $row['id_user'] ?>" class="shop-name" style="text-decoration: none;">
                        <?= htmlspecialchars($row['nama_usaha']) ?>
                    </a>
                </div>

                <div class="product-description">
                    <div class="description-label">Kategori</div>
                    <a href="../pages/kategori.php?kategori=<?= urlencode($row['nama_kategori']) ?>" class="category-tag" style="text-decoration: none;">
                        <?= htmlspecialchars($row['nama_kategori']) ?>
                    </a>
                    <div class="description-text">
                        <?= nl2br(htmlspecialchars($row['deskripsi'])) ?>
                    </div>
                </div>

                <a href="<?= htmlspecialchars($row['link_whatsapp']) ?>" target="_blank" style="text-decoration: none;">
                    <button class="buy-button" >
                        <svg class="cart-icon" viewBox="0 0 24 24">
                            <path d="M7 18c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2zM1 2v2h2l3.6 7.59-1.35 2.45c-.16.28-.25.61-.25.96 0 1.1.9 2 2 2h12v-2H7.42c-.14 0-.25-.11-.25-.25l.03-.12L8.1 13h7.45c.75 0 1.41-.41 1.75-1.03L21.7 4H5.21l-.94-2H1zm16 16c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2z"/>
                        </svg>
                        Beli
                    </button>
                </a>
            </div>
        </div>
    <?php endwhile; ?>
    </div>
    

    
    <script>
        $(document).ready(function() {
            // Handle search functionality
            $('.search-btn').click(function() {
                const searchTerm = $('.search-input').val();
                if (searchTerm.trim()) {
                    alert('Mencari: ' + searchTerm);
                    // Di sini bisa ditambahkan logika pencarian sebenarnya
                }
            });

            // Handle enter key in search input
            $('.search-input').keypress(function(e) {
                if (e.which === 13) {
                    $('.search-btn').click();
                }
            });

            // Handle navigation buttons
            $('.nav-btn').click(function() {
                const buttonText = $(this).text();
                alert('Tombol ' + buttonText + ' diklik');
                // Di sini bisa ditambahkan logika navigasi sebenarnya
            });

            // Handle image dots navigation
            $('.dot').click(function() {
                $('.dot').removeClass('active');
                $(this).addClass('active');
                
                // Simulasi pergantian gambar
                const index = $('.dot').index(this);
                console.log('Menampilkan gambar ke-' + (index + 1));
            });

            // Handle buy button
            $('.buy-button').click(function() {
                $(this).html('<span>Memproses...</span>');
                
                setTimeout(function() {
                    alert('Produk berhasil ditambahkan ke keranjang!');
                    $('.buy-button').html(`
                        <svg class="cart-icon" viewBox="0 0 24 24">
                            <path d="M7 18c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2zM1 2v2h2l3.6 7.59-1.35 2.45c-.16.28-.25.61-.25.96 0 1.1.9 2 2 2h12v-2H7.42c-.14 0-.25-.11-.25-.25l.03-.12L8.1 13h7.45c.75 0 1.41-.41 1.75-1.03L21.7 4H5.21l-.94-2H1zm16 16c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2z"/>
                        </svg>
                        Beli
                    `);
                }, 1000);
            });
        });
    </script>

    <?php include '../Includes/footer.php'; ?>
</body>
</html>