# Tes CRUD — Data Karyawan & Pengajuan Cuti (PHP 5.6)

Aplikasi CRUD sederhana untuk tes praktik IT Paket A: pengelolaan data karyawan dengan tabel master departemen, plus fitur transaksi pengajuan cuti.

- PHP 5.6, ekstensi `mysql_*` (bukan mysqli/PDO).
- MySQL 5.7, engine InnoDB (PK, FK, UNIQUE di level database).
- Semua input string lolos `mysql_real_escape_string()`; semua id/angka di-cast `(int)`.

## Menjalankan dengan Docker (utama)

Prasyarat: Docker + Docker Compose.

```bash
docker compose up -d
```

Saat container MySQL pertama kali jalan, `db_tes_karyawan.sql` dieksekusi otomatis (schema + data contoh).

| Halaman | URL |
|---|---|
| Aplikasi (daftar karyawan) | http://localhost:8080/index.php |
| Pengajuan cuti | http://localhost:8080/cuti.php |
| phpMyAdmin | http://localhost:8081 |
| Info PHP | http://localhost:8080/info.php |

Hentikan: `docker compose down` (tambahkan `-v` untuk menghapus data MySQL agar seed terisi ulang pada start berikutnya).

### Kredensial demo

| | |
|---|---|
| MySQL host | `db` (dari container app) / `localhost:3306` (dari host) |
| User / password | `root` / `root` |
| Database | `db_tes_karyawan` |
| phpMyAdmin | login dengan kredensial di atas |

## Alternatif: XAMPP 5.6

1. Install XAMPP 5.6.x, jalankan Apache + MySQL.
2. Salin semua file ke `htdocs/tes_<nama>`.
3. Import `db_tes_karyawan.sql` lewat phpMyAdmin XAMPP.
4. `koneksi.php` otomatis mencoba host `db` lalu fallback `localhost` (user `root`, password kosong untuk XAMPP).
5. Buka `http://localhost/tes_<nama>/index.php`.

## Struktur

| File | Fungsi |
|---|---|
| `koneksi.php` | Koneksi DB + helper `escape_sql()`/`escape_html()` (anti SQLi/XSS), `post_value()`/`request_value()` (input), `render_page_header()`/`render_page_footer()`/`render_errors()` (layout) |
| `validasi.php` | Validasi form karyawan |
| `validasi_cuti.php` | Validasi form cuti |
| `form.php` | Partial form tambah/edit karyawan |
| `index.php` | Daftar karyawan (JOIN departemen) + pencarian |
| `tambah.php` / `edit.php` / `hapus.php` | CRUD karyawan |
| `cuti.php` | Daftar cuti (JOIN karyawan), filter rentang tanggal, rekap COUNT + SUM |
| `cuti_tambah.php` | Form pengajuan cuti (lama cuti dihitung otomatis) |
| `db_tes_karyawan.sql` | Schema InnoDB + data contoh |
| `docker-compose.yml` | 3 service: app, db, phpmyadmin |
| `info.php` | `phpinfo()` untuk verifikasi ekstensi `mysql` |

## Validasi server-side

Semua field wajib diisi; NIK tepat 16 digit angka dan unik; tanggal divalidasi `checkdate()`; tanggal lahir tidak boleh di masa depan; tanggal masuk ≥ tanggal lahir; tanggal selesai cuti ≥ tanggal mulai. Pesan error berbahasa Indonesia dan isian form tidak hilang saat error.
