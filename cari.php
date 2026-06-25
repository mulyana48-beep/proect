<?php 
include "header.php";
include "config.php";
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Pencarian Siswa</title>
</head>
<body>
    <br><br>
    
    <!-- PERBAIKAN: Struktur Form dipusatkan menggunakan margin otomatis (bukan tag <center> kuno) -->
    <div style="display: flex; justify-content: center; width: 100%;">
        <form name="formcari" method="post" action="hasilcari.php" style="width: 100%; max-width: 420px; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;">
            <div style="display: flex; flex-direction: column; gap: 10px; background-color: #ffffff; padding: 24px; border-radius: 12px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05), 0 2px 4px -1px rgba(0,0,0,0.03); border: 1px solid #e2e8f0;">
                
                <!-- Label Input -->
                <label for="pencarian_siswa" style="font-size: 14px; font-weight: 600; color: #334155; margin-left: 2px;">
                    Nama Siswa
                </label>
                
                <!-- Area Input dan Tombol -->
                <div style="display: flex; gap: 10px; align-items: center;">
                    <!-- PERBAIKAN: Menggabungkan style modern bawaan Anda ke input baru agar serasi dengan tombol -->
                    <input type="text" id="pencarian_siswa" placeholder="Ketik nama siswa..." list="list_siswa" oninput="input_nama_datalist(this)" autocomplete="off" required
                           style="flex: 1; padding: 10px 14px; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 14px; outline: none; transition: all 0.2s;"
                           onfocus="this.style.borderColor='#3b82f6'; this.style.boxShadow='0 0 0 3px rgba(59, 130, 246, 0.15)';"
                           onblur="this.style.borderColor='#cbd5e1'; this.style.boxShadow='none';">
                    
                    <input type="hidden" name="id_siswa" id="id_siswa_hidden">

                    <input type="submit" name="SUBMIT" id="SUBMIT" value="Cari" 
                           style="padding: 10px 20px; background-color: #3b82f6; color: white; border: none; border-radius: 8px; font-weight: 600; font-size: 14px; cursor: pointer; transition: background-color 0.2s; white-space: nowrap;"
                           onmouseover="this.style.backgroundColor='#2563eb';"
                           onmouseout="this.style.backgroundColor='#3b82f6';">
                </div>
            </div>
        </form>
    </div>

    <!-- Elemen DataList ditaruh di luar form agar tidak mengganggu layout Flexbox -->
    <datalist id="list_siswa">
      <?php 
      $result = mysqli_query($con, "SELECT * FROM data_siswa ORDER BY nama_siswa ASC");   
      
      // Inisialisasi awal objek JavaScript
      echo '<script>const dataSiswaMap = {};</script>';
      $script_buffer = ""; 

      if ($result && mysqli_num_rows($result) > 0) {
          while ($row = mysqli_fetch_array($result)) {    
              $tampilan_opsi = $row['nama_siswa'] . " - " . $row['kelas'];
              echo '<option value="' . htmlspecialchars($tampilan_opsi) . '">';    
              
              $script_buffer .= 'dataSiswaMap["' . addslashes($tampilan_opsi) . '"] = "' . $row['id_rekap'] . '";' . "\n";
          }
      }      
      ?>   
    </datalist>

    <!-- Skrip ditaruh di paling bawah halaman untuk performa rendering HTML yang lebih optimal -->
    <script>
    // Cetak data map dari PHP
    <?php echo $script_buffer; ?>

    function input_nama_datalist(input) {
        const nilaiDiketik = input.value;
        const hiddenInput = document.getElementById('id_siswa_hidden');
        
        if (dataSiswaMap[nilaiDiketik] !== undefined) {
            hiddenInput.value = dataSiswaMap[nilaiDiketik];
        } else {
            hiddenInput.value = "";
        }
    }
    </script>
</body>
</html>
