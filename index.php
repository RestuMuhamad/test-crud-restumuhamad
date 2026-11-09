<?php
require_once 'koneksi.php';

$q = isset($_GET['q']) ? trim($_GET['q']) : '';
$where = '';
if ($q !== '') {
    $qc = esc($q);
    $where = "WHERE k.nama LIKE '%$qc%' OR k.nik LIKE '%$qc%'";
}
$hasil = mysql_query("SELECT k.*, d.nama_departemen
                      FROM karyawan k
                      JOIN departemen d ON d.id = k.departemen_id
                      $where
                      ORDER BY k.id") or die('Query gagal: ' . mysql_error());
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Data Karyawan</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1>Data Karyawan</h1>
    <div class="nav">
        <a href="index.php">Data Karyawan</a>
        <a href="tambah.php">+ Tambah Karyawan</a>
        <a href="cuti.php">Data Cuti</a>
        <a href="cuti_tambah.php">+ Pengajuan Cuti</a>
    </div>

    <form class="cari" method="get" action="index.php">
        <input type="text" name="q" value="<?php echo h($q); ?>" placeholder="Cari nama / NIK...">
        <button type="submit">Cari</button>
        <?php if ($q !== '') { ?><a href="index.php">Reset</a><?php } ?>
    </form>

    <table>
        <tr>
            <th>NIK</th>
            <th>Nama</th>
            <th>Gender</th>
            <th>Tanggal Lahir</th>
            <th>Departemen</th>
            <th>Tanggal Masuk</th>
            <th>Aksi</th>
        </tr>
        <?php while ($r = mysql_fetch_assoc($hasil)) { ?>
        <tr>
            <td><?php echo h($r['nik']); ?></td>
            <td><?php echo h($r['nama']); ?></td>
            <td><?php echo h($r['gender']); ?></td>
            <td><?php echo h($r['tanggal_lahir']); ?></td>
            <td><?php echo h($r['nama_departemen']); ?></td>
            <td><?php echo h($r['tanggal_masuk']); ?></td>
            <td>
                <a href="edit.php?id=<?php echo (int)$r['id']; ?>">Edit</a> |
                <a href="hapus.php?id=<?php echo (int)$r['id']; ?>"
                   onclick="return confirm('Hapus karyawan <?php echo h($r['nama']); ?>?');">Hapus</a>
            </td>
        </tr>
        <?php } ?>
    </table>
</body>
</html>
