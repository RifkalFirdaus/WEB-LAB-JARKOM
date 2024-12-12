<?php
// Mulai sesi
session_start();

// Koneksi ke database
$conn = new mysqli("localhost", "root", "", "databaseweb"); // Ganti dengan username dan password yang sesuai

// Periksa koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Tambah berita
if (isset($_POST['add_berita'])) {
    $judul = htmlspecialchars($_POST['judul']);
    $isi = htmlspecialchars($_POST['isi']);

    $sql = "INSERT INTO berita (judul, isi) VALUES ('$judul', '$isi')";
    $conn->query($sql);
}

// Hapus berita
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $sql = "DELETE FROM berita WHERE id = $id";
    $conn->query($sql);
}

// Ambil data berita
$sql = "SELECT * FROM berita";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Berita</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <h1>Berita</h1>
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
            <h2>Tambah Berita</h2>
            <form action="Berita.php" method="POST">
                <input type="text" name="judul" placeholder="Judul Berita" required>
                <textarea name="isi" placeholder="Isi Berita" required></textarea>
                <button type="submit" name="add_berita">Tambah Berita</button>
            </form>
        <?php endif; ?>

        <h2>Daftar Berita</h2>
        <div class="container">
            <?php while ($row = $result->fetch_assoc()): ?>
                <div class="berita-card">
                    <h3><?= htmlspecialchars($row['judul']) ?></h3>
                    <p><?= htmlspecialchars($row['isi']) ?></p>
                    <p><em>Tanggal: <?= htmlspecialchars($row['tanggal']) ?></em></p>
                    <?php if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true): ?>
                        <a href="Berita.php?delete=<?= $row['id'] ?>" class="delete-btn">Hapus</a>
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
