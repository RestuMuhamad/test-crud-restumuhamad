<?php
require_once 'koneksi.php';

$employee_id = (int)request_value('id');
if ($employee_id > 0) {
    if (!mysql_query("DELETE FROM karyawan WHERE id = $employee_id")) {
        die('Gagal menghapus: data karyawan masih dipakai pada transaksi cuti.');
    }
}
header('Location: index.php');
exit;
