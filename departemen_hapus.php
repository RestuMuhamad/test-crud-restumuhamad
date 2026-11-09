<?php
require_once 'koneksi.php';

$department_id = (int)request_value('id');
if ($department_id > 0) {
    if (!mysql_query("DELETE FROM departemen WHERE id = $department_id")) {
        die('Gagal menghapus: departemen masih dipakai oleh data karyawan.');
    }
}
header('Location: departemen.php');
exit;
