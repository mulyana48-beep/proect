<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include "config.php";
include "header.php";

$query_kelas = "SELECT DISTINCT kelas FROM data_siswa ORDER BY kelas ASC";
$result_kelas = mysqli_query($con, $query_kelas);

$kelas_terpilih = isset($_GET['filter_kelas']) ? $_GET['filter_kelas'] : '';

$sudah_absen_hari_ini = false;
if ($kelas_terpilih) {
    $kode_form_pengirim = 1;
    $hari_ini = date('Y-m-d');
    
    $query_cek = "SELECT id_catat FROM catat WHERE kelas = ? AND pengirim = ? AND tgl LIKE ? LIMIT 1";
    $stmt_cek = mysqli_prepare($con, $query_cek);
    
    if ($stmt_cek) {
        $param_tanggal = $hari_ini . "%";
        mysqli_stmt_bind_param($stmt_cek, "sis", $kelas_terpilih, $kode_form_pengirim, $param_tanggal);
        mysqli_stmt_execute($stmt_cek);
        mysqli_stmt_store_result($stmt_cek);
        
        if (mysqli_stmt_num_rows($stmt_cek) > 0) {
            $sudah_absen_hari_ini = true;
        }
        mysqli_stmt_close($stmt_cek);
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Absensi Siswa</title>
    <style>
        .absensi-container { padding: 10px; font-family: sans-serif; max-width: 1200px; margin: 0 auto; }
        h2 { font-size: 1.3rem; color: #333; margin-bottom: 15px; }
        .filter-section { background: #fdfdfd; padding: 12px; border-radius: 8px; border: 1px solid #e2e8f0; margin-bottom: 15px; }
        .filter-section label { display: block; margin-bottom: 6px; color: #4a5568; font-weight: 600; }
        .filter-section select { width: 100%; padding: 8px 10px; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 1rem; max-width: 300px; }
        .table-responsive { width: 100%; overflow-x: auto; background: #fff; }
        .table-absensi { border-collapse: collapse; width: 100%; table-layout: fixed; }
        .table-absensi th, .table-absensi td { border-bottom: 1px solid #e2e8f0; padding: 6px 2px; text-align: center; font-size: 0.75rem; word-wrap: break-word; }
        .table-absening th { background-color: #f8fafc; color: #475569; font-weight: 600; }
        .btn-simpan { background-color: #2563eb; color: white; padding: 12px 24px; border: none; border-radius: 6px; cursor: pointer; font-size: 1rem; font-weight: bold; margin-top: 15px; width: 100%; }
        .alert-warning { background-color: #fffbea; border: 1px solid #fef3c7; color: #b45309; padding: 12px; border-radius: 8px; margin-bottom: 15px; font-size: 0.85rem; line-height: 1.4; }
        @media (min-width: 768px) {
            .absensi-container { padding: 25px; }
            h2 { font-size: 1.6rem; }
            .table-absensi th, .table-absensi td { font-size: 0.9rem; padding: 10px; }
            .btn-simpan { width: auto; }
        }
    </style>
</head>
<body>

<div class="absensi-container">
    <h2>Form Absensi Perkelas</h2>

    <!-- FORM FILTER KELAS (DIPASTIKAN BERDIRI SENDIRI & PENUTUPNYA JELAS) -->
    <div class="filter-section">
        <form method="GET" action="absen_kelas.php">
            <label for="filter_kelas">Pilih Kelas: </label>
            <select name="filter_kelas" id="filter_kelas" onchange="this.form.submit()">
                <option value="">-- Pilih Kelas --</option>
                <?php while($row = mysqli_fetch_assoc($result_kelas)): ?>
                    <option value="<?= $row['kelas']; ?>" <?= $kelas_terpilih == $row['kelas'] ? 'selected' : ''; ?>>
                        <?= $row['kelas']; ?>
                    </option>
                <?php endwhile; ?>
            </select>
        </form>
    </div>
    <?php if ($kelas_terpilih): ?>
        
        <?php if ($sudah_absen_hari_ini): ?>
            <div class="alert-warning">
                <strong>⚠️ Perhatian Penting!</strong> Kelas ini terdeteksi <b>sudah melakukan pengisian absensi hari ini</b>.<br>
                Pengiriman data ulang akan tetap <b>menambah akumulasi (+1)</b> pada data utama siswa dan membuat baris catatan baru. Harap berhati-hati agar data tidak ganda.
            </div>
        <?php endif; ?>

        <!-- FORM UTAMA ABSENSI -->
        <form method="POST" action="proses.php" id="form-absensi">
            <input type="hidden" name="kelas_aktif" value="<?= htmlspecialchars($kelas_terpilih); ?>">
            
            <div class="table-responsive">
                <table class="table-absensi">
                    <thead>
                        <tr>
                            <th style="width: 7%">No</th>
                            <th style="width: 18%">NIS</th>
                            <th style="width: 43%">Nama Siswa</th>
                            <th style="width: 8%">H</th>
                            <th style="width: 8%">S</th>
                            <th style="width: 8%">I</th>
                            <th style="width: 8%">A</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $stmt = mysqli_prepare($con, "SELECT id_siswa, nis, nama_siswa, kelas FROM data_siswa WHERE kelas = ?");
                        mysqli_stmt_bind_param($stmt, "s", $kelas_terpilih);
                        mysqli_stmt_execute($stmt);
                        $result_siswa = mysqli_stmt_get_result($stmt);
                        
                        $no = 1;
                        if (mysqli_num_rows($result_siswa) > 0):
                            while($siswa = mysqli_fetch_assoc($result_siswa)):
                                $id = $siswa['id_siswa'];
                        ?>
                                <tr>
                                    <td><?= $no++; ?></td>
                                    <td><?= $siswa['nis']; ?></td>
                                    
                                    <td style="text-align: left;">
                                        <?= htmlspecialchars($siswa['nama_siswa']); ?>
                                        <input type="hidden" name="nama_siswa[<?= $id; ?>]" value="<?= htmlspecialchars($siswa['nama_siswa']); ?>">
                                        <input type="hidden" name="id_siswa[]" value="<?= $id; ?>">
                                        <input type="hidden" name="nis[<?= $id; ?>]" value="<?= $siswa['nis']; ?>">
                                        <input type="hidden" name="kelas[<?= $id; ?>]" value="<?= $siswa['kelas']; ?>">
                                        <input type="hidden" name="pengirim[<?= $id; ?>]" value="1">
                                    </td>
                                    
                                    <td><input type="radio" name="absensi[<?= $id; ?>]" value="hadir" checked></td>
                                    <td><input type="radio" name="absensi[<?= $id; ?>]" value="sakit"></td>
                                    <td><input type="radio" name="absensi[<?= $id; ?>]" value="ijin"></td>
                                    <td><input type="radio" name="absensi[<?= $id; ?>]" value="alfa"></td>
                                </tr>
                        <?php 
                            endwhile;
                        else:
                        ?>
                            <tr><td colspan="7">Tidak ada data siswa di kelas ini.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            
            <button type="submit" name="simpan_absensi" class="btn-simpan" 
                onclick="if(<?= $sudah_absen_hari_ini ? 'true' : 'false'; ?>) { return confirm('PERINGATAN!\n\nKelas ini sudah melakukan absensi hari ini.\nPengiriman ulang ini AKAN MENAMBAH NILAI AKUMULASI SISWA LAGI (+1) dan TIDAK memperbaiki data lama.\n\nApakah Anda yakin ingin melanjutkan dan menambah data?'); }">
                Simpan Data Absensi
            </button>
        </form>
    <?php endif; ?>
</div>

</body>
</html>
