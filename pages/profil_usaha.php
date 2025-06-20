<?php
session_start();
require_once '../config/koneksi.php'; // sesuaikan path-nya jika perlu

// Cek status form dari session
$status = $_SESSION['form_submitted'] ?? 'not set';
unset($_SESSION['form_submitted']); // hapus setelah dibaca

// Pastikan user sudah login
if (!isset($_SESSION['id_user'])) {
    header("Location: masuk.php");
    exit;
}

// Ambil id_profil berdasarkan id_user yang sedang login
$id_user = $_SESSION['id_user'];

// Saat menarik data dari tabel profil_usaha
$query_profil = $conn->prepare("SELECT id_profil, id_user, gambar_usaha, nama_usaha, nama_pemilik, kategori_usaha, alamat, kecamatan, kelurahan, deskripsi, link_whatsapp FROM profil_usaha WHERE id_user = ?");
$query_profil->bind_param("i", $id_user);
$query_profil->execute();
$result_profil = $query_profil->get_result();
$data_profil = $result_profil->fetch_assoc();



// 3. Jika user belum mengisi profil usaha, redirect ke formulir
if (!$data_profil) {
    header("Location: formulir.php");
    exit;
}

$id_profil = $data_profil['id_profil'];

// Ambil semua kolom dari produk, karena akan dipakai di bagian HTML
$query_produk = $conn->prepare("SELECT * FROM produk WHERE id_profil = ?");
$query_produk->bind_param("i", $id_profil);
$query_produk->execute();
$result_produk = $query_produk->get_result();
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
        
        <div class="profile-container">
            <div class="profile-icon" id="profileToggle">
                <?php if (!empty($data_profil['gambar_usaha'])): ?>
                    <img src="../user_img/foto_usaha/<?= htmlspecialchars($data_profil['gambar_usaha']) ?>" alt="Akun" class="user-avatar">
                <?php else: ?>
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none">
                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2" stroke="currentColor" stroke-width="2"/>
                        <circle cx="12" cy="7" r="4" stroke="currentColor" stroke-width="2"/>
                    </svg>
                <?php endif; ?>
            </div>

            <div class="profile-dropdown" id="profileDropdown" style="display: none;">
                <div class="profile-avatar">
                    <?php if (!empty($data_profil['gambar_usaha'])): ?>
                        <img src="../user_img/foto_usaha/<?= htmlspecialchars($data_profil['gambar_usaha']) ?>" alt="Akun" class="user-avatar">
                    <?php else: ?>
                        <svg width="40" height="40" viewBox="0 0 24 24" fill="none">
                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2" stroke="currentColor" stroke-width="2"/>
                            <circle cx="12" cy="7" r="4" stroke="currentColor" stroke-width="2"/>
                        </svg>
                    <?php endif; ?>
                </div>
                <div class="profile-email"><?= htmlspecialchars($_SESSION['email']) ?></div>
                <form method="POST" action="logout.php">
                    <button type="submit" class="logout-btn">Log Out</button>
                </form>
            </div>
        </div>
    </header>

    <div class="container">
        <div class="main-content">
            <div class="form-section">
            <?php if ($status === 'success'): ?>
                <div class="alert alert-success">Profil berhasil disimpan!</div>
            <?php endif; ?>
            <div class="form-header">
                    <h2 class="form-title">Profil Usaha Anda</h2>
                    <a href="formulir.php?edit=true" class="edit-button" method="POST" enctype="multipart/form-data">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" width="20px" height="20px" class="edit-icon">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                        </svg>
                    </a>
                </div>
                
                <div class="form-content">
                    <div class="image-placeholder">
                        <?php if (!empty($data_profil['gambar_usaha'])): ?>
                            <img src="../user_img/foto_usaha/<?= htmlspecialchars($data_profil['gambar_usaha']) ?>" alt="Gambar Usaha" class="img-preview">
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

                <!-- Tombol tambah produk -->
                <a href="tambah_produk.php?id_profil=<?= $id_profil ?>" class="add-product" style="text-decoration: none;">
                    <div class="add-icon">+</div>
                    <div class="add-text">Tambah Produk</div>
                </a>

                <?php
                // Ambil produk berdasarkan id_profil (yang sudah kamu ambil sebelumnya)
                // $produk_stmt = $conn->prepare("SELECT * FROM produk WHERE id_profil = ?");
                // $produk_stmt->bind_param("i", $id_profil);
                // $produk_stmt->execute();
                // $produk_result = $produk_stmt->get_result();
                                    
                
                // Cek apakah ada produk
                if ($result_produk->num_rows > 0):
                    while ($produk = $result_produk->fetch_assoc()):
                ?>
                    <div class="product-card" data-id="<?= $produk['id_produk'] ?>">
                        <div class="action-buttons">
                            <!-- Tombol Edit -->
                            <a href="tambah_produk.php?edit=true&id_produk=<?= $produk['id_produk'] ?>" class="edit-btn" title="Edit">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" width="20px" height="20px" class="edit-icon">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                                </svg>
                            </a>

                            <!-- Tombol Hapus -->
                            <button class="delete-btn" onclick="if(confirm('Hapus produk ini?')) location.href='hapus_produk.php?id=<?= $produk['id_produk'] ?>'" title="Hapus">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                </svg>
                            </button>
                        </div>
                        
                        <div class="product-image" name="gambar_produk">
                            <?php if (!empty($produk['gambar_produk'])): ?>
                                <img src="../user_img/foto_produk/<?= htmlspecialchars($produk['gambar_produk']) ?>" alt="<?= htmlspecialchars($produk['nama_produk']) ?>"/>
                            <?php else: ?>
                                <img src="icon/no-image.png" alt="Belum ada gambar" />
                            <?php endif; ?>
                        </div>
                        <div class="product-info">
                            <div class="product-name"><?= htmlspecialchars($produk['nama_produk']) ?></div>
                            <div class="price">Rp<?= number_format($produk['harga'], 0, ',', '.') ?></div>

                        </div>
                    </div>
                <?php
                    endwhile;
                else:
                ?>
                    <p style="margin: 0; white-space: nowrap; display: flex; align-items: center; height:auto;">Belum ada produk. Silakan tambah produk pertama Anda.</p>
                <?php endif; ?>
            </div>
        </div>

    </div>

    <script>
        $(document).ready(function () {
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

            // // Klik ikon profil → toggle dropdown
            $('.profile-icon').on('click', function (e) {
                e.preventDefault(); // Hindari redirect ke profil_usaha.php
                e.stopPropagation(); // Mencegah klik ke dokumen

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
                window.location.href = 'logout.php'; // pastikan kamu punya logout.php
            });

            // Tambah produk
            $('.add-product').on('click', function () {
                window.location.href = 'tambah_produk.php';
            });

            // Klik produk: redirect ke katalog
            $('.products-grid').on('click', '.product-card', function () {
                const id = $(this).data('id');
                window.location.href = 'katalog_produk.php?id=' + id;
            });

            // Hapus produk
            // $('.products-grid').on('click', '.delete-btn', function (e) {
            //     e.stopPropagation();

            //     if (!confirm('Hapus produk ini?')) return;

            //     const card = $(this).closest('.product-card');
            //     const id = card.data('id');

            //     $.ajax({
            //         url: 'hapus_produk.php',
            //         method: 'GET',
            //         data: { id },
            //         success: function (res) {
            //             const json = JSON.parse(res);
            //             if (json.status === 'success') {
            //                 card.fadeOut(300, () => card.remove());
            //             } else {
            //                 alert('Gagal menghapus produk.');
            //             }
            //         },
            //         error: function () {
            //             alert('Terjadi kesalahan saat menghapus.');
            //         }
            //     });
            // });

            // Jika tidak ada produk
            // if ($('.product-card').length === 0) {
            //     $('.products-grid').html('<p>Tidak ada produk.</p>');
            // }
        });
    </script>

    <?php include '../Includes/footer.php'; ?>
</body>
</html>