<?php
session_start();
require_once '../config/koneksi.php';

echo "SESSION: " . ($_SESSION['form_submitted'] ?? 'not set') . "<br>"; // <-- DEBUG: tampilkan status session

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    unset($_SESSION['form_submitted']);
}

$is_edit = isset($_GET['edit']) && $_GET['edit'] === 'true';
$id_user = $_SESSION['id_user'] ?? null;

if (!$id_user) {
    header('Location: masuk.php');
    exit;
}

if (isset($_SESSION['form_submitted']) && $_SESSION['form_submitted'] === true) {
    header("Location: profil_usaha.php");
    exit;
}

// Ambil data lama untuk mode edit
$data_lama = null;
if ($is_edit) {
    $stmt = $conn->prepare("SELECT * FROM profil_usaha WHERE id_user = ?");
    $stmt->bind_param("i", $id_user);
    $stmt->execute();
    $result = $stmt->get_result();
    $data_lama = $result->fetch_assoc();
}

// Variabel error dan input default
$error_message = '';
$gambar_error = '';

$nama_usaha = $_POST['business-name'] ?? ($data_lama['nama_usaha'] ?? '');
$nama_pemilik = $_POST['owner-name'] ?? ($data_lama['nama_pemilik'] ?? '');
$kategori_usaha = $_POST['business-category'] ?? ($data_lama['kategori_usaha'] ?? '');
$alamat = $_POST['business-address'] ?? ($data_lama['alamat'] ?? '');
$kecamatan = $_POST['kecamatan'] ?? ($data_lama['kecamatan'] ?? '');
$kelurahan = $_POST['kelurahan'] ?? ($data_lama['kelurahan'] ?? '');
$deskripsi = $_POST['business-description'] ?? ($data_lama['deskripsi'] ?? '');
$link_whatsapp = $_POST['whatsapp-link'] ?? ($data_lama['link_whatsapp'] ?? '');
$gambar = $data_lama['gambar'] ?? '';

// Jika form disubmit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama_usaha = trim($_POST['business-name']);
    $nama_pemilik = trim($_POST['owner-name']);
    $kategori_usaha = $_POST['business-category'];
    $alamat = trim($_POST['business-address']);
    $kecamatan = trim($_POST['kecamatan']);
    $kelurahan = trim($_POST['kelurahan']);
    $deskripsi = trim($_POST['business-description']);
    $link_whatsapp = trim($_POST['whatsapp-link']);

    $kategori_valid = ['Perdagangan', 'Jasa'];
    if (!in_array($kategori_usaha, $kategori_valid)) {
        $error_message = "Kategori usaha tidak valid.";
    }

    // Validasi gambar
    if (!$upload_gambar_baru) {
        $gambar = $_POST['gambar_lama'] ?? ($data_lama['gambar'] ?? null);
    }

    $upload_gambar_baru = isset($_FILES['business-photo']) && $_FILES['business-photo']['error'] !== UPLOAD_ERR_NO_FILE;

    if ($upload_gambar_baru) {
        // $gambar = "$id_user/$file_name";
        $ext = pathinfo($_FILES['business-photo']['name'], PATHINFO_EXTENSION);
        $allowed_ext = ['jpg', 'jpeg', 'png', 'webp'];
        
        if (!in_array(strtolower($ext), $allowed_ext)) {
            $gambar_error = "Format gambar tidak didukung (masukkan jpg, jpeg, png, webp).";
        } elseif ($_FILES['business-photo']['size'] > 2 * 1024 * 1024) {
            $gambar_error = "Ukuran gambar maksimal 2MB.";
        } else {
            $gambar = $_POST['gambar_lama'] ?? null;
            $folder = "../user_img/foto_usaha/$id_user/";
            if (!is_dir($folder)) mkdir($folder, 0755, true);

            $file_name = 'usaha_' . time() . '.' . $ext;
            $full_path = $folder . $file_name;

            if (move_uploaded_file($_FILES['business-photo']['tmp_name'], $full_path)) {
                $gambar = "$id_user/$file_name";
                echo "DEBUG: Gambar baru berhasil diupload => $gambar<br>";
            } else {
                $gambar_error = "Gagal mengunggah gambar.";
            }
        }
    } else {
        // Jika tidak upload gambar baru, ambil dari hidden input gambar lama
        $gambar = $_POST['gambar_lama'] ?? null;
        
        echo "DEBUG: gambar lama dari hidden input => " . ($_POST['gambar_lama'] ?? 'NULL') . "<br>";

        // Kalau tambah baru tapi tidak upload dan tidak ada gambar lama
        if (!$is_edit && !$gambar) {
            $gambar_error = "Foto usaha wajib diunggah.";
        }
    }


    echo "DEBUG: gambar = $gambar<br>";
    echo "DEBUG: gambar_error = $gambar_error<br>";
    echo "DEBUG: error_message = $error_message<br>";

    // Jika tidak ada error, simpan ke database
    if (empty($gambar_error) && empty($error_message)) {
        echo "DEBUG: masuk ke if eksekusi SQL<br>";
        if ($is_edit) {
            echo "DEBUG: mode edit<br>";
            echo "DEBUG: is_edit = " . ($is_edit ? 'true' : 'false') . "<br>";
            $stmt = $conn->prepare("UPDATE profil_usaha SET gambar=?, nama_usaha=?, nama_pemilik=?, kategori_usaha=?, alamat=?, kecamatan=?, kelurahan=?, deskripsi=?, link_whatsapp=? WHERE id_user=?");
            $stmt->bind_param("sssssssssi", $gambar, $nama_usaha, $nama_pemilik, $kategori_usaha, $alamat, $kecamatan, $kelurahan, $deskripsi, $link_whatsapp, $id_user);
        } else {
            echo "DEBUG: mode tambah baru<br>";
            $stmt = $conn->prepare("INSERT INTO profil_usaha (id_user, gambar, nama_usaha, nama_pemilik, kategori_usaha, alamat, kecamatan, kelurahan, deskripsi, link_whatsapp) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("isssssssss", $id_user, $gambar, $nama_usaha, $nama_pemilik, $kategori_usaha, $alamat, $kecamatan, $kelurahan, $deskripsi, $link_whatsapp);
        }

        // HAPUS 2 KODE INI KALAU BERHASIL
        // var_dump($_POST['gambar_lama']);  // Harus tampil string "nama_file.jpg"
        // var_dump($gambar);                // Harus sama persis dengan di atas
        // echo "DEBUG: gambar = " . $gambar; exit;

        if ($stmt->execute()) {
            echo "DEBUG: berhasil simpan<br>";
            $_SESSION['form_submitted'] = true;

            $id_user = $_SESSION['id_user'];
            $query = $conn->query("SELECT * FROM profil_usaha WHERE id_user = $id_user");
            $data_lama = $query->fetch_assoc();

            header("Location: profil_usaha.php");
            exit;
        } else {
            // echo "DEBUG: gagal simpan: " . $stmt->error;
            $error_message = "Terjadi kesalahan saat menyimpan data: " . $stmt->error;
            echo "<p style='color:red;'>$error_message</p>"; // TAMPILKAN ERROR
        }
    } else {
        echo "DEBUG: Tidak masuk SQL karena error input<br>";
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
    <style>
        .alert-box {
            background-color: #fdecea;
            color: #b71c1c;
            padding: 12px 20px;
            margin-bottom: 20px;
            border: 1px solid #f5c6cb;
            border-radius: 6px;
            position: relative;
            font-size: 14px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        }

        .alert-box .close-btn {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: #b71c1c;
            font-weight: bold;
            font-size: 18px;
            cursor: pointer;
        }
    </style>
    <div class="header">
        <h1 class="brand-title" style="color: #205781;">GoUMKM</h1>
    </div>

    <div class="form-container">
        <h2 class="form-title">Formulir Profil Usaha</h2>
        
        <?php if (!empty($gambar_error)): ?>
            <div class="alert-box">
                <?= htmlspecialchars($gambar_error) ?>
                <span class="close-btn" onclick="this.parentElement.style.display='none';">&times;</span>
            </div>
        <?php endif; ?>
        
        <!-- FORM INPUT PROFIL -->
        <form id="business-profile-form" method="POST" enctype="multipart/form-data">
            <div class="photo-upload-section">
                <div class="photo-upload" id="photo-upload">
                    <?php if ($is_edit && isset($data_lama['gambar'])): ?>
                        <img src="../user_img/foto_usaha/<?= htmlspecialchars($data_lama['gambar']) ?>?v=<?= time() ?>" alt="Foto Usaha Lama" class="photo-preview-old" id="photo-preview-old">
                        <input type="hidden" name="gambar_lama" id="gambar_lama_hidden" value="<?= htmlspecialchars($data_lama['gambar']) ?>">
                    <?php endif; ?>
                    
                    <input type="file" id="business-photo" name="business-photo" accept="image/*">
                    <i class="fas fa-camera" id="camera-icon"></i>

                    <!-- Ini untuk JS preview gambar baru -->
                    <img class="photo-preview" id="photo-preview" alt="Preview Gambar Baru">
                    <div class="tooltip">Upload foto usaha</div>
                </div>
            
                <div class="input-group">
                    <label class="form-label" for="business-name">Nama Usaha</label>
                    <input type="text" class="form-input" id="business-name" name="business-name" placeholder="Masukkan nama usaha" value="<?= htmlspecialchars($nama_usaha) ?>" required>
                    <div class="validation-message" id="business-name-error">Nama usaha harus diisi</div>
                </div>
            </div>

            <div class="input-row">
                <div class="input-group">
                    <label class="form-label" for="owner-name">Nama Pemilik Usaha</label>
                    <input type="text" class="form-input" id="owner-name" name="owner-name" placeholder="Masukkan nama pemilik" value="<?= htmlspecialchars($nama_pemilik) ?>" required>
                    <div class="validation-message" id="owner-name-error">Nama pemilik harus diisi</div>
                </div>
                
                <div class="input-group">
                    <label class="form-label" for="business-category">Kategori Usaha</label>
                    <select class="form-select" id="business-category" name="business-category" value="<?= htmlspecialchars($data_lama['kategori_usaha'] ?? '') ?>" required>
                        <option value="">Pilih kategori</option>
                        <option value="Perdagangan" <?= $kategori_usaha === 'Perdagangan' ? 'selected' : '' ?>>Perdagangan</option>
                        <option value="Jasa" <?= $kategori_usaha === 'Jasa' ? 'selected' : '' ?>>Jasa</option>
                    </select>
                    <div class="validation-message" id="business-category-error">Kategori usaha harus dipilih</div>
                </div>
            </div>

            <div class="input-group full-width">
                <label class="form-label" for="business-address">Alamat Usaha</label>
                <textarea class="form-textarea small" id="business-address" name="business-address" required><?= htmlspecialchars($alamat) ?></textarea>
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
                        <option value="bacukiki" <?= $kecamatan === 'bacukiki' ? 'selected' : '' ?>>Bacukiki</option>
                        <option value="bacukiki-barat" <?= $kecamatan === 'bacukiki-barat' ? 'selected' : '' ?>>Bacukiki Barat</option>
                        <option value="soreang" <?= $kecamatan === 'soreang' ? 'selected' : '' ?>>Soreang</option>
                        <option value="ujung" <?= $kecamatan === 'ujung' ? 'selected' : '' ?>>Ujung</option>
                    </select>
                    <div class="validation-message" id="kecamatan-error">Kecamatan harus dipilih</div>
                </div>
                
                <div class="input-group">
                    <label class="form-label" for="kelurahan">Kelurahan</label>
                    <select class="form-select" id="kelurahan" name="kelurahan" required>
                        <option value="">Pilih Kelurahan</option>
                        <option value="<?= $kelurahan ?>" selected><?= $kelurahan ?></option> <!-- opsional: bisa dinamis -->
                    </select>
                    <div class="validation-message" id="kelurahan-error">Kelurahan harus dipilih</div>
                </div>
            </div>

            <div class="input-group full-width">
                <label class="form-label" for="business-description">Deskripsi Usaha</label>
                <textarea class="form-textarea" id="business-description" name="business-description" required><?= htmlspecialchars($deskripsi) ?></textarea>
                <div class="char-counter">
                    <span id="description-counter">max 1000 character</span>
                </div>
                <div class="validation-message" id="business-description-error">Deskripsi usaha harus diisi</div>
            </div>

            <div class="input-group full-width">
                <label class="form-label" for="whatsapp-link">Link WhatsApp</label>
                <input type="url" class="form-input" id="whatsapp-link" name="whatsapp-link" placeholder="https://wa.me/6281xxx" value="<?= htmlspecialchars($link_whatsapp) ?>" required>
                <div class="validation-message" id="whatsapp-link-error">Format link WhatsApp tidak valid</div>
            </div>

            <button type="submit" class="submit-btn" id="submit-btn">
                <span id="btn-text">Simpan</span>
            </button>
            <?php if ($is_edit): ?>
                <a href="profil_usaha.php" class="batal-btn" >Batal</a>
            <?php endif; ?>
        </form>

        <div class="success-message message" id="success-message">
            <i class="fas fa-check-circle"></i>
            <span>Profil usaha berhasil disimpan!</span>
        </div>
        
        <?php if (!empty($error_message)): ?>
        <div class="error-message message">
            <i class="fas fa-exclamation-circle"></i>
            <span><?= htmlspecialchars($error_message) ?></span>
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
                    if (file.size > 2 * 1024 * 1024) { // 5MB limit
                        showError('Ukuran foto maksimal 2MB');
                        return;
                    }

                    const reader = new FileReader();
                    reader.onload = function(e) {
                        $('#photo-preview').attr('src', e.target.result).show();
                        $('#photo-preview-old').hide(); // SEMBUNYIKAN gambar lama
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
                        kelurahanSelect.append(`<option value="${kelurahan}">${kelurahan}</option>`);
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

                // DEBUG
                const gambarLama = document.getElementById('gambar_lama_hidden');
                console.log("DEBUG: gambar_lama yang terkirim =", gambarLama ? gambarLama.value : 'TIDAK ADA');
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

            // Trigger pengisian kelurahan sesuai kecamatan saat edit (jika sudah ada value lama)
            if ($('#kecamatan').val()) {
                $('#kecamatan').trigger('change');

                // Tunggu sedikit agar kelurahan terisi dulu, lalu pilih value lama jika ada
                setTimeout(() => {
                    const kelurahanLama = '<?= isset($kelurahan) ? addslashes($kelurahan) : '' ?>';
                    if (kelurahanLama) {
                        $('#kelurahan').val(kelurahanLama);
                    }
                }, 50);
            }
        });
    </script>
</body>
</html>