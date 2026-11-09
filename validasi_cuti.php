<?php
require_once 'validasi.php';

// Nilai ENUM status cuti (sumber tunggal untuk form & validasi).
function leave_statuses() {
    return array('Menunggu', 'Disetujui', 'Ditolak');
}

// Validasi data cuti. Mengembalikan array pesan error (kosong = valid).
function validate_leave($leave) {
    $errors = array();

    if ($leave['karyawan_id'] === '' || $leave['tanggal_mulai'] === '' || $leave['tanggal_selesai'] === ''
        || $leave['alasan'] === '' || $leave['status'] === '') {
        $errors[] = 'Semua field wajib diisi.';
    }

    if ($leave['karyawan_id'] !== '') {
        if (!ctype_digit($leave['karyawan_id'])) {
            $errors[] = 'Karyawan tidak valid.';
        } else {
            $employee = mysql_query("SELECT id FROM karyawan WHERE id = " . (int)$leave['karyawan_id'])
                or die('Query karyawan gagal: ' . mysql_error());
            if (mysql_num_rows($employee) === 0) {
                $errors[] = 'Karyawan tidak ditemukan.';
            }
        }
    }

    $start_date_valid = $leave['tanggal_mulai'] !== '' && is_valid_date($leave['tanggal_mulai']);
    $end_date_valid = $leave['tanggal_selesai'] !== '' && is_valid_date($leave['tanggal_selesai']);

    if ($leave['tanggal_mulai'] !== '' && !$start_date_valid) {
        $errors[] = 'Tanggal mulai harus berformat YYYY-MM-DD dan berupa tanggal yang valid.';
    }
    if ($leave['tanggal_selesai'] !== '' && !$end_date_valid) {
        $errors[] = 'Tanggal selesai harus berformat YYYY-MM-DD dan berupa tanggal yang valid.';
    }
    if ($start_date_valid && $end_date_valid && $leave['tanggal_selesai'] < $leave['tanggal_mulai']) {
        $errors[] = 'Tanggal selesai tidak boleh lebih awal dari tanggal mulai.';
    }

    if ($leave['status'] !== '' && !in_array($leave['status'], leave_statuses())) {
        $errors[] = 'Status tidak valid.';
    }

    return $errors;
}

// Ambil dan rapikan input POST form cuti.
function get_leave_input() {
    return array(
        'karyawan_id'     => post_value('karyawan_id'),
        'tanggal_mulai'   => post_value('tanggal_mulai'),
        'tanggal_selesai' => post_value('tanggal_selesai'),
        'alasan'          => post_value('alasan'),
        'status'          => post_value('status', 'Menunggu'),
    );
}
