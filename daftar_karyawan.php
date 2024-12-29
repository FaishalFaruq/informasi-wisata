<?php
session_start();

// Cek apakah admin sudah login
if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true) {
    header('Location: login_admin.php');
    exit();
}

require_once 'connection.php';

// CREATE: Tambah data karyawan
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add'])) {
    $nama = $_POST['nama'];
    $status = $_POST['status'];
    $waktu_kerja = $_POST['waktu_kerja'];
    $usia = $_POST['usia'];
    $jenis_kelamin = $_POST['jenis_kelamin'];
    $alamat = $_POST['alamat'];
    $nomor_telepon = $_POST['nomor_telepon'];

    $sql = "INSERT INTO karyawan (nama, status, waktu_kerja, usia, jenis_kelamin, alamat, nomor_telepon) 
        VALUES ('$nama', '$status', '$waktu_kerja', $usia, '$jenis_kelamin', '$alamat', '$nomor_telepon')";
    $conn->query($sql);
}

// UPDATE: Ubah data karyawan
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit'])) {
    $id = $_POST['id'];
    $nama = $_POST['nama'];
    $status = $_POST['status'];
    $waktu_kerja = $_POST['waktu_kerja'];
    $usia = $_POST['usia'];
    $jenis_kelamin = $_POST['jenis_kelamin'];
    $alamat = $_POST['alamat'];
    $nomor_telepon = $_POST['nomor_telepon'];

    $sql = "UPDATE karyawan SET nama='$nama', status='$status', waktu_kerja='$waktu_kerja', usia=$usia, 
        jenis_kelamin='$jenis_kelamin', alamat='$alamat', nomor_telepon='$nomor_telepon' WHERE id=$id";
    $conn->query($sql);
}

// DELETE: Hapus data karyawan
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete'])) {
    $id = $_POST['id'];
    $sql = "DELETE FROM karyawan WHERE id=$id";
    $conn->query($sql);
}

// REPORTTING: laporan CSV
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['download_report'])) {
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment;filename="laporan_karyawan.csv"');

    $output = fopen('php://output', 'w');
    fputcsv($output, ['Nama', 'Status', 'Waktu Kerja', 'Usia', 'Jenis Kelamin', 'Alamat', 'Nomor Telepon']);

    $sql = "SELECT * FROM karyawan";
    $result = $conn->query($sql);

    while ($row = $result->fetch_assoc()) {
        fputcsv($output, [$row['nama'], $row['status'], $row['waktu_kerja'], $row['usia'], $row['jenis_kelamin'], $row['alamat'], $row['nomor_telepon']]);
    }

    fclose($output);
    exit();
}

// READ: Ambil data karyawan dengan pencarian (jika ada)
$search = isset($_GET['search']) ? $_GET['search'] : '';
$sql = "SELECT * FROM karyawan";

if (!empty($search)) {
    $sql .= " WHERE nama LIKE '%$search%' OR status LIKE '%$search%' OR waktu_kerja LIKE '%$search%' OR jenis_kelamin LIKE '%$search%'";
}

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Karyawan</title>
    <link rel="stylesheet" href="karyawan.css">
</head>
<body>
    <h1>Daftar Karyawan</h1>

    <!-- Form Tambah Data -->
    <form method="post" action="">
        <h2>Tambah Karyawan</h2>
        <input type="text" name="nama" placeholder="Nama" required>
        <input type="text" name="status" placeholder="Status" required>
        <input type="text" name="waktu_kerja" placeholder="Waktu Kerja" required>
        <input type="number" name="usia" placeholder="Usia" required>
        <select name="jenis_kelamin" required>
            <option value="Laki-laki">Laki-laki</option>
            <option value="Perempuan">Perempuan</option>
        </select>
        <textarea name="alamat" placeholder="Alamat" required></textarea>
        <input type="text" name="nomor_telepon" placeholder="Nomor Telepon" required>
        <button type="submit" name="add">Tambah</button>
    </form>

    <!-- Form Pencarian -->
    <form method="get" action="">
        <h2>Cari Karyawan</h2>
        <input type="text" name="search" placeholder="Cari berdasarkan nama, status, jenis kelamin, atau waktu kerja" value="<?= htmlspecialchars($search) ?>">
        <button type="submit">Cari</button>
        <a href="daftar_karyawan.php"><button type="button">Tampilkan Semua</button></a>
    </form>

    <!-- Tabel Data Karyawan -->
    <h2>Data Karyawan</h2>
    <button onclick="window.print()">Cetak Data</button>
    <table border="1">
        <tr>
            <th>Nama</th>
            <th>Status</th>
            <th>Waktu Kerja</th>
            <th>Usia</th>
            <th>Jenis Kelamin</th>
            <th>Alamat</th>
            <th>Nomor Telepon</th>
            <th>Aksi</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <form method="post" action="">
                    <td><input type="text" name="nama" value="<?= htmlspecialchars($row['nama']) ?>"></td>
                    <td><input type="text" name="status" value="<?= htmlspecialchars($row['status']) ?>"></td>
                    <td><input type="text" name="waktu_kerja" value="<?= htmlspecialchars($row['waktu_kerja']) ?>"></td>
                    <td><input type="number" name="usia" value="<?= htmlspecialchars($row['usia']) ?>"></td>
                    <td>
                        <select name="jenis_kelamin">
                            <option value="Laki-laki" <?= $row['jenis_kelamin'] == 'Laki-laki' ? 'selected' : '' ?>>Laki-laki</option>
                            <option value="Perempuan" <?= $row['jenis_kelamin'] == 'Perempuan' ? 'selected' : '' ?>>Perempuan</option>
                        </select>
                    </td>
                    <td><textarea name="alamat"><?= htmlspecialchars($row['alamat']) ?></textarea></td>
                    <td><input type="text" name="nomor_telepon" value="<?= htmlspecialchars($row['nomor_telepon']) ?>"></td>
                    <td>
                        <input type="hidden" name="id" value="<?= $row['id'] ?>">
                        <button type="submit" name="edit">Update</button>
                        <button type="submit" name="delete">Hapus</button>
                    </td>
                </form>
            </tr>
        <?php endwhile; ?>
    </table>

    <!-- Form download laporan CSV -->
    <form method="post" action="">
        <button type="submit" name="download_report">Unduh Laporan (CSV)</button>
    </form>
    
</body>
</html>
