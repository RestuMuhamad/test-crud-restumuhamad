<?php
require_once 'validasi.php';

$dari = isset($_GET['dari']) ? trim($_GET['dari']) : '';
$sampai = isset($_GET['sampai']) ? trim($_GET['sampai']) : '';

$where = array();
if ($dari !== '' && tanggal_valid($dari)) {
    $where[] = "c.tanggal_mulai >= '" . esc($dari) . "'";
}
if ($sampai !== '' && tanggal_valid($sampai)) {
    $where[] = "c.tanggal_mulai <= '" . esc($sampai) . "'";
}
$sql_where = $where ? 'WHERE ' . implode(' AND ', $where) : '';

$hasil = mysql_query("SELECT c.*, k.nik, k.nama
                      FROM cuti c
                      JOIN karyawan k ON k.id = c.karyawan_id
                      $sql_where
                      ORDER BY c.tanggal_mulai DESC, c.id DESC")
    or die('Query gagal: ' . mysql_error());

$rekap = mysql_query("SELECT COUNT(*) AS jumlah, COALESCE(SUM(c.lama_cuti), 0) AS total_hari
                      FROM cuti c
                      JOIN karyawan k ON k.id = c.karyawan_id
                      $sql_where")
    or die('Query rekap gagal: ' . mysql_error());
$rk = mysql_fetch_assoc($rekap);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Data Cuti</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1>Data Pengajuan Cuti</h1>
    <div class="nav">
        <a href="index.php">Data Karyawan</a>
        <a href="cuti.php">Data Cuti</a>
        <a href="cuti_tambah.php">+ Pengajuan Cuti</a>
    </div>

    <form class="cari" method="get" action="cuti.php">
        Dari <input type="date" name="dari" value="<?php echo h($dari); ?>">
        Sampai <input type="date" name="sampai" value="<?php echo h($sampai); ?>">
        <button type="submit">Filter</button>
        <?php if ($dari !== '' || $sampai !== '') { ?><a href="cuti.php">Reset</a><?php } ?>
    </form>

    <table>
        <tr>
            <th>NIK</th>
            <th>Nama</th>
            <th>Tanggal Mulai</th>
            <th>Tanggal Selesai</th>
            <th>Lama (hari)</th>
            <th>Alasan</th>
            <th>Status</th>
        </tr>
        <?php while ($r = mysql_fetch_assoc($hasil)) { ?>
        <tr>
            <td><?php echo h($r['nik']); ?></td>
            <td><?php echo h($r['nama']); ?></td>
            <td><?php echo h($r['tanggal_mulai']); ?></td>
            <td><?php echo h($r['tanggal_selesai']); ?></td>
            <td><?php echo (int)$r['lama_cuti']; ?></td>
            <td><?php echo h($r['alasan']); ?></td>
            <td><?php echo h($r['status']); ?></td>
        </tr>
        <?php } ?>
    </table>

    <table class="rekap">
        <tr>
            <th>Jumlah Pengajuan</th>
            <th>Total Hari Cuti</th>
        </tr>
        <tr>
            <td><?php echo (int)$rk['jumlah']; ?></td>
            <td><?php echo (int)$rk['total_hari']; ?></td>
        </tr>
    </table>
</body>
</html>
