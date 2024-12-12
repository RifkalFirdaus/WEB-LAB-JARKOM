<?php
// Mulai sesi
session_start();
include "Koneksi.php";

// Proses input ke tabel permintaan
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nama_peminjam = htmlspecialchars($_POST['nama_peminjam']);
    $nim_nip = htmlspecialchars($_POST['nim_nip']);
    $nama_barang = htmlspecialchars($_POST['nama_barang']);
    $jumlah = (int)$_POST['jumlah'];
    $tanggal_peminjaman = $_POST['tanggal_peminjaman'];
    $tanggal_pengembalian = $_POST['tanggal_pengembalian'];

    $sql = "INSERT INTO permintaan (nama_peminjam, nim_nip, nama_barang, jumlah, tanggal_peminjaman, tanggal_pengembalian) 
            VALUES ('$nama_peminjam', '$nim_nip', '$nama_barang', '$jumlah', '$tanggal_peminjaman', '$tanggal_pengembalian')";

    if ($conn->query($sql) === TRUE) {
        echo '<div class="notification">Permintaan peminjaman berhasil diajukan.</div>';
    } else {
        echo '<div class="notification error">Gagal mengajukan permintaan: ' . $conn->error . '</div>';
    }
}

// Query untuk menampilkan barang yang dipinjam dari tabel peminjaman_barang
$sql_peminjaman = "SELECT * FROM peminjaman_barang";
$result_peminjaman = $conn->query($sql_peminjaman);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Peminjaman Barang</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <h1>Peminjaman Barang</h1>
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
        <section>
            <h2 style="text-align: center;">Ajukan Permintaan Peminjaman</h2>
            <div class="form-container">
                <form action="" method="POST" class="peminjaman-form">
                    <label for="nama_peminjam">Nama Peminjam:</label>
                    <input type="text" id="nama_peminjam" name="nama_peminjam" required>

                    <label for="nim_nip">NIM/NIP:</label>
                    <input type="text" id="nim_nip" name="nim_nip" required>

                    <label for="nama_barang">Nama Barang:</label>
                    <input type="text" id="nama_barang" name="nama_barang" required>

                    <label for="jumlah">Jumlah:</label>
                    <input type="number" id="jumlah" name="jumlah" required>

                    <label for="tanggal_peminjaman">Tanggal Peminjaman:</label>
                    <input type="date" id="tanggal_peminjaman" name="tanggal_peminjaman" required>

                    <label for="tanggal_pengembalian">Tanggal Pengembalian:</label>
                    <input type="date" id="tanggal_pengembalian" name="tanggal_pengembalian" required>

                    <button type="submit" class="btn-submit">Ajukan</button>
                </form>
            </div>
        </section>
    </main>

    <!-- Tabel Barang yang Dipinjam -->
    <div class="table-container">
        <h2>Barang yang Dipinjam</h2>
        <table>
            <tr>
                <th>Nama Peminjam</th>
                <th>NIM/NIP</th>
                <th>Nama Barang</th>
                <th>Jumlah</th>
                <th>Tanggal Peminjaman</th>
                <th>Tanggal Pengembalian</th>
                <th>Status</th>
            </tr>
            <?php if ($result_peminjaman->num_rows > 0): ?>
                <?php while ($row = $result_peminjaman->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['nama_peminjam']); ?></td>
                        <td><?= htmlspecialchars($row['nim_nip']); ?></td>
                        <td><?= htmlspecialchars($row['nama_barang']); ?></td>
                        <td><?= htmlspecialchars($row['jumlah']); ?></td>
                        <td><?= htmlspecialchars($row['tanggal_peminjaman']); ?></td>
                        <td><?= htmlspecialchars($row['tanggal_pengembalian']); ?></td>
                        <td><?= htmlspecialchars($row['status']); ?></td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="7" style="text-align: center;">Tidak ada data peminjaman barang</td>
                </tr>
            <?php endif; ?>
        </table>
    </div>

    <footer>
        <p>&copy; 2024 Lab Jaringan</p>
    </footer>
</body>
</html>
