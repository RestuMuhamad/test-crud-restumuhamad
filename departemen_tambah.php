<?php
require_once 'validasi_departemen.php';

$errors = array();
$department = array('id' => 0, 'nama_departemen' => '');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $department = get_department_input();
    $department['id'] = 0;
    $errors = validate_department($department);
    if (!$errors) {
        mysql_query("INSERT INTO departemen (nama_departemen)
                     VALUES ('" . escape_sql($department['nama_departemen']) . "')")
            or die('Gagal menyimpan: ' . mysql_error());
        header('Location: departemen.php');
        exit;
    }
}

render_page_header('Tambah Departemen');
?>
<div class="nav"><a href="departemen.php">&larr; Kembali ke daftar</a></div>
<?php include 'departemen_form.php'; ?>
<?php render_page_footer(); ?>
