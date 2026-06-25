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
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Rekapitulasi Disiplin Siswa</title>
    <style>
        /* 1. Kunci Utama: Pembungkus Tabel */
        .table-responsive {
            width: 100%;
            overflow-x: auto; /* Otomatis memunculkan scroll hanya pada tabel jika layar HP sempit */
            -webkit-overflow-scrolling: touch;
            margin-top: 15px;
            background: #fff;
            border-radius: 6px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.05);
        }

        /* 2. Desain Tabel Modern & Ringan */
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

        /* Desain Tombol Cetak */
        .btn-cetak {
            background-color: #2563eb;
            color: white;
            padding: 5px 10px;
            border: none;
            border-radius: 5px;
            font-size: 12px;
            cursor: pointer;
            margin-top: 8px;
            margin-bottom: 5px;
        }
        .btn-cetak:hover {
            background-color: #1d4ed8;
        }

        /* =======================================================
           CSS KHUSUS UNTUK PROSES CETAK PRINTER (MEDIA PRINT)
           ======================================================= */
        @media print {
            /* 1. Sembunyikan elemen header.php bawaan aplikasi Anda */
            header, nav, .navbar, .sidebar, .main-header, #header {
                display: none !important;
            }

            /* 2. Sembunyikan tombol cetak agar tidak ikut terprint di kertas */
            .btn-cetak {
                display: none !important;
            }

            /* 3. Hilangkan tulisan tanggal, judul, dan URL bawaan browser di pojok kertas */
            @page {
                margin-top: 1cm;
                margin-bottom: 1cm;
                margin-left: 1cm;
                margin-right: 1cm;
            }

            /* 4. Sesuaikan warna tabel agar tetap terlihat saat diprint */
            .table-custom th {
                background-color: #334155 !important;
                color: #fff !important;
                -webkit-print-color-adjust: exact; /* Memaksa browser memunculkan warna background */
                print-color-adjust: exact;
            }
        }
        .btn-cetak {
    position: fixed;
    top: 60px;       /* Jarak dari atas halaman */
    right: 20px;     /* Jarak dari kanan halaman */
    z-index: 9999;   /* Memastikan tombol berada di lapisan paling atas */
}

/* Menyembunyikan tombol saat dicetak agar tidak mengotori dokumen hasil print */
@media print {
    .btn-cetak {
        display: none;
    }
}
    </style>
</head>
<body>

<div class="main-container" id="area-cetak">
    <h2 class="page-title">Rekapitulasi Disiplin Siswa</h2><small>Semester <?php echo htmlspecialchars($data['semester']); ?> Tahun Pelajaran <?php echo htmlspecialchars($data['tahun']); ?></small>
    <button class="btn-cetak" onclick="window.print()">🖨️ Cetak Laporan</button>
    <h3>Kelas: <?php echo isset($_POST['kelas']) ? htmlspecialchars($_POST['kelas']) : '-'; ?></h3>

    <!-- Tombol Cetak Baru -->


    <div class="table-responsive">
        <table class="table-custom">                     
            <thead>
                <tr style="font-size: 14px;">
                    <th style='text-align: center;padding:6px;width: 40px;'>No</th>
                    <th style='text-align: center;padding:6px;width: 60px;'>NIS</th>
                    <th style='text-align: center;padding:6px;width: 240px;'>Nama Siswa</th>
                    <th style='text-align: center;padding:6px;width: 80px;'>Kelas</th>
                    <th style='text-align: center;padding:6px;width:60px;'>Sakit</th>
                    <th style='text-align: center;padding:6px;width:60px;'>Izin</th>
                    <th style='text-align: center;padding:6px;width:60px;'>Alpa</th>
                    <th style='text-align: center;padding:6px;width:60px;'>Bolos</th>
                    <th style='text-align: center;padding:6px;width:80px;'>Penghargaan</th>
                    <th style='text-align: center;padding:6px;width:80px;'>Pelanggaran</th>
                    <th style='text-align: center;padding:6px;width:80px;'>Dispen Sekolah</th>
                    <th style='text-align: center;padding:6px;width:80px;'>Dispen Lainnya</th>
                    <th style='text-align: center;padding:6px;width:80px;'>Kesiangan</th>
                    <th style='text-align: center;padding:6px;width:60px;'>Menit</th>    
                </tr>
            </thead>
            
            <tbody>
                <?php
                if (isset($_POST['kelas'])) {
                    $kelas = mysqli_real_escape_string($con, $_POST['kelas']);                                    
                    $query = mysqli_query($con, "SELECT * FROM data_siswa where kelas='$kelas'");                                      
                    $no = 1;
                    while ($tampil = mysqli_fetch_array($query)) {
                        echo "<tr style='font-size: 11px;'>
                        <td style='text-align: center;padding: 2px;'>$no</td>
                        <td style='text-align: center;padding: 2px;'>".htmlspecialchars($tampil['nis'])."</td>
                        <td style='text-align: left;padding: 2px;'>".htmlspecialchars($tampil['nama_siswa'])."</td>
                        <td style='text-align: center;padding: 4px;'>".htmlspecialchars($tampil['kelas'])."</td>
                        <td style='text-align: center;padding: 2px;'>".htmlspecialchars($tampil['sakit'])."</td>
                        <td style='text-align: center;padding: 2px;'>".htmlspecialchars($tampil['ijin'])."</td>
                        <td style='text-align: center;padding: 2px;'>".htmlspecialchars($tampil['alfa'])."</td>
                        <td style='text-align: center;padding: 2px;'>".htmlspecialchars($tampil['bolos'])."</td>
                        <td style='text-align: center;padding: 2px;'>".htmlspecialchars($tampil['penghargaan'])."</td>
                        <td style='text-align: center;padding: 2px;'>".htmlspecialchars($tampil['pelanggaran'])."</td>
                        <td style='text-align: center;padding: 2px;'>".htmlspecialchars($tampil['dispen'])."</td>
                        <td style='text-align: center;padding: 2px;'>".htmlspecialchars($tampil['dispen2'])."</td>
                        <td style='text-align: center;padding: 2px;'>".htmlspecialchars($tampil['kesiangan'])."</td>
                        <td style='text-align: center;padding: 2px;'>".htmlspecialchars($tampil['menit'])."</td>
                        </tr>";
                        $no++;
                    }
                }
                ?>
            </tbody>
        </table>
    </div>
    <br>
</div>

<!-- Bootstrap core JavaScript -->
<script src="js/bootstrap.min.js"></script>
</body>
</html>
