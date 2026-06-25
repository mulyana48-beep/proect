<?php 
session_start();
if (!isset($_SESSION["username"])) {
    header("Location: index.php");
    exit();
}    
$file_json = file_get_contents('pengaturan.json');
$data = json_decode($file_json, true); 

if (!$data) {
    $data = [
        'nama_sekolah' => '-',
        'tahun' => '-',
        'kontak' => '-'
    ];
}
include "config.php";
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/ico" href="images/favicon.ico">
    <title>Aplikasi Disiplin <?php echo htmlspecialchars($data['nama_sekolah']); ?></title>
    
    <style>
        * {
            box-sizing: border-box; 
            margin: 0; 
            padding: 0; 
        }
        body { 
            font-family: sans-serif; 
            background-color: #f4f4f4; 
            padding-top: 60px; 
            color: #333; 
        }
        
        .navbar-custom {
            position: fixed; 
            top: 0; 
            left: 0; 
            right: 0; 
            height: 55px;
            background-color: #1a1a1a; 
            border-bottom: 3px solid #FF9100;
            display: flex; 
            align-items: center; 
            justify-content: space-between;
            padding: 0 20px; 
            z-index: 1000; 
            box-shadow: 0 2px 5px rgba(0,0,0,0.2); 
        }
        .brand img { 
            height: 35px; 
            width: auto; 
            vertical-align: middle; 
        }
        .brand-text {
            color: #ccff00; 
            font-size: 16px; 
            font-weight: bold; 
            margin-left: 10px; 
            vertical-align: middle; 
            display: inline-block; 
        }
        .form-group textarea {
            width: 100%;
            padding: 10px 14px;
            border: 1px solid #cbd5e0;
            border-radius: 6px;
            font-size: 15px;
            color: #2d3748;
            background-color: #fff; 
            box-sizing: border-box;
            transition: border-color 0.2s, box-shadow 0.2s; 
        }

        .nav-menu { 
            display: flex; 
            list-style: none; 
            gap: 15px; 
            align-items: center; 
        }
        .nav-menu a {
            color: #e0e0e0; 
            text-decoration: none; 
            font-size: 14px; 
            font-weight: bold;
            padding: 8px 12px; 
            border-radius: 4px; 
            display: inline-block; 
        }
        .nav-menu a:hover { 
            background-color: #FF9100; 
            color: #fff; 
        }
        .logout-btn a { 
            background-color: #d9534f; 
            color: #fff; 
        }
        .logout-btn a:hover { 
            background-color: #c9302c; 
        }
        
        .menu-toggle { 
            display: none; 
            background: none; 
            border: none; 
            color: #fff; 
            font-size: 20px; 
            cursor: pointer; 
        }

        @media (max-width: 850px) {
            .menu-toggle { 
                display: block; 
            }
            .nav-menu {
                display: none; 
                position: absolute; top: 55px; left: 0; right: 0;
                background-color: #222; 
                flex-direction: column; 
                padding: 10px;
                gap: 5px; 
                box-shadow: 0 4px 6px rgba(0,0,0,0.2); 
            }
            .nav-menu.active { display: flex; }
            .nav-menu li { width: 100%; text-align: center; }
            .nav-menu a { display: block; padding: 10px; border-bottom: 1px solid #333; }
            .logout-btn a { margin-top: 5px; }
        }

        .tree, .tree ul, .tree li { margin: 0; padding: 0; }
        .tree ul { list-style: none; margin-left: 15px; position: relative; }
        .tree ul:before { 
            content: ""; display: block; width: 0; position: absolute; 
            inset: 0 auto 0 0; border-left: 1px solid #ccc; 
        }
        .tree li { padding: 0 10px; line-height: 28px; font-weight: bold; color: #369; position: relative; }
        .tree ul li:before { 
            content: ""; display: block; width: 8px; height: 0; 
            border-top: 1px solid #ccc; position: absolute; top: 14px; left: 0; 
        }
        .tree ul li:last-child:before { background: #f4f4f4; height: auto; bottom: 0; }
        .indicator { margin-right: 5px; cursor: pointer; user-select: none; }
    </style>
</head>
<body>

    <!-- NAVBAR -->
    <nav class="navbar-custom">
        <div class="brand">
            <a href="home.php" style="text-decoration: none;">
                <img src="images/logo1.png" alt="brandlogo" style="background: transparent !important; background-color: transparent !important;" onerror="this.style.display='none'; this.nextElementSibling.style.marginLeft='0';">
                <span class="brand-text"><b>APLIKASI DISIPLIN</b><br><small><?php echo strtoupper(htmlspecialchars($data['nama_sekolah'])); ?></small></span>
            </a>
        </div>

        <button class="menu-toggle" onclick="toggleMenu()">☰</button>
        
        <ul class="nav-menu" id="navMenu">
            <li><a href="form_catat.php"> Input</a></li>
            <li><a href="absen_kelas.php"> Absensi Kelas</a></li>
            <li><a href="absentercatat.php">Catatan</a></li>
            <li><a href="rekap.php">Rekapitulasi</a></li>
            <li><a href="filter.php">Data Perkelas</a></li>
            <li><a href="cari.php">Cari Data</a></li>
            <?php if (isset($_SESSION['level']) && $_SESSION['level'] == 'admin') : ?>
                <li><a href="admin.php">Dashboard</a></li>
            <?php endif; ?>
            <li class="logout-btn"><a href="logout.php">Logout</a></li>
        </ul>
    </nav>

    <!-- Vanilla Javascript -->
    <script>
        function toggleMenu() {
            var menu = document.getElementById("navMenu");
            menu.classList.toggle("active");
        }

        document.addEventListener("DOMContentLoaded", function() {
            var trees = document.querySelectorAll('.tree');
            trees.forEach(function(tree) {
                var branches = tree.querySelectorAll('li');
                branches.forEach(function(branch) {
                    var sublist = branch.querySelector('ul');
                    if (sublist) {
                        sublist.style.display = 'none';
                        
                        var indicator = document.createElement('span');
                        indicator.className = 'indicator';
                        indicator.innerText = '[+] ';
                        branch.insertBefore(indicator, branch.firstChild);
                        
                        var toggleTarget = function(e) {
                            if (e.target === branch || e.target === indicator) {
                                if (sublist.style.display === 'none') {
                                    sublist.style.display = 'block';
                                    indicator.innerText = '[-] ';
                                } else {
                                    sublist.style.display = 'none';
                                    indicator.innerText = '[+] ';
                                }
                                e.stopPropagation();
                            }
                        };
                        
                        branch.addEventListener('click', toggleTarget);
                    }
                });
            });
        });
    </script>
