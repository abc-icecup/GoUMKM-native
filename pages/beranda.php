<?php
require_once '../config/init.php';
include '../includes/header.php';

// Ambil semua produk yang punya profil usaha
$query = "
    SELECT 
        produk.id_produk,
        produk.nama_produk,
        produk.harga,
        produk.gambar_produk,
        profil_usaha.nama_usaha
    FROM produk
    INNER JOIN profil_usaha ON produk.id_profil = profil_usaha.id_profil
    ORDER BY produk.id_produk DESC
";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UMKM Indonesia</title>
    <link rel="stylesheet" href="../css/beranda.css">
</head>
<body>
    <!-- Header -->
    <!-- <header class="header">
        <div class="nav-left" >
            <a href="../pages/beranda.php">
                <img src="../assets/img/image (2).png" alt="logo umkm" class="logo-umkm" >
            </a>     
        </div>
        
        <div class="search-container">
            <input type="text" class="search-box" placeholder="">
            <button class="search-btn">Search</button>
        </div>
        
        <div class="nav-buttons">
            <a href="masuk.php" class="nav-btn" style="text-decoration: none;">Masuk</a>
            <a href="daftar.php" class="nav-btn" style="text-decoration: none;">Daftar</a> -->
            <!-- <button class="nav-btn">Masuk</button>
            <button class="nav-btn">Daftar</button> -->
        <!-- </div>
    </header> -->

    <!-- Hero Section -->
    <section class="hero">
        <div class="hero-overlay">
            <div class="hero-text-box">
                <div class="hero-text">
                    Tunjukkan semangat bertumbuh,<br>
                    bersama kita membangun<br>
                    <strong>UMKM INDONESIA</strong>
                </div>
                <?php if (!isset($_SESSION['id_user'])): ?>
                    <a href="daftar.php" class="hero-btn">Ayo Daftarkan UMKM Anda >></a>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <!-- Categories -->
    <section class="categories">
        <button class="product-category">Makanan dan Minuman</button>
        <button class="product-category">Fashion</button>
        <button class="product-category">Kerajinan</button>
        <button class="product-category">Produk Kecantikan</button>
        <button class="product-category">Pertanian dan Perkebunan</button>
    </section>

    <!-- Products Grid -->
    <div class="products-section">
        <div class="products-grid">
            <?php if ($result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <a href="katalog_produk.php?id_produk=<?= $row['id_produk'] ?>" class="product-card">
                        <div class="product-image">
                            <?php if (!empty($row['gambar_produk'])): ?>
                                <img src="../user_img/foto_produk/<?= htmlspecialchars($row['gambar_produk']) ?>" alt="Gambar Produk">
                            <?php else: ?>
                                <img src="icon/no-image.png" alt="Belum ada gambar" />
                            <?php endif; ?>
                        </div>
                        <div class="product-info">
                            <div class="product-name"><?= htmlspecialchars($row['nama_produk']) ?></div>
                            <div class="toko-link"><?= htmlspecialchars($row['nama_usaha']) ?></div>
                            <div class="price">Rp<?= number_format($row['harga'], 0, ',', '.') ?></div>
                        </div>
                    </a>
                <?php endwhile; ?>
            <?php else: ?>
                <p>Tidak ada produk ditemukan.</p>
            <?php endif; ?>
        </div>
    </div>

    <!-- jQuery Library -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    
    <script>
        $(document).ready(function() {
            // Search functionality
            $('.search-btn').click(function() {
                const searchValue = $('.search-box').val().trim();
                if (searchValue) {
                    alert('Mencari: ' + searchValue);
                } else {
                    alert('Masukkan kata kunci pencarian');
                }
            });

            // Enter key for search
            $('.search-box').keypress(function(e) {
                if (e.which === 13) { // Enter key
                    $('.search-btn').click();
                }
            });

            // Category buttons interaction
            $('.product-category').click(function() {
                // Remove active state from all buttons
                $('.product-category').css({
                    'background-color': 'white',
                    'border-color': '#dee2e6',
                    'color': '#495057'
                });
                
                // Add active state to clicked button
                $(this).css({
                    'background-color': '#e7f1ff',
                    'border-color': '#0d6efd',
                    'color': '#0d6efd'
                });
                
                // Simulate category filtering
                console.log('Kategori dipilih:', $(this).text());
            });

            // Product card interactions
            $('.product-card').click(function() {
                const productName = $(this).find('.product-title').text();
                const storeName = $(this).find('.product-subtitle').text();
                alert(`Produk: ${productName}\nToko: ${storeName}`);
            });

            // Product card hover effects
            $('.product-card').hover(
                function() {
                    $(this).css({
                        'background-color': '#e9ecef',
                        'transform': 'translateY(-2px)'
                    });
                },
                function() {
                    $(this).css({
                        'background-color': '#f8f9fa',
                        'transform': 'translateY(0)'
                    });
                }
            );

            // Hero button interaction
            // $('.hero-btn').click(function() {
            //    alert('Mengarahkan ke halaman pendaftaran UMKM...');
            // });

            // Navigation buttons
            // $('.nav-btn').click(function() {
            //     const action = $(this).text().toLowerCase();
            //     alert(`Mengarahkan ke halaman ${action}...`);
            // });

            // Category hover effects
            $('.product-category').hover(
                function() {
                    if ($(this).css('background-color') === 'rgb(255, 255, 255)' || $(this).css('background-color') === 'white') {
                        $(this).css({
                            'background-color': '#f8f9fa',
                            'border-color': '#adb5bd'
                        });
                    }
                },
                function() {
                    if ($(this).css('background-color') === 'rgb(248, 249, 250)') {
                        $(this).css({
                            'background-color': 'white',
                            'border-color': '#dee2e6'
                        });
                    }
                }
            );

            // Search box focus effects
            $('.search-box').focus(function() {
                $(this).css({
                    'border-color': '#0d6efd',
                    'box-shadow': '0 0 0 0.2rem rgba(13, 110, 253, 0.25)'
                });
            }).blur(function() {
                $(this).css({
                    'border-color': '#ced4da',
                    'box-shadow': 'none'
                });
            });

            // Button hover effects
            $('.search-btn').hover(
                function() {
                    $(this).css('background-color', '#0b5ed7');
                },
                function() {
                    $(this).css('background-color', '#0d6efd');
                }
            );

            $('.hero-btn').hover(
                function() {
                    $(this).css('background-color', '#0b5ed7');
                },
                function() {
                    $(this).css('background-color', '#0d6efd');
                }
            );

            $('.nav-btn').hover(
                function() {
                    $(this).css('background-color', '#e7f1ff');
                },
                function() {
                    $(this).css('background-color', 'white');
                }
            );

            // Logo hover effect
            $('.home-link').hover(
                function() {
                    $(this).css('background-color', '#495057');
                },
                function() {
                    $(this).css('background-color', '#6c757d');
                }
            );
        });
    </script>

    <?php include '../Includes/footer.php'; ?>
</body>
</html>