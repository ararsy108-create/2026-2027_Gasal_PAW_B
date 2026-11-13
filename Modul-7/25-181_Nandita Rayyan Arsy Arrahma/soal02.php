<?php
include "koneksi.php";

$labels = [];
$data_total = [];

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
        $labels[] = $r['tgl'];
        $data_total[] = $r['total_harian'];
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Soal 2 - Grafik Penjualan</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
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
                        backgroundColor: 'rgba(137, 207, 240, 0.7)',
                        borderColor: '#89CFF0',
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
    <?php elseif (isset($_GET['tanggal_mulai'])): ?>
        <p>Tidak ada data transaksi pada rentang tanggal tersebut.</p>
    <?php endif; ?>

</body>
</html>