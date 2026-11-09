<?php
require_once 'validasi_cuti.php';

// Filter rentang dibaca dari GET (form filter) atau POST (form status per baris).
$date_from = request_value('dari');
$date_to = request_value('sampai');

$errors = array();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $leave_id = post_value('id');
    $status = post_value('status');
    if ($leave_id === '' || !ctype_digit($leave_id)) {
        $errors[] = 'Data cuti tidak valid.';
    }
    if (!in_array($status, leave_statuses())) {
        $errors[] = 'Status tidak valid.';
    }
    if (!$errors) {
        mysql_query("UPDATE cuti SET status = '" . escape_sql($status) . "' WHERE id = " . (int)$leave_id)
            or die('Query gagal: ' . mysql_error());
        $filter_params = array();
        if ($date_from !== '' && is_valid_date($date_from)) $filter_params['dari'] = $date_from;
        if ($date_to !== '' && is_valid_date($date_to)) $filter_params['sampai'] = $date_to;
        header('Location: cuti.php' . ($filter_params ? '?' . http_build_query($filter_params) : ''));
        exit;
    }
}

$conditions = array();
if ($date_from !== '' && is_valid_date($date_from)) {
    $conditions[] = "c.tanggal_mulai >= '" . escape_sql($date_from) . "'";
}
if ($date_to !== '' && is_valid_date($date_to)) {
    $conditions[] = "c.tanggal_mulai <= '" . escape_sql($date_to) . "'";
}
$where_clause = $conditions ? 'WHERE ' . implode(' AND ', $conditions) : '';

$leaves = mysql_query("SELECT c.*, k.nik, k.nama
                       FROM cuti c
                       JOIN karyawan k ON k.id = c.karyawan_id
                       $where_clause
                       ORDER BY c.tanggal_mulai DESC, c.id DESC")
    or die('Query gagal: ' . mysql_error());

$summary_result = mysql_query("SELECT COUNT(*) AS jumlah, COALESCE(SUM(c.lama_cuti), 0) AS total_hari
                               FROM cuti c
                               JOIN karyawan k ON k.id = c.karyawan_id
                               $where_clause")
    or die('Query rekap gagal: ' . mysql_error());
$summary = mysql_fetch_assoc($summary_result);

render_page_header('Data Cuti', 'Data Pengajuan Cuti');
?>
<div class="nav">
    <a href="index.php">Data Karyawan</a>
    <a href="cuti.php">Data Cuti</a>
    <a href="cuti_tambah.php">+ Pengajuan Cuti</a>
</div>

<?php render_errors($errors); ?>

<form class="cari" method="get" action="cuti.php">
    Dari <input type="date" name="dari" value="<?php echo escape_html($date_from); ?>">
    Sampai <input type="date" name="sampai" value="<?php echo escape_html($date_to); ?>">
    <button type="submit">Filter</button>
    <?php if ($date_from !== '' || $date_to !== '') { ?><a href="cuti.php">Reset</a><?php } ?>
</form>

<table>
    <tr>
        <th>NIK</th>
        <th>Nama</th>
        <th>Tanggal Mulai</th>
        <th>Tanggal Selesai</th>
        <th>Lama (hari)</th>
        <th>Alasan</th>
        <th>Status</th>
    </tr>
    <?php while ($row = mysql_fetch_assoc($leaves)) { ?>
    <tr>
        <td><?php echo escape_html($row['nik']); ?></td>
        <td><?php echo escape_html($row['nama']); ?></td>
        <td><?php echo escape_html($row['tanggal_mulai']); ?></td>
        <td><?php echo escape_html($row['tanggal_selesai']); ?></td>
        <td><?php echo (int)$row['lama_cuti']; ?></td>
        <td><?php echo escape_html($row['alasan']); ?></td>
        <td>
            <form class="inline" method="post" action="cuti.php">
                <input type="hidden" name="id" value="<?php echo (int)$row['id']; ?>">
                <input type="hidden" name="dari" value="<?php echo escape_html($date_from); ?>">
                <input type="hidden" name="sampai" value="<?php echo escape_html($date_to); ?>">
                <select name="status">
                    <?php foreach (leave_statuses() as $status_option) { ?>
                    <option value="<?php echo $status_option; ?>" <?php if ($row['status'] === $status_option) echo 'selected'; ?>><?php echo $status_option; ?></option>
                    <?php } ?>
                </select>
                <button type="submit">Simpan</button>
            </form>
        </td>
    </tr>
    <?php } ?>
</table>

<table class="rekap">
    <tr>
        <th>Jumlah Pengajuan</th>
        <th>Total Hari Cuti</th>
    </tr>
    <tr>
        <td><?php echo (int)$summary['jumlah']; ?></td>
        <td><?php echo (int)$summary['total_hari']; ?></td>
    </tr>
</table>
<?php render_page_footer(); ?>
