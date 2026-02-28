<?php
// 1. Jalankan session di baris paling atas tanpa spasi sebelumnya
session_start();

// 2. Jika admin SUDAH login, jangan biarkan dia ke halaman login lagi,
// langsung lempar ke dashboard.php
if (isset($_SESSION['status']) && $_SESSION['status'] === "login") {
    header("Location: dashboard.php");
    exit();
}

// 3. Tangkap pesan error jika ada
$pesan = isset($_GET['pesan']) ? $_GET['pesan'] : '';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin - SayurPraya</title>
    <script src="https://unpkg.com/lucide@latest"></script>
    <link rel="stylesheet" href="../assets/css/index_admin.css">
</head>
<body style="background-color: #0f0a19; display: flex; justify-content: center; align-items: center; min-height: 100vh; margin: 0; font-family: sans-serif;">

    <div class="login-container" style="width: 100%; max-width: 350px; padding: 20px;">
        <h2 style="color: white; text-align: center; margin-bottom: 30px;">Admin Login</h2>

        <?php if ($pesan == "gagal"): ?>
            <div style="background: rgba(255,0,0,0.1); color: #ff4d4d; padding: 10px; border-radius: 8px; text-align: center; margin-bottom: 20px; border: 1px solid #ff4d4d;">
                Username atau Password salah!
            </div>
        <?php elseif ($pesan == "belum_login"): ?>
            <div style="background: rgba(255,165,0,0.1); color: #ffa500; padding: 10px; border-radius: 8px; text-align: center; margin-bottom: 20px; border: 1px solid #ffa500;">
                Akses ditolak. Silakan login.
            </div>
        <?php endif; ?>

        <form action="login_process.php" method="POST" autocomplete="off">
            <input type="text" name="b_name" style="display:none !important" tabindex="-1">

            <div style="margin-bottom: 15px;">
                <input type="text" name="username" placeholder="Username" required 
                    style="width: 100%; padding: 15px; border-radius: 50px; border: 1px solid #3d354a; background: transparent; color: white; box-sizing: border-box;">
            </div>

            <div style="margin-bottom: 20px; position: relative;">
                <input type="password" name="password" id="password" placeholder="Password" required 
                    style="width: 100%; padding: 15px; border-radius: 50px; border: 1px solid #3d354a; background: transparent; color: white; box-sizing: border-box;">
                <i data-lucide="eye" id="eyeIcon" style="position: absolute; right: 20px; top: 15px; color: #6a5d81; cursor: pointer;"></i>
            </div>

            <button type="submit" style="width: 100%; padding: 15px; border-radius: 15px; border: none; background: #ffff00; color: black; font-weight: bold; cursor: pointer; text-transform: uppercase;">
                LOGIN
            </button>
        </form>
    </div>

    <script>
        lucide.createIcons();
        const eyeIcon = document.getElementById('eyeIcon');
        const passwordInput = document.getElementById('password');

        eyeIcon.addEventListener('click', () => {
            const isPassword = passwordInput.type === 'password';
            passwordInput.type = isPassword ? 'text' : 'password';
            eyeIcon.setAttribute('data-lucide', isPassword ? 'eye-off' : 'eye');
            
            // Refresh icon Lucide
            eyeIcon.innerHTML = '';
            lucide.createIcons();
        });
    </script>
</body>
</html>