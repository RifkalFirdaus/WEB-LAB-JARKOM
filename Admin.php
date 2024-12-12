<?php
// Mulai sesi
session_start();
include "Koneksi.php";

// Proses login
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = htmlspecialchars($_POST['username']);
    $password = htmlspecialchars($_POST['password']);

    // Validasi username dan password
    $sql = "SELECT * FROM admin WHERE username = '$username' AND password = '$password'";
    $result = $conn->query($sql);

    if ($result->num_rows === 1) {
        $_SESSION['admin_logged_in'] = true;
        $_SESSION['admin_username'] = $username;

        // Notifikasi login berhasil
        echo '<div class="notification">Selamat datang, Anda masuk sebagai admin.</div>';
    } else {
        echo '<div class="notification error">Username atau password salah!</div>';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <h1>Halaman Admin</h1>
        <nav>
            <ul>
                <li><a href="DataDosen.php">Data Dosen</a></li>
                <li><a href="Kegiatan.php">Kegiatan</a></li>
                <li><a href="Berita.php">Berita</a></li>
                <li><a href="PeminjamanBarang.php">Peminjaman Barang</a></li>
                <li><a href="Admin.php">Admin</a></li>
                <?php if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true): ?>
                    <li><a href="permintaan.php">Permintaan</a></li>
                    <li><a href="logout.php" class="logout-btn">Logout</a></li>
                <?php endif; ?>
            </ul>
        </nav>
    </header>

    <main>
        <?php if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true): ?>
            <!-- Form Login -->
            <div class="form-container">
                <form action="Admin.php" method="POST" class="login-form">
                    <label for="username">Username:</label>
                    <input type="text" id="username" name="username" placeholder="Masukkan username" required>

                    <label for="password">Password:</label>
                    <input type="password" id="password" name="password" placeholder="Masukkan password" required>

                    <button type="submit" class="btn-submit">Login</button>
                </form>
            </div>
        <?php else: ?>
            <h2 style="text-align: center;">Selamat datang, <?= htmlspecialchars($_SESSION['admin_username']); ?>!</h2>
            <p style="text-align: center;">Anda telah masuk sebagai admin.</p>
        <?php endif; ?>
    </main>

    <footer>
        <p>&copy; 2024 Lab Jaringan</p>
    </footer>
</body>
</html>
