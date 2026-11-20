<?php
    include 'soal2_navbar.php';
    include 'koneksi.php';

    // Cek level akses (hanya level 1 / Owner yang boleh akses Data Master)
    if ((int) ($_SESSION['level'] ?? 0) !== 1) {
        echo "<div style='padding:24px;font-family:Arial,sans-serif;'>Akses ditolak. Halaman ini khusus Owner (Level 1).</div>";
        exit;
    }

    // Sesuaikan nama kolom dengan tabel 'barang' di database kamu
    $query_barang = mysqli_query($conn, "SELECT kode_barang, nama_barang, harga, stok FROM barang");
    if (!$query_barang) {
        die("Query barang gagal: " . mysqli_error($conn));
    }
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Data Barang</title>
        <style>
            body { 
                font-family: Arial, sans-serif; 
                margin: 0; 
            }
            .konten { 
                padding: 24px; 
            }
            table { 
                width: 100%; 
                border-collapse: collapse; 
                margin-top: 12px; 
            }
            th, td { 
                border: 1px solid #ddd; 
                padding: 10px; 
                text-align: left; 
            }
            th { 
                background-color: #8d6e63; 
            }
        </style>
    </head>
    <body>
        <div class="konten">
            <h2>Data Barang</h2>
            <table>
                <thead>
                    <tr>
                        <th>Kode Barang</th>
                        <th>Nama Barang</th>
                        <th>Harga Satuan</th>
                        <th>Stok</th>
                        <th>Nilai Stok</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (mysqli_num_rows($query_barang) > 0): ?>
                        <?php while ($b = mysqli_fetch_assoc($query_barang)): ?>
                            <?php $nilai_stok = $b['harga'] * $b['stok']; ?>
                            <tr>
                                <td><?= htmlspecialchars($b['kode_barang']) ?></td>
                                <td><?= htmlspecialchars($b['nama_barang']) ?></td>
                                <td>Rp <?= number_format($b['harga'], 0, ',', '.') ?></td>
                                <td><?= (int) $b['stok'] ?></td>
                                <td>Rp <?= number_format($nilai_stok, 0, ',', '.') ?></td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5">Data barang kosong.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </body>
</html>