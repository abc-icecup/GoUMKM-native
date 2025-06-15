<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard GoUMKM</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <link rel="stylesheet" href="../css/logout.css">
</head>
<body>
    <div class="header">
        <div class="logo-section">
            <div class="logo-icon"></div>
            <span class="logo-text">GoUMKM</span>
        </div>
        
        <div class="search-container">
            <input type="text" class="search-input" placeholder="">
            <button class="search-btn">Search</button>
        </div>
        
        <div class="user-section">
            <div class="profile-icon">👤</div>
        </div>
    </div>

    <div class="container">
        <div class="main-content">
            <div class="form-section">
                <div class="form-header">
                    <h2 class="form-title">Formulir Profil Usaha</h2>
                </div>
                
                <div class="form-content">
                    <div class="image-placeholder">
                        <div class="image-icon">🏔</div>
                    </div>
                    
                    <div class="form-fields">
                        <div class="form-row">
                            <span class="form-label">Nama Usaha</span>
                            <span class="form-value form-dash">-</span>
                        </div>
                        <div class="form-row">
                            <span class="form-label">Nama Pemilik Usaha</span>
                            <span class="form-value form-dash">-</span>
                        </div>
                        <div class="form-row">
                            <span class="form-label">Kategori Usaha</span>
                            <span class="form-value form-dash">-</span>
                        </div>
                        <div class="form-row">
                            <span class="form-label">Deskripsi Usaha</span>
                            <span class="form-value form-dash">-</span>
                        </div>
                        <div class="form-row">
                            <span class="form-label">Link Whatsapp</span>
                            <span class="form-value form-dash">-</span>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="sidebar">
                <div class="user-icon">👤</div>
                <div class="qr-placeholder">
                    QR Code placeholder
                </div>
                <button class="register-btn">REGISTER</button>
            </div>
        </div>

        <div class="products-section">
            <div class="products-grid">
                <div class="product-card">
                    <button class="delete-btn">×</button>
                    <div class="product-image">🏔</div>
                    <div class="product-name">Nama Produk</div>
                    <div class="product-price">Rp. -</div>
                </div>
                
                <div class="product-card">
                    <button class="delete-btn">×</button>
                    <div class="product-image">🏔</div>
                    <div class="product-name">Nama Produk</div>
                    <div class="product-price">Rp. -</div>
                </div>
                
                <div class="product-card">
                    <button class="delete-btn">×</button>
                    <div class="product-image">🏔</div>
                    <div class="product-name">Nama Produk</div>
                    <div class="product-price">Rp. -</div>
                </div>
                
                <div class="product-card">
                    <button class="delete-btn">×</button>
                    <div class="product-image">🏔</div>
                    <div class="product-name">Nama Produk</div>
                    <div class="product-price">Rp. -</div>
                </div>
                
                <div class="product-card">
                    <button class="delete-btn">×</button>
                    <div class="product-image">🏔</div>
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

            // Handle register button click
            $('.register-btn').click(function() {
                alert('Register diklik');
            });

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

            // Handle user icon in sidebar
            $('.user-icon').click(function() {
                alert('User icon diklik');
            });
        });
    </script>
</body>
</html>