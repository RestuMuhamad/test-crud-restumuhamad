<?php
require_once 'validasi.php';

$employee_id = (int)request_value('id');
$result = mysql_query("SELECT * FROM karyawan WHERE id = $employee_id")
    or die('Query gagal: ' . mysql_error());
$employee = mysql_fetch_assoc($result);
if (!$employee) {
    header('Location: index.php');
    exit;
}

$errors = array();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $employee = get_employee_input();
    $employee['id'] = $employee_id;
    $errors = validate_employee($employee, $employee_id);
    if (!$errors) {
        mysql_query("UPDATE karyawan SET
                        nik = '" . escape_sql($employee['nik']) . "',
                        nama = '" . escape_sql($employee['nama']) . "',
                        gender = '" . escape_sql($employee['gender']) . "',
                        tanggal_lahir = '" . escape_sql($employee['tanggal_lahir']) . "',
                        departemen_id = " . (int)$employee['departemen_id'] . ",
                        tanggal_masuk = '" . escape_sql($employee['tanggal_masuk']) . "'
                     WHERE id = $employee_id")
            or die('Gagal mengubah: ' . mysql_error());
        header('Location: index.php');
        exit;
    }
}

render_page_header('Edit Karyawan');
?>
<div class="nav"><a href="index.php">&larr; Kembali ke daftar</a></div>
<?php include 'form.php'; ?>
<?php render_page_footer(); ?>
