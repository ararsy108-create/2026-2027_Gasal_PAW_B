<?php
	include 'soal2_navbar.php';
	include 'koneksi.php';

	if ((int) ($_SESSION['level'] ?? 0) !== 1) {
	    echo "<div style='padding:24px;font-family:Arial,sans-serif;'>Akses ditolak. Halaman ini khusus Owner (Level 1).</div>";
	    exit;
	}

	$query_pelanggan = mysqli_query($conn, "SELECT id, nama, jenis_kelamin, telp, alamat FROM pelanggan");
	if (!$query_pelanggan) {
	    die("Query pelanggan gagal: " . mysqli_error($conn));
	}
?>
<!DOCTYPE html>
<html>
	<head>
	    <title>Data Pelanggan</title>
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
                background-color: #ff4da6; 
            }
	    </style>
	</head>
	<body>
	    <div class="konten">
	        <h2>Data Pelanggan</h2>
	        <table>
	            <thead>
	                <tr>
	                    <th>ID</th>
	                    <th>Nama Pelanggan</th>
	                    <th>Jenis Kelamin</th>
	                    <th>No. Telp</th>
	                    <th>Alamat</th>
	                </tr>
	            </thead>
	            <tbody>
	                <?php if (mysqli_num_rows($query_pelanggan) > 0): ?>
	                    <?php while ($p = mysqli_fetch_assoc($query_pelanggan)): ?>
	                        <tr>
	                            <td><?= htmlspecialchars($p['id']) ?></td>
	                            <td><?= htmlspecialchars($p['nama']) ?></td>
	                            <td><?= $p['jenis_kelamin'] === 'L' ? 'Laki-laki' : 'Perempuan' ?></td>
	                            <td><?= htmlspecialchars($p['telp']) ?></td>
	                            <td><?= htmlspecialchars($p['alamat']) ?></td>
	                        </tr>
	                    <?php endwhile; ?>
	                <?php else: ?>
	                    <tr>
	                        <td colspan="5">Data pelanggan kosong.</td>
	                    </tr>
	                <?php endif; ?>
	            </tbody>
	        </table>
	    </div>
	</body>
</html>