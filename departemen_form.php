<?php
// Partial form departemen. Mengharapkan $department (nilai form) dan $errors (array pesan).
render_errors($errors);
?>

<form class="data" method="post" action="">
    <?php if (!empty($department['id'])) { ?>
        <input type="hidden" name="id" value="<?php echo (int)$department['id']; ?>">
    <?php } ?>

    <label for="nama_departemen">Nama Departemen</label>
    <input type="text" id="nama_departemen" name="nama_departemen" maxlength="50"
           value="<?php echo escape_html($department['nama_departemen']); ?>">

    <button type="submit">Simpan</button>
    <a href="departemen.php">Batal</a>
</form>
