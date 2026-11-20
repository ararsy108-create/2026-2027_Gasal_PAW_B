<?php
    include 'soal2_navbar.php';
    include 'koneksi.php';

    if ((int) ($_SESSION['level'] ?? 0) !== 1) {
        echo "<div style='padding:24px;font-family:Arial,sans-serif;'>Akses ditolak. Halaman ini khusus Owner (Level 1).</div>";
        exit;
    }

    $query_supplier = mysqli_query($conn, "SELECT nama, telp, alamat FROM suplier");
    if (!$query_supplier) {
        die("Query suplier gagal: " . mysqli_error($conn));
    }
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Data Supplier</title>
        <style>
            body { 
                font-family: Arial, sans-serif;
                margin: 0; }
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
                background-color: #ffd700; 
            }
        </style>
    </head>
    <body>
        <div class="konten">
            <h2>Data Supplier</h2>
            <table>
                <thead>
                    <tr>
                        <th>Nama Supplier</th>
                        <th>No. Telp</th>
                        <th>Alamat</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (mysqli_num_rows($query_supplier) > 0): ?>
                        <?php while ($s = mysqli_fetch_assoc($query_supplier)): ?>
                            <tr>
                                <td><?= htmlspecialchars($s['nama']) ?></td>
                                <td><?= htmlspecialchars($s['telp']) ?></td>
                                <td><?= htmlspecialchars($s['alamat']) ?></td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="3">Data supplier kosong.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </body>
</html>