<!DOCTYPE html>
<html lang="id">
<head>
    <!-- <meta charset="UTF-8"> -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin GoUMKM</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <link rel="stylesheet" href="../css/admin.css">
</head>
<body>
    <div class="main-container">
        <div class="header">
            <h1>ADMIN GoUMKM</h1>
        </div>
        
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Usaha</th>
                        <th>Pemilik (Email)</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody id="tableBody">
                    <!-- Data akan diisi oleh JavaScript -->
                </tbody>
            </table>
        </div>
        
        <div class="pagination">
            <div class="pagination-left">
                <span class="arrow">◀</span>
                <span>Previous</span>
            </div>
            
            <div class="pagination-center">
                <a href="#" class="page-number active" data-page="1">01</a>
                <a href="#" class="page-number" data-page="2">02</a>
                <a href="#" class="page-number" data-page="3">03</a>
                <a href="#" class="page-number" data-page="4">04</a>
                <a href="#" class="page-number" data-page="5">05</a>
            </div>
            
            <div class="pagination-right">
                <span>Next</span>
                <span class="arrow">▶</span>
            </div>
        </div>
    </div>

    <!-- Modal Confirmation -->
    <div class="modal-overlay" id="modalOverlay">
        <div class="modal">
            <span class="close-btn" id="closeBtn">&times;</span>
            <h3 id="modalText">Anda yakin ingin menonaktifkan usaha ini?</h3>
            <div class="modal-buttons">
                <button class="btn btn-yes" id="confirmBtn">Ya</button>
                <button class="btn btn-no" id="cancelBtn">Tidak</button>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            // Data untuk semua halaman
            var allData = [];
            var currentPage = 1;
            var itemsPerPage = 10;
            
            // Generate data untuk 50 item (5 halaman)
            for (let i = 1; i <= 50; i++) {
                allData.push({
                    no: i,
                    namaUsaha: 'Nama Usaha',
                    email: 'Nama@contoh.com',
                    status: i % 2 === 0 ? 'Nonaktif' : 'Aktif'
                });
            }
            
            // Function untuk render data berdasarkan halaman
            function renderTable(page) {
                var startIndex = (page - 1) * itemsPerPage;
                var endIndex = startIndex + itemsPerPage;
                var pageData = allData.slice(startIndex, endIndex);
                
                var html = '';
                pageData.forEach(function(item) {
                    var statusClass = item.status === 'Aktif' ? 'status-aktif' : 'status-nonaktif';
                    var actionText = item.status === 'Aktif' ? 'Nonaktifkan' : 'Aktifkan';
                    
                    html += `
                        <tr>
                            <td>${item.no}</td>
                            <td>${item.namaUsaha}</td>
                            <td>${item.email}</td>
                            <td class="status-cell">${item.status}</td>
                            <td class="action-cell" data-original-status="${item.status}">
                                <div class="action-content">
                                    <span class="status-indicator ${statusClass}"></span>
                                    <span>${actionText}</span>
                                </div>
                            </td>
                        </tr>
                    `;
                });
                
                $('#tableBody').html(html);
            }
            
            // Initial render
            renderTable(currentPage);
            
            // Handle pagination clicks
            $('.page-number').click(function(e) {
                e.preventDefault();
                
                // Remove active class from all page numbers
                $('.page-number').removeClass('active');
                
                // Add active class to clicked page number
                $(this).addClass('active');
                
                // Get page number and render table
                currentPage = parseInt($(this).data('page'));
                renderTable(currentPage);
            });
            
            // Handle previous button
            $('.pagination-left').click(function() {
                if (currentPage > 1) {
                    currentPage--;
                    $('.page-number').removeClass('active');
                    $(`.page-number[data-page="${currentPage}"]`).addClass('active');
                    renderTable(currentPage);
                }
            });
            
            // Handle next button
            $('.pagination-right').click(function() {
                if (currentPage < 5) {
                    currentPage++;
                    $('.page-number').removeClass('active');
                    $(`.page-number[data-page="${currentPage}"]`).addClass('active');
                    renderTable(currentPage);
                }
            });
            
            // Modal functionality
            var currentActionCell;
            
            // Handle action buttons (Aktifkan/Nonaktifkan)
            $(document).on('click', '.action-cell', function() {
                currentActionCell = $(this);
                var row = $(this).closest('tr');
                var statusCell = row.find('.status-cell');
                var currentStatus = statusCell.text();
                
                var modalText = currentStatus === 'Aktif' ? 
                    'Anda yakin ingin menonaktifkan usaha ini?' : 
                    'Anda yakin ingin mengaktifkan usaha ini?';
                
                $('#modalText').text(modalText);
                $('#modalOverlay').fadeIn(200);
            });
            
            // Handle modal confirmation
            $('#confirmBtn').click(function() {
                var row = currentActionCell.closest('tr');
                var statusCell = row.find('.status-cell');
                
                if (statusCell.text() === 'Aktif') {
                    statusCell.text('Nonaktif');
                    currentActionCell.html(`
                        <div class="action-content">
                            <span class="status-indicator status-nonaktif"></span>
                            <span>Aktifkan</span>
                        </div>
                    `);
                } else {
                    statusCell.text('Aktif');
                    currentActionCell.html(`
                        <div class="action-content">
                            <span class="status-indicator status-aktif"></span>
                            <span>Nonaktifkan</span>
                        </div>
                    `);
                }
                
                $('#modalOverlay').fadeOut(200);
            });
            
            // Handle modal cancel
            $('#cancelBtn, #closeBtn').click(function() {
                $('#modalOverlay').fadeOut(200);
            });
            
            // Close modal when clicking overlay
            $('#modalOverlay').click(function(e) {
                if (e.target === this) {
                    $(this).fadeOut(200);
                }
            });
        });
    </script>
</body>
</html>