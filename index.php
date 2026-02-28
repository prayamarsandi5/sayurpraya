<?php
// 1. Hubungkan ke database (Gunakan nama db 'sayur_praya' sesuai gambar Anda)
$conn = mysqli_connect("sql206.infinityfree.com", "if0_41180984", "sayurpraya1990", "if0_41180984_sayur_praya");

// 2. Tangkap parameter 'category' dari URL (hasil klik dari index.php)
$category_filter = isset($_GET['category']) ? mysqli_real_escape_string($conn, $_GET['category']) : '';

// 3. Logika Filter: Jika ada kategori, saring. Jika tidak, ambil semua.
if ($category_filter != '') {
    // Sesuaikan dengan nama kolom 'category' di tabel Anda
    $query = "SELECT * FROM products WHERE category = '$category_filter' ORDER BY id DESC";
} else {
    $query = "SELECT * FROM products ORDER BY id DESC";
}

$result = mysqli_query($conn, $query);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SayurPraya</title>
    <link rel="stylesheet" href="assets/css/index.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
</head>
<body>

<nav class="navbar">
    <div class="logo">
        <img src="assets/img/logo.png" alt="Logo" class="logo-img">
        SayurPraya
    </div>

    <div class="menu-toggle" id="mobile-menu">
        <span></span>
        <span></span>
        <span></span>
    </div>

    <ul class="nav-links">
        <li><a href="index.php" class="active">Beranda</a></li>
        <li><a href="user/menu.php">Menu</a></li>
    </ul>

    </nav>

<header class="hero">
    <div class="hero-content">
        <span class="tagline">100% Produk Organik</span>
        <h1>Belanja Sayur Segar <br><span class="text-gradient">Tanpa Perlu Keluar Rumah</span></h1>
        <p>Dapatkan bahan makanan berkualitas tinggi langsung dari petani lokal ke meja makan Anda hanya dalam hitungan jam.</p>
        
        <div class="hero-buttons">
            <button class="btn-primary" onclick="window.location.href='user/menu.php'">Mulai Belanja</button>
            <button class="btn-secondary" onclick="window.location.href='user/menu.php'">Lihat Menu</button>
        </div>
    </div>

    <div class="hero-image">
        <div class="image-wrapper">
            <img src="assets/img/gambarsayur.jpg" alt="Gambar Sayur">
        </div>
    </div>
</header>

<section class="categories">
    <div class="category-grid">
        <div class="category-card" onclick="window.location.href='user/menu.php?category=buah'">
            <div class="icon-box bg-green">
                <i class="fas fa-apple-alt"></i>
            </div>
            <h3>Buah-buahan</h3>
        </div>

        <div class="category-card" onclick="window.location.href='user/menu.php?category=sayur'">
            <div class="icon-box bg-orange">
                <i class="fas fa-carrot"></i>
            </div>
            <h3>Sayuran</h3>
        </div>

        <div class="category-card" onclick="window.location.href='user/menu.php?category=daging'">
            <div class="icon-box bg-red">
                <i class="fas fa-drumstick-bite"></i>
            </div>
            <h3>Daging dan Ikan</h3>
        </div>

        
    </div>
</section>

<script src="assets/js/index.js"></script>

</body>
</html>