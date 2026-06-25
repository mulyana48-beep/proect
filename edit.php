<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include "config.php";
include "header.php"; 

// 1. Proteksi Admin
if (!isset($_SESSION['level']) || $_SESSION['level'] != 'admin') {
    echo "<script>alert('Akses ditolak!'); window.location.href = 'absentercatat.php';</script>";
    exit();
}

// 2. Ambil data lama berdasarkan id_catat
if (isset($_GET['id'])) {
    $id = mysqli_real_escape_string($con, $_GET['id']);
    $query = "SELECT * FROM catat WHERE id_catat = '$id'";
    $result = mysqli_query($con, $query);
    $data = mysqli_fetch_assoc($result);
    
    if (!$data) {
        echo "<div style='padding:20px; color:red;'>Data tidak ditemukan!</div>";
        exit();
    }
} else if (!isset($_POST['update'])) { 
    // Diubah agar tidak langsung redirect saat form disubmit via POST
    header("Location: absentercatat.php");
    exit();
}

// 3. Proses simpan perubahan
// 1. Pastikan ID didapatkan dari GET atau POST saat form disubmit
$id = '';
if (isset($_GET['id'])) {
    $id = mysqli_real_escape_string($con, $_GET['id']);
} elseif (isset($_POST['id_catat'])) {
    $id = mysqli_real_escape_string($con, $_POST['id_catat']);
}

// Jika tidak ada ID sama sekali, tendang kembali ke halaman utama
if (empty($id)) {
    header("Location: absentercatat.php");
    exit();
}

// 2. Ambil data dari tabel 'catat' (Ini akan selalu berhasil mengambil id_siswa)
$query = "SELECT * FROM catat WHERE id_catat = '$id'";
$result = mysqli_query($con, $query);
$data = mysqli_fetch_assoc($result);

if (!$data) {
    echo "<div style='padding:20px; color:red;'>Data tidak ditemukan!</div>";
    exit();
}

// 3. Proses simpan perubahan saat tombol 'update' ditekan
if (isset($_POST['update'])) {
    $tgl_raw           = $_POST['tgl'];
    $tgl               = date('Y-m-d H:i:s', strtotime($tgl_raw));
    $tgl               = mysqli_real_escape_string($con, $tgl);
    $nama_siswa        = mysqli_real_escape_string($con, $_POST['nama_siswa']);
    $nis               = mysqli_real_escape_string($con, $_POST['nis']);
    $kelas             = mysqli_real_escape_string($con, $_POST['kelas']);
    $sakit             = mysqli_real_escape_string($con, $_POST['sakit']);
    $ijin              = mysqli_real_escape_string($con, $_POST['ijin']);
    $alfa              = mysqli_real_escape_string($con, $_POST['alfa']);
    $bolos             = mysqli_real_escape_string($con, $_POST['bolos']);
    $dispen            = mysqli_real_escape_string($con, $_POST['dispen']);
    $dispen2           = mysqli_real_escape_string($con, $_POST['dispen2']);
    $penghargaan       = mysqli_real_escape_string($con, $_POST['penghargaan']);
    $jenis_penghargaan = mysqli_real_escape_string($con, $_POST['jenis_penghargaan']);
    $poin_penghargaan  = mysqli_real_escape_string($con, $_POST['poin_penghargaan']);
    $pelanggaran       = mysqli_real_escape_string($con, $_POST['pelanggaran']);
    $jenis_pelanggaran = mysqli_real_escape_string($con, $_POST['jenis_pelanggaran']);
    $poin_pelanggaran  = mysqli_real_escape_string($con, $_POST['poin_pelanggaran']);
    $kesiangan         = mysqli_real_escape_string($con, $_POST['kesiangan']);
    $menit             = mysqli_real_escape_string($con, $_POST['menit']);
    
    $query_update = "UPDATE catat SET 
                     tgl = '$tgl', nama_siswa = '$nama_siswa', nis = '$nis', kelas = '$kelas',
                     sakit = '$sakit', ijin = '$ijin', alfa = '$alfa', bolos = '$bolos', 
                     dispen = '$dispen', dispen2 = '$dispen2', penghargaan = '$penghargaan', 
                     jenis_penghargaan = '$jenis_penghargaan', poin_penghargaan = '$poin_penghargaan', 
                     pelanggaran = '$pelanggaran', jenis_pelanggaran = '$jenis_pelanggaran', 
                     poin_pelanggaran = '$poin_pelanggaran', kesiangan = '$kesiangan', menit = '$menit'
                     WHERE id_catat = '$id'";
    
    // Perbaikan: Menggunakan kurung kurawal {} agar array $data['id_siswa'] bisa dibaca di dalam string SQL
    $query_update2 = "UPDATE data_siswa SET 
                     sakit = sakit - '{$data['sakit']}' + '$sakit', ijin = ijin - '{$data['ijin']}' + '$ijin', alfa = alfa - '{$data['alfa']}' + '$alfa', bolos = bolos - '{$data['bolos']}' + '$bolos', dispen = dispen - '{$data['dispen']}' + '$dispen', dispen2 = dispen2 - '{$data['dispen2']}' + '$dispen2', penghargaan = penghargaan - '{$data['penghargaan']}' + '$penghargaan', poin_penghargaan = poin_penghargaan - '{$data['poin_penghargaan']}' + '$poin_penghargaan', pelanggaran = pelanggaran - '{$data['pelanggaran']}' + '$pelanggaran', poin_pelanggaran = poin_pelanggaran - '{$data['poin_pelanggaran']}' + '$poin_pelanggaran', kesiangan = kesiangan - '{$data['kesiangan']}' + '$kesiangan', menit = menit - '{$data['menit']}' + '$menit'
                     WHERE id_rekap = '{$data['id_siswa']}'";
                     
    $exec_update = mysqli_query($con, $query_update);
    $exec_update2 = mysqli_query($con, $query_update2);
    
    if ($exec_update && $exec_update2) {
        echo "<script>alert('Data berhasil diperbarui!'); window.location.href = 'absentercatat.php';</script>";
        exit();
    } else {
        echo "Gagal: " . mysqli_error($con);
    }
}


?>
<style>

/* Container Utama Form */
.app-edit-wrapper {
    max-width: 750px;
    margin: 40px auto;
    padding: 0 20px;
    font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
    color: #334155;
}

/* Bagian Atas / Judul */
.app-edit-header {
    margin-bottom: 25px;
    text-align: left;
}
.app-edit-header h2 {
    font-size: 24px;
    font-weight: 700;
    color: #1e293b;
    margin: 0 0 8px 0;
}
.app-edit-header p {
    font-size: 14px;
    color: #64748b;
    margin: 0;
}

/* Kotak Form Utama */
.app-main-form {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 12px;
    padding: 30px;
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03);
}

/* Setiap Blok Seksi Form */
.app-form-section {
    background: #f8fafc;
    border-radius: 8px;
    padding: 20px;
    margin-bottom: 25px;
    border-left: 4px solid #cbd5e1;
}
.app-form-section.accent-green { border-left-color: #10b981; background: #f0fdf4; }
.app-form-section.accent-red { border-left-color: #ef4444; background: #fef2f2; }

/* Judul Kecil Seksi */
.app-section-head {
    font-size: 15px;
    font-weight: 600;
    color: #0f172a;
    margin-bottom: 15px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

/* Grid Tata Letak Kolom */
.app-grid-3 {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 15px;
}
.app-grid-2 {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 15px;
}

/* Pengaturan Label dan Input */
.app-field {
    display: flex;
    flex-direction: column;
    gap: 6px;
}
.app-field label {
    font-size: 13px;
    font-weight: 600;
    color: #475569;
}
.app-input {
    width: 100%;
    padding: 10px 14px;
    font-size: 14px;
    border: 1px solid #cbd5e1;
    border-radius: 6px;
    box-sizing: border-box;
    background-color: #ffffff;
    color: #1e293b;
    transition: all 0.15s ease-in-out;
}
.app-input:focus {
    border-color: #3b82f6;
    outline: none;
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.15);
}

/* Desain Khusus Input Terkunci */
.app-input.locked {
    background-color: #e2e8f0;
    color: #64748b;
    border-color: #cbd5e1;
    cursor: not-allowed;
}

/* Bar Tombol di Paling Bawah */
.app-action-bar {
    display: flex;
    justify-content: flex-end;
    gap: 12px;
    margin-top: 10px;
    padding-top: 20px;
    border-top: 1px solid #e2e8f0;
}

/* Tombol Dasar */
.app-btn {
    padding: 11px 24px;
    font-size: 14px;
    font-weight: 600;
    border-radius: 6px;
    cursor: pointer;
    text-decoration: none;
    display: inline-block;
    border: none;
    transition: background-color 0.15s ease;
}
.btn-muted {
    background-color: #f1f5f9;
    color: #475569;
}
.btn-muted:hover { background-color: #e2e8f0; }
.btn-primary {
    background-color: #3b82f6;
    color: #ffffff;
}
.btn-primary:hover { background-color: #2563eb; }

/* Responsif di HP Layar Kecil */
@media (max-width: 600px) {
    .app-grid-3, .app-grid-2 {
        grid-template-columns: 1fr;
    }
    .app-action-bar {
        flex-direction: column-reverse;
    }
    .app-btn {
        text-align: center;
        width: 100%;
    }
}
</style>
<div class="app-edit-wrapper">
    <!-- Header Form -->
    <div class="app-edit-header">
        <h2>Form Edit Rekap Siswa</h2>
        <p>Silakan sesuaikan data absensi, catatan prestasi, atau pelanggaran di bawah ini.</p>
    </div>

    <!-- Form Utama -->
    <form action="edit.php?id=<?php echo $id; ?>" method="POST" class="app-main-form">
        <input type="hidden" name="id_catat" value="<?php echo $data['id_catat']; ?>">
        
        <!-- BAGIAN 1: INFORMASI UTAMA -->
        <div class="app-form-section">
            <div class="app-section-head">👤 Data Utama Siswa</div>
            <div class="app-grid-3">
                <div class="app-field">
                    <label>Nama Siswa</label>
                    <input type="text" name="nama_siswa" value="<?php echo $data['nama_siswa']; ?>" readonly class="app-input locked">
                </div>
                <div class="app-field">
                    <label>NIS</label>
                    <input type="text" name="nis" value="<?php echo $data['nis']; ?>" readonly class="app-input locked">
                </div>
                <div class="app-field">
                    <label>Kelas</label>
                    <input type="text" name="kelas" value="<?php echo $data['kelas']; ?>" readonly class="app-input locked">
                </div>
            </div>
            <div class="app-field" style="margin-top: 15px;">
                <label>Tanggal & Waktu Catat</label>
                <?php $tanggal_jam_format = date('Y-m-d\TH:i:s', strtotime($data['tgl'])); ?>
                <input type="datetime-local" name="tgl" value="<?php echo $tanggal_jam_format; ?>" required class="app-input">
            </div>
        </div>

        <!-- BAGIAN 2: DATA REKAP ABSENSI -->
        <div class="app-form-section">
            <div class="app-section-head">📅 Rekap Absensi (Jumlah Hari)</div>
            <div class="app-grid-2">
                <div class="app-field"><label>Sakit</label><input type="number" name="sakit" value="<?php echo $data['sakit']; ?>" min="0" class="app-input"></div>
                <div class="app-field"><label>Izin</label><input type="number" name="ijin" value="<?php echo $data['ijin']; ?>" min="0" class="app-input"></div>
                <div class="app-field"><label>Alpa</label><input type="number" name="alfa" value="<?php echo $data['alfa']; ?>" min="0" class="app-input"></div>
                <div class="app-field"><label>Bolos</label><input type="number" name="bolos" value="<?php echo $data['bolos']; ?>" min="0" class="app-input"></div>
                <div class="app-field"><label>Dispensasi</label><input type="number" name="dispen" value="<?php echo $data['dispen']; ?>" min="0" class="app-input"></div>
                <div class="app-field"><label>Izin Keluar</label><input type="number" name="dispen2" value="<?php echo $data['dispen2']; ?>" min="0" class="app-input"></div>
            </div>
        </div>

        <!-- BAGIAN 3: PRESTASI & PENGHARGAAN -->
        <div class="app-form-section accent-green">
            <div class="app-section-head">🏆 Catatan Prestasi / Penghargaan</div>
            <div class="app-field"><label>Penghargaan (Jumlah Tindakan)</label><input type="number" name="penghargaan" value="<?php echo $data['penghargaan']; ?>" min="0" class="app-input"></div>
            <div class="app-field" style="margin: 15px 0;"><label>Jenis Penghargaan</label><input type="text" name="jenis_penghargaan" value="<?php echo $data['jenis_penghargaan']; ?>" class="app-input" placeholder="Contoh: Juara 1 Futsal"></div>
            <div class="app-field"><label>Poin Penghargaan</label><input type="number" name="poin_penghargaan" value="<?php echo $data['poin_penghargaan']; ?>" min="0" class="app-input"></div>
        </div>

        <!-- BAGIAN 4: PELANGGARAN & KESIANGAN -->
        <div class="app-form-section accent-red">
            <div class="app-section-head">⚠️ Catatan Pelanggaran & Kesiangan</div>
            <div class="app-grid-2">
                <div class="app-field"><label>Pelanggaran (Isi 1 atau 0)</label><input type="number" name="pelanggaran" value="<?php echo $data['pelanggaran']; ?>" min="0" max="1" class="app-input"></div>
                <div class="app-field"><label>Poin Pelanggaran</label><input type="number" name="poin_pelanggaran" value="<?php echo $data['poin_pelanggaran']; ?>" min="0" class="app-input"></div>
            </div>
            <div class="app-field" style="margin: 15px 0;"><label>Jenis Pelanggaran</label><input type="text" name="jenis_pelanggaran" value="<?php echo $data['jenis_pelanggaran']; ?>" class="app-input" placeholder="Contoh: Terlambat masuk kelas"></div>
            <div class="app-grid-2" style="border-top: 1px dashed #e2e8f0; padding-top: 15px;">
                <div class="app-field"><label>Kesiangan (Isi 1 atau 0)</label><input type="number" name="kesiangan" value="<?php echo $data['kesiangan']; ?>" min="0" max="1" class="app-input"></div>
                <div class="app-field"><label>Durasi Keterlambatan (Menit)</label><input type="number" name="menit" value="<?php echo $data['menit']; ?>" min="0" class="app-input"></div>
            </div>
        </div>

        <!-- TOMBOL AKSI -->
        <div class="app-action-bar">
            <a href="absentercatat.php" class="app-btn btn-muted">Kembali</a>
            <button type="submit" name="update" class="app-btn btn-primary">Simpan Perubahan</button>
        </div>
    </form>
</div>
