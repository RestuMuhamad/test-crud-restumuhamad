<?php
require_once 'validasi_departemen.php';

$department_id = (int)request_value('id');
$result = mysql_query("SELECT * FROM departemen WHERE id = $department_id")
    or die('Query gagal: ' . mysql_error());
$department = mysql_fetch_assoc($result);
if (!$department) {
    header('Location: departemen.php');
    exit;
}

$errors = array();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $department = get_department_input();
    $department['id'] = $department_id;
    $errors = validate_department($department, $department_id);
    if (!$errors) {
        mysql_query("UPDATE departemen SET
                        nama_departemen = '" . escape_sql($department['nama_departemen']) . "'
                     WHERE id = $department_id")
            or die('Gagal mengubah: ' . mysql_error());
        header('Location: departemen.php');
        exit;
    }
}

render_page_header('Edit Departemen');
?>
<div class="nav"><a href="departemen.php">&larr; Kembali ke daftar</a></div>
<?php include 'departemen_form.php'; ?>
<?php render_page_footer(); ?>
