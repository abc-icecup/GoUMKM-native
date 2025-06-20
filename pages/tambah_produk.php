<?php
session_start();
require_once '../config/koneksi.php'; // file koneksi ke database

// Cek login
if (!isset($_SESSION['id_user'])) {
    header('Location: masuk.php');
    exit;
}

$id_user = $_SESSION['id_user'];
$id_profil = $_GET['id_profil'] ?? null;
$is_edit = isset($_GET['edit']) && $_GET['edit'] === 'true';

// Cek apakah user sudah punya profil usaha
$stmt = $conn->prepare("SELECT id_profil FROM profil_usaha WHERE id_user = ?");
$stmt->bind_param("i", $id_user);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows === 0) {
    die("Silakan isi profil usaha terlebih dahulu sebelum menambahkan produk.");
}
$id_profil = $result->fetch_assoc()['id_profil'];

// Jika edit, ambil data produk
$data_produk = [];
if ($is_edit && isset($_GET['id_produk'])) {
    $id_produk = $_GET['id_produk'];
    $stmt = $conn->prepare("SELECT * FROM produk WHERE id_produk = ? AND id_user = ?");
    $stmt->bind_param("ii", $id_produk, $id_user);
    $stmt->execute();
    $result = $stmt->get_result();
    $data_produk = $result->fetch_assoc();
}

