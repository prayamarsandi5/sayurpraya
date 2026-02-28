<?php 
session_start();

// 1. Proteksi akses langsung - Harus di paling atas
if (!isset($_SESSION['status']) || $_SESSION['status'] !== "login") {
    header("Location: index.php?pesan=belum_login");
    exit();
}

// 2. Perbaikan Path: Karena dashboard di root, panggil langsung folder koneksi
include '../koneksi/config.php'; 

// --- Logika Mengambil Data Statistik ---
// Gunakan @ untuk meredam warning jika koneksi bermasalah saat testing
$q_total = mysqli_query($conn, "SELECT COUNT(*) as total FROM products");
$total_produk = ($q_total) ? mysqli_fetch_assoc($q_total)['total'] : 0;

$q_sayur = mysqli_query($conn, "SELECT COUNT(*) as total FROM products WHERE category = 'sayur'");
$total_sayur = ($q_sayur) ? mysqli_fetch_assoc($q_sayur)['total'] : 0;

$q_buah = mysqli_query($conn, "SELECT COUNT(*) as total FROM products WHERE category = 'buah'");
$total_buah = ($q_buah) ? mysqli_fetch_assoc($q_buah)['total'] : 0;

$q_daging = mysqli_query($conn, "SELECT COUNT(*) as total FROM products WHERE category = 'daging'");
$total_daging = ($q_daging) ? mysqli_fetch_assoc($q_daging)['total'] : 0;
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - SayurPraya</title>
    <link rel="stylesheet" href="../assets/css/admin_style.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body>

<div class="admin-container dashboard-layout">
    <div class="header-section" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <div>
            <h2>Dashboard Admin</h2>
            <p>Halo, <b><?php echo htmlspecialchars($_SESSION['username']); ?></b></p>
        </div>
        <div style="display: flex; gap: 10px;">
            <a href="index.php" class="btn-secondary" target="_blank">Lihat Web <i class="fas fa-external-link-alt"></i></a>
            <a href="logout.php" style="background:#ff4d4d; color:white; padding:10px 20px; border-radius:8px; text-decoration:none; font-weight:bold;">Logout</a>
        </div>
    </div>

    <div class="stats-grid">
        <div class="stat-card bg-green">
            <h3>Total Produk</h3>
            <p class="number"><?php echo $total_produk; ?></p>
            <span class="label">Item di Database</span>
        </div>
        <div class="stat-card">
            <h3>Sayuran</h3>
            <p class="number text-green"><?php echo $total_sayur; ?></p>
        </div>
        <div class="stat-card">
            <h3>Buah-buahan</h3>
            <p class="number text-orange"><?php echo $total_buah; ?></p>
        </div>
        <div class="stat-card">
            <h3>Daging & Ikan</h3>
            <p class="number text-red"><?php echo $total_daging; ?></p>
        </div>
    </div>

    <hr style="margin: 30px 0; border: 0; border-top: 1px solid #eee;">

    <h3>Menu Kelola</h3>
    <div class="menu-grid">
        <a href="menu.php" class="menu-card">
            <div class="icon-box"><i class="fas fa-plus-circle"></i></div>
            <h4>Tambah Produk</h4>
            <p>Input sayur/buah baru</p>
        </a>
        <a href="product.php" class="menu-card">
            <div class="icon-box"><i class="fas fa-list"></i></div>
            <h4>Lihat Daftar Produk</h4>
            <p>Edit atau hapus stok</p>
        </a>
    </div>
</div>

</body>
</html>