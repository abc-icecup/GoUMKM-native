<?php

session_start();
require_once '../config/koneksi.php'




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
    <div class="header">
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
    </div>

    <div class="container">
        <div class="image-section">
            <div class="image-placeholder">
                <svg class="image-icon" viewBox="0 0 24 24">
                    <path d="M21 19V5c0-1.1-.9-2-2-2H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2zM8.5 13.5l2.5 3.01L14.5 12l4.5 6H5l3.5-4.5z"/>
                </svg>
            </div>
            <div class="image-dots">
                <div class="dot active"></div>
                <div class="dot"></div>
                <div class="dot"></div>
            </div>
        </div>

        <div class="product-info">
            <h1 class="product-title">Nama Produk / Jasa</h1>
            <div class="product-price">Rp. -</div>
            
            <div class="shop-info">
                <div class="shop-icon">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none">
                        <rect x="3" y="3" width="18" height="18" rx="2" stroke="#666" stroke-width="2"/>
                        <circle cx="9" cy="9" r="2" stroke="#666" stroke-width="2"/>
                        <path d="M21 15.5c-1.4-1.4-3.1-2.3-5.1-2.3s-3.7.9-5.1 2.3" stroke="#666" stroke-width="2"/>
                    </svg>
                </div>
                <a href="toko.php" class="shop-name">Akun Toko</a>

                
            </div>

            <div class="product-description">
                <div class="description-label">Kategori</div>
                <a href="kategori.php" class="category-tag">Kategori</a>
                <div class="description-text">
                    Deskripsi Produk
                </div>
            </div>

            <button class="buy-button">
                <svg class="cart-icon" viewBox="0 0 24 24">
                    <path d="M7 18c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2zM1 2v2h2l3.6 7.59-1.35 2.45c-.16.28-.25.61-.25.96 0 1.1.9 2 2 2h12v-2H7.42c-.14 0-.25-.11-.25-.25l.03-.12L8.1 13h7.45c.75 0 1.41-.41 1.75-1.03L21.7 4H5.21l-.94-2H1zm16 16c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2z"/>
                </svg>
                Beli
            </button>

            
        </div>
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
</body>
</html>