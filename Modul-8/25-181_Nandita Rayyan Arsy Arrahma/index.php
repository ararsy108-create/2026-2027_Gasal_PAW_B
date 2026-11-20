<?php 
    include 'soal2_navbar.php'; 
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Home - Sistem Penjualan</title>
    </head>
    <body style="font-family: Arial, sans-serif; margin: 0;">
        <div style="padding: 24px;">
            <h2>Selamat Datang, <?= htmlspecialchars($_SESSION['nama']) ?></h2>
            <p>Level akses saat ini: <strong><?= (int)$_SESSION['level'] === 1 ? 'Owner (Level 1)' : 'Kasir (Level 2)' ?></strong></p>
        </div>
    </body>
</html>