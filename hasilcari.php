<?php 
include "header.php";
include "config.php";
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
</style>

<div class="main-container">
    <h2 class="page-title">Rekapitulasi Disiplin Siswa</h2>
    
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
// 1. Tangkap id_siswa dari input hidden form baru
$id_siswa = isset($_POST['id_siswa']) ? $_POST['id_siswa'] : '';

// 2. Pastikan ID tidak kosong sebelum melakukan query
if (!empty($id_siswa)) {
    // PERBAIKAN: Cari menggunakan id_rekap (Gunakan '=' karena ID bersifat unik dan pasti akurat)
    $query = mysqli_query($con, "SELECT * FROM data_siswa WHERE id_rekap = '$id_siswa'");
} else {
    // Antisipasi jika user mengetik asal atau tidak memilih dari datalist
    $query = false; 
    echo "<div class='alert alert-danger'>Silakan pilih nama siswa yang tersedia dari daftar!</div>";
}

// 3. Proses menampilkan data (silakan lanjutkan kode Anda di bawah)
if ($query && mysqli_num_rows($query) > 0) {
    // Kode looping atau penampilan data siswa Anda sebelumnya...
    // contoh: while($row = mysqli_fetch_array($query)) { ... }
} else if (empty($id_siswa)) {
    // Pesan jika input kosong sudah ditangani di atas
} else {
    echo "<div class='alert alert-warning'>Data siswa tidak ditemukan.</div>";
}

                                   
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
<br>


<?php
// 1. Ambil id_siswa di bagian paling atas file hasilcari.php
$id_siswa = isset($_POST['id_siswa']) ? $_POST['id_siswa'] : '';

// 2. Jalankan query untuk tabel 'catat'
$query2 = false;
if (!empty($id_siswa)) {
    // Mencari riwayat absensi/catatan siswa berdasarkan id_siswa
    $query2 = mysqli_query($con, "SELECT * FROM catat WHERE id_siswa = '$id_siswa'");
}
?>

<!-- Tampilkan pesan peringatan di luar tabel jika input kosong -->
<?php if (empty($id_siswa)): ?>
    <div class='alert alert-danger'>Silakan pilih nama siswa yang tersedia dari daftar!</div>
<?php else: ?>

    <table class="table-custom">
        <thead>
            <tr style="font-size: 14px;">
                <th style='text-align: center;padding:6px;width: 40px;'>No</th>
                <th style='text-align: center;padding:6px;width: 200px;'>Tanggal</th> 
                <th style='text-align: center;padding:6px;width: 300px;'>Nama Siswa</th>
                <th style='text-align: center;padding:6px;width: 60px;'>NIS</th>
                <th style='text-align: center;padding:6px;width: 80px;'>Kelas</th>
                <th style='text-align: center;padding:6px;width:80px;'>Sakit</th>
                <th style='text-align: center;padding:6px;width:80px;'>Ijin</th>
                <th style='text-align: center;padding:6px;width:80px;'>Alpa</th>
                <th style='text-align: center;padding:6px;width:80px;'>Bolos</th>
                <th style='text-align: center;padding:6px;width:80px;'>Dispensasi</th>
                <th style='text-align: center;padding:6px;width:80px;'>Izin Keluar</th>
                <th style='text-align: center;padding:6px;width:80px;'>Penghargaan</th>
                <th style='text-align: center;padding:6px;width:140px;'>Jenis Penghargaan</th>
                <th style='text-align: center;padding:6px;width:0px;'>Poin</th>
                <th style='text-align: center;padding:6px;width:80px;'>Pelanggaran</th>
                <th style='text-align: center;padding:6px;width:140px;'>Jenis Pelanggaran</th>
                <th style='text-align: center;padding:6px;width:0px;'>Poin</th>
                <th style='text-align: center;padding:6px;width:80px;'>Kesiangan</th>
                <th style='text-align: center;padding:6px;width:40px;'>Menit</th>
            </tr>
        </thead>
        
        <tbody>
            <?php
            // 3. Tampilkan data dari database ke dalam baris tabel
            if ($query2 && mysqli_num_rows($query2) > 0) {
                $no = 1;
                while ($tampil = mysqli_fetch_array($query2)) {
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
                        <td style='text-align: center;padding: 4px;'>{$tampil['menit']}</td>
                    </tr>";
                    $no++;
                }
            } else {
                // Jika query berhasil tapi datanya kosong di tabel catat
                echo "<tr><td colspan='19' style='text-align: center; padding: 10px;'>Data catatan siswa tidak ditemukan.</td></tr>";
            }
            ?>
        </tbody>
    </table>

<?php endif; ?>

    </div>

</html>