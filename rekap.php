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
<html>
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

    .table-custom td {
        padding: 12px 15px;
        border-bottom: 1px solid #eee;
    }

    /* Warna Header Tabel */
    .table-custom th {
        background-color: #334155;
        color: #fff;
    	position: sticky;
    	top: 0;        
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
</style>

<div class="main-container">
    <h2 class="page-title">Rekapitulasi Disiplin Siswa</h2><small>Semester <?php echo htmlspecialchars($data['semester']); ?> Tahun Pelajaran <?php echo htmlspecialchars($data['tahun']); ?></small>
    
    <!-- TOMBOL EKSPOR KE EXCEL BARU (MENGARAH KE FILE TERPISAH) -->
<div style="position: fixed; top: 60px; right: 10px; z-index: 9999;">
    <a href="proses-excel.php" target="_blank" style="background-color: #16a34a; color: white; padding: 10px 20px; border-radius: 5px; text-decoration: none; font-weight: bold; font-size: 12px; display: inline-block;">
        📊 Ekspor ke Excel
    </a>
</div>

    <!-- Tabel wajib dibungkus div ini -->
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
                              <th style='text-align: center;padding:6px;width:80px;'>Dispensasi</th>
                              <th style='text-align: center;padding:6px;width:80px;'>Izin Keluar</th>
                              <th style='text-align: center;padding:6px;width:80px;'>Penghargaan</th>
                              <th style='text-align: center;padding:6px;width:80px;'>Poin</th>
                              <th style='text-align: center;padding:6px;width:80px;'>Pelanggaran</th>
                              <th style='text-align: center;padding:6px;width:80px;'>Poin</th>
                              <th style='text-align: center;padding:6px;width:80px;'>Kesiangan</th>
                              <th style='text-align: center;padding:6px;width:60px;'>Menit</th>    
                          </tr>
                      </thead>
                      
                      <tbody>

                            <?php
                                $query = mysqli_query($con,"SELECT * FROM data_siswa");
                                   
                    			$no=1;
								while ($tampil = mysqli_fetch_array($query)) {
								echo "<tr style='font-size: 11px;'>
								<td style='text-align: center;padding: 2px;'>$no</td>";
								echo"
								<td style='text-align: center;padding: 2px;'>$tampil[nis]</td>
                                <td style='text-align: left;padding: 2px;'>$tampil[nama_siswa]</td>
								<td style='text-align: center;padding: 4px;'>$tampil[kelas]</td>
                                <td style='text-align: center;padding: 2px;'>$tampil[sakit]</td>
								<td style='text-align: center;padding: 2px;'>$tampil[ijin]</td>
								<td style='text-align: center;padding: 2px;'>$tampil[alfa]</td>
								<td style='text-align: center;padding: 2px;'>$tampil[bolos]</td>
								<td style='text-align: center;padding: 2px;'>$tampil[dispen]</td>
								<td style='text-align: center;padding: 2px;'>$tampil[dispen2]</td>
								<td style='text-align: center;padding: 2px;'>$tampil[penghargaan]</td>
								<td style='text-align: center;padding: 2px;'>$tampil[poin_penghargaan]</td>
								<td style='text-align: center;padding: 2px;'>$tampil[pelanggaran]</td>
								<td style='text-align: center;padding: 2px;'>$tampil[poin_pelanggaran]</td>
								<td style='text-align: center;padding: 2px;'>$tampil[kesiangan]</td>
								<td style='text-align: center;padding: 2px;'>$tampil[menit]</td>
								</tr>";
								$no++;}
							?>
                      </tbody>
                    </table>
			</div>  
    <br>
		</div>
</html>
