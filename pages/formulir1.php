<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GoUMKM - Formulir Profil Usaha</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
</head>
<style>
      * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        background: #f9fafb;
        min-height: 100vh;
        padding: 20px;
        display: flex;
        flex-direction: column;
        align-items: center;
    }

    .header {
        text-align: center;
        margin-bottom: 30px;
    }

    .brand-title {
        font-size: 2.5rem;
        font-weight: bold;
        color: #333;
        letter-spacing: -1px;
        margin-bottom: 10px;
    }

    .form-container {
        background: white;
        border-radius: 16px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
        padding: 40px;
        max-width: 600px;
        width: 100%;
        border: 1px solid #e5e7eb;
    }

    .form-title {
        font-size: 1.4rem;
        font-weight: 600;
        text-align: center;
        margin-bottom: 30px;
        color: #333;
    }

    .photo-upload-section {
        display: flex;
        align-items: center;
        margin-bottom: 30px;
        gap: 20px;
    }

    .photo-upload {
        width: 80px;
        height: 80px;
        border-radius: 12px;
        background: #d1d5db;
        border: none;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }

    .photo-upload:hover {
        background: #c3c7cc;
    }

    .photo-upload.has-image {
        border: 2px solid #4285f4;
    }

    .photo-upload input[type="file"] {
        position: absolute;
        width: 100%;
        height: 100%;
        opacity: 0;
        cursor: pointer;
    }

    .photo-upload i {
        font-size: 1.8rem;
        color: #6b7280;
    }

    .photo-preview {
        width: 100%;
        height: 100%;
        object-fit: cover;
        border-radius: 12px;
        display: none;
    }

    .input-row {
        display: flex;
        gap: 20px;
        margin-bottom: 25px;
    }

    .input-group {
        flex: 1;
        position: relative;
    }

    .input-group.full-width {
        flex: 100%;
    }

    .form-label {
        display: block;
        margin-bottom: 8px;
        font-weight: 500;
        color: #333;
        font-size: 0.95rem;
    }

    .form-input, .form-select, .form-textarea {
        width: 100%;
        padding: 12px 16px;
        border: 1px solid #d1d5db;
        border-radius: 6px;
        font-size: 0.95rem;
        transition: all 0.3s ease;
        background: white;
        font-family: inherit;
    }

    .form-input:focus, .form-select:focus, .form-textarea:focus {
        outline: none;
        border-color: #3b82f6;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    }

    .form-textarea {
        resize: vertical;
        min-height: 80px; /* Diperkecil dari 100px */
        max-height: 120px; /* Dibatasi tinggi maksimal */
    }

    .form-textarea.small {
        min-height: 60px; /* Untuk alamat yang lebih kecil */
        max-height: 100px;
    }

    .form-select {
        appearance: none;
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='m6 8 4 4 4-4'/%3e%3c/svg%3e");
        background-position: right 12px center;
        background-repeat: no-repeat;
        background-size: 16px;
        cursor: pointer;
    }

    .submit-btn {
        padding: 14px 30px;
        background: #2563eb;
        color: white;
        border: none;
        border-radius: 8px;
        font-size: 1rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        margin: 20px 10px 0 0;
    }

    .submit-btn:hover {
        background: #1d4ed8;
    }

    .submit-btn:disabled {
        background: #ccc;
        cursor: not-allowed;
        transform: none;
        box-shadow: none;
    }

    .batal-btn {
        padding: 14px 30px;
        background: #6b7280;
        color: white;
        border: none;
        border-radius: 8px;
        font-size: 1rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        margin: 20px 0 0 10px;
    }

    .batal-btn:hover {
        background: #4b5563;
    }

    .button-row {
        display: flex;
        justify-content: center;
        gap: 20px;
        margin-top: 30px;
    }

    /* Success and Error Messages */
    .message {
        padding: 12px 16px;
        border-radius: 8px;
        margin-bottom: 20px;
        display: none;
        align-items: center;
        gap: 10px;
    }

    .success-message {
        background: #d4edda;
        color: #155724;
        border: 1px solid #c3e6cb;
    }

    .error-message {
        background: #f8d7da;
        color: #721c24;
        border: 1px solid #f5c6cb;
    }

    /* Loading State */
    .loading .submit-btn {
        background: #999;
        cursor: not-allowed;
    }

    .loading .form-input, 
    .loading .form-select, 
    .loading .form-textarea {
        pointer-events: none;
        opacity: 0.7;
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .form-container {
            padding: 25px;
            margin: 10px;
        }

        .brand-title {
            font-size: 2rem;
        }

        .input-row {
            flex-direction: column;
            gap: 0;
        }

        .photo-upload-section {
            flex-direction: column;
            text-align: center;
        }

        .button-row {
            flex-direction: column;
            gap: 10px;
        }

        .submit-btn, .batal-btn {
            width: 100%;
            margin: 5px 0;
        }
    }

    /* Animation */
    .form-container {
        animation: fadeInUp 0.6s ease-out;
    }

    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* Input validation styles */
    .form-input.valid {
        border-color: #10b981;
    }

    .form-input.invalid {
        border-color: #ef4444;
    }

    .validation-message {
        font-size: 0.8rem;
        margin-top: 5px;
        color: #ea4335;
        display: none;
    }

    /* Tooltip */
    .tooltip {
        position: absolute;
        top: -35px;
        left: 50%;
        transform: translateX(-50%);
        background: #333;
        color: white;
        padding: 5px 10px;
        border-radius: 4px;
        font-size: 0.8rem;
        white-space: nowrap;
        opacity: 0;
        pointer-events: none;
        transition: opacity 0.3s;
    }

    .tooltip::after {
        content: '';
        position: absolute;
        top: 100%;
        left: 50%;
        transform: translateX(-50%);
        border: 5px solid transparent;
        border-top-color: #333;
    }

    .photo-upload:hover .tooltip {
        opacity: 1;
    }

    /* Character counter styling */
    .char-counter {
        font-size: 0.75rem;
        color: #6b7280;
        text-align: right;
        margin-top: 5px;
    }

</style>
<body>
    <div class="header">
        <h1 class="brand-title">GoUMKM</h1>
    </div>

    <div class="form-container">
        <h2 class="form-title">Formulir Profil Usaha</h2>
        
        <div class="success-message message" id="success-message">
            <i class="fas fa-check-circle"></i>
            <span>Profil usaha berhasil disimpan!</span>
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
                        <option value="">Pilih Kategori</option>
                        <option value="jasa">Jasa</option>
                        <option value="perdagangan">Perdagangan</option>
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
                <input type="url" class="form-input" id="whatsapp-link" name="whatsapp-link" placeholder="https://wa.me/628123456789">
                <div class="validation-message" id="whatsapp-link-error">Format link WhatsApp tidak valid</div>
            </div>

            <div class="button-row">
                <button type="button" class="batal-btn" id="batal-btn">Batal</button>
                <button type="submit" class="submit-btn" id="submit-btn">
                    <span id="btn-text">Simpan</span>
                    <i class="fas fa-spinner fa-spin" id="loading-icon" style="display: none; margin-left: 8px;"></i>
                </button>
            </div>
        </form>
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
                validateField('business-name', val => val.length >= 2, 'Nama usaha minimal 2 karakter');
            });

            $('#owner-name').on('blur', function() {
                validateField('owner-name', val => val.length >= 2, 'Nama pemilik minimal 2 karakter');
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
                validateField('business-description', val => val.length >= 10, 'Deskripsi minimal 10 karakter');
            });

            $('#whatsapp-link').on('blur', function() {
                const val = $(this).val().trim();
                if (val === '') return true; // Optional field
                
                validateField('whatsapp-link', val => {
                    return val === '' || /^https:\/\/wa\.me\/\d+/.test(val);
                }, 'Format: https://wa.me/628123456789');
            });

            // Batal button handler
            $('#batal-btn').on('click', function() {
                if (confirm('Apakah Anda yakin ingin membatalkan? Semua data yang telah diisi akan hilang.')) {
                    resetForm();
                }
            });

            // Form submission
            $('#business-profile-form').on('submit', function(e) {
                e.preventDefault();
                
                // Hide previous messages
                $('.message').hide();
                
                // Validate all fields
                const isBusinessNameValid = validateField('business-name', val => val.length >= 4, 'Nama usaha minimal 4 karakter');
                const isOwnerNameValid = validateField('owner-name', val => val.length >= 3, 'Nama pemilik minimal 3 karakter');
                const isCategoryValid = validateField('business-category', val => val !== '', 'Kategori usaha harus dipilih');
                const isAddressValid = validateField('business-address', val => val.length >= 10, 'Alamat usaha minimal 10 karakter');
                const isKecamatanValid = validateField('kecamatan', val => val !== '', 'Kecamatan harus dipilih');
                const isKelurahanValid = validateField('kelurahan', val => val !== '', 'Kelurahan harus dipilih');
                const isDescriptionValid = validateField('business-description', val => val.length >= 15, 'Deskripsi minimal 15 karakter');
                
                const whatsappVal = $('#whatsapp-link').val().trim();
                const isWhatsappValid = whatsappVal === '' || /^https:\/\/wa\.me\/\d+/.test(whatsappVal);
                
                if (!isWhatsappValid) {
                    validateField('whatsapp-link', () => false, 'Format: https://wa.me/628123456789');
                }

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