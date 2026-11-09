<?php
require_once 'validasi.php';

$errors = array();
$employee = array(
    'id' => 0,
    'nik' => '',
    'nama' => '',
    'gender' => '',
    'tanggal_lahir' => '',
    'departemen_id' => '',
    'tanggal_masuk' => ''
);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $employee = get_employee_input();
    $employee['id'] = 0;
    $errors = validate_employee($employee);
    if (!$errors) {
        mysql_query("INSERT INTO karyawan (nik, nama, gender, tanggal_lahir, departemen_id, tanggal_masuk)
                     VALUES ('" . escape_sql($employee['nik']) . "', '" . escape_sql($employee['nama']) . "', '" . escape_sql($employee['gender']) . "',
                             '" . escape_sql($employee['tanggal_lahir']) . "', " . (int)$employee['departemen_id'] . ",
                             '" . escape_sql($employee['tanggal_masuk']) . "')")
            or die('Gagal menyimpan: ' . mysql_error());
        header('Location: index.php');
        exit;
    }
}

render_page_header('Tambah Karyawan');
?>
<div class="nav"><a href="index.php">&larr; Kembali ke daftar</a></div>
<?php include 'form.php'; ?>
<?php render_page_footer(); ?>
