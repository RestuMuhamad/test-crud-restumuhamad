<?php
date_default_timezone_set('Asia/Jakarta');
// Koneksi database — mencoba Docker (host "db") dulu, lalu XAMPP (localhost).
$connection = @mysql_connect('db', 'root', 'root');
if (!$connection) {
    $connection = mysql_connect('localhost', 'root', '')
        or die('Koneksi database gagal: ' . mysql_error());
}
mysql_select_db('db_tes_karyawan', $connection)
    or die('Database tidak ditemukan: ' . mysql_error($connection));
mysql_set_charset('utf8', $connection);

// Wajib untuk semua nilai string yang masuk query (anti SQL Injection).
function escape_sql($value) {
    return mysql_real_escape_string($value);
}

// Wajib untuk semua nilai yang ditampilkan ke HTML (anti XSS).
function escape_html($value) {
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}

// Ambil satu nilai string dari POST, selalu di-trim.
function post_value($key, $default = '') {
    return isset($_POST[$key]) ? trim($_POST[$key]) : $default;
}

// Ambil satu nilai string dari GET, lalu POST sebagai cadangan.
function request_value($key, $default = '') {
    if (isset($_GET[$key])) return trim($_GET[$key]);
    if (isset($_POST[$key])) return trim($_POST[$key]);
    return $default;
}

// Kerangka <head> + judul halaman. $title dan $heading dianggap teks aman
// (literal kode, bukan input user).
function render_page_header($title, $heading = null) {
    if ($heading === null) $heading = $title;
    echo '<!DOCTYPE html><html lang="id"><head><meta charset="utf-8">'
        . '<title>' . $title . '</title>'
        . '<link rel="stylesheet" href="style.css"></head><body>'
        . '<h1>' . $heading . '</h1>';
}

function render_page_footer() {
    echo '</body></html>';
}

// Blok pesan error validasi, seragam di semua form.
function render_errors($errors) {
    if (empty($errors)) return;
    echo '<div class="error"><strong>Periksa isian Anda:</strong><ul>';
    foreach ($errors as $message) {
        echo '<li>' . escape_html($message) . '</li>';
    }
    echo '</ul></div>';
}
