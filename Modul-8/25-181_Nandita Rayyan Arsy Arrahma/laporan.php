<?php
    include 'soal2_navbar.php';
    include 'koneksi.php';

    $tgl_awal  = $_GET['tgl_awal'] ?? date('Y-m-01');
    $tgl_akhir = $_GET['tgl_akhir'] ?? date('Y-m-d');

    // Rekap data barang yang tersedia di database
    $query_barang = mysqli_query($conn, "SELECT kode_barang, nama_barang, harga, stok FROM barang");
    if (!$query_barang) {
        die("Query laporan barang gagal: " . mysqli_error($conn));
    }

    $total_item = 0;
    $total_nilai_stok = 0;
    $data_barang = [];

    while ($row = mysqli_fetch_assoc($query_barang)) {
        $nilai = (float) $row['harga'] * (int) $row['stok'];
        $row['nilai_stok'] = $nilai;
        $total_item += (int) $row['stok'];
        $total_nilai_stok += $nilai;
        $data_barang[] = $row;
    }
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Laporan - Sistem Penjualan</title>
        <style>
            body { 
                font-family: Arial, sans-serif; 
                margin: 0; 
            }
            .konten { 
                padding: 24px; 
            }
            .filter-box { 
                display: flex; 
                flex-wrap: wrap; 
                gap: 12px; 
                align-items: end; 
                margin-bottom: 18px; 
            }
            label { 
                display: block; 
                font-size: 13px; 
                margin-bottom: 4px; 
            }
            input[type="date"] { 
                padding: 7px 10px; 
                border: 1px solid #ccc; 
                border-radius: 4px; 
            }
            button { 
                padding: 8px 14px; 
                border: none; 
                border-radius: 4px; 
                cursor: pointer; 
                background: #0b4f8a; 
                color: #fff; 
            }
            button.btn-print { 
                background: #27ae60; 
            }
            .ringkasan { 
                display: flex; 
                gap: 16px; 
                margin-bottom: 18px; 
                flex-wrap: wrap; 
            }
            .kartu { 
                background: #f4f6f9; 
                padding: 14px 18px; 
                border-radius: 6px; 
                border-left: 4px solid #0b4f8a; 
                min-width: 200px; 
            }
            .kartu span { 
                display: block; 
                font-size: 13px; 
                color: #555; 
            }
            .kartu strong { 
                font-size: 18px; 
                color: #111; 
            }
            table { 
                width: 100%; 
                border-collapse: collapse; 
                margin-top: 10px; 
            }
            th, td { 
                border: 1px solid #ddd; 
                padding: 10px; 
                text-align: left; 
            }
            th { 
                background-color: #f2f4f8; 
            }
            @media print {
                .navbar, .filter-box { 
                    display: none !important; 
                }
            }
        </style>
    </head>
    <body>
        <div class="konten">
            <h2>Laporan Penjualan & Stok Barang</h2>
            <p style="font-size: 13px; color: #555;">
                Periode: <strong><?= htmlspecialchars($tgl_awal) ?></strong> s/d <strong><?= htmlspecialchars($tgl_akhir) ?></strong>
            </p>

            <form method="GET" action="" class="filter-box">
                <div>
                    <label for="tgl_awal">Tanggal Awal</label>
                    <input type="date" id="tgl_awal" name="tgl_awal" value="<?= htmlspecialchars($tgl_awal) ?>">
                </div>
                <div>
                    <label for="tgl_akhir">Tanggal Akhir</label>
                    <input type="date" id="tgl_akhir" name="tgl_akhir" value="<?= htmlspecialchars($tgl_akhir) ?>">
                </div>
                <div>
                    <button type="submit">Tampilkan</button>
                    <button type="button" class="btn-print" onclick="window.print()">Cetak Laporan</button>
                </div>
            </form>

            <div class="ringkasan">
                <div class="kartu">
                    <span>Total Jenis Barang</span>
                    <strong><?= count($data_barang) ?> Item</strong>
                </div>
                <div class="kartu">
                    <span>Total Seluruh Stok</span>
                    <strong><?= $total_item ?> Unit</strong>
                </div>
                <div class="kartu">
                    <span>Total Nilai Stok</span>
                    <strong>Rp <?= number_format($total_nilai_stok, 0, ',', '.') ?></strong>
                </div>
            </div>

            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Kode Barang</th>
                        <th>Nama Barang</th>
                        <th>Harga Satuan</th>
                        <th>Sisa Stok</th>
                        <th>Nilai Stok</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($data_barang)): ?>
                        <?php foreach ($data_barang as $i => $b): ?>
                            <tr>
                                <td><?= $i + 1 ?></td>
                                <td><?= htmlspecialchars($b['kode_barang']) ?></td>
                                <td><?= htmlspecialchars($b['nama_barang']) ?></td>
                                <td>Rp <?= number_format($b['harga'], 0, ',', '.') ?></td>
                                <td><?= (int) $b['stok'] ?></td>
                                <td>Rp <?= number_format($b['nilai_stok'], 0, ',', '.') ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6">Tidak ada data laporan.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </body>
</html>