<?php
// Partial form karyawan. Mengharapkan $employee (nilai form) dan $errors (array pesan).
$departments = mysql_query("SELECT id, nama_departemen FROM departemen ORDER BY nama_departemen")
    or die('Query departemen gagal: ' . mysql_error());

render_errors($errors);
?>

<form class="data" method="post" action="">
    <?php if (!empty($employee['id'])) { ?>
        <input type="hidden" name="id" value="<?php echo (int)$employee['id']; ?>">
    <?php } ?>

    <label for="nik">NIK (16 digit)</label>
    <input type="text" id="nik" name="nik" maxlength="16" value="<?php echo escape_html($employee['nik']); ?>">

    <label for="nama">Nama</label>
    <input type="text" id="nama" name="nama" maxlength="100" value="<?php echo escape_html($employee['nama']); ?>">

    <label>Gender</label>
    <label><input type="radio" name="gender" value="Laki-laki" <?php if ($employee['gender'] === 'Laki-laki') echo 'checked'; ?>> Laki-laki</label>
    <label><input type="radio" name="gender" value="Perempuan" <?php if ($employee['gender'] === 'Perempuan') echo 'checked'; ?>> Perempuan</label>

    <label for="tanggal_lahir">Tanggal Lahir</label>
    <input type="date" id="tanggal_lahir" name="tanggal_lahir" value="<?php echo escape_html($employee['tanggal_lahir']); ?>">

    <label for="departemen_id">Departemen</label>
    <select id="departemen_id" name="departemen_id">
        <option value="">-- Pilih Departemen --</option>
        <?php while ($department = mysql_fetch_assoc($departments)) { ?>
            <option value="<?php echo (int)$department['id']; ?>" <?php if ($employee['departemen_id'] == $department['id']) echo 'selected'; ?>>
                <?php echo escape_html($department['nama_departemen']); ?>
            </option>
        <?php } ?>
    </select>

    <label for="tanggal_masuk">Tanggal Masuk</label>
    <input type="date" id="tanggal_masuk" name="tanggal_masuk" value="<?php echo escape_html($employee['tanggal_masuk']); ?>">

    <button type="submit">Simpan</button>
    <a href="index.php">Batal</a>
</form>
