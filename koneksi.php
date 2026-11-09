<?php
date_default_timezone_set('Asia/Jakarta');
// Koneksi database — mencoba Docker (host "db") dulu, lalu XAMPP (localhost).
$koneksi = @mysql_connect('db', 'root', 'root');
if (!$koneksi) {
    $koneksi = mysql_connect('localhost', 'root', '')
        or die('Koneksi database gagal: ' . mysql_error());
}
mysql_select_db('db_tes_karyawan', $koneksi)
    or die('Database tidak ditemukan: ' . mysql_error($koneksi));
mysql_set_charset('utf8', $koneksi);

// Wajib untuk semua nilai string yang masuk query (anti SQL Injection).
function esc($nilai) {
    return mysql_real_escape_string($nilai);
}

// Wajib untuk semua nilai yang ditampilkan ke HTML (anti XSS).
function h($nilai) {
    return htmlspecialchars($nilai, ENT_QUOTES, 'UTF-8');
}
