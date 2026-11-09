<?php
require_once 'validasi.php';

$id = isset($_REQUEST['id']) ? (int)$_REQUEST['id'] : 0;
$hasil = mysql_query("SELECT * FROM karyawan WHERE id = $id");
$row = $hasil ? mysql_fetch_assoc($hasil) : false;
if (!$row) {
    header('Location: index.php');
    exit;
}

$error = array();
$d = $row;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $d = ambil_input_karyawan();
    $d['id'] = $id;
    $error = validasi_karyawan($d, $id);
    if (!$error) {
        mysql_query("UPDATE karyawan SET
                        nik = '" . esc($d['nik']) . "',
                        nama = '" . esc($d['nama']) . "',
                        gender = '" . esc($d['gender']) . "',
                        tanggal_lahir = '" . esc($d['tanggal_lahir']) . "',
                        departemen_id = " . (int)$d['departemen_id'] . ",
                        tanggal_masuk = '" . esc($d['tanggal_masuk']) . "'
                     WHERE id = $id")
            or die('Gagal mengubah: ' . mysql_error());
        header('Location: index.php');
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Edit Karyawan</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1>Edit Karyawan</h1>
    <div class="nav"><a href="index.php">&larr; Kembali ke daftar</a></div>
    <?php include '_form.php'; ?>
</body>
</html>
