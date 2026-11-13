<?php
include "koneksi.php";

$rekap = [];

if (!empty($_GET['tanggal_mulai']) && !empty($_GET['tanggal_selesai'])) {
    $mulai = $_GET['tanggal_mulai'];
    $selesai = $_GET['tanggal_selesai'];

    $q = "SELECT DATE(waktu_transaksi) AS tgl, SUM(total) AS total_harian 
          FROM transaksi 
          WHERE waktu_transaksi BETWEEN '$mulai' AND '$selesai' 
          GROUP BY DATE(waktu_transaksi) 
          ORDER BY tgl ASC";
    
    $res = mysqli_query($conn, $q);
    while ($r = mysqli_fetch_assoc($res)) {
        $rekap[] = $r;
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Soal 1 - Filter Laporan Penjualan</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        table { border-collapse: collapse; width: 100%; margin-top: 15px; }
        th, td { border: 1px solid #000; padding: 8px; text-align: left; }
        th { background-color: #89CFF0; }
        .tampilkan { background-color: #98FF98; padding: 5px 12px; cursor: pointer; }
    </style>
</head>
<body>

    <h1>Filter Laporan Penjualan</h1>
    <form method="GET">
        <label>Dari:</label> 
        <input type="date" name="tanggal_mulai" value="<?= $_GET['tanggal_mulai'] ?? '' ?>" required>
        <label>Sampai:</label> 
        <input type="date" name="tanggal_selesai" value="<?= $_GET['tanggal_selesai'] ?? '' ?>" required>
        <button class="tampilkan" type="submit">Tampilkan</button>
    </form>
    <hr>

    <?php if (!empty($rekap)): ?>
        <h3>Hasil Filter Transaksi (<?= $_GET['tanggal_mulai']; ?> s.d <?= $_GET['tanggal_selesai']; ?>)</h3>
        <table>
            <tr>
                <th>Tanggal Transaksi</th>
                <th>Total Pendapatan Harian</th>
            </tr>
            <?php foreach ($rekap as $row): ?>
            <tr>
                <td><?= $row['tgl']; ?></td>
                <td>Rp. <?= number_format($row['total_harian'], 0, ',', '.'); ?></td>
            </tr>
            <?php endforeach; ?>
        </table>
    <?php elseif (isset($_GET['tanggal_mulai'])): ?>
        <p>Tidak ada data transaksi pada rentang tanggal tersebut.</p>
    <?php endif; ?>

</body>
</html>