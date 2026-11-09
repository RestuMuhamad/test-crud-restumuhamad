<?php
require_once 'validasi.php';

$error = array();
$d = array('id' => 0, 'nik' => '', 'nama' => '', 'gender' => '',
           'tanggal_lahir' => '', 'departemen_id' => '', 'tanggal_masuk' => '');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $d = ambil_input_karyawan();
    $d['id'] = 0;
    $error = validasi_karyawan($d);
    if (!$error) {
        mysql_query("INSERT INTO karyawan (nik, nama, gender, tanggal_lahir, departemen_id, tanggal_masuk)
                     VALUES ('" . esc($d['nik']) . "', '" . esc($d['nama']) . "', '" . esc($d['gender']) . "',
                             '" . esc($d['tanggal_lahir']) . "', " . (int)$d['departemen_id'] . ",
                             '" . esc($d['tanggal_masuk']) . "')")
            or die('Gagal menyimpan: ' . mysql_error());
        header('Location: index.php');
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Tambah Karyawan</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1>Tambah Karyawan</h1>
    <div class="nav"><a href="index.php">&larr; Kembali ke daftar</a></div>
    <?php include '_form.php'; ?>
</body>
</html>
