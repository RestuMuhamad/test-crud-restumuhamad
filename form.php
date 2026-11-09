<?php
// Partial form karyawan. Mengharapkan $d (nilai form) dan $error (array pesan).
$departemen = mysql_query("SELECT id, nama_departemen FROM departemen ORDER BY nama_departemen")
    or die('Query departemen gagal: ' . mysql_error());
?>
<?php if (!empty($error)) { ?>
    <div class="error">
        <strong>Periksa isian Anda:</strong>
        <ul>
            <?php foreach ($error as $e) { ?><li><?php echo h($e); ?></li><?php } ?>
        </ul>
    </div>
<?php } ?>

<form class="data" method="post" action="">
    <?php if (!empty($d['id'])) { ?>
        <input type="hidden" name="id" value="<?php echo (int)$d['id']; ?>">
    <?php } ?>

    <label for="nik">NIK (16 digit)</label>
    <input type="text" id="nik" name="nik" maxlength="16" value="<?php echo h($d['nik']); ?>">

    <label for="nama">Nama</label>
    <input type="text" id="nama" name="nama" maxlength="100" value="<?php echo h($d['nama']); ?>">

    <label>Gender</label>
    <label><input type="radio" name="gender" value="Laki-laki" <?php if ($d['gender'] === 'Laki-laki') echo 'checked'; ?>> Laki-laki</label>
    <label><input type="radio" name="gender" value="Perempuan" <?php if ($d['gender'] === 'Perempuan') echo 'checked'; ?>> Perempuan</label>

    <label for="tanggal_lahir">Tanggal Lahir</label>
    <input type="date" id="tanggal_lahir" name="tanggal_lahir" value="<?php echo h($d['tanggal_lahir']); ?>">

    <label for="departemen_id">Departemen</label>
    <select id="departemen_id" name="departemen_id">
        <option value="">-- Pilih Departemen --</option>
        <?php while ($dep = mysql_fetch_assoc($departemen)) { ?>
            <option value="<?php echo (int)$dep['id']; ?>" <?php if ($d['departemen_id'] == $dep['id']) echo 'selected'; ?>>
                <?php echo h($dep['nama_departemen']); ?>
            </option>
        <?php } ?>
    </select>

    <label for="tanggal_masuk">Tanggal Masuk</label>
    <input type="date" id="tanggal_masuk" name="tanggal_masuk" value="<?php echo h($d['tanggal_masuk']); ?>">

    <button type="submit">Simpan</button>
    <a href="index.php">Batal</a>
</form>