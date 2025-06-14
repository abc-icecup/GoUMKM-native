<?php
session_start();
require_once '../config/koneksi.php'; // file koneksi ke database

$id_user = $_SESSION['id_user'];
$is_edit = isset($_GET['edit']) && $_GET['edit'] === 'true';

// Cek login
if (!isset($_SESSION['id_user'])) {
    header('Location: masuk.php');
    exit;
}

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
    $stmt = $conn->prepare("SELECT * FROM produk WHERE id_produk = ? AND id_user = ?");
    $stmt->bind_param("ii", $_GET['id_produk'], $id_user);
    $stmt->execute();
    $result = $stmt->get_result();
    $data_produk = $result->fetch_assoc();
}

// Proses form tambah produk
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_user = $_SESSION['id_user'];
    $nama_produk = $_POST['product-name'] ?? '';
    $harga = $_POST['price'] ?? '';
    $gambar = $_POST['business-photo'] ??'';
    $deskripsi = $_POST['business-description'] ?? '';
    $link_whatsapp = $_POST['whatsapp-link'] ?? '';
    $id_kategori = $_POST['business-category'] ?? '';
    $id_profil = $_POST['id_profil'] ?? '';

    // Validasi sederhana
    if (strlen($nama_produk) < 2 || strlen($deskripsi) < 10) {
        http_response_code(400);
        echo json_encode(['status' => 'error', 'message' => 'Validasi gagal, masukkan nama produk dan deskripsi yang lebih panjang']);
        exit;
    }

    // Proses upload gambar
    $gambar = $data_produk['gambar'] ?? null;
    if (isset($_FILES['business-photo']) && $_FILES['business-photo']['error'] === UPLOAD_ERR_OK) {
        $targetDir = "../uploads/";
        if (!is_dir($targetDir)) mkdir($targetDir, 0755, true);

        $ext = pathinfo($_FILES['business-photo']['name'], PATHINFO_EXTENSION);
        $fileName = uniqid('produk_') . '.' . $ext;
        $filePath = $targetDir . $fileName;

        if (move_uploaded_file($_FILES['business-photo']['tmp_name'], $filePath)) {
            $gambar = $fileName;
        } else {
            http_response_code(500);
            echo json_encode(['status' => 'error', 'message' => 'Upload gambar gagal.']);
            exit;
        }
    }

    // Simpan ke database (edit atau insert)
    if ($is_edit && isset($_GET['id_produk'])) {
        $stmt = $conn->prepare("UPDATE produk SET nama_produk=?, harga=?, kategori=?, deskripsi=?, whatsapp_link=?, gambar=? WHERE id_produk=? AND id_user=?");
        $stmt->bind_param("ssssssii", $nama_produk, $harga, $kategori, $deskripsi, $whatsapp, $gambar, $_GET['id_produk'], $id_user);
    } else {
        $stmt = $conn->prepare("INSERT INTO produk (id_user, id_profil, nama_produk, harga, kategori, deskripsi, whatsapp_link, gambar) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("iissssss", $id_user, $id_profil, $nama_produk, $harga, $kategori, $deskripsi, $whatsapp, $gambar);
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
            <span>Produk Anda  berhasil disimpan!</span>
        </div>
        
        <div class="error-message message" id="error-message">
            <i class="fas fa-exclamation-circle"></i>
            <span id="error-text">Terjadi kesalahan. Silakan periksa kembali form Anda.</span>
        </div>

        <form id="business-profile-form" enctype="multipart/form-data" method="POST" action="">
            <div class="photo-upload-section">
                <div class="photo-upload" id="photo-upload">
                    <input type="file" id="business-photo" accept="image/*">
                    <i class="fas fa-camera" id="camera-icon"></i>
                    <img class="photo-preview" id="photo-preview" alt="Preview">
                    <div class="tooltip">Upload foto produk </div>
                </div>
                
                <div class="input-group">
                    <label class="form-label" for="product-name">Nama Produk </label>
                    <input type="text" class="form-input" id="product-name" name="product-name" placeholder="Masukkan nama Produk" required>
                    <div class="validation-message" id="product-name-error">Nama Produk  harus diisi</div>
                </div>
            </div>

            <div class="input-row">
                <div class="input-group">
                    <label class="form-label" for="price">Harga(Rp.)</label>
                    <input type="text" class="form-input" id="price" name="price" placeholder="Masukkan Harga " required>
                    <div class="validation-message" id="price-error">Nama produk  harus diisi</div>
                </div>
            
            <div class="input-group full-width">
                <label class="form-label" for="business-photo">Foto Produk</label>
                <input type="file" class="form-input" id="business-photo" name="business-photo" accept="image/*" required>
                <div class="validation-message" id="business-photo-error">Foto produk harus diunggah</div>
            </div>

            <div class="input-group full-width">
                <label class="form-label" for="business-description">Deskripsi Produk </label>
                <textarea class="form-textarea" id="business-description" name="business-description" placeholder="Ceritakan tentang Produk  Anda..." required></textarea>
                <div class="validation-message" id="business-description-error">Deskripsi Produk  harus diisi</div>
            </div>

            <div class="input-group full-width">
                <label class="form-label" for="whatsapp-link">Link WhatsApp</label>
                <input type="url" class="form-input" id="whatsapp-link" name="whatsapp-link" placeholder="https://wa.me/628123456789">
                <div class="validation-message" id="whatsapp-link-error">Format link WhatsApp tidak valid</div>
            </div>

                <div class="input-group">
                    <label class="form-label" for="business-category">Kategori Produk </label>
                    <select class="form-select" id="business-category" name="business-category" required>
                        <option value="Makanan dan Minuman ">Makanan dan Minuman </option>
                        <option value="Fashion">Fashion</option>
                        <option value="Kerajinan">Kerajinan</option>
                        <option value="Produk Kecantikan">Produk Kecantikan</option>
                        <option value="Pertanian dan Perkebunan">Pertanian dan Perkebunan </option>
                    </select>
                    <div class="validation-message" id="business-category-error">Kategori Produk harus dipilih</div>
                </div>
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
            $('#business-photo').on('change', function(e) {
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
                    $('#business-photo').click();
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
            $('#product-name').on('blur', function() {
                validateField('product-name', val => val.length >= 2, 'Nama Produk minimal 2 karakter');
            });

            $('#price').on('blur', function() {
                validateField('price', val => val.length >= 2, 'Harga(Rp.)');
            });

            $('#business-category').on('change', function() {
                validateField('business-category', val => val !== '', 'Kategori Produk  harus dipilih');
            });

            $('#business-description').on('blur', function() {
                validateField('business-description', val => val.length >= 10, 'Deskripsi minimal 10 karakter');
            });

            $('#whatsapp-link').on('blur', function() {
                const val = $(this).val().trim();
                if (val === '') return true; // Optional field
                
                validateField('whatsapp-link', val => {
                    return val === '' || /^https:\/\/wa\.me\/\d+/.test(val);
                }, 'Format: https://wa.me/628123456789');
            });

            // Form submission
            $('#business-profile-form').on('submit', function(e) {
                e.preventDefault();
                
                // Hide previous messages
                $('.message').hide();
                
                // Validate all fields
                const isProductNameValid = validateField('product-name', val => val.length >= 2, 'Nama Produk minimal 2 karakter');
                const isPriceValid = validateField('price', val => val.length >= 2, 'Harga(Rp.)');
                const isCategoryValid = validateField('business-category', val => val !== '', 'Kategori Produk harus dipilih');
                const isDescriptionValid = validateField('business-description', val => val.length >= 10, 'Deskripsi minimal 10 karakter');
                
                const whatsappVal = $('#whatsapp-link').val().trim();
                const isWhatsappValid = whatsappVal === '' || /^https:\/\/wa\.me\/\d+/.test(whatsappVal);
                
                if (!isWhatsappValid) {
                    validateField('whatsapp-link', () => false, 'Format: https://wa.me/628123456789');
                }

                if (!isProductNameValid || !isPriceValid || !isCategoryValid || !isDescriptionValid || !isWhatsappValid) {
                    showError('Silakan perbaiki kesalahan pada form');
                    return;
                }

                // Show loading state
                showLoading(true);

                // Simulate API call
                setTimeout(function() {
                    showLoading(false);
                    
                    // Simulate success (90% success rate for demo)
                    if (Math.random() > 0.1) {
                        showSuccess();
                        // Reset form after success
                        setTimeout(function() {
                            resetForm();
                        }, 2000);
                    } else {
                        showError('Terjadi kesalahan server. Silakan coba lagi.');
                    }
                }, 2000);
            });

            // Utility functions
            function showLoading(show) {
                if (show) {
                    $('.form-container').addClass('loading');
                    $('#btn-text').text('Menyimpan...');
                    $('#loading-icon').show();
                    $('#submit-btn').prop('disabled', true);
                } else {
                    $('.form-container').removeClass('loading');
                    $('#btn-text').text('Simpan');
                    $('#loading-icon').hide();
                    $('#submit-btn').prop('disabled', false);
                }
            }

            function showSuccess() {
                $('#success-message').fadeIn();
                setTimeout(function() {
                    $('#success-message').fadeOut();
                }, 4000);
            }

            function showError(message) {
                $('#error-text').text(message);
                $('#error-message').fadeIn();
                setTimeout(function() {
                    $('#error-message').fadeOut();
                }, 5000);
            }

            function resetForm() {
                $('#business-profile-form')[0].reset();
                $('#photo-preview').hide();
                $('#camera-icon').show();
                $('#photo-upload').removeClass('has-image');
                $('.form-input, .form-select, .form-textarea').removeClass('valid invalid');
                $('.validation-message').hide();
            }

            // Character counter for description
            $('#business-description').on('input', function() {
                const maxLength = 500;
                const currentLength = $(this).val().length;
                
                if (currentLength > maxLength) {
                    $(this).val($(this).val().substring(0, maxLength));
                }
            });

            // Auto-format WhatsApp link
            $('#whatsapp-link').on('input', function() {
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