<?php
require_once 'validasi_cuti.php';

$errors = array();
$leave = array('karyawan_id' => '', 'tanggal_mulai' => '', 'tanggal_selesai' => '',
               'alasan' => '', 'status' => 'Menunggu');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $leave = get_leave_input();
    $errors = validate_leave($leave);
    if (!$errors) {
        $leave_days = (int)((strtotime($leave['tanggal_selesai']) - strtotime($leave['tanggal_mulai'])) / 86400) + 1;
        mysql_query("INSERT INTO cuti (karyawan_id, tanggal_mulai, tanggal_selesai, lama_cuti, alasan, status)
                     VALUES (" . (int)$leave['karyawan_id'] . ", '" . escape_sql($leave['tanggal_mulai']) . "',
                             '" . escape_sql($leave['tanggal_selesai']) . "', $leave_days,
                             '" . escape_sql($leave['alasan']) . "', '" . escape_sql($leave['status']) . "')")
            or die('Gagal menyimpan: ' . mysql_error());
        header('Location: cuti.php');
        exit;
    }
}

$employees = mysql_query("SELECT id, nik, nama FROM karyawan ORDER BY nama")
    or die('Query karyawan gagal: ' . mysql_error());

render_page_header('Pengajuan Cuti');
?>
<div class="nav"><a href="cuti.php">&larr; Kembali ke daftar cuti</a></div>

<?php render_errors($errors); ?>

<form class="data" method="post" action="">
    <label for="karyawan_id">Karyawan</label>
    <select id="karyawan_id" name="karyawan_id">
        <option value="">-- Pilih Karyawan --</option>
        <?php while ($employee = mysql_fetch_assoc($employees)) { ?>
        <option value="<?php echo (int)$employee['id']; ?>" <?php if ($leave['karyawan_id'] == $employee['id']) echo 'selected'; ?>>
            <?php echo escape_html($employee['nik'] . ' - ' . $employee['nama']); ?>
        </option>
        <?php } ?>
    </select>

    <label for="tanggal_mulai">Tanggal Mulai</label>
    <input type="date" id="tanggal_mulai" name="tanggal_mulai" value="<?php echo escape_html($leave['tanggal_mulai']); ?>">

    <label for="tanggal_selesai">Tanggal Selesai</label>
    <input type="date" id="tanggal_selesai" name="tanggal_selesai" value="<?php echo escape_html($leave['tanggal_selesai']); ?>">

    <label for="alasan">Alasan</label>
    <input type="text" id="alasan" name="alasan" maxlength="255" value="<?php echo escape_html($leave['alasan']); ?>">

    <label for="status">Status</label>
    <select id="status" name="status">
        <?php foreach (leave_statuses() as $status_option) { ?>
        <option value="<?php echo $status_option; ?>" <?php if ($leave['status'] === $status_option) echo 'selected'; ?>><?php echo $status_option; ?></option>
        <?php } ?>
    </select>

    <button type="submit">Simpan</button>
    <a href="cuti.php">Batal</a>
</form>
<?php render_page_footer(); ?>
