<?php
require_once 'koneksi.php';

$search = request_value('q');
$where_clause = '';
if ($search !== '') {
    $where_clause = "WHERE d.nama_departemen LIKE '%" . escape_sql($search) . "%'";
}
$result = mysql_query("SELECT d.*, COUNT(k.id) AS jumlah_karyawan
                       FROM departemen d
                       LEFT JOIN karyawan k ON k.departemen_id = d.id
                       $where_clause
                       GROUP BY d.id
                       ORDER BY d.nama_departemen")
    or die('Query gagal: ' . mysql_error());

render_page_header('Data Departemen');
?>
<div class="nav">
    <a href="index.php">Data Karyawan</a>
    <a href="departemen.php">Data Departemen</a>
    <a href="departemen_tambah.php">+ Tambah Departemen</a>
    <a href="cuti.php">Data Cuti</a>
</div>

<form class="cari" method="get" action="departemen.php">
    <input type="text" name="q" value="<?php echo escape_html($search); ?>" placeholder="Cari nama departemen...">
    <button type="submit">Cari</button>
    <?php if ($search !== '') { ?><a href="departemen.php">Reset</a><?php } ?>
</form>

<table>
    <tr>
        <th>Nama Departemen</th>
        <th>Jumlah Karyawan</th>
        <th>Aksi</th>
    </tr>
    <?php while ($row = mysql_fetch_assoc($result)) { ?>
    <tr>
        <td><?php echo escape_html($row['nama_departemen']); ?></td>
        <td><?php echo (int)$row['jumlah_karyawan']; ?></td>
        <td>
            <a href="departemen_edit.php?id=<?php echo (int)$row['id']; ?>">Edit</a> |
            <a href="departemen_hapus.php?id=<?php echo (int)$row['id']; ?>"
               onclick="return confirm('Hapus departemen <?php echo escape_html($row['nama_departemen']); ?>?');">Hapus</a>
        </td>
    </tr>
    <?php } ?>
</table>
<?php render_page_footer(); ?>
