<?php
// Mulai sesi
session_start();

// Koneksi ke database
$conn = new mysqli("localhost", "root", "", "databaseweb"); // Ganti dengan username dan password yang sesuai

// Periksa koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Hapus permintaan
if (isset($_GET['reject'])) {
    $id = $_GET['reject'];
    $sql = "UPDATE permintaan SET status = 'Ditolak' WHERE id = $id";
    $conn->query($sql);
}

// Setujui permintaan
if (isset($_GET['approve'])) {
    $id = $_GET['approve'];

    // Ambil data permintaan
    $sql = "SELECT * FROM permintaan WHERE id = $id";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();

        // Insert ke tabel peminjaman_barang
        $nama_peminjam = $row['nama_peminjam'];
        $nim_nip = $row['nim_nip'];
        $nama_barang = $row['nama_barang'];
        $jumlah = $row['jumlah'];
        $tanggal_peminjaman = $row['tanggal_peminjaman'];
        $tanggal_pengembalian = $row['tanggal_pengembalian'];

        $sql_insert = "INSERT INTO peminjaman_barang (nama_peminjam, nim_nip, nama_barang, jumlah, tanggal_peminjaman, tanggal_pengembalian, status) VALUES ('$nama_peminjam', '$nim_nip', '$nama_barang', $jumlah, '$tanggal_peminjaman', '$tanggal_pengembalian', 'Belum Dikembalikan')";
        $conn->query($sql_insert);

        // Update status permintaan
        $sql_update = "UPDATE permintaan SET status = 'Disetujui' WHERE id = $id";
        $conn->query($sql_update);
    }
}

// Mengubah status pengembalian barang
if (isset($_GET['return'])) {
    $id = $_GET['return'];
    $sql = "UPDATE peminjaman_barang SET status = 'Sudah Dikembalikan' WHERE id = $id";
    $conn->query($sql);
}

// Ambil data permintaan
$sql = "SELECT * FROM permintaan";
$permintaan = $conn->query($sql);

// Ambil data peminjaman barang
$sql_peminjaman = "SELECT * FROM peminjaman_barang";
$peminjaman_barang = $conn->query($sql_peminjaman);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Permintaan</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <h1>Permintaan</h1>
        <nav>
            <ul>
                <li><a href="DataDosen.php">Data Dosen</a></li>
                <li><a href="Kegiatan.php">Kegiatan</a></li>
                <li><a href="Berita.php">Berita</a></li>
                <li><a href="PeminjamanBarang.php">Peminjaman Barang</a></li>
                <li><a href="permintaan.php">Permintaan</a></li>
                <li><a href="Admin.php">Admin</a></li>
                <?php if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true): ?>
                    <li><a href="logout.php" class="logout-btn">Logout</a></li>
                <?php endif; ?>
            </ul>
        </nav>
    </header>

    <main>
        <h2>Daftar Permintaan</h2>
        <table>
            <tr>
                <th>Nama Peminjam</th>
                <th>NIM/NIP</th>
                <th>Nama Barang</th>
                <th>Jumlah</th>
                <th>Tanggal Peminjaman</th>
                <th>Tanggal Pengembalian</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
            <?php while ($row = $permintaan->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($row['nama_peminjam']) ?></td>
                    <td><?= htmlspecialchars($row['nim_nip']) ?></td>
                    <td><?= htmlspecialchars($row['nama_barang']) ?></td>
                    <td><?= htmlspecialchars($row['jumlah']) ?></td>
                    <td><?= htmlspecialchars($row['tanggal_peminjaman']) ?></td>
                    <td><?= htmlspecialchars($row['tanggal_pengembalian']) ?></td>
                    <td><?= htmlspecialchars($row['status']) ?></td>
                    <td>
                        <?php if ($row['status'] == 'Menunggu Persetujuan'): ?>
                            <a href="permintaan.php?approve=<?= $row['id'] ?>" onclick="return confirm('Apakah Anda yakin ingin menyetujui permintaan ini?')">Disetujui</a> |
                            <a href="permintaan.php?reject=<?= $row['id'] ?>" onclick="return confirm('Apakah Anda yakin ingin menolak permintaan ini?')">Ditolak</a>
                        <?php elseif ($row['status'] == 'Disetujui'): ?>
                            Disetujui
                        <?php elseif ($row['status'] == 'Ditolak'): ?>
                            Ditolak
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endwhile; ?>
        </table>

        <h2>Daftar Peminjaman Barang</h2>
        <table>
            <tr>
                <th>Nama Peminjam</th>
                <th>NIM/NIP</th>
                <th>Nama Barang</th>
                <th>Jumlah</th>
                <th>Tanggal Pinjam</th>
                <th>Tanggal Kembali</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
            <?php while ($row = $peminjaman_barang->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($row['nama_peminjam']) ?></td>
                    <td><?= htmlspecialchars($row['nim_nip']) ?></td>
                    <td><?= htmlspecialchars($row['nama_barang']) ?></td>
                    <td><?= htmlspecialchars($row['jumlah']) ?></td>
                    <td><?= htmlspecialchars($row['tanggal_peminjaman']) ?></td>
                    <td><?= htmlspecialchars($row['tanggal_pengembalian']) ?></td>
                    <td><?= htmlspecialchars($row['status']) ?></td>
                    <td>
                        <?php if ($row['status'] == 'Belum Dikembalikan'): ?>
                            <a href="permintaan.php?return=<?= $row['id'] ?>" onclick="return confirm('Apakah Anda yakin barang ini sudah dikembalikan?')">Tandai Kembali</a>
                        <?php else: ?>
                            Sudah Dikembalikan
                        <?php endif; ?>
                    </td>
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
