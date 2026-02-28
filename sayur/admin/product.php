<?php 
// 1. Jalankan session di baris paling atas
session_start();

// 2. PROTEKSI: Tendang user jika belum login
if (!isset($_SESSION['status']) || $_SESSION['status'] !== "login") {
    header("Location: index.php?pesan=belum_login");
    exit();
}

include '../koneksi/config.php'; 

// LOGIKA UPDATE (Proses dari Pop-up)
if(isset($_POST['update'])){
    $id = $_POST['id'];
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $price = $_POST['price'];
    $category = $_POST['category'];

    $query = "UPDATE products SET name='$name', price='$price', category='$category' WHERE id='$id'";
    if(mysqli_query($conn, $query)){
        header("Location: product.php?pesan=diupdate");
        exit();
    }
}

// Logika Hapus Produk
if(isset($_GET['hapus'])){
    $id = $_GET['hapus'];
    $get_img = mysqli_query($conn, "SELECT image FROM products WHERE id = '$id'");
    $data_img = mysqli_fetch_assoc($get_img);
    
    if($data_img) {
        // Path disesuaikan ke folder assets di root
        $path_foto = "../assets/img/" . $data_img['image'];
        if(file_exists($path_foto)){
            unlink($path_foto); 
        }
        mysqli_query($conn, "DELETE FROM products WHERE id = '$id'");
        header("Location: product.php?pesan=terhapus");
        exit();
    }
}

// Ambil data produk
$query = "SELECT * FROM products ORDER BY id DESC";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Produk - SayurPraya</title>
    
    <link rel="stylesheet" href="../assets/css/admin_style.css">
    <link rel="stylesheet" href="../assets/css/product_admin.css">
    
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    
    <style>
        .wrapper { max-width: 1100px; margin: 20px auto; padding: 0 20px; }
        .nav-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px; }
        .btn-back-outside { text-decoration: none; color: #555; font-weight: 600; display: inline-flex; align-items: center; gap: 8px; transition: 0.3s; }
        .btn-back-outside:hover { color: #27ae60; transform: translateX(-5px); }
        .user-tag { font-size: 13px; color: #888; background: #eee; padding: 5px 12px; border-radius: 20px; }
    </style>
</head>
<body>

<div class="wrapper">
    <div class="nav-header">
        <a href="../dashboard.php" class="btn-back-outside">
            <i class="fas fa-arrow-left"></i> Kembali ke Dashboard
        </a>
        <span class="user-tag"><i class="fas fa-user-shield"></i> <?php echo htmlspecialchars($_SESSION['username']); ?></span>
    </div>

    <div class="container">
        <div class="top-bar">
            <h2 style="margin: 0;">Daftar Stok Produk</h2>
            <a href="menu.php" class="btn-secondary" style="background:#27ae60; color:white; padding: 8px 15px; border-radius:8px; text-decoration:none;">
                <i class="fas fa-plus"></i> Tambah Produk
            </a>
        </div>

        <?php if(isset($_GET['pesan'])): ?>
            <div class="alert-mini">
                <i class="fas fa-check-circle"></i> 
                Data berhasil <?php echo ($_GET['pesan'] == 'terhapus') ? 'dihapus' : 'diperbarui'; ?>!
            </div>
        <?php endif; ?>

        <table>
            <thead>
                <tr>
                    <th>Produk</th>
                    <th>Kategori</th>
                    <th>Harga</th>
                    <th style="text-align: center;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php while($row = mysqli_fetch_assoc($result)): ?>
                <tr>
                    <td style="display: flex; align-items: center; gap: 15px;">
                        <img src="../assets/img/<?php echo $row['image']; ?>" class="img-prod" alt="">
                        <strong><?php echo $row['name']; ?></strong>
                    </td>
                    <td>
                        <span class="badge-cat cat-<?php echo $row['category']; ?>">
                            <?php echo $row['category']; ?>
                        </span>
                    </td>
                    <td>Rp <?php echo number_format($row['price'], 0, ',', '.'); ?></td>
                    <td style="text-align: center;">
                        <button type="button" class="btn-action edit" 
                                onclick="openEditModal('<?php echo $row['id']; ?>', '<?php echo $row['name']; ?>', '<?php echo $row['price']; ?>', '<?php echo $row['category']; ?>')">
                            <i class="fas fa-edit"></i>
                        </button>
                        
                        <a href="product.php?hapus=<?php echo $row['id']; ?>" class="btn-action delete" onclick="return confirm('Hapus produk ini?')">
                            <i class="fas fa-trash"></i>
                        </a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

<div id="editModal" class="modal-overlay">
    <div class="modal-content">
        <h3><i class="fas fa-edit"></i> Edit Produk</h3>
        <form action="" method="POST">
            <input type="hidden" name="id" id="edit_id">
            
            <div class="form-group">
                <label>Nama Produk</label>
                <input type="text" name="name" id="edit_name" required>
            </div>
            
            <div class="form-group">
                <label>Harga (Rp)</label>
                <input type="number" name="price" id="edit_price" required>
            </div>
            
            <div class="form-group">
                <label>Kategori</label>
                <select name="category" id="edit_category">
                    <option value="sayur">Sayuran</option>
                    <option value="buah">Buah-buahan</option>
                    <option value="daging">Daging & Ikan</option>
                </select>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn-cancel" onclick="closeEditModal()">Batal</button>
                <button type="submit" name="update" class="btn-save">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>

<script src="../assets/js/modal_edit.js"></script>
</body>
</html>