<?php
require_once 'validasi_cuti.php';

$error = array();
$d = array('karyawan_id' => '', 'tanggal_mulai' => '', 'tanggal_selesai' => '',
           'alasan' => '', 'status' => 'Menunggu');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $d = ambil_input_cuti();
    $error = validasi_cuti($d);
    if (!$error) {
        $lama = (int)((strtotime($d['tanggal_selesai']) - strtotime($d['tanggal_mulai'])) / 86400) + 1;
        mysql_query("INSERT INTO cuti (karyawan_id, tanggal_mulai, tanggal_selesai, lama_cuti, alasan, status)
                     VALUES (" . (int)$d['karyawan_id'] . ", '" . esc($d['tanggal_mulai']) . "',
                             '" . esc($d['tanggal_selesai']) . "', $lama,
                             '" . esc($d['alasan']) . "', '" . esc($d['status']) . "')")
            or die('Gagal menyimpan: ' . mysql_error());
        header('Location: cuti.php');
        exit;
    }
}

$karyawan = mysql_query("SELECT id, nik, nama FROM karyawan ORDER BY nama")
    or die('Query karyawan gagal: ' . mysql_error());
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Pengajuan Cuti</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1>Pengajuan Cuti</h1>
    <div class="nav"><a href="cuti.php">&larr; Kembali ke daftar cuti</a></div>

    <?php if (!empty($error)) { ?>
    <div class="error">
        <strong>Periksa isian Anda:</strong>
        <ul>
            <?php foreach ($error as $e) { ?><li><?php echo h($e); ?></li><?php } ?>
        </ul>
    </div>
    <?php } ?>

    <form class="data" method="post" action="">
        <label for="karyawan_id">Karyawan</label>
        <select id="karyawan_id" name="karyawan_id">
            <option value="">-- Pilih Karyawan --</option>
            <?php while ($k = mysql_fetch_assoc($karyawan)) { ?>
            <option value="<?php echo (int)$k['id']; ?>" <?php if ($d['karyawan_id'] == $k['id']) echo 'selected'; ?>>
                <?php echo h($k['nik'] . ' - ' . $k['nama']); ?>
            </option>
            <?php } ?>
        </select>

        <label for="tanggal_mulai">Tanggal Mulai</label>
        <input type="date" id="tanggal_mulai" name="tanggal_mulai" value="<?php echo h($d['tanggal_mulai']); ?>">

        <label for="tanggal_selesai">Tanggal Selesai</label>
        <input type="date" id="tanggal_selesai" name="tanggal_selesai" value="<?php echo h($d['tanggal_selesai']); ?>">

        <label for="alasan">Alasan</label>
        <input type="text" id="alasan" name="alasan" maxlength="255" value="<?php echo h($d['alasan']); ?>">

        <label for="status">Status</label>
        <select id="status" name="status">
            <?php foreach (daftar_status_cuti() as $s) { ?>
            <option value="<?php echo $s; ?>" <?php if ($d['status'] === $s) echo 'selected'; ?>><?php echo $s; ?></option>
            <?php } ?>
        </select>

        <button type="submit">Simpan</button>
        <a href="cuti.php">Batal</a>
    </form>
</body>
</html>
