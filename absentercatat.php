<?php 

include "header.php";
include "config.php";
$file_json = file_get_contents('pengaturan.json');
$data = json_decode($file_json, true); 
if (!$data) {
    $data = [
        'nama_sekolah' => '-',
        'tahun' => '-',
        'kontak' => '-'
    ];
}
// Ambil input filter tanggal jika ada, jika tidak ada diset kosong
$dari = isset($_POST['dari']) ? $_POST['dari'] : '';
$sampai = isset($_POST['sampai']) ? $_POST['sampai'] : '';
?>
<style>
/* Pembungkus Utama Judul (Dipaksa Melebar Penuh Layar Monitor) */
.main-container {
    width: 100vw; /* Mengambil 100% lebar total layar monitor, bukan lebar kontainer induk */
    position: relative;
    left: 50%;
    right: 50%;
    margin-left: -50vw; /* Trik minus untuk menjebol pembatas container bawaan header */
    margin-right: -50vw;
    
    margin-top: 0; /* Jarak dari header agar tidak tertutup */
    margin-bottom: 25px;
    padding: 15px 40px; /* Jarak aman di ujung kiri dan kanan layar */
    text-align: center; 
    font-family: 'Segoe UI', Arial, sans-serif;
    box-sizing: border-box;
    background-color: transparent; /* Bisa diganti #ffffff jika ingin latar judul berwarna putih penuh */
}

/* Desain Judul Utama (H2) */
.page-title {
    color: #1e293b; 
    font-size: 34px; /* Diperbesar sedikit agar gagah di layar lebar */
    font-weight: 700;
    margin-bottom: 10px; 
    letter-spacing: -0.5px;
    width: 100%;
}

/* Desain Keterangan Semester & Tahun */
.main-container small {
    display: inline-block; 
    color: #475569; 
    font-size: 15px; 
    font-weight: 600;
    background-color: #e2e8f0; 
    padding: 6px 18px;
    border-radius: 20px;
}

/* --- RESPONSIF UNTUK LAYAR HP (MAKSIMAL 768px) --- */
@media (max-width: 768px) {
    .main-container {
        width: 100%; /* Di HP dikembalikan ke normal agar tidak meluber samping */
        left: auto;
        right: auto;
        margin-left: 0;
        margin-right: 0;
        margin-top: 75px; /* Jarak atas disesuaikan dengan header HP */
        padding: 5px 15px;
    }

    .page-title {
        font-size: 22px; 
    }

    .main-container small {
        display: block; 
        font-size: 13px; 
        padding: 4px 12px;
        width: fit-content;
        margin: 8px auto 0 auto;
    }
}


