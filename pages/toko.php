<?php
// $id_user_toko = $_GET['id_user']; // misal dari URL
// $result = $conn->query("SELECT * FROM produk WHERE id_user = $id_user_toko");

?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Toko UMKM</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <link rel="stylesheet" href="../css/toko.css">
    
</head>
<body>
    <!-- HEADER -->
    <header class="header">
        <div class="nav-left" >
            <a href="#">
                <img src="../assets/img/image (2).png" alt="logo umkm" class="logo-umkm" height="30px">
            </a>     
        </div>
        
        <div class="search-container">
            <input type="text" class="search-box" placeholder="">
            <button class="search-btn">Search</button>
        </div>
        
        <div class="nav-buttons">
            <a href="masuk.php" class="nav-btn">Masuk</a>
            <a href="daftar.php" class="nav-btn">Daftar</a>
            <!-- <button class="nav-btn">Masuk</button>
            <button class="nav-btn">Daftar</button> -->
        </div>
    </header>

    <!-- <div class="container">
        Header
        <div class="header">
            <div class="logo">Logo UMKM</div>
            <div class="search-container">
                <input type="text" class="search-box" placeholder="Cari produk...">
                <button class="search-btn">Search</button>
            </div>
            <div class="header-buttons">
                <button class="masuk-btn">Masuk</button>
                <button class="daftar-btn">Daftar</button>
            </div>
        </div> -->

        <!-- Store Profile -->
        <div class="store-profile">
            <div class="store-header">
                <div class="store-avatar">🏪</div>
                <div class="store-info">
                    <h2>Nama Toko</h2>
                    <button class="contact-btn">Hubungi</button>
                </div>
            </div>
            
            <div class="store-description">
                <textarea class="description-input" placeholder="Deskripsi Toko / Usaha"></textarea>
            </div>
            
            <div class="categories">
                <button class="category-btn">Kategori</button>
                <button class="category-btn">Kategori</button>
                <button class="category-btn">Kategori</button>
            </div>
        </div>

        <!-- Products Grid -->
        <div class="products-section">
            <div class="products-grid">
                <div class="product-card">
                    <div class="product-image">📷</div>
                    <div class="product-name">Nama Produk</div>
                    <div class="product-category">Nama Toko</div>
                    <div class="product-price">Rp. -</div>
                </div>
                <div class="product-card">
                    <div class="product-image">📷</div>
                    <div class="product-name">Nama Produk</div>
                    <div class="product-category">Nama Toko</div>
                    <div class="product-price">Rp. -</div>
                </div>
                <div class="product-card">
                    <div class="product-image">📷</div>
                    <div class="product-name">Nama Produk</div>
                    <div class="product-category">Nama Toko</div>
                    <div class="product-price">Rp. -</div>
                </div>
                <div class="product-card">
                    <div class="product-image">📷</div>
                    <div class="product-name">Nama Produk</div>
                    <div class="product-category">Nama Toko</div>
                    <div class="product-price">Rp. -</div>
                </div>
                <div class="product-card">
                    <div class="product-image">📷</div>
                    <div class="product-name">Nama Produk</div>
                    <div class="product-category">Nama Toko</div>
                    <div class="product-price">Rp. -</div>
                </div>
                <div class="product-card">
                    <div class="product-image">📷</div>
                    <div class="product-name">Nama Produk</div>
                    <div class="product-category">Nama Toko</div>
                    <div class="product-price">Rp. -</div>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            // Search functionality
            $('.search-btn').click(function() {
                var searchTerm = $('.search-box').val();
                if (searchTerm.trim() !== '') {
                    alert('Mencari: ' + searchTerm);
                    // Implementasi pencarian di sini
                }
            });

            // Enter key search
            $('.search-box').keypress(function(e) {
                if (e.which == 13) {
                    $('.search-btn').click();
                }
            });

            // Button interactions
            $('.search-btn, .contact-btn, .nav-btn').hover(
                function() {
                    $(this).css('opacity', '0.8');
                },
                function() {
                    $(this).css('opacity', '1');
                }
            );            

            $('.contact-btn').click(function() {
                alert('Menghubungi toko');
            });

            // Category buttons
            $('.category-btn').click(function() {
                $('.category-btn').removeClass('active');
                $(this).addClass('active');
                
                // Add active style
                $('.category-btn.active').css({
                    'background-color': '#2c5aa0',
                    'color': 'white'
                });
                
                // Remove active style from others
                $('.category-btn:not(.active)').css({
                    'background-color': 'white',
                    'color': 'black'
                });
            });

            // Product card interactions
            $('.product-card').click(function() {
                var productName = $(this).find('.product-name').text();
                alert('Membuka produk: ' + productName);
            });

            // Hover effects for buttons
            $('.search-btn, .contact-btn, .daftar-btn').hover(
                function() {
                    $(this).css('opacity', '0.8');
                },
                function() {
                    $(this).css('opacity', '1');
                }
            );

            // Auto-resize textarea
            $('.description-input').on('input', function() {
                this.style.height = 'auto';
                this.style.height = this.scrollHeight + 'px';
            });

            // Responsive menu toggle (for mobile)
            $(window).resize(function() {
                if ($(window).width() <= 768) {
                    $('.header').addClass('mobile');
                } else {
                    $('.header').removeClass('mobile');
                }
            });
        });
    </script>
</body>
</html>