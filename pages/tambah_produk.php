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

        <form id="business-profile-form" enctype="multipart/form-data">
            <div class="photo-upload-section">
                <div class="photo-upload" id="photo-upload">
                    <input type="file" id="business-photo" accept="image/*">
                    <i class="fas fa-camera" id="camera-icon"></i>
                    <img class="photo-preview" id="photo-preview" alt="Preview">
                    <div class="tooltip">Upload foto produk </div>
                </div>
                
                <div class="input-group">
                    <label class="form-label" for="business-name">Nama Produk </label>
                    <input type="text" class="form-input" id="business-name" name="business-name" placeholder="Masukkan nama Produk" required>
                    <div class="validation-message" id="business-name-error">Nama Produk  harus diisi</div>
                </div>
            </div>

            <div class="input-row">
                <div class="input-group">
                    <label class="form-label" for="owner-name">Harga(Rp.)</label>
                    <input type="text" class="form-input" id="owner-name" name="owner-name" placeholder="Masukkan Harga " required>
                    <div class="validation-message" id="owner-name-error">Nama produk  harus diisi</div>
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
            $('#business-name').on('blur', function() {
                validateField('business-name', val => val.length >= 2, 'Nama Produk minimal 2 karakter');
            });

            $('#owner-name').on('blur', function() {
                validateField('owner-name', val => val.length >= 2, 'Harga(Rp.)');
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
                const isBusinessNameValid = validateField('business-name', val => val.length >= 2, 'Nama Produk minimal 2 karakter');
                const isOwnerNameValid = validateField('owner-name', val => val.length >= 2, 'Harga(Rp.)');
                const isCategoryValid = validateField('business-category', val => val !== '', 'Kategori Produk harus dipilih');
                const isDescriptionValid = validateField('business-description', val => val.length >= 10, 'Deskripsi minimal 10 karakter');
                
                const whatsappVal = $('#whatsapp-link').val().trim();
                const isWhatsappValid = whatsappVal === '' || /^https:\/\/wa\.me\/\d+/.test(whatsappVal);
                
                if (!isWhatsappValid) {
                    validateField('whatsapp-link', () => false, 'Format: https://wa.me/628123456789');
                }

                if (!isBusinessNameValid || !isOwnerNameValid || !isCategoryValid || !isDescriptionValid || !isWhatsappValid) {
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