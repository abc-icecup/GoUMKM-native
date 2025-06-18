<?php
require_once 'init.php';

$is_logged_in = isset($_SESSION['id_user']);
$data_profil = $is_logged_in ? getUserIfExists($conn) : null;

if ($is_logged_in && !$data_profil) {
    session_unset();
    session_destroy();
    $is_logged_in = false;
}
?>

<style>
    /* Header Styles */
    .header {
        background-color: white;
        padding: 15px 20px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    
    .header-buttons {
        display: flex;
        gap: 10px;
    }

    .search-container {
        display: flex;
        flex: 1;
        max-width: 500px;
        margin: 0 30px;
        border: 2px solid #ddd;
        border-radius: 4px;
        overflow: hidden;
    }

    .search-input {
        flex: 1;
        padding: 8px 15px;
        border: none;
        outline: none;
        font-size: 14px;
    }

    .search-btn {
        background-color: #205781;
        color: white;
        border: none;
        padding: 8px 20px;
        cursor: pointer;
        font-size: 14px;
    }

    .search-btn:hover {
        background-color: #205781;
    }

    .nav-btn {
        background-color: white;
        border: 1px solid #205781;
        color: #205781;
        padding: 7px 14px;
        border-radius: 4px;
        cursor: pointer;
        font-size: 13px;
        font-weight: 500;
    }

    .nav-btn:hover {
        background-color: #e7f1ff;
    }

    .profile-icon {
        width: 40px;
        height: 40px;
        background-color: #f0f0f0;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        overflow: hidden;
    }

    .profile-icon:hover {
        background-color: #e0e0e0;
    }

    @media (max-width: 768px) {
        .header {
            flex-direction: column;
            gap: 15px;
        }
        .search-container {
            margin: 0;
            max-width: 100%;
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

<header class="header">
    <div class="nav-left" >
        <a href="../pages/beranda.php">
            <img src="../assets/img/image (2).png" alt="logo umkm" class="logo-umkm" >
        </a>     
    </div>
    
    <a href="pencarian.php" class="search-container" style="text-decoration: none;">
        <input type="text" class="search-input" placeholder="nama produk" id="searchInput">
        <button class="search-btn" id="searchBtn">Search</button>
    </a>
    
    <?php if ($is_logged_in): ?>
        <a href="profil_usaha.php" class="profile-icon" id="profileToggle">
            <?php if (!empty($data_profil['gambar'])): ?>
                <img src="../user_img/foto_usaha/<?= htmlspecialchars($data_profil['gambar']) ?>" alt="Akun" class="user-avatar">
            <?php else: ?>
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none">
                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2" stroke="currentColor" stroke-width="2"/>
                    <circle cx="12" cy="7" r="4" stroke="currentColor" stroke-width="2"/>
                </svg>
            <?php endif; ?>
        </a>
    <?php else: ?>
        <div class="nav-buttons">
            <a href="masuk.php" class="nav-btn" style="text-decoration: none;">Masuk</a>
            <a href="daftar.php" class="nav-btn" style="text-decoration: none;">Daftar</a>
        </div>
    <?php endif; ?>

    
</header>