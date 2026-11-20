<?php
include 'soal2_navbar.php';
include 'koneksi.php';

if ((int) ($_SESSION['level'] ?? 0) !== 1) {
    echo "<div style='padding:24px;font-family:Arial,sans-serif;'>Akses ditolak. Halaman ini khusus Owner (Level 1).</div>";
    exit;
}

$query_user = mysqli_query($conn, "SELECT username, nama, alamat, hp, level FROM usser");
if (!$query_user) {
    die("Query usser gagal: " . mysqli_error($conn));
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Data User</title>
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
            background-color: #2ecc71; 
        }
    </style>
</head>
<body>
    <div class="konten">
        <h2>Data User</h2>
        <table>
            <thead>
                <tr>
                    <th>Username</th>
                    <th>Nama</th>
                    <th>Alamat</th>
                    <th>No. HP</th>
                    <th>Level</th>
                </tr>
            </thead>
            <tbody>
                <?php if (mysqli_num_rows($query_user) > 0): ?>
                    <?php while ($u = mysqli_fetch_assoc($query_user)): ?>
                        <tr>
                            <td><?= htmlspecialchars($u['username']) ?></td>
                            <td><?= htmlspecialchars($u['nama']) ?></td>
                            <td><?= htmlspecialchars($u['alamat']) ?></td>
                            <td><?= htmlspecialchars($u['hp']) ?></td>
                            <td><?= (int) $u['level'] === 1 ? 'Owner (Level 1)' : 'Kasir (Level 2)' ?></td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5">Data user kosong.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>
</html>