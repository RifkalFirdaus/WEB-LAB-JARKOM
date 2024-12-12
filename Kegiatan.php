<?php
// Mulai sesi
session_start();

// Koneksi ke database
$conn = new mysqli("localhost", "root", "", "databaseweb"); // Ganti dengan username dan password yang sesuai

// Periksa koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Tambah kegiatan
if (isset($_POST['add_kegiatan'])) {
    $nama_kegiatan = htmlspecialchars($_POST['nama_kegiatan']);
    $deskripsi = htmlspecialchars($_POST['deskripsi']);

    $sql = "INSERT INTO kegiatan (nama_kegiatan, deskripsi) VALUES ('$nama_kegiatan', '$deskripsi')";
    $conn->query($sql);
}

// Hapus kegiatan
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $sql = "DELETE FROM kegiatan WHERE id = $id";
    $conn->query($sql);
}

// Ambil data kegiatan
$sql = "SELECT * FROM kegiatan";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kegiatan</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <h1>Kegiatan</h1>
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
        <?php if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true): ?>
            <h2>Tambah Kegiatan</h2>
            <form action="Kegiatan.php" method="POST">
                <input type="text" name="nama_kegiatan" placeholder="Nama Kegiatan" required>
                <textarea name="deskripsi" placeholder="Deskripsi" required></textarea>
                <button type="submit" name="add_kegiatan">Tambah Kegiatan</button>
            </form>
        <?php endif; ?>

        <h2>Daftar Kegiatan</h2>
        <div class="container">
            <?php while ($row = $result->fetch_assoc()): ?>
                <div class="kegiatan-card">
                    <h3><?= htmlspecialchars($row['nama_kegiatan']) ?></h3>
                    <p><?= htmlspecialchars($row['deskripsi']) ?></p>
                    <?php if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true): ?>
                        <a href="Kegiatan.php?delete=<?= $row['id'] ?>" class="delete-btn">Hapus</a>
                    <?php endif; ?>
                </div>
            <?php endwhile; ?>
        </div>
    </main>

    <footer>
        <p>&copy; 2024 Lab Jaringan</p>
    </footer>
</body>
</html>

<?php
$conn->close();
?>
