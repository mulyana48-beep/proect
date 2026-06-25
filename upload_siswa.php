<?php
include 'header.php';
include 'config.php';

session_start();
// Proteksi Session Admin
if (!isset($_SESSION['level']) || $_SESSION['level'] !== 'admin') {
    echo "<script>alert('Akses ditolak!'); window.location.href = 'home.php';</script>";
    exit();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Kelola Data Siswa</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 40px; background-color: #f9f9f9; }
        .card { max-width: 550px; margin: 20px auto; padding: 25px; border: 1px solid #ddd; border-radius: 8px; background: #fff; box-shadow: 0 4px 6px rgba(0,0,0,0.05); }
        .card-danger { border-top: 5px solid #d9534f; }
        .card-success { border-top: 5px solid #5cb85c; }
        .title { font-size: 18px; font-weight: bold; margin-bottom: 10px; }
        .desc { font-size: 13px; color: #666; line-height: 1.4; margin-bottom: 15px; }
        .btn { padding: 10px 20px; font-size: 14px; border-radius: 4px; cursor: pointer; font-weight: bold; border: none; }
        .btn-danger { background-color: #d9534f; color: white; }
        .btn-danger:hover { background-color: #c9302c; }
        .btn-success { background-color: #5cb85c; color: white; }
        .btn-success:hover { background-color: #4cae4c; }
        hr { border: 0; border-top: 1px solid #eee; margin: 20px 0; }
    </style>
</head>
<body>

    <!-- OPSI 1: KOSONGKAN TABEL (Adopsi dari konsep tabel catat) -->
    <div class="card card-danger">
        <div class="title" style="color: #d9534f;">❌ Pilihan 1: Kosongkan Semua Data Siswa</div>
        <p class="desc">
            Tindakan ini akan menghapus <strong>seluruh data siswa lama</strong> secara permanen dari database dan meriset hitungan ID kembali ke angka 1. Gunakan ini hanya saat pergantian tahun ajaran baru.
        </p>
        <form action="proses_siswa.php" method="POST" onsubmit="return confirm('Apakah Anda BENAR-BENAR YAKIN ingin mengosongkan semua data siswa? Tindakan ini permanen dan tidak bisa dibatalkan!');">
            <button type="submit" name="aksi_kosongkan" class="btn btn-danger">Kosongkan Semua Data Sekarang</button>
        </form>
    </div>

        <!-- OPSI 3: MENGUBAH SEMUA NILAI ABSENSI & POIN MENJADI 0 -->
    <div class="card" style="border-top: 5px solid #f0ad4e;">
        <div class="title" style="color: #f0ad4e;">🔄 Pilihan 3: Setel Ulang (Reset) Semua Nilai Kolom Menjadi 0</div>
        <p class="desc">
            Pilihan ini akan **mengubah nilai kolom absensi, penghargaan, dan pelanggaran seluruh siswa menjadi 0**. Identitas siswa (Nama, NIS, Kelas, ID) tetap aman dan tidak akan dihapus.
        </p>
        <form action="proses_siswa.php" method="POST" onsubmit="return confirm('Apakah Anda BENAR-BENAR YAKIN ingin mengubah semua kolom absensi dan poin siswa menjadi 0? Tindakan ini tidak bisa dibatalkan!');">
            <button type="submit" name="aksi_setel_nol" class="btn" style="background-color: #f0ad4e; color: white;">Reset Data Disiplin</button>
        </form>
    </div>

    <!-- OPSI 2: TAMBAH DATA BARU VIA UPLOAD -->
    <div class="card card-success">
        <div class="title" style="color: #5cb85c;">📥 Pilihan 2: Tambah Data Siswa Baru via CSV</div>
        <p class="desc">
            Gunakan pilihan ini untuk <strong>menambahkan (menggabungkan)</strong> siswa baru ke dalam tabel tanpa mengganggu atau menghapus data siswa yang sudah ada saat ini.
        </p>
        <form action="proses_siswa.php" method="POST" enctype="multipart/form-data">
            <label style="font-weight: bold; font-size: 14px;">Pilih File CSV Siswa Baru:</label><br><br>
            <input type="file" name="file_csv" accept=".csv" required><br><br>
            <button type="submit" name="aksi_upload" class="btn btn-success">Upload & Tambah Data</button>
        </form>
        
    </div>
<a align="center" href="admin.php" class="btn btn-default btn-sm">🏠 Home</a>
</body>
</html>
