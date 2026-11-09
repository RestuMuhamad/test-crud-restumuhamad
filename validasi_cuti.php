<?php
require_once 'validasi.php';

// Validasi data cuti. Mengembalikan array pesan error (kosong = valid).
function validasi_cuti($d) {
    $error = array();

    if ($d['karyawan_id'] === '' || $d['tanggal_mulai'] === '' || $d['tanggal_selesai'] === ''
        || $d['alasan'] === '' || $d['status'] === '') {
        $error[] = 'Semua field wajib diisi.';
    }

    if ($d['karyawan_id'] !== '') {
        if (!ctype_digit($d['karyawan_id'])) {
            $error[] = 'Karyawan tidak valid.';
        } else {
            $kar = mysql_query("SELECT id FROM karyawan WHERE id = " . (int)$d['karyawan_id'])
                or die('Query karyawan gagal: ' . mysql_error());
            if (mysql_num_rows($kar) === 0) {
                $error[] = 'Karyawan tidak ditemukan.';
            }
        }
    }

    $mulai_ok = $d['tanggal_mulai'] !== '' && tanggal_valid($d['tanggal_mulai']);
    $selesai_ok = $d['tanggal_selesai'] !== '' && tanggal_valid($d['tanggal_selesai']);

    if ($d['tanggal_mulai'] !== '' && !$mulai_ok) {
        $error[] = 'Tanggal mulai harus berformat YYYY-MM-DD dan berupa tanggal yang valid.';
    }
    if ($d['tanggal_selesai'] !== '' && !$selesai_ok) {
        $error[] = 'Tanggal selesai harus berformat YYYY-MM-DD dan berupa tanggal yang valid.';
    }
    if ($mulai_ok && $selesai_ok && $d['tanggal_selesai'] < $d['tanggal_mulai']) {
        $error[] = 'Tanggal selesai tidak boleh lebih awal dari tanggal mulai.';
    }

    if ($d['status'] !== '' && !in_array($d['status'], array('Menunggu', 'Disetujui', 'Ditolak'))) {
        $error[] = 'Status tidak valid.';
    }

    return $error;
}

// Ambil dan rapikan input POST form cuti.
function ambil_input_cuti() {
    return array(
        'karyawan_id'     => isset($_POST['karyawan_id']) ? trim($_POST['karyawan_id']) : '',
        'tanggal_mulai'   => isset($_POST['tanggal_mulai']) ? trim($_POST['tanggal_mulai']) : '',
        'tanggal_selesai' => isset($_POST['tanggal_selesai']) ? trim($_POST['tanggal_selesai']) : '',
        'alasan'          => isset($_POST['alasan']) ? trim($_POST['alasan']) : '',
        'status'          => isset($_POST['status']) ? trim($_POST['status']) : 'Menunggu',
    );
}
