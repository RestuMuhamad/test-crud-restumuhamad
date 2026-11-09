<?php
require_once 'koneksi.php';

// Cek format YYYY-MM-DD dan validitas kalender.
function tanggal_valid($t)
{
    if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $t)) return false;
    list($thn, $bln, $tgl) = explode('-', $t);
    return checkdate((int)$bln, (int)$tgl, (int)$thn);
}

// Validasi data karyawan. $kecuali_id dipakai saat edit agar NIK sendiri
// tidak dianggap duplikat. Mengembalikan array pesan error (kosong = valid).
function validasi_karyawan($d, $kecuali_id = 0)
{
    $error = array();

    if (
        $d['nik'] === '' || $d['nama'] === '' || $d['gender'] === ''
        || $d['tanggal_lahir'] === '' || $d['departemen_id'] === '' || $d['tanggal_masuk'] === ''
    ) {
        $error[] = 'Semua field wajib diisi.';
    }

    if ($d['nik'] !== '') {
        if (!preg_match('/^\d{16}$/', $d['nik'])) {
            $error[] = 'NIK harus tepat 16 digit angka.';
        } else {
            $hasil = mysql_query("SELECT id FROM karyawan WHERE nik = '" . esc($d['nik']) . "'
                                  AND id <> " . (int)$kecuali_id);
            if ($hasil && mysql_num_rows($hasil) > 0) {
                $error[] = 'NIK sudah terdaftar, gunakan NIK lain.';
            }
        }
    }

    if ($d['gender'] !== '' && !in_array($d['gender'], array('Laki-laki', 'Perempuan'))) {
        $error[] = 'Gender tidak valid.';
    }

    $lahir_ok = $d['tanggal_lahir'] !== '' && tanggal_valid($d['tanggal_lahir']);
    $masuk_ok = $d['tanggal_masuk'] !== '' && tanggal_valid($d['tanggal_masuk']);

    if ($d['tanggal_lahir'] !== '' && !$lahir_ok) {
        $error[] = 'Tanggal lahir harus berformat YYYY-MM-DD dan berupa tanggal yang valid.';
    } elseif ($lahir_ok && $d['tanggal_lahir'] > date('Y-m-d')) {
        $error[] = 'Tanggal lahir tidak boleh di masa depan.';
    }

    if ($d['tanggal_masuk'] !== '' && !$masuk_ok) {
        $error[] = 'Tanggal masuk harus berformat YYYY-MM-DD dan berupa tanggal yang valid.';
    }

    if ($lahir_ok && $masuk_ok && $d['tanggal_masuk'] < $d['tanggal_lahir']) {
        $error[] = 'Tanggal masuk tidak boleh lebih awal dari tanggal lahir.';
    }

    return $error;
}

// Ambil dan rapikan input POST form karyawan.
function ambil_input_karyawan()
{
    return array(
        'nik'           => isset($_POST['nik']) ? trim($_POST['nik']) : '',
        'nama'          => isset($_POST['nama']) ? trim($_POST['nama']) : '',
        'gender'        => isset($_POST['gender']) ? trim($_POST['gender']) : '',
        'tanggal_lahir' => isset($_POST['tanggal_lahir']) ? trim($_POST['tanggal_lahir']) : '',
        'departemen_id' => isset($_POST['departemen_id']) ? trim($_POST['departemen_id']) : '',
        'tanggal_masuk' => isset($_POST['tanggal_masuk']) ? trim($_POST['tanggal_masuk']) : '',
    );
}