/* --- RESPONSIVE UNTUK LAYAR HP (MAKSIMAL 768px) --- */
@media (max-width: 768px) {
    .main-container {
        margin: 70px auto 10px auto; /* Sesuaikan jarak atas saat di HP */
        padding: 5px 15px;
    }

    .page-title {
        font-size: 20px; /* Ukuran huruf judul lebih kecil di HP agar muat */
    }

    .main-container small {
        font-size: 12px; /* Ukuran keterangan lebih kecil di HP */
        padding: 3px 10px;
    }
}

    /* Kunci Utama: Pembungkus Tabel */
    .table-responsive {
        width: 100%;
        overflow-x: auto; /* Otomatis memunculkan scroll hanya pada tabel jika layar HP sempit */
        -webkit-overflow-scrolling: touch;
        margin-top: 15px;
        background: #fff;
        border-radius: 6px;
        box-shadow: 0 2px 5px rgba(0,0,0,0.05);
    }

    /* Desain Tabel Modern & Ringan */
    .table-custom {
        width: 100%;
        border-collapse: collapse; /* Menghilangkan double border */
        text-align: left;
        font-size: 14px;
    }

    .table-custom th, 
    .table-custom td {
        padding: 12px 15px;
        border-bottom: 1px solid #eee;
    }

    /* Warna Header Tabel */
    .table-custom th {
        background-color: #334155;
        color: #fff;
        font-weight: 400;
    }

    /* Efek Baris Belang-Belang (Zebra Striping) */
    .table-custom tr:nth-child(even) {
        background-color: #f9f9f9;
    }

    /* Efek Hover saat Kursor Menyentuh Baris */
    .table-custom tr:hover {
        background-color: #f1f1f1;
    }

    /* Desain Tambahan untuk Form Filter Ringan */
    .filter-wrapper {
        background: #f8f9fa;
        padding: 12px;
        border-radius: 6px;
        margin-top: 10px;
        border: 1px solid #e3e6f0;
    }
    .form-inline-custom {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        gap: 10px;
    }
    /* Style Komponen Kalender Bawaan Browser agar Rapi */
    .form-inline-custom input[type="date"] {
        height: 30px;
        font-size: 12px;
        padding: 4px 8px;
        border: 1px solid #d1d3e2;
        border-radius: 4px;
        width: 145px;
        background-color: #fff;
        font-family: inherit;
    }
    .btn-filter {
        height: 30px;
        font-size: 12px;
        padding: 0 15px;
        background-color: #007bff;
        color: white;
        border: none;
        border-radius: 4px;
        cursor: pointer;
    }
    .btn-filter:hover { background-color: #0056b3; }
    .btn-reset {
        height: 30px;
        font-size: 12px;
        padding: 0 15px;
        background-color: #6c757d;
        color: white;
        border: none;
        border-radius: 4px;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
    }
    .btn-reset:hover { background-color: #5a6268; color: white; }
</style>
<div class="main-container">
    <h2 class="page-title"> Catatan Disiplin Siswa</h2><small> Semester <?php echo htmlspecialchars($data['semester']); ?> Tahun Pelajaran <?php echo htmlspecialchars($data['tahun']); ?></small>
    
    <!-- Blok Form Filter Tanggal Menggunakan Tipe "date" Bawaan Browser -->
    <div class="filter-wrapper">
        <form class="form-inline-custom" method="post" action="">
            <label style="font-size: 13px; font-weight: bold; color: #333;">Filter Tanggal:</label>
            
            <!-- Menggunakan type="date" agar otomatis memunculkan datepicker bawaan tanpa loading -->
            <input type="date" name="dari" required value="<?php echo htmlspecialchars($dari); ?>">
            
            <label style="font-size: 12px; color: #666;">s/d</label>
            
            <input type="date" name="sampai" required value="<?php echo htmlspecialchars($sampai); ?>">
            
            <input type="submit" value="Tampilkan" class="btn-filter">
            
            <?php if ($dari != '' && $sampai != ''): ?>
                <a href="" class="btn-reset">Reset</a>
            <?php endif; ?>
        </form>
    </div>

<?php
if ($dari != '' && $sampai != '') {
// GANTI QUERY FILTER TANGGAL ANDA MENJADI SEPERTI INI:
$sql = "SELECT * FROM catat WHERE (tgl BETWEEN '$dari' AND '$sampai') AND ((pengirim != 1) OR (pengirim = 1 AND (sakit > 0 OR ijin > 0 OR alfa > 0))) ORDER BY id_catat DESC";
} else {
// GANTI QUERY DEFAULT ANDA MENJADI SEPERTI INI:
$sql = "SELECT * FROM catat WHERE (pengirim != 1) OR (pengirim = 1 AND (sakit > 0 OR ijin > 0 OR alfa > 0)) ORDER BY id_catat DESC";
}
$query = mysqli_query($con, $sql);
        // 2. Siapkan Variabel Penampung Total Angka Statistik (Set Awal ke 0)
        $total_sakit        = 0;
        $total_ijin         = 0;
        $total_alfa         = 0;
        $total_bolos        = 0;
        $total_dispen       = 0;
        $total_dispen2      = 0;
        $total_penghargaan  = 0;
        $total_poin_penghargaan = 0;
        $total_pelanggaran  = 0;
        $total_poin_pelanggaran = 0;
        $total_kesiangan    = 0;
        $total_menit        = 0;

        // Ambil semua data terlebih dahulu ke dalam array PHP agar bisa dihitung rangkumannya di atas tabel
        $semua_data = [];
        while ($tampil = mysqli_fetch_array($query)) {
            $semua_data[] = $tampil;
            
            // Proses Penjumlahan Data Statistik (Menggunakan typecast (int) aman dari data kosong/null)
            $total_sakit        += (int)$tampil['sakit'];
            $total_ijin         += (int)$tampil['ijin'];
            $total_alfa         += (int)$tampil['alfa'];
            $total_bolos        += (int)$tampil['bolos'];
            $total_dispen       += (int)$tampil['dispen'];
            $total_dispen2      += (int)$tampil['dispen2'];
            $total_penghargaan  += (int)$tampil['penghargaan'];
            $total_poin_penghargaan += (int)$tampil['poin_penghargaan'];
            $total_pelanggaran  += (int)$tampil['pelanggaran'];
            $total_poin_pelanggaran += (int)$tampil['poin_pelanggaran'];
            $total_kesiangan    += (int)$tampil['kesiangan'];
            $total_menit        += (int)$tampil['menit'];
        }
    ?>

    <!-- ======================================================= -->
    <!-- 3. BLOK RANGKUMAN DATA (CARDS STATISTIK) DI ATAS TABEL -->
    <!-- ======================================================= -->
    <div style="display: flex; gap: 5px; flex-wrap: wrap; margin-bottom: 20px; font-family: sans-serif;">
        <!-- Card Ketidakhadiran -->
        <div style="flex: 1; min-width: 80px; background: #fff; padding: 12px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.05); border-left: 4px solid #f59e0b;">
            <div style="font-size: 11px; text-transform: uppercase; color: #666; font-weight: bold;">Presensi (S/I/A)</div>
            <div style="font-size: 14px; font-weight: bold; color: #333; margin-top: 4px;">
                <span style="color: #3b82f6;"><?= $total_sakit ?> Sakit</span> / 
                <span style="color: #10b981;"><?= $total_ijin ?> Izin</span> / 
                <span style="color: #ef4444;"><?= $total_alfa ?> Alpa</span>
            </div>
        </div>

        <!-- Card Pelanggaran & Kesiangan -->
        <div style="flex: 1; min-width: 80px; background: #fff; padding: 12px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.05); border-left: 4px solid #ef4444;">
            <div style="font-size: 11px; text-transform: uppercase; color: #666; font-weight: bold;">Dispensasi/ Izin Keluar/ Bolos</div>
            <div style="font-size: 14px; font-weight: bold; color: #ef4444; margin-top: 4px;">
                <span style="color: #3b82f6;"><?= $total_dispen ?> Dispen</span> / 
                <span style="color: #10b981;"><?= $total_dispen2 ?> Izin Keluar</span> / 
                <span style="color: #ef4444;"><?= $total_bolos ?> Bolos</span>                
            </div>
        </div>

        <!-- Card Kesiangan -->
        <div style="flex: 1; min-width: 80px; background: #fff; padding: 12px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.05); border-left: 4px solid #10b981;">
            <div style="font-size: 11px; text-transform: uppercase; color: #666; font-weight: bold;">Keterlambatan</div>
            <div style="font-size: 16px; font-weight: bold; color: #10b981; margin-top: 4px;">
                 <span style="color: #3b82f6;"><?= $total_kesiangan ?> <span style="font-size: 12px; color: #666;"> Keterlambatan, total  </span> <?= $total_menit ?> <span style="font-size: 12px; color: #666;"> Menit </span>
        </div>
    </div>
    
    <!-- Card Total Poin Pelanggaran -->
        <div style="flex: 1; min-width: 80px; background: #fff; padding: 12px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.05); border-left: 4px solid #b91c1c;">
            <div style="font-size: 11px; text-transform: uppercase; color: #666; font-weight: bold;">Pelanggaran</div>
            <div style="font-size: 16px; font-weight: bold; color: #b91c1c; margin-top: 4px;">
                <?= $total_pelanggaran ?> <span style="font-size: 12px; color: #666;">Pelanggaran, total  </span> <?= $total_poin_pelanggaran ?> <span style="font-size: 12px; color: #666;">Poin </span>
            </div>
        </div>

        <!-- Card Penghargaan -->
       		<div style="flex: 1; min-width: 80px; background: #fff; padding: 12px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.05); border-left: 4px solid #10b981;">
            <div style="font-size: 11px; text-transform: uppercase; color: #666; font-weight: bold;">Penghargaan</div>
            <div style="font-size: 16px; font-weight: bold; color: #10b981; margin-top: 4px;">
                <?= $total_penghargaan ?> <span style="font-size: 12px; color: #666;">Penghargaan, total  </span> <?= $total_poin_penghargaan ?> <span style="font-size: 12px; color: #666;">Poin </span>
            </div>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table-custom">
            <thead>
                <tr style="font-size: 12px;">
                    <th style='text-align: center;padding:6px;width: 40px;'>No</th>
                    <th style='text-align: center;padding:6px;width: 300px;'>Tanggal</th> 
                    <th style='text-align: center;padding:6px;width: 350px;'>Nama Siswa</th>
                    <th style='text-align: center;padding:6px;width: 60px;'>NIS</th>
                    <th style='text-align: center;padding:6px;width: 100px;'>Kelas</th>
                    <th style='text-align: center;padding:6px;width:60px;'>Sakit</th>
                    <th style='text-align: center;padding:6px;width:60px;'>Ijin</th>
                    <th style='text-align: center;padding:6px;width:60px;'>Alpa</th>
                    <th style='text-align: center;padding:6px;width:80px;'>Bolos</th>
                    <th style='text-align: center;padding:6px;width:80px;'>Dispensasi</th>
                    <th style='text-align: center;padding:6px;width:80px;'>Izin Keluar</th>
                    <th style='text-align: center;padding:6px;width:80px;'>Penghargaan</th>
                    <th style='text-align: center;padding:6px;width:140px;'>Jenis Penghargaan</th>
                    <th style='text-align: center;padding:6px;width:60px;'>Poin</th>
                    <th style='text-align: center;padding:6px;width:80px;'>Pelanggaran</th>
                    <th style='text-align: center;padding:6px;width:140px;'>Jenis Pelanggaran</th>
                    <th style='text-align: center;padding:6px;width:60px;'>Poin</th>
                    <th style='text-align: center;padding:6px;width:80px;'>Kesiangan</th>
                    <th style='text-align: center;padding:6px;width:40px;'>Menit</th>
                        <?php if (isset($_SESSION['level']) && $_SESSION['level'] == 'admin') : ?>
    <th style='text-align: center;padding:6px;width:140px;'>Aksi</th>
<?php endif; ?>
                </tr>
            </thead>
            
<tbody>
    <?php
    $no = 1;
    // Looping data dari array yang telah kita siapkan di atas
    foreach ($semua_data as $tampil) {
        // 1. Cetak data utama siswa terlebih dahulu
        echo "<tr style='font-size: 12px;'>
        <td style='text-align: center;padding: 4px;'>$no</td>
        <td style='text-align: center;padding: 4px;'>{$tampil['tgl']}</td>
        <td style='text-align: left;padding: 4px;'>{$tampil['nama_siswa']}</td>
        <td style='text-align: center;padding: 4px;'>{$tampil['nis']}</td>
        <td style='text-align: center;padding: 4px;'>{$tampil['kelas']}</td>
        <td style='text-align: center;padding: 4px;'>{$tampil['sakit']}</td>
        <td style='text-align: center;padding: 4px;'>{$tampil['ijin']}</td>
        <td style='text-align: center;padding: 4px;'>{$tampil['alfa']}</td>
        <td style='text-align: center;padding: 4px;'>{$tampil['bolos']}</td>
        <td style='text-align: center;padding: 4px;'>{$tampil['dispen']}</td>
        <td style='text-align: center;padding: 4px;'>{$tampil['dispen2']}</td>
        <td style='text-align: center;padding: 4px;'>{$tampil['penghargaan']}</td>
        <td style='text-align: center;padding: 4px;'>{$tampil['jenis_penghargaan']}</td>
        <td style='text-align: center;padding: 4px;'>{$tampil['poin_penghargaan']}</td>
        <td style='text-align: center;padding: 4px;'>{$tampil['pelanggaran']}</td>
        <td style='text-align: center;padding: 4px;'>{$tampil['jenis_pelanggaran']}</td>
        <td style='text-align: center;padding: 4px;'>{$tampil['poin_pelanggaran']}</td>
        <td style='text-align: center;padding: 4px;'>{$tampil['kesiangan']}</td>
        <td style='text-align: center;padding: 4px;'>{$tampil['menit']}</td>";

        // 2. Cek session level admin (Menggunakan logika PHP murni, tanpa tag pembuka baru)
if (isset($_SESSION['level']) && $_SESSION['level'] == 'admin') {
    echo "<td style='text-align: center; padding: 4px;'>
        <!-- UBAH DI SINI: ganti ['id'] menjadi ['id_catat'] -->
        <a href='edit.php?id={$tampil['id_catat']}' class='btn btn-warning btn-sm'>Edit</a>
    </td>";
}

        // 3. Tutup baris tabel tr
        echo "</tr>";
        $no++;
    }

    // Jika data kosong, tampilkan info
    if (empty($semua_data)) {
        // Mengubah colspan menjadi 20 jika admin masuk agar tabel tidak timpang
        $total_kolom = (isset($_SESSION['level']) && $_SESSION['level'] == 'admin') ? 20 : 19;
        echo "<tr><td colspan='{$total_kolom}' style='text-align:center; padding:20px; color:#999; font-style:italic;'>Tidak ada data pada rentang tanggal ini.</td></tr>";
    }
    ?>
</tbody>

</div>
</body>
</html>