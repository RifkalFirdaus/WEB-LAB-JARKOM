<?php
// Mulai sesi
session_start();

// Koneksi ke database
$conn = new mysqli("localhost", "root", "", "databaseweb"); // Ganti dengan username dan password yang sesuai

// Periksa koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Tambah dosen
if (isset($_POST['add_dosen'])) {
    $nama = htmlspecialchars($_POST['nama']);
    $nip = htmlspecialchars($_POST['nip']);
    $bidang = htmlspecialchars($_POST['bidang']);
    $email = htmlspecialchars($_POST['email']);
    $foto = htmlspecialchars($_POST['foto']); // Nama file foto

    $sql = "INSERT INTO dosen (nama, nip, bidang, email, foto) VALUES ('$nama', '$nip', '$bidang', '$email', '$foto')";
    $conn->query($sql);
}

// Hapus dosen
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $sql = "DELETE FROM dosen WHERE id = $id";
    $conn->query($sql);
}

// Ambil data dosen
$sql = "SELECT * FROM dosen";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Dosen</title>
    <link rel="stylesheet" href="styles.css">
    <script>
        function confirmDelete() {
            return confirm("Yakin ingin menghapus dosen ini?");
        }
    </script>
</head>
<body>
    <header>
        <h1>Data Dosen</h1>
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
            <h2>Tambah Dosen</h2>
            <form action="DataDosen.php" method="POST" class="add-dosen-form">
                <input type="text" name="nama" placeholder="Nama" required>
                <input type="text" name="nip" placeholder="NIP" required>
                <input type="text" name="bidang" placeholder="Bidang">
                <input type="email" name="email" placeholder="Email" required>
                <input type="text" name="foto" placeholder="Nama File Foto" required>
                <button type="submit" name="add_dosen">Tambah Dosen</button>
            </form>
        <?php endif; ?>

        <h2>Daftar Dosen</h2>
        <table>
            <tr>
                <th>Nama</th>
                <th>NIP</th>
                <th>Bidang</th>
                <th>Email</th>
                <?php if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true): ?>
                    <th>Aksi</th>
                <?php endif; ?>
            </tr>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($row['nama']) ?></td>
                    <td><?= htmlspecialchars($row['nip']) ?></td>
                    <td><?= htmlspecialchars($row['bidang']) ?></td>
                    <td><?= htmlspecialchars($row['email']) ?></td>
                    <?php if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true): ?>
                        <td>
                            <a href="DataDosen.php?delete=<?= $row['id'] ?>" onclick="return confirmDelete();">Hapus</a>
                        </td>
                    <?php endif; ?>
                </tr>
            <?php endwhile; ?>
        </table>
    </main>

    <footer>
        <p>&copy; 2024 Lab Jaringan</p>
    </footer>
</body>
</html>

<?php
$conn->close();
?>