// Proses form tambah produk
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama_produk = $_POST['nama_produk'] ?? '';
    $harga_input = $_POST['harga'] ?? '0'; // Ambil dari form, walaupun Rp.100.000,00
    $harga = preg_replace('/[^\d]/', '', $harga_input); // Ambil hanya angkanya, misalnya jadi 100000
    $deskripsi = $_POST['deskripsi'] ?? '';
    $id_kategori = $_POST['id_kategori'] ?? '';
    $link_whatsapp = $_POST['link_whatsapp'] ?? '';

    // Validasi sederhana
    if (strlen($nama_produk) < 2 || strlen($deskripsi) < 10) {
        die("Nama dan deskripsi produk tidak valid.");
    }

    // Proses upload gambar
    $gambar_produk = $data_produk['gambar_produk'] ?? null;
    if (isset($_FILES['gambar_produk']) && $_FILES['gambar_produk']['error'] === UPLOAD_ERR_OK) {
        $folder = "../user_img/foto_produk/$id_user/";
        if (!is_dir($folder)) mkdir($folder, 0755, true);

        $ext = pathinfo($_FILES['gambar_produk']['name'], PATHINFO_EXTENSION);
        $file_name = 'produk_' . time() . '.' . $ext;
        $full_path = $folder . $file_name;

        if (move_uploaded_file($_FILES['gambar_produk']['tmp_name'], $full_path)) {
            $gambar_produk = "$id_user/$file_name"; // Simpan path relatif dari folder foto_produk
        } else {
            die("Gagal mengupload gambar.");
        }
    }

    // Simpan ke database
    if ($is_edit && isset($_GET['id_produk'])) {
        $stmt = $conn->prepare("UPDATE produk SET nama_produk=?, harga=?, id_kategori=?, deskripsi=?, link_whatsapp=?, gambar_produk=? WHERE id_produk=? AND id_user=?");
        $stmt->bind_param("siisssii", $nama_produk, $harga, $id_kategori, $deskripsi, $link_whatsapp, $gambar_produk, $_GET['id_produk'], $id_user);
    } else {
        $stmt = $conn->prepare("INSERT INTO produk (id_user, id_profil, nama_produk, harga, id_kategori, deskripsi, link_whatsapp, gambar_produk) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("iisiisss", $id_user, $id_profil, $nama_produk, $harga, $id_kategori, $deskripsi, $link_whatsapp, $gambar_produk);
    }

    if ($stmt->execute()) {
        header('Location: profil_usaha.php');
        exit;
    } else {
        echo "Query error: " . $stmt->error;
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GoUMKM - Formulir Profil Usaha</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
   <link rel="stylesheet" href="../css/tambah_produk.css">
</head>
<body>
    <div class="header">
        <h1 class="brand-title">GoUMKM</h1>
    </div>

    <div class="form-container">
        <h2 class="form-title">Tambahkan Produk Anda </h2>
        
        <div class="success-message message" id="success-message">
            <i class="fas fa-check-circle"></i>
            <span>Produk Anda berhasil disimpan!</span>
        </div>
        
        <div class="error-message message" id="error-message">
            <i class="fas fa-exclamation-circle"></i>
            <span id="error-text">Terjadi kesalahan. Silakan periksa kembali form Anda.</span>
        </div>

        <form id="business-profile-form" enctype="multipart/form-data" method="POST" action="">
            <div class="photo-upload-section">
                <div class="photo-upload" id="photo-upload">
                    <input type="file" id="gambar_produk" name="gambar_produk" accept="image/*" <?= $is_edit ? '' : 'required' ?>>
                    <i class="fas fa-camera" id="camera-icon"></i>
                    <?php if (!empty($data_produk['gambar_produk'])): ?>
                        <img class="photo-preview" id="photo-preview" src="../user_img/foto_produk/<?= htmlspecialchars($data_produk['gambar_produk']) ?>" alt="Preview">
                    <?php else: ?>
                        <img class="photo-preview" id="photo-preview" alt="Preview">
                    <?php endif; ?>

                    <div class="tooltip">Upload foto produk</div>
                </div>
                
                <div class="input-group">
                    <label class="form-label" for="nama_produk">Nama Produk </label>
                    <input type="text" class="form-input" id="nama_produk" name="nama_produk" placeholder="Masukkan nama Produk" value="<?= htmlspecialchars($data_produk['nama_produk'] ?? '') ?>" required>
                    <div class="validation-message" id="nama_produk-error">Nama Produk harus diisi</div>
                </div>
            </div>

            <div class="input-row">
                <div class="input-group">
                    <label class="form-label" for="harga">Harga(Rp.)</label>
                    <input type="text" class="form-input" id="harga" name="harga" placeholder="Masukkan Harga " value="<?= htmlspecialchars($data_produk['harga'] ?? '') ?>" required>
                    <div class="validation-message" id="harga-error">Harga harus diisi</div>
                </div>

                <div class="input-group">
                    <label class="form-label" for="id_kategori">Kategori Produk</label>
                    <select class="form-select" id="id_kategori" name="id_kategori" required>
                        <option value="1" <?= (isset($data_produk['id_kategori']) && $data_produk['id_kategori'] == 1) ? 'selected' : '' ?>>Makanan dan Minuman</option>
                        <option value="2" <?= (isset($data_produk['id_kategori']) && $data_produk['id_kategori'] == 2) ? 'selected' : '' ?>>Fashion</option>
                        <option value="3" <?= (isset($data_produk['id_kategori']) && $data_produk['id_kategori'] == 3) ? 'selected' : '' ?>>Kerajinan</option>
                        <option value="4" <?= (isset($data_produk['id_kategori']) && $data_produk['id_kategori'] == 4) ? 'selected' : '' ?>>Produk Kecantikan</option>
                        <option value="6" <?= (isset($data_produk['id_kategori']) && $data_produk['id_kategori'] == 6) ? 'selected' : '' ?>>Pertanian dan Perkebunan</option>
                    </select>
                    <div class="validation-message" id="id_kategori-error">Kategori Produk harus dipilih</div>
                </div>
            </div>

            <div class="input-group full-width">
                <label class="form-label" for="deskripsi">Deskripsi Produk </label>
                <textarea class="form-textarea" id="deskripsi" name="deskripsi" placeholder="Ceritakan tentang Produk  Anda..." required><?= htmlspecialchars($data_produk['deskripsi'] ?? '') ?></textarea>
                <div class="validation-message" id="deskripsi-error">Deskripsi Produk  harus diisi</div>
            </div>

            <div class="input-group full-width">
                <label class="form-label" for="link_whatsapp">Link WhatsApp</label>
                <input type="url" class="form-input" id="link_whatsapp" name="link_whatsapp" placeholder="https://wa.me/62xxxxxxxxxxx" value="<?= htmlspecialchars($data_produk['link_whatsapp'] ?? '') ?>">
                <div class="validation-message" id="link_whatsapp-error">Format link WhatsApp tidak valid</div>
            </div>

            <button type="submit" class="submit-btn" id="submit-btn">
                <span id="btn-text">Simpan</span>
                <i class="fas fa-spinner fa-spin" id="loading-icon" style="display: none; margin-left: 8px;"></i>
            </button>
        </form>
    </div>


    <script>
        $(document).ready(function() {
            // Photo upload functionality
            $('#gambar_produk').on('change', function(e) {
                const file = e.target.files[0];
                if (file) {
                    if (file.size > 5 * 1024 * 1024) { // 5MB limit
                        showError('Ukuran foto maksimal 5MB');
                        return;
                    }

                    const reader = new FileReader();
                    reader.onload = function(e) {
                        $('#photo-preview').attr('src', e.target.result).show();
                        $('#camera-icon').hide();
                        $('#photo-upload').addClass('has-image');
                    };
                    reader.readAsDataURL(file);
                }
            });

            // Click on photo upload area
            $('#photo-upload').on('click', function(e) {
                if (e.target.type !== 'file') {
                    $('#gambar_produk').click();
                }
            });

            // Form validation
            function validateField(fieldId, validationFn, errorMessage) {
                const field = $('#' + fieldId);
                const value = field.val().trim();
                const isValid = validationFn(value);
                
                if (isValid) {
                    field.removeClass('invalid').addClass('valid');
                    $('#' + fieldId + '-error').hide();
                } else {
                    field.removeClass('valid').addClass('invalid');
                    $('#' + fieldId + '-error').text(errorMessage).show();
                }
                
                return isValid;
            }

            // Real-time validation
            $('#nama_produk').on('blur', function() {
                validateField('nama_produk', val => val.length >= 2, 'Nama Produk minimal 2 karakter');
            });

            $('#harga').on('blur', function() {
                validateField('harga', val => val.length >= 2, 'Harga(Rp.)');
            });

            $('#id_kategori').on('change', function() {
                validateField('id_kategori', val => val !== '', 'Kategori Produk  harus dipilih');
            });

            $('#deskripsi').on('blur', function() {
                validateField('deskripsi', val => val.length >= 10, 'Deskripsi minimal 10 karakter');
            });

            $('#link_whatsapp').on('blur', function() {
                const val = $(this).val().trim();
                if (val === '') return true; // Optional field
                
                validateField('link_whatsapp', val => {
                    return val === '' || /^https:\/\/wa\.me\/\d+/.test(val);
                }, 'Format: https://wa.me/628123456789');
            });

            // Form submission
            $('#business-profile-form').on('submit', function(e) {
                // Validate all fields
                const isProductNameValid = validateField('nama_produk', val => val.length >= 2, 'Nama Produk minimal 2 karakter');
                const isPriceValid = validateField('harga', val => val.length >= 2, 'Harga(Rp.)');
                const isCategoryValid = validateField('id_kategori', val => val !== '', 'Kategori Produk harus dipilih');
                const isDescriptionValid = validateField('deskripsi', val => val.length >= 10, 'Deskripsi minimal 10 karakter');
                
                const whatsappVal = $('#link_whatsapp').val().trim();
                const isWhatsappValid = whatsappVal === '' || /^https:\/\/wa\.me\/\d+/.test(whatsappVal);
                
                if (!isWhatsappValid) {
                    validateField('link_whatsapp', () => false, 'Format: https://wa.me/628123456789');
                }

                if (!isProductNameValid || !isPriceValid || !isCategoryValid || !isDescriptionValid || !isWhatsappValid) {
                    showError('Silakan perbaiki kesalahan pada form');
                    return false;
                }

                // Jika semua valid, submit form ke PHP
                return true;
            });

            function showError(message) {
                $('#error-text').text(message);
                $('#error-message').fadeIn();
                setTimeout(function() {
                    $('#error-message').fadeOut();
                }, 5000);
            }

            // Character counter for description
            $('#deskripsi').on('input', function() {
                const maxLength = 500;
                const currentLength = $(this).val().length;
                
                if (currentLength > maxLength) {
                    $(this).val($(this).val().substring(0, maxLength));
                }
            });

            // Auto-format WhatsApp link
            $('#link_whatsapp').on('input', function() {
                let val = $(this).val();
                
                // Auto-add https://wa.me/ if user enters just numbers
                if (/^\d+$/.test(val) && val.length > 8) {
                    $(this).val('https://wa.me/' + val);
                }
            });

            // Smooth scroll to error
            function scrollToFirstError() {
                const firstError = $('.form-input.invalid, .form-select.invalid, .form-textarea.invalid').first();
                if (firstError.length) {
                    $('html, body').animate({
                        scrollTop: firstError.offset().top - 100
                    }, 500);
                }
            }

            // Add some visual feedback
            $('.form-input, .form-select, .form-textarea').on('focus', function() {
                $(this).parent().addClass('focused');
            }).on('blur', function() {
                $(this).parent().removeClass('focused');
            });
        });
    </script>

</body>
</html>