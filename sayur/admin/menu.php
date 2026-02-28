<?php 
// 1. WAJIB: Jalankan session di baris paling atas (tanpa spasi/enter sebelumnya)
session_start();

// 2. PROTEKSI: Cek apakah user sudah login. Jika tidak ada status 'login', usir ke index.php
if (!isset($_SESSION['status']) || $_SESSION['status'] !== "login") {
    // Menggunakan ../ karena file ini ada di dalam folder, sedangkan index.php ada di luar
    header("Location: index.php?pesan=belum_login");
    exit();
}

include '../koneksi/config.php'; 

$pesan = "";

if(isset($_POST['submit'])){
    $name     = mysqli_real_escape_string($conn, $_POST['name']);
    $price    = $_POST['price'];
    $category = $_POST['category'];
    
    $filename = $_FILES['image']['name'];
    $tmp_name = $_FILES['image']['tmp_name'];
    $error    = $_FILES['image']['error'];

    if($error === 0){
        $target_dir = "../assets/img/"; 
        
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true);
        }

        $target_file = $target_dir . basename($filename);

        if (move_uploaded_file($tmp_name, $target_file)) {
            $query = "INSERT INTO products (name, price, category, image) VALUES ('$name', '$price', '$category', '$filename')";
            if(mysqli_query($conn, $query)){
                $pesan = "<div class='alert alert-success'>✅ Produk '$name' berhasil ditambahkan!</div>";
            } else {
                $pesan = "<div class='alert alert-error'>❌ Database error: " . mysqli_error($conn) . "</div>";
            }
        } else {
            $pesan = "<div class='alert alert-error'>❌ Gagal memindahkan file. Cek izin folder.</div>";
        }
    } else {
        $pesan = "<div class='alert alert-error'>❌ Error saat upload: Kode $error</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Produk - SayurPraya</title>
    <link rel="stylesheet" href="../assets/css/menu_admin.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        .btn-back {
            display: block;
            text-align: center;
            margin-top: 15px;
            text-decoration: none;
            color: #666;
            font-size: 14px;
            transition: 0.3s;
        }
        .btn-back:hover {
            color: #000;
            text-decoration: underline;
        }
        .user-info {
            text-align: right;
            font-size: 12px;
            color: #888;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>

<div class="admin-container">
    <div class="user-info">Login sebagai: <strong><?php echo htmlspecialchars($_SESSION['username']); ?></strong></div>
    
    <h2>Tambah Produk</h2>
    
    <?php echo $pesan; ?>

    <form action="" method="POST" enctype="multipart/form-data">
        <div class="form-group">
            <label for="name">Nama Sayur/Buah</label>
            <input type="text" name="name" id="name" placeholder="Misal: Wortel Organik" required>
        </div>

        <div class="form-group">
            <label for="price">Harga Satuan (Rp)</label>
            <input type="number" name="price" id="price" placeholder="Misal: 12000" required>
        </div>

        <div class="form-group">
            <label for="category">Kategori</label>
            <select name="category" id="category" required>
                <option value="sayur">Sayuran</option>
                <option value="buah">Buah-buahan</option>
                <option value="daging">Daging & Ikan</option>
            </select>
        </div>

        <div class="form-group">
            <label for="image">Foto Produk</label>
            <input type="file" name="image" id="image" accept="image/*" required>
        </div>

        <button type="submit" name="submit" class="btn-submit">Simpan ke Database</button>
        
        <a href="dashboard.php" class="btn-back">← Kembali ke Dashboard</a>
    </form>
</div>

</body>
</html>