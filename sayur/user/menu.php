<?php 
include '../koneksi/config.php'; 

// 1. Tangkap kategori dari URL (hasil klik dari index.php)
$category_filter = isset($_GET['category']) ? mysqli_real_escape_string($conn, $_GET['category']) : '';
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menu SayurPraya</title>
    
    <link rel="stylesheet" href="../assets/css/menu_user.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
</head>
<body>

    <div style="padding: 20px 5%; display: flex; justify-content: space-between; align-items: center;">
        <a href="../index.php" style="text-decoration:none; color:#111827; font-weight:700;">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
        
        <?php if ($category_filter != ''): ?>
            <div style="background: #f0fdf4; padding: 5px 15px; border-radius: 20px; border: 1px solid #22c55e;">
                <span style="font-size: 14px; color: #16a34a;">Kategori: <b><?= ucfirst($category_filter); ?></b></span>
                <a href="menu.php" style="margin-left: 10px; color: #ef4444; text-decoration: none; font-size: 12px;"><i class="fas fa-times-circle"></i> Reset</a>
            </div>
        <?php endif; ?>
    </div>

    <div class="cart-floating-btn" id="openCart">
        <i class="fas fa-shopping-basket fa-lg"></i>
        <span id="cart-badge" style="display: none;">0</span>
    </div>

    <div id="toast-notif" class="toast-notif"></div>

    <div class="menu-container">
        <div class="menu-title" style="text-align: center; margin-bottom: 40px;">
            <h1>Menu Segar <span style="color: #22c55e;">Hari Ini</span></h1>
        </div>

        <div class="product-grid">
            <?php
            // 2. Logika Query Filter
            if ($category_filter != '') {
                $query = "SELECT * FROM products WHERE category = '$category_filter' ORDER BY id DESC";
            } else {
                $query = "SELECT * FROM products ORDER BY id DESC";
            }

            $result = mysqli_query($conn, $query);

            if (mysqli_num_rows($result) > 0) {
                while($row = mysqli_fetch_assoc($result)) {
                    $gambar = "../assets/img/" . $row['image'];
            ?>
                    <div class="product-card">
                        <div class="product-image">
                            <span class="category-badge"><?= ucfirst($row['category']); ?></span>
                            <img src="<?= $gambar; ?>" alt="<?= $row['name']; ?>" class="item-img">
                        </div>
                        <div class="product-info">
                            <h3 class="item-name"><?= $row['name']; ?></h3>
                            <p class="product-price">Rp <span class="item-price"><?= number_format($row['price'], 0, ',', '.'); ?></span></p>
                            <button class="btn-add" onclick="tambahItem(this)">
                                <i class="fas fa-plus"></i> Tambah
                            </button>
                        </div>
                    </div>
            <?php 
                } 
            } else {
                echo "<div style='grid-column: 1/-1; text-align: center; padding: 40px;'>
                        <i class='fas fa-search' style='font-size: 48px; color: #ccc; margin-bottom: 15px;'></i>
                        <p style='color: #666;'>Maaf, produk kategori <b>$category_filter</b> belum tersedia.</p>
                        <a href='menu.php' style='color: #22c55e; font-weight: bold;'>Lihat Semua Menu</a>
                      </div>";
            }
            ?>
        </div>
    </div>

    <div id="cartModal" class="modal">
        <div class="modal-content">
            <span id="closeCart">&times;</span>
            <h3 style="margin-top:0; margin-bottom:15px;">Keranjang Belanja</h3>
            <div id="cartItemsList" style="max-height: 180px; overflow-y: auto;"></div>
            <div id="cartFooter" style="display: none;">
                <div style="margin-top: 15px; border-top: 1px solid #eee; padding-top: 10px;">
                    <input type="text" id="nama_pelanggan" placeholder="Nama Lengkap">
                    <input type="text" id="hp_pelanggan" placeholder="No. WhatsApp (Contoh: 0812...)">
                    <select id="metode_bayar" onchange="cekMetode()">
                        <option value="">-- Pilih Pembayaran --</option>
                        <option value="QRIS / Transfer">QRIS / Transfer</option>
                        <option value="COD (Bayar di Tempat)">COD (Bayar di Tempat)</option>
                    </select>
                    <div id="qr-preview" style="display: none;">
                        <p style="font-size: 11px; font-weight: bold; color: #22c55e; margin-bottom: 5px;">Silakan Scan/Screenshot:</p>
                        <img src="../assets/img/qris.jpeg" alt="QRIS SayurPraya">
                    </div>
                    <textarea id="alamat_pelanggan" placeholder="Alamat Lengkap Pengiriman" rows="2"></textarea>
                </div>
                <p style="display:flex; justify-content:space-between; font-weight:800; margin: 15px 0; font-size: 1.1rem;">
                    Total: <span id="totalPrice" style="color:#22c55e;"></span>
                </p>
                <button class="checkout-btn" onclick="checkoutWA()">
                    <i class="fab fa-whatsapp"></i> Kirim ke WhatsApp
                </button>
            </div>
        </div>
    </div>

    <script src="../assets/js/menu_user.js"></script>
</body>
</html>