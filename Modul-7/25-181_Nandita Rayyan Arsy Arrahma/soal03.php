<?php
include "koneksi.php";

$labels = [];
$data_total = [];
$rekap = [];
$total_pendapatan = 0;
$total_pelanggan = 0;

if (!empty($_GET['tanggal_mulai']) && !empty($_GET['tanggal_selesai'])) {
    $mulai = $_GET['tanggal_mulai'];
    $selesai = $_GET['tanggal_selesai'];

    // 1. Query Agregasi Total Pendapatan Harian
    $q = "SELECT DATE(waktu_transaksi) AS tgl, SUM(total) AS total_harian 
          FROM transaksi 
          WHERE waktu_transaksi BETWEEN '$mulai' AND '$selesai' 
          GROUP BY DATE(waktu_transaksi) 
          ORDER BY tgl ASC";
    
    $res = mysqli_query($conn, $q);
    while ($r = mysqli_fetch_assoc($res)) {
        $labels[] = $r['tgl'];
        $data_total[] = $r['total_harian'];
        $rekap[] = $r;
        $total_pendapatan += $r['total_harian'];
    }

    // 2. Query Jumlah Pelanggan Unik
    $qp = "SELECT COUNT(DISTINCT pelanggan_id) AS jml 
           FROM transaksi 
           WHERE waktu_transaksi BETWEEN '$mulai' AND '$selesai'";
    $rp = mysqli_fetch_assoc(mysqli_query($conn, $qp));
    $total_pelanggan = $rp['jml'] ?? 0;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Soal 3 - Rekapitulasi dan Ringkasan Total</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        table { border-collapse: collapse; width: 100%; margin-top: 15px; }
        th, td { border: 1px solid #000; padding: 8px; text-align: left; }
        th { background-color: #89CFF0; }
        .tampilkan { background-color: #98FF98; padding: 5px 12px; cursor: pointer; }
        .card-total {
            margin-top: 15px;
            padding: 12px;
            border: 1px solid #89CFF0;
            background-color: #f4faff;
            font-size: 16px;
        }
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

    <?php if (!empty($labels)): ?>
        <h2>Grafik Penjualan</h2>
        <div style="width: 100%; max-width: 600px; height: 300px;">
            <canvas id="myChart"></canvas>
        </div>

        <script>
            const ctx = document.getElementById('myChart').getContext('2d');
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: <?= json_encode($labels); ?>,
                    datasets: [{
                        label: 'Total Pendapatan Harian (Rp)',
                        data: <?= json_encode($data_total); ?>,
                        backgroundColor: 'rgba(54, 162, 235, 0.5)',
                        borderColor: 'rgba(54, 162, 235, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: { beginAtZero: true }
                    }
                }
            });
        </script>

        <h2>Rekapitulasi Penjualan</h2>
        <table>
            <tr>
                <th>No</th>
                <th>Total Pendapatan</th>
                <th>Tanggal Transaksi</th>
            </tr>
            <?php $no = 1; foreach ($rekap as $row): ?>
            <tr>
                <td><?= $no++; ?></td>
                <td>Rp. <?= number_format($row['total_harian'], 0, ',', '.'); ?></td>
                <td><?= $row['tgl']; ?></td>
            </tr>
            <?php endforeach; ?>
        </table>

        <div class="card-total">
            <b>Jumlah Pelanggan:</b> <?= $total_pelanggan; ?> Orang | 
            <b>Total Pendapatan:</b> Rp. <?= number_format($total_pendapatan, 0, ',', '.'); ?>
        </div>
    <?php elseif (isset($_GET['tanggal_mulai'])): ?>
        <p>Tidak ada data transaksi pada rentang tanggal tersebut.</p>
    <?php endif; ?>

</body>
</html>