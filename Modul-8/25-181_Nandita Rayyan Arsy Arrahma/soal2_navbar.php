<?php
	if (session_status() === PHP_SESSION_NONE) {
	    session_start();
	}

	// Jika belum login, tidak boleh mengakses menu / halaman
	if (!isset($_SESSION['login']) || $_SESSION['login'] !== true) {
	    header("Location: soal1_login.php");
	    exit;
	}

	$level = (int) ($_SESSION['level'] ?? 0);
	$namaUser = htmlspecialchars($_SESSION['nama'] ?? 'User');
?>
<style>
    .navbar {
        background-color: #0b4f8a;
        color: #fff;
        padding: 0 20px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        font-family: Arial, sans-serif;
    }
    .nav-left {
        display: flex;
        align-items: center;
        gap: 18px;
    }
    .brand {
        font-weight: bold;
        font-size: 16px;
        padding: 14px 0;
        color: #fff;
        text-decoration: none;
    }
    .navbar a, .dropbtn {
        color: #fff;
        text-decoration: none;
        font-size: 14px;
        padding: 14px 6px;
        display: inline-block;
        background: none;
        border: none;
        cursor: pointer;
    }
    .dropdown {
        position: relative;
        display: inline-block;
    }
    .dropdown-content {
        display: none;
        position: absolute;
        background-color: #fff;
        min-width: 170px;
        box-shadow: 0 4px 8px rgba(0,0,0,0.15);
        z-index: 10;
        border-radius: 4px;
        overflow: hidden;
    }
    .dropdown-content a {
        color: #333;
        padding: 10px 14px;
        display: block;
    }
    .dropdown-content a:hover {
        background-color: #f0f4f8;
    }
    .dropdown:hover .dropdown-content {
        display: block;
    }
    .nav-right {
        position: relative;
        display: flex;
        align-items: center;
        gap: 12px;
    }
    .logout-link {
        background: #c0392b !important;
        padding: 6px 12px !important;
        border-radius: 4px;
        font-size: 13px !important;
    }
</style>

<nav class="navbar">
    <div class="nav-left">
        <a href="index.php" class="brand">Sistem Penjualan</a>
        <a href="index.php">Home</a>

        <?php if ($level === 1): ?>
            <!-- Level 1 (Owner): Home, Data Master, Transaksi, Laporan -->
            <div class="dropdown">
                <span class="dropbtn">Data Master &#9662;</span>
                <div class="dropdown-content">
                    <a href="data_barang.php">Data Barang</a>
                    <a href="data_supplier.php">Data Supplier</a>
                    <a href="data_pelanggan.php">Data Pelanggan</a>
                    <a href="data_user.php">Data User</a>
                </div>
            </div>
        <?php endif; ?>

        <a href="transaksi.php">Transaksi</a>
        <a href="laporan.php">Laporan</a>
    </div>

    <div class="nav-right">
        <span><?= $namaUser ?></span>
        <a href="soal3_logout.php" class="logout-link">Logout</a>
    </div>
</nav>