<?php
require_once 'koneksi.php';

$search = request_value('q');
$where_clause = '';
if ($search !== '') {
    $escaped_search = escape_sql($search);
    $where_clause = "WHERE k.nama LIKE '%$escaped_search%' OR k.nik LIKE '%$escaped_search%'";
}
$result = mysql_query("SELECT k.*, d.nama_departemen
                       FROM karyawan k
                       JOIN departemen d ON d.id = k.departemen_id
                       $where_clause
                       ORDER BY k.id") or die('Query gagal: ' . mysql_error());

render_page_header('Data Karyawan');
?>
<div class="nav">
    <a href="index.php">Data Karyawan</a>
    <a href="tambah.php">+ Tambah Karyawan</a>
    <a href="cuti.php">Data Cuti</a>
    <a href="cuti_tambah.php">+ Pengajuan Cuti</a>
</div>

<form class="cari" method="get" action="index.php">
    <input type="text" name="q" value="<?php echo escape_html($search); ?>" placeholder="Cari nama / NIK...">
    <button type="submit">Cari</button>
    <?php if ($search !== '') { ?><a href="index.php">Reset</a><?php } ?>
</form>

<table>
    <tr>
        <th>NIK</th>
        <th>Nama</th>
        <th>Gender</th>
        <th>Tanggal Lahir</th>
        <th>Departemen</th>
        <th>Tanggal Masuk</th>
        <th>Aksi</th>
    </tr>
    <?php while ($row = mysql_fetch_assoc($result)) { ?>
    <tr>
        <td><?php echo escape_html($row['nik']); ?></td>
        <td><?php echo escape_html($row['nama']); ?></td>
        <td><?php echo escape_html($row['gender']); ?></td>
        <td><?php echo escape_html($row['tanggal_lahir']); ?></td>
        <td><?php echo escape_html($row['nama_departemen']); ?></td>
        <td><?php echo escape_html($row['tanggal_masuk']); ?></td>
        <td>
            <a href="edit.php?id=<?php echo (int)$row['id']; ?>">Edit</a> |
            <a href="hapus.php?id=<?php echo (int)$row['id']; ?>"
               onclick="return confirm('Hapus karyawan <?php echo escape_html($row['nama']); ?>?');">Hapus</a>
        </td>
    </tr>
    <?php } ?>
</table>
<?php render_page_footer(); ?>
