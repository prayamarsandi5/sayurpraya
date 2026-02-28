<?php
session_start();
// Hubungkan ke file config kamu
require_once '../koneksi/config.php'; 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // Cek Honeypot
    if (!empty($_POST['b_name'])) {
        die("Bot detected!");
    }

    // Ambil data dan bersihkan (Sanitasi)
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = $_POST['password'];

    // 1. Query aman dengan Prepared Statement (MySQLi version)
    $sql = "SELECT * FROM users WHERE username = ? LIMIT 1";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "s", $username);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $user = mysqli_fetch_assoc($result);

    // 2. Verifikasi Password
    if ($user && password_verify($password, $user['password'])) {
        
        // Login Berhasil
        session_regenerate_id(true); 
        $_SESSION['status'] = "login";
        $_SESSION['username'] = $user['username'];

        header("Location: dashboard.php");
        exit();
    } else {
        // Login Gagal
        sleep(1); 
        header("Location: index.php?pesan=gagal");
        exit();
    }
} else {
    header("Location: index.php");
    exit();
}