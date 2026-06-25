<?php 
include "header.php";
include "config.php";
    $query = "SELECT DISTINCT kelas FROM data_siswa WHERE kelas IS NOT NULL AND kelas != '' ORDER BY kelas ASC";
    $result = mysqli_query($con, $query);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Filter Kelas</title>
    <style>
        .filter-container {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 40px 20px;
            box-sizing: border-box;
        }

        .filter-card {
            background: #ffffff;
            width: 100%;
            max-width: 400px;
            padding: 30px;
            border-radius: 16px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08);
            border: 1px solid #eef2f5;
        }

        .filter-title {
            font-size: 20px;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 24px;
            text-align: center;
        }

        .form-group {
            margin-bottom: 20px;
        }
        .form-label {
            display: block;
            font-size: 14px;
            font-weight: 600;
            color: #64748b;
            margin-bottom: 8px;
        }

        .form-select {
            width: 100%;
            padding: 12px 16px;
            font-size: 16px;
            color: #334155;
            background-color: #f8fafc;
            border: 2px solid #e2e8f0;
            border-radius: 10px;
            outline: none;
            transition: all 0.2s ease;
            box-sizing: border-box;
            cursor: pointer;
        }
        .form-select:focus {
            border-color: #3b82f6;
            background-color: #ffffff;
            box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.1);
        }

        .btn-submit {
            width: 100%;
            padding: 14px;
            font-size: 16px;
            font-weight: 600;
            color: #ffffff;
            background-color: #3b82f6;
            border: none;
            border-radius: 10px;
            cursor: pointer;
            transition: background-color 0.2s ease;
            box-sizing: border-box;
        }
        .btn-submit:hover {
            background-color: #2563eb;
        }
        .btn-submit:active {
            background-color: #1d4ed8;
        }
    </style>
</head>
<body>

<div class="filter-container">
    <div class="filter-card">
        <div class="filter-title">Filter Data Kelas</div>
        
        <form name="formfilter" method="post" action="hasilfilter.php">
            
            <div class="form-group">
                <select name="kelas" id="kelas" class="form-select">
    <option value="">-- Pilih Kelas --</option>
    
    <?php
    $query = "SELECT DISTINCT kelas FROM data_siswa WHERE kelas IS NOT NULL AND kelas != '' ORDER BY kelas ASC";
    $result = mysqli_query($con, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $nama_kelas = htmlspecialchars($row['kelas']);
            
            echo "<option value='" . $nama_kelas . "'>" . $nama_kelas . "</option>";
        }
    } else {
        echo "<option value=''>Tidak ada data kelas</option>";
    }
    ?>
    
                </select>
            </div>
            
            <!-- Tombol -->
            <button type="submit" name="SUBMIT" id="SUBMIT" class="btn-submit">
                Tampilkan Data
            </button>

        </form>
    </div>
</div>

</body>
</html>
