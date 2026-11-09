<?php
require_once 'koneksi.php';

// Cek format YYYY-MM-DD dan validitas kalender.
function is_valid_date($date)
{
    if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $date)) return false;
    list($year, $month, $day) = explode('-', $date);
    return checkdate((int)$month, (int)$day, (int)$year);
}

// Validasi data karyawan. $exclude_id dipakai saat edit agar NIK sendiri
// tidak dianggap duplikat. Mengembalikan array pesan error (kosong = valid).
function validate_employee($employee, $exclude_id = 0)
{
    $errors = array();

    if (
        $employee['nik'] === '' || $employee['nama'] === '' || $employee['gender'] === ''
        || $employee['tanggal_lahir'] === '' || $employee['departemen_id'] === '' || $employee['tanggal_masuk'] === ''
    ) {
        $errors[] = 'Semua field wajib diisi.';
    }

    if ($employee['nik'] !== '') {
        if (!preg_match('/^\d{16}$/', $employee['nik'])) {
            $errors[] = 'NIK harus tepat 16 digit angka.';
        } else {
            $result = mysql_query("SELECT id FROM karyawan WHERE nik = '" . escape_sql($employee['nik']) . "'
                                   AND id <> " . (int)$exclude_id)
                or die('Query cek NIK gagal: ' . mysql_error());
            if ($result && mysql_num_rows($result) > 0) {
                $errors[] = 'NIK sudah terdaftar, gunakan NIK lain.';
            }
        }
    }

    if ($employee['departemen_id'] !== '') {
        if (!ctype_digit($employee['departemen_id'])) {
            $errors[] = 'Departemen tidak valid.';
        } else {
            $department = mysql_query("SELECT id FROM departemen WHERE id = " . (int)$employee['departemen_id'])
                or die('Query departemen gagal: ' . mysql_error());
            if (mysql_num_rows($department) === 0) {
                $errors[] = 'Departemen tidak ditemukan.';
            }
        }
    }
    if ($employee['gender'] !== '' && !in_array($employee['gender'], array('Laki-laki', 'Perempuan'))) {
        $errors[] = 'Gender tidak valid.';
    }

    $birthdate_valid = $employee['tanggal_lahir'] !== '' && is_valid_date($employee['tanggal_lahir']);
    $hire_date_valid = $employee['tanggal_masuk'] !== '' && is_valid_date($employee['tanggal_masuk']);

    if ($employee['tanggal_lahir'] !== '' && !$birthdate_valid) {
        $errors[] = 'Tanggal lahir harus berformat YYYY-MM-DD dan berupa tanggal yang valid.';
    } elseif ($birthdate_valid && $employee['tanggal_lahir'] > date('Y-m-d')) {
        $errors[] = 'Tanggal lahir tidak boleh di masa depan.';
    }

    if ($employee['tanggal_masuk'] !== '' && !$hire_date_valid) {
        $errors[] = 'Tanggal masuk harus berformat YYYY-MM-DD dan berupa tanggal yang valid.';
    }

    if ($birthdate_valid && $hire_date_valid && $employee['tanggal_masuk'] < $employee['tanggal_lahir']) {
        $errors[] = 'Tanggal masuk tidak boleh lebih awal dari tanggal lahir.';
    }

    return $errors;
}

// Ambil dan rapikan input POST form karyawan.
function get_employee_input()
{
    return array(
        'nik'           => post_value('nik'),
        'nama'          => post_value('nama'),
        'gender'        => post_value('gender'),
        'tanggal_lahir' => post_value('tanggal_lahir'),
        'departemen_id' => post_value('departemen_id'),
        'tanggal_masuk' => post_value('tanggal_masuk'),
    );
}
