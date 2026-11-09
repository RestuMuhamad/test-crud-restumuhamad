<?php
require_once 'koneksi.php';

// Validasi data departemen. $exclude_id dipakai saat edit agar nama sendiri
// tidak dianggap duplikat. Mengembalikan array pesan error (kosong = valid).
function validate_department($department, $exclude_id = 0)
{
    $errors = array();

    if ($department['nama_departemen'] === '') {
        $errors[] = 'Nama departemen wajib diisi.';
    } elseif (strlen($department['nama_departemen']) > 50) {
        $errors[] = 'Nama departemen maksimal 50 karakter.';
    } else {
        $result = mysql_query("SELECT id FROM departemen
                               WHERE nama_departemen = '" . escape_sql($department['nama_departemen']) . "'
                               AND id <> " . (int)$exclude_id)
            or die('Query cek nama departemen gagal: ' . mysql_error());
        if ($result && mysql_num_rows($result) > 0) {
            $errors[] = 'Nama departemen sudah terdaftar, gunakan nama lain.';
        }
    }

    return $errors;
}

// Ambil dan rapikan input POST form departemen.
function get_department_input()
{
    return array(
        'nama_departemen' => post_value('nama_departemen'),
    );
}
