<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard GoUMKM</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <link rel="stylesheet" href="css/usaha _nonaktif.css">
</head>
<body>
    <!-- Popup Overlay -->
    <div class="popup-overlay" id="popupOverlay">
        <div class="popup-container">
            <button class="popup-close" id="popupClose">×</button>
            <h3 class="popup-title">Usaha Anda dinonaktifkan karena melanggar kebijakan. Silahkan hubungi customer service</h3>
            <button class="popup-button" id="popupButton">Hubungi</button>
        </div>
    </div>

    <header class="header">
        <div class="nav-left">
            <a href="#beranda">
                <!-- Logo placeholder - ganti dengan logo sebenarnya -->
                <div class="logo-umkm" style="background: #2c5282; color: white; padding: 5px 10px; border-radius: 4px; font-weight: bold; font-size: 14px;">
                    GoUMKM
                </div>
            </a>     
        </div>
        
        <div class="search-container">
            <input type="text" class="search-input" placeholder="Cari...">
            <button class="search-btn">Search</button>
        </div>
        
        <div class="profile-container">
            <div class="profile-icon" id="profileToggle">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none">
                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2" stroke="currentColor" stroke-width="2"/>
                    <circle cx="12" cy="7" r="4" stroke="currentColor" stroke-width="2"/>
                </svg>
            </div>

            <div class="profile-dropdown" id="profileDropdown">
                <div class="profile-avatar">
                    <svg width="30" height="30" viewBox="0 0 24 24" fill="none">
                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2" stroke="currentColor" stroke-width="2"/>
                        <circle cx="12" cy="7" r="4" stroke="currentColor" stroke-width="2"/>
                    </svg>
                </div>
                <div class="profile-email">user@example.com</div>
                <button type="button" class="logout-btn">Log Out</button>
            </div>
        </div>
    </header>

    <div class="container">
        <div class="main-content">
            <div class="form-section">
                <div class="form-header">
                    <h2 class="form-title">Profil Usaha Anda</h2>
                    <a href="#edit" class="edit-button">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" width="20px" height="20px" class="edit-icon">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                        </svg>
                    </a>
                </div>
                
                <div class="form-content">
                    <div class="image-placeholder" id="businessImage">
                        <svg class="image-icon" viewBox="0 0 24 24">
                            <rect x="3" y="3" width="18" height="18" rx="2" ry="2"/>
                            <circle cx="8.5" cy="8.5" r="1.5"/>
                            <polyline points="21,15 16,10 5,21"/>
                        </svg>
                    </div>
                    
                    <div class="form-fields">
                        <div class="form-row">
                            <span class="form-label">Nama Usaha</span>
                            <span class="form-value" id="namaUsaha">Toko Sembako Berkah</span>
                        </div>
                        <div class="form-row">
                            <span class="form-label">Nama Pemilik Usaha</span>
                            <span class="form-value" id="namaPemilik">Budi Santoso</span>
                        </div>
                        <div class="form-row">
                            <span class="form-label">Kategori Usaha</span>
                            <span class="form-value" id="kategoriUsaha">Makanan & Minuman</span>
                        </div>
                        <div class="form-row">
                            <span class="form-label">Deskripsi Usaha</span>
                            <span class="form-value" id="deskripsiUsaha">Toko sembako lengkap dengan berbagai kebutuhan sehari-hari</span>
                        </div>
                        <div class="form-row">
                            <span class="form-label">Link Whatsapp</span>
                            <span class="form-value">
                                <a href="https://wa.me/6281234567890" target="_blank" id="linkWhatsapp">https://wa.me/6281234567890</a>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="products-section">
            <div class="products-grid" id="productsGrid">
                <!-- Tombol tambah produk -->
                <div class="add-product">
                    <div class="add-icon">+</div>
                    <div class="add-text">Tambah Produk</div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Data dummy untuk produk
        const dummyProducts = [
            {
                id: 1,
                name: "Beras Premium",
                price: 15000,
                image: null
            },
            {
                id: 2,
                name: "Minyak Goreng",
                price: 25000,
                image: null
            },
            {
                id: 3,
                name: "Gula Pasir",
                price: 12000,
                image: null
            },
            {
                id: 4,
                name: "Teh Celup",
                price: 8000,
                image: null
            }
        ];

        // Fungsi untuk format rupiah
        function formatRupiah(angka) {
            return 'Rp. ' + angka.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
        }

        // Fungsi untuk render produk
        function renderProducts() {
            const grid = $('#productsGrid');
            const addButton = grid.find('.add-product').detach(); // Simpan tombol tambah
            
            grid.empty(); // Kosongkan grid
            grid.append(addButton); // Kembalikan tombol tambah
            
            if (dummyProducts.length === 0) {
                grid.append('<div class="no-products">Belum ada produk. Silakan tambah produk pertama Anda.</div>');
                return;
            }
            
            dummyProducts.forEach(product => {
                const productCard = $(`
                    <div class="product-card" data-id="${product.id}">
                        <button class="delete-btn">×</button>
                        <div class="product-image">
                            ${product.image ? 
                                `<img src="${product.image}" alt="${product.name}" />` :
                                `<svg viewBox="0 0 24 24">
                                    <rect x="3" y="3" width="18" height="18" rx="2" ry="2"/>
                                    <circle cx="8.5" cy="8.5" r="1.5"/>
                                    <polyline points="21,15 16,10 5,21"/>
                                </svg>`
                            }
                        </div>
                        <div class="product-name">${product.name}</div>
                        <div class="product-price">${formatRupiah(product.price)}</div>
                    </div>
                `);
                
                grid.append(productCard);
            });
        }

        // Fungsi untuk menampilkan popup dengan animasi
        function showPopup() {
            const overlay = $('#popupOverlay');
            overlay.css('display', 'flex').hide().fadeIn(300, function() {
                overlay.addClass('show');
            });
        }

        // Fungsi untuk menyembunyikan popup dengan animasi
        function hidePopup() {
            const overlay = $('#popupOverlay');
            overlay.removeClass('show');
            setTimeout(() => {
                overlay.fadeOut(300);
            }, 100);
        }

        $(document).ready(function () {
            // Render produk awal
            renderProducts();

            // Event handlers untuk popup
            $('#popupClose').on('click', function() {
                hidePopup();
            });

            $('#popupButton').on('click', function() {
                // Redirect ke WhatsApp customer service
                window.open('https://wa.me/6281234567890?text=Halo,%20saya%20ingin%20menanyakan%20mengenai%20status%20usaha%20saya%20yang%20dinonaktifkan', '_blank');
                hidePopup();
            });

            // Tutup popup jika klik di luar area popup
            $('#popupOverlay').on('click', function(e) {
                if (e.target === this) {
                    hidePopup();
                }
            });

            // Tutup popup dengan tombol ESC
            $(document).on('keydown', function(e) {
                if (e.key === 'Escape' && $('#popupOverlay').hasClass('show')) {
                    hidePopup();
                }
            });

            // Pencarian
            $('.search-btn').on('click', function () {
                const searchTerm = $('.search-input').val().trim();
                if (searchTerm) {
                    alert('Mencari: ' + searchTerm);
                }
            });

            $('.search-input').on('keypress', function (e) {
                if (e.which === 13) {
                    $('.search-btn').click();
                }
            });

            // Toggle dropdown profil
            $('.profile-icon').on('click', function (e) {
                e.preventDefault();
                e.stopPropagation();
                $('.profile-dropdown').fadeToggle(200);
            });

            // Klik di luar dropdown → tutup
            $(document).on('click', function (e) {
                if (!$(e.target).closest('.profile-container').length) {
                    $('.profile-dropdown').fadeOut(200);
                }
            });

            // Klik tombol logout
            $('.logout-btn').on('click', function () {
                if (confirm('Apakah Anda yakin ingin logout?')) {
                    alert('Logout berhasil!');
                    // Di sini bisa redirect ke halaman login
                }
            });

            // Tambah produk - tampilkan popup nonaktif
            $(document).on('click', '.add-product', function () {
                showPopup();
            });

            // Klik produk untuk melihat detail - Tampilkan popup nonaktif
            $(document).on('click', '.product-card', function (e) {
                if ($(e.target).hasClass('delete-btn')) return;
                showPopup();
            });

            // Hapus produk
            $(document).on('click', '.delete-btn', function (e) {
                e.stopPropagation();
                
                if (!confirm('Hapus produk ini?')) return;
                
                const card = $(this).closest('.product-card');
                const id = card.data('id');
                
                // Hapus dari array
                const index = dummyProducts.findIndex(p => p.id === id);
                if (index > -1) {
                    dummyProducts.splice(index, 1);
                    renderProducts();
                    alert('Produk berhasil dihapus!');
                }
            });

            // Edit profil - tampilkan popup nonaktif
            $('.edit-button').on('click', function (e) {
                e.preventDefault();
                showPopup();
            });
        });
    </script>
</body>
</html>