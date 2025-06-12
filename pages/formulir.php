<?php
session_start();
require_once '../config/koneksi.php'; // File koneksi database Anda

// Cek apakah user sudah login
// if (!isset($_SESSION['id_user'])) {
//     header('Location: login.php');
//     exit;
// }

// Proses form saat disubmit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_user = $_SESSION['id_user'];
    $nama_usaha = $_POST['business-name'];
    $nama_pemilik = $_POST['owner-name'];
    $kategori_usaha = $_POST['business-category'];
    
    // INI BELUM DITAMBAHKAN DI DIV INPUT
    $alamat = $_POST['business-address'];
    $kecamatan = $_POST['kecamatan'];
    $kelurahan = $_POST['kelurahan'];
    // dalam kurung [] itu id dan name di HTML
    // BELUMMM
    
    $deskripsi = $_POST['business-description'];
    $link_whatsapp = $_POST['whatsapp-link'];

    $kategori_valid = ['Perdagangan', 'Jasa'];
    if (!in_array($kategori_usaha, $kategori_valid)) {
        die("Kategori usaha tidak valid.");
    }
    
    // Proses upload gambar
    $gambar = null;
    if (isset($_FILES['business-photo']) && $_FILES['business-photo']['error'] == UPLOAD_ERR_OK) {
        $targetDir = "uploads/";
        if (!is_dir($targetDir)) mkdir($targetDir, 0755, true);

        $ext = pathinfo($_FILES['business-photo']['name'], PATHINFO_EXTENSION);
        $fileName = uniqid('usaha_') . '.' . $ext;
        $filePath = $targetDir . $fileName;

        if (move_uploaded_file($_FILES['business-photo']['tmp_name'], $filePath)) {
            $gambar = $fileName;
        }
    }

    // Simpan ke database
    $stmt = $conn->prepare("INSERT INTO profil_usaha (id_user, gambar, nama_usaha, nama_pemilik, kategori_usaha, deskripsi, link_whatsapp) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("issssss", $id_user, $gambar, $nama_usaha, $nama_pemilik, $kategori_usaha, $deskripsi, $link_whatsapp);
    if ($stmt->execute()) {
        header('Location: beranda.php '); // Redirect setelah sukses
        exit;
    } else {
        $error = "Gagal menyimpan data. " . $stmt->error;
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
    <link rel="stylesheet" href="../css/formulir.css">
</head>
<body>
    <div class="header">
        <h1 class="brand-title">GoUMKM</h1>
    </div>

    <div class="form-container">
        <h2 class="form-title">Formulir Profil Usaha</h2>
        
        <!-- FORM INPUT PROFIL -->
        <form id="business-profile-form" method="POST" enctype="multipart/form-data">
            <div class="photo-upload-section">
                <div class="photo-upload" id="photo-upload">
                    <input type="file" id="business-photo" name="business-photo" accept="image/*">
                    <i class="fas fa-camera" id="camera-icon"></i>
                    <img class="photo-preview" id="photo-preview" alt="Preview">
                    <div class="tooltip">Upload foto usaha</div>
                </div>
                
                <div class="input-group">
                    <label class="form-label" for="business-name">Nama Usaha</label>
                    <input type="text" class="form-input" id="business-name" name="business-name" placeholder="Masukkan nama usaha" required>
                    <div class="validation-message" id="business-name-error">Nama usaha harus diisi</div>
                </div>
            </div>

            <div class="input-row">
                <div class="input-group">
                    <label class="form-label" for="owner-name">Nama Pemilik Usaha</label>
                    <input type="text" class="form-input" id="owner-name" name="owner-name" placeholder="Masukkan nama pemilik" required>
                    <div class="validation-message" id="owner-name-error">Nama pemilik harus diisi</div>
                </div>
                
                <div class="input-group">
                    <label class="form-label" for="business-category">Kategori Usaha</label>
                    <select class="form-select" id="business-category" name="business-category" required>
                        <option value="">Pilih kategori</option>
                        <option value="Perdagangan">Perdagangan</option>
                        <option value="Jasa">Jasa</option>
                    </select>
                    <div class="validation-message" id="business-category-error">Kategori usaha harus dipilih</div>
                </div>
            </div>

            <div class="input-group full-width">
                <label class="form-label" for="business-address">Alamat Usaha</label>
                <textarea class="form-textarea small" id="business-address" name="business-address" placeholder="Masukkan alamat lengkap usaha Anda..." required></textarea>
                <div class="char-counter">
                    <span id="address-counter">max 300 character</span>
                </div>
                <div class="validation-message" id="business-address-error">Alamat usaha harus diisi</div>
            </div>

            <div class="input-row">
                <div class="input-group">
                    <label class="form-label" for="kecamatan">Kecamatan</label>
                    <select class="form-select" id="kecamatan" name="kecamatan" required>
                        <option value="">Pilih Kecamatan</option>
                        <option value="bacukiki">Bacukiki</option>
                        <option value="bacukiki-barat">Bacukiki Barat</option>
                        <option value="soreang">Soreang</option>
                        <option value="ujung">Ujung</option>
                    </select>
                    <div class="validation-message" id="kecamatan-error">Kecamatan harus dipilih</div>
                </div>
                
                <div class="input-group">
                    <label class="form-label" for="kelurahan">Kelurahan</label>
                    <select class="form-select" id="kelurahan" name="kelurahan" required>
                        <option value="">Pilih Kelurahan</option>
                    </select>
                    <div class="validation-message" id="kelurahan-error">Kelurahan harus dipilih</div>
                </div>
            </div>

            <div class="input-group full-width">
                <label class="form-label" for="business-description">Deskripsi Usaha</label>
                <textarea class="form-textarea" id="business-description" name="business-description" placeholder="Ceritakan tentang usaha Anda..." required></textarea>
                <div class="char-counter">
                    <span id="description-counter">max 1000 character</span>
                </div>
                <div class="validation-message" id="business-description-error">Deskripsi usaha harus diisi</div>
            </div>

            <div class="input-group full-width">
                <label class="form-label" for="whatsapp-link">Link WhatsApp</label>
                <input type="url" class="form-input" id="whatsapp-link" name="whatsapp-link" placeholder="https://wa.me/6281xxx">
                <div class="validation-message" id="whatsapp-link-error">Format link WhatsApp tidak valid</div>
            </div>

            <button type="submit" class="submit-btn" id="submit-btn">
                <span id="btn-text">Simpan</span>
            </button>
        </form>

        <div class="success-message message" id="success-message">
            <i class="fas fa-check-circle"></i>
            <span>Profil usaha berhasil disimpan!</span>
        </div>
        
        <div class="error-message message" id="error-message">
            <i class="fas fa-exclamation-circle"></i>
            <span id="error-text">Terjadi kesalahan. Silakan periksa kembali form Anda.</span>
        </div>

        <?php if (isset($error)): ?>
        <div class="error-message message">
            <i class="fas fa-exclamation-circle"></i>
            <span><?= htmlspecialchars($error) ?></span>
        </div>
        <?php endif; ?>

    </div>

    <script>
        // Data kelurahan berdasarkan kecamatan sesuai tabel Kabupaten Gowa
        const kelurahanData = {
            'bacukiki': ['Galung Maloang', 'Lemoe', 'Lompoe', 'Watang Bacukiki'],
            'bacukiki-barat': ['Bumi Harapan', 'Cappa Galung', 'Kampung Baru', 'Lumpue', 'Sumpang Minangae', 'Tiro Sompe'],
            'soreang': ['Bukit Harapan', 'Bukit Indah', 'Kampung Pisang', 'Lakessi', 'Ujung Baru', 'Ujung Lare', 'Watang Soreang'],
            'ujung': ['Labukkang', 'Lapadde', 'Mallusetasi', 'Ujung Bulu', 'Ujung Sabbang']
        };
        
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

            // Kecamatan change handler
            $('#kecamatan').on('change', function() {
                const selectedKecamatan = $(this).val();
                const kelurahanSelect = $('#kelurahan');
                
                // Clear kelurahan options
                kelurahanSelect.html('<option value="">Pilih Kelurahan</option>');
                
                if (selectedKecamatan && kelurahanData[selectedKecamatan]) {
                    // Add kelurahan options
                    kelurahanData[selectedKecamatan].forEach(function(kelurahan) {
                        kelurahanSelect.append(`<option value="${kelurahan.toLowerCase().replace(/\s+/g, '-')}">${kelurahan}</option>`);
                    });
                }
                
                // Reset kelurahan validation
                kelurahanSelect.removeClass('valid invalid');
                $('#kelurahan-error').hide();
            });

            // Character counter functionality
            function updateCharCounter(fieldId, counterId, maxLength) {
                const field = $('#' + fieldId);
                const counter = $('#' + counterId);
                
                field.on('input', function() {
                    const currentLength = $(this).val().length;
                    counter.text(currentLength);
                    
                    if (currentLength > maxLength) {
                        $(this).val($(this).val().substring(0, maxLength));
                        counter.text(maxLength);
                    }
                    
                    // Change color based on usage
                    if (currentLength > maxLength * 0.9) {
                        counter.parent().css('color', '#ef4444');
                    } else if (currentLength > maxLength * 0.75) {
                        counter.parent().css('color', '#f59e0b');
                    } else {
                        counter.parent().css('color', '#6b7280');
                    }
                });
            }

            // Initialize character counters
            updateCharCounter('business-address', 'address-counter', 300);
            updateCharCounter('business-description', 'description-counter', 1000);


            // Form validation
            function validateField(fieldId, validationFn, errorMessage, optional = false) {
                const field = $('#' + fieldId);
                const value = field.val().trim();

                if (optional && value === '') {
                    field.removeClass('invalid').addClass('valid');
                    $('#' + fieldId + '-error').hide();
                    return true;
                }

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
            $('#business-name').on('blur', function() {
                validateField('business-name', val => val.length >= 7, 'Nama usaha minimal 7 karakter');
            });

            $('#owner-name').on('blur', function() {
                validateField('owner-name', val => val.length >= 3, 'Nama pemilik minimal 3 karakter');
            });

            $('#business-category').on('change', function() {
                validateField('business-category', val => val !== '', 'Kategori usaha harus dipilih');
            });

            $('#business-address').on('blur', function() {
                validateField('business-address', val => val.length >= 10, 'Alamat usaha minimal 10 karakter');
            });

            $('#kecamatan').on('change', function() {
                validateField('kecamatan', val => val !== '', 'Kecamatan harus dipilih');
            });

            $('#kelurahan').on('change', function() {
                validateField('kelurahan', val => val !== '', 'Kelurahan harus dipilih');
            });

            $('#business-description').on('blur', function() {
                validateField('business-description', val => val.length >= 20, 'Deskripsi minimal 20 karakter');
            });

            // WhatsApp wajib diisi dan harus sesuai format
            $('#whatsapp-link').on('blur', function() {
                validateField('whatsapp-link', val => /^https:\/\/wa\.me\/\d+$/.test(val), 'Format WA wajib: https://wa.me/628123456789');
            });


            // Form submission
            $('#business-profile-form').on('submit', function(e) {
                e.preventDefault();
                
                // Hide previous messages
                $('.message').hide();
                
                // Validate all fields
                const isBusinessNameValid = validateField('business-name', val => val.length >= 7, 'Nama usaha minimal 7 karakter');
                const isOwnerNameValid = validateField('owner-name', val => val.length >= 3, 'Nama pemilik minimal 3 karakter');
                const isCategoryValid = validateField('business-category', val => val !== '', 'Kategori usaha harus dipilih');
                const isAddressValid = validateField('business-address', val => val.length >= 10, 'Alamat usaha minimal 10 karakter');
                const isKecamatanValid = validateField('kecamatan', val => val !== '', 'Kecamatan harus dipilih');
                const isKelurahanValid = validateField('kelurahan', val => val !== '', 'Kelurahan harus dipilih');
                const isDescriptionValid = validateField('business-description', val => val.length >= 20, 'Deskripsi minimal 20 karakter');
                
                const isWhatsappValid = validateField('whatsapp-link', val => /^https:\/\/wa\.me\/\d+$/.test(val), 'Format WA wajib: https://wa.me/628123456789');

                if (!isBusinessNameValid || !isOwnerNameValid || !isCategoryValid || !isAddressValid || 
                    !isKecamatanValid || !isKelurahanValid || !isDescriptionValid || !isWhatsappValid) {
                    showError('Silakan perbaiki kesalahan pada form');
                    scrollToFirstError();
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
                $('#kelurahan').html('<option value="">Pilih Kelurahan</option>');
                $('#address-counter').text('0');
                $('#description-counter').text('0');
                $('.char-counter').css('color', '#6b7280');
            }

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