<?php 
// Sertakan file koneksi database Anda saja (tanpa header.php)
include "config.php";

// Paksa browser untuk langsung mendownload halaman ini sebagai file Excel .xls
header("Content-type: application/vnd-ms-excel");
header("Content-Disposition: attachment; filename=Rekap_Disiplin_Siswa.xls");
header("Pragma: no-cache");
header("Expires: 0");
?>

<h2>Rekapitulasi Disiplin Siswa</h2>
<table border="1">
    <thead>
        <tr style="background-color: #334155; color: #ffffff;">
            <th>No</th>
            <th>NIS</th>
            <th>Nama Siswa</th>
            <th>Kelas</th>
            <th>Sakit</th>
            <th>Izin</th>
            <th>Alpa</th>
            <th>Bolos</th>
            <th>Dispensasi</th>
            <th>Izin Keluar</th>
            <th>Penghargaan</th>
            <th>Poin</th>
            <th>Pelanggaran</th>
            <th>Poin</th>
            <th>Kesiangan</th>
            <th>Menit</th>    
        </tr>
    </thead>
    <tbody>
        <?php
        $query = mysqli_query($con, "SELECT * FROM data_siswa");
        $no = 1;
        while ($tampil = mysqli_fetch_array($query)) {
            echo "<tr>
            <td style='text-align: center;'>$no</td>
            <td style='mso-number-format:\"\\@\"; text-align: center;'>".$tampil['nis']."</td>
            <td>".$tampil['nama_siswa']."</td>
            <td style='text-align: center;'>".$tampil['kelas']."</td>
            <td style='text-align: center;'>".$tampil['sakit']."</td>
            <td style='text-align: center;'>".$tampil['ijin']."</td>
            <td style='text-align: center;'>".$tampil['alfa']."</td>
            <td style='text-align: center;'>".$tampil['bolos']."</td>
            <td style='text-align: center;'>".$tampil['dispen']."</td>
            <td style='text-align: center;'>".$tampil['dispen2']."</td>
            <td style='text-align: center;'>".$tampil['penghargaan']."</td>
            <td style='text-align: center;'>".$tampil['poin_penghargaan']."</td>
            <td style='text-align: center;'>".$tampil['pelanggaran']."</td>
            <td style='text-align: center;'>".$tampil['poin_pelanggaran']."</td>
            <td style='text-align: center;'>".$tampil['kesiangan']."</td>
            <td style='text-align: center;'>".$tampil['menit']."</td>
            </tr>";
            $no++;
        }
        ?>
    </tbody>
</table>
