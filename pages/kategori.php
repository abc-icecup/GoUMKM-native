<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GoUMKM</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <!-- <link rel="stylesheet" href="../css/kategori.css"> -->
</head>
<body>
    <style>
         * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
        }

        /* Header Styles */
        .header {
            background-color: white;
            padding: 15px 20px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .logo {
            display: flex;
            align-items: center;
            color: #2c5aa0;
            font-weight: bold;
            font-size: 18px;
        }

        .logo-icon {
            width: 16px;
            height: 16px;
            background-color: #2c5aa0;
            margin-right: 8px;
            border-radius: 3px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 10px;
        }

        .search-container {
            display: flex;
            flex: 1;
            max-width: 500px;
            margin: 0 30px;
        }

        .dropdown-container {
            position: relative;
            display: inline-block;
        }

        .product_category {
            padding: 8px 12px;
            border: 1px solid #ddd;
            border-radius: 4px 0 0 4px;
            outline: none;
            font-size: 14px;
            background-color: white;
            min-width: 120px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .product_category:after {
            content: '▼';
            font-size: 10px;
            color: #666;
            margin-left: 8px;
        }

        .dropdown-menu {
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            background-color: white;
            border: 1px solid #ddd;
            border-top: none;
            border-radius: 0 0 4px 4px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            z-index: 1000;
            display: none;
            max-height: 200px;
            overflow-y: auto;
        }

        .dropdown-item {
            padding: 10px 12px;
            cursor: pointer;
            font-size: 14px;
            color: #333;
            border-bottom: 1px solid #f0f0f0;
        }

        .dropdown-item:hover {
            background-color: #f8f9fa;
        }

        .dropdown-item:last-child {
            border-bottom: none;
        }

        .search-input {
            flex: 1;
            padding: 8px 15px;
            border: 1px solid #ddd;
            border-left: none;
            outline: none;
            font-size: 14px;
        }

        .search-btn {
            background-color: #4a90e2;
            color: white;
            border: none;
            padding: 8px 20px;
            border-radius: 0 4px 4px 0;
            cursor: pointer;
            font-size: 14px;
        }

        .search-btn:hover {
            background-color: #357abd;
        }

        .header-buttons {
            display: flex;
            gap: 10px;
        }

        .btn {
            padding: 8px 16px;
            border: 1px solid #ddd;
            border-radius: 4px;
            background-color: white;
            cursor: pointer;
            font-size: 14px;
            text-decoration: none;
            color: #333;
        }

        .btn:hover {
            background-color: #f0f0f0;
        }

        .btn-primary {
            background-color: #4a90e2;
            color: white;
            border-color: #4a90e2;
        }

        .btn-primary:hover {
            background-color: #357abd;
        }

        /* Categories Section */
        .categories {
            background-color: white;
            padding: 20px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
            gap: 10px;
        }

        .product-category {
            padding: 10px 20px;
            border: 2px solid #4a90e2;
            border-radius: 25px;
            background-color: white;
            color: #4a90e2;
            cursor: pointer;
            font-size: 14px;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .product-category:hover {
            background-color: #4a90e2;
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(74, 144, 226, 0.3);
        }

        .product-category.active {
            background-color: #4a90e2;
            color: white;
        }

        /* Main Content */
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 30px 20px;
        }

        .products-grid {
            display: grid;
            grid-template-columns: repeat(6, 1fr);
            gap: 15px;
            margin-top: 20px;
        }

        .product-card {
            background-color: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 4px 16px rgba(0,0,0,0.15);
        }

        .product-image {
            width: 100%;
            height: 80px;
            background-color: #e0e0e0;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #999;
            font-size: 18px;
        }

        .product-info {
            padding: 10px;
            text-align: center;
        }

        .product-title {
            font-weight: bold;
            font-size: 12px;
            color: #333;
            margin-bottom: 3px;
        }

        .product-subtitle {
            font-size: 10px;
            color: #666;
            margin-bottom: 2px;
        }

        .product-price {
            font-size: 10px;
            color: #888;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .header {
                flex-direction: column;
                gap: 15px;
            }

            .search-container {
                margin: 0;
                max-width: 100%;
            }

            .categories {
                padding: 15px;
            }

            .product-category {
                padding: 8px 15px;
                font-size: 12px;
            }

            .products-grid {
                grid-template-columns: repeat(3, 1fr);
                gap: 10px;
            }
        }
        
        .logo-umkm {
        /* max-height: 90px; atau sesuaikan */
        height: 30px;
        /* width: auto; */
        object-fit: contain;
        background: none;
        display: block;
        }
    </style>
    <!-- Header -->
    <header class="header">
        <div class="nav-left" >
            <a href="../pages/beranda.php">
                <img src="../assets/img/image (2).png" alt="logo umkm" class="logo-umkm" >
            </a>     
        </div>
        
        <div class="search-container">
            <div class="dropdown-container">
                <div class="product_category" id="categorySelect">
                    <span id="categoryText">Kategori</span>
                </div>
                <div class="dropdown-menu" id="dropdownMenu">
                    <div class="dropdown-item" data-value="">Semua Kategori</div>
                    <div class="dropdown-item" data-value="makanan-minuman">Makanan dan Minuman</div>
                    <div class="dropdown-item" data-value="fashion">Fashion</div>
                    <div class="dropdown-item" data-value="kerajinan">Kerajinan</div>
                    <div class="dropdown-item" data-value="kecantikan">Produk Kecantikan</div>
                    <div class="dropdown-item" data-value="pertanian">Pertanian dan Perkebunan</div>
                </div>
            </div>
            <input type="text" class="search-input" placeholder="nama produk" id="searchInput">
            <button class="search-btn" id="searchBtn">Search</button>
        </div>
        
        <div class="header-buttons">
            <a href="#" class="btn">Masuk</a>
            <a href="#" class="btn btn-primary">Daftar</a>
        </div>
    </header>

    <!-- Categories Section -->
    <section class="categories">
        <button class="product-category" data-category="">Semua</button>
        <button class="product-category" data-category="makanan-minuman">Makanan dan Minuman</button>
        <button class="product-category" data-category="fashion">Fashion</button>
        <button class="product-category" data-category="kerajinan">Kerajinan</button>
        <button class="product-category" data-category="kecantikan">Produk Kecantikan</button>
        <button class="product-category" data-category="pertanian">Pertanian dan Perkebunan</button>
    </section>

    <!-- Main Content -->
    <div class="container">
        <div class="products-grid" id="productsGrid">
            <!-- Products will be generated by JavaScript -->
        </div>
    </div>

    <script>
        $(document).ready(function() {
            // Sample product data dengan kategori yang sesuai
            const products = [
                { title: "Nasi Gudeg", subtitle: "Warung Bu Sari", price: "Rp. 15.000", category: "makanan-minuman" },
                { title: "Es Teh Manis", subtitle: "Depot Segar", price: "Rp. 5.000", category: "makanan-minuman" },
                { title: "Kemeja Batik", subtitle: "Batik Nusantara", price: "Rp. 150.000", category: "fashion" },
                { title: "Sepatu Sneakers", subtitle: "Toko Sepatu Jaya", price: "Rp. 200.000", category: "fashion" },
                { title: "Tas Anyaman", subtitle: "Kerajinan Desa", price: "Rp. 75.000", category: "kerajinan" },
                { title: "Patung Kayu", subtitle: "Art & Craft", price: "Rp. 100.000", category: "kerajinan" },
                { title: "Serum Wajah", subtitle: "Beauty Shop", price: "Rp. 85.000", category: "kecantikan" },
                { title: "Lipstik Natural", subtitle: "Kosmetik Alami", price: "Rp. 45.000", category: "kecantikan" },
                { title: "Beras Organik", subtitle: "Tani Sejahtera", price: "Rp. 25.000", category: "pertanian" },
                { title: "Kopi Arabika", subtitle: "Kebun Kopi", price: "Rp. 50.000", category: "pertanian" },
                { title: "Soto Ayam", subtitle: "Warung Pak Mahmud", price: "Rp. 18.000", category: "makanan-minuman" },
                { title: "Jus Jeruk", subtitle: "Fresh Juice", price: "Rp. 12.000", category: "makanan-minuman" },
                { title: "Dress Casual", subtitle: "Fashion Store", price: "Rp. 125.000", category: "fashion" },
                { title: "Topi Rajut", subtitle: "Handmade Shop", price: "Rp. 35.000", category: "fashion" },
                { title: "Gelang Manik", subtitle: "Craft Corner", price: "Rp. 25.000", category: "kerajinan" },
                { title: "Dompet Kulit", subtitle: "Leather Works", price: "Rp. 65.000", category: "kerajinan" },
                { title: "Sabun Herbal", subtitle: "Natural Care", price: "Rp. 15.000", category: "kecantikan" },
                { title: "Masker Wajah", subtitle: "Skincare Plus", price: "Rp. 30.000", category: "kecantikan" }
            ];

            let currentCategory = '';
            let currentCategoryText = 'Kategori';

            // Custom dropdown functionality
            $('#categorySelect').click(function(e) {
                e.stopPropagation();
                $('#dropdownMenu').toggle();
            });

            // Dropdown item click
            $('.dropdown-item').click(function() {
                const value = $(this).data('value');
                const text = $(this).text();
                
                currentCategory = value;
                currentCategoryText = text;
                
                $('#categoryText').text(text);
                $('#dropdownMenu').hide();
                
                // Update category buttons to match
                $('.categories .product-category').removeClass('active');
                $('.categories .product-category[data-category="' + currentCategory + '"]').addClass('active');
                
                filterProducts();
            });

            // Close dropdown when clicking outside
            $(document).click(function() {
                $('#dropdownMenu').hide();
            });

            // Search and filter functionality
            function filterProducts() {
                const searchTerm = $('#searchInput').val().toLowerCase();
                const selectedCategory = currentCategory;
                
                let filteredProducts = products;
                
                // Filter by category
                if (selectedCategory) {
                    filteredProducts = filteredProducts.filter(product => 
                        product.category === selectedCategory
                    );
                }
                
                // Filter by search term
                if (searchTerm) {
                    filteredProducts = filteredProducts.filter(product => 
                        product.title.toLowerCase().includes(searchTerm) || 
                        product.subtitle.toLowerCase().includes(searchTerm)
                    );
                }
                
                generateProducts(filteredProducts);
            }

            // Generate product cards
            function generateProducts(filteredProducts = products) {
                const grid = $('#productsGrid');
                grid.empty();
                
                filteredProducts.forEach(function(product, index) {
                    const productCard = `
                        <div class="product-card" data-index="${index}">
                            <div class="product-image">
                                📷
                            </div>
                            <div class="product-info">
                                <div class="product-title">${product.title}</div>
                                <div class="product-subtitle">${product.subtitle}</div>
                                <div class="product-price">${product.price}</div>
                            </div>
                        </div>
                    `;
                    grid.append(productCard);
                });
            }

            // Category button click (dari section categories)
            $('.categories .product-category').click(function() {
                // Remove active class from all buttons
                $('.categories .product-category').removeClass('active');
                
                // Add active class to clicked button
                $(this).addClass('active');
                
                // Get category from data attribute
                currentCategory = $(this).data('category');
                
                // Update dropdown text to match
                const categoryMap = {
                    '': 'Kategori',
                    'makanan-minuman': 'Makanan dan Minuman',
                    'fashion': 'Fashion',
                    'kerajinan': 'Kerajinan',
                    'kecantikan': 'Produk Kecantikan',
                    'pertanian': 'Pertanian dan Perkebunan'
                };
                
                currentCategoryText = categoryMap[currentCategory];
                $('#categoryText').text(currentCategoryText);
                
                // Filter products
                filterProducts();
            });

            // Search button click
            $('#searchBtn').click(function() {
                filterProducts();
            });

            // Category dropdown change - remove this since we're using custom dropdown
            // $('#categorySelect').change(function() {
            //     currentCategory = $(this).val();
            //     
            //     // Update category buttons to match
            //     $('.categories .product-category').removeClass('active');
            //     $('.categories .product-category[data-category="' + currentCategory + '"]').addClass('active');
            //     
            //     filterProducts();
            // });

            // Search on Enter key
            $('#searchInput').keypress(function(e) {
                if (e.which == 13) {
                    filterProducts();
                }
            });

            // Product card click event
            $(document).on('click', '.product-card', function() {
                const index = $(this).data('index');
                const filteredProducts = products.filter(product => {
                    const searchTerm = $('#searchInput').val().toLowerCase();
                    const selectedCategory = currentCategory || $('#categorySelect').val();
                    
                    let matches = true;
                    
                    if (selectedCategory) {
                        matches = matches && product.category === selectedCategory;
                    }
                    
                    if (searchTerm) {
                        matches = matches && (product.title.toLowerCase().includes(searchTerm) || 
                                           product.subtitle.toLowerCase().includes(searchTerm));
                    }
                    
                    return matches;
                });
                
                alert('Produk diklik: ' + filteredProducts[index].title);
            });

            // Add hover effects with jQuery
            $(document).on('mouseenter', '.product-card', function() {
                $(this).css('cursor', 'pointer');
            });

            // Initialize - set "Semua" as active and show all products
            $('.categories .product-category[data-category=""]').addClass('active');
            generateProducts();
        });
    </script>

    <?php include '../Includes/footer.php'; ?>
</body>
</html>