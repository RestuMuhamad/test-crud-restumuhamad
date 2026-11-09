<?php
require_once 'koneksi.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id > 0) {
    if (!mysql_query("DELETE FROM karyawan WHERE id = $id")) {
        die('Gagal menghapus: data karyawan masih dipakai pada transaksi cuti.');
    }
}
header('Location: index.php');
exit;
