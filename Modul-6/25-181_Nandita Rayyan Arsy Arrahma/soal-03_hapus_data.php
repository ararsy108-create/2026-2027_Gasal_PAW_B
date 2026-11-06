<?php
    include 'koneksi.php';

    if (isset($_GET['action']) && $_GET['action'] == 'hapus') {
        $barang_id = $_GET['id'];

        // pengecekan apakah barang_id sudah pernah dipakai
        $cek_query = "SELECT COUNT(*) AS total FROM transaksi_detail WHERE barang_id = '$barang_id'";
        $cek_result = mysqli_query($conn, $cek_query);
        $data = mysqli_fetch_assoc($cek_result);

        if ($data['total'] > 0) {
            // jika barang sudah dipakai, cegah hapus dan tampilkan notifikasi
            echo "<script>
                    alert('Gagal menghapus! Barang ini masih digunakan pada transaksi.');
                    window.location.href='soal-03_hapus_data.php';
                  </script>";
            exit;
        } else {
            // jika belum dipakai, hapus dari tabel barang
            $delete_query = "DELETE FROM barang WHERE id = '$barang_id'";
            if (mysqli_query($conn, $delete_query)) {
                echo "<script>
                        alert('Data barang berhasil dihapus!');
                        window.location.href='soal-03_hapus_data.php';
                      </script>";
                exit;
            } else {
                echo "Error: " . mysqli_error($conn);
            }
        }
    }
    $barang = mysqli_query($conn, "SELECT * FROM barang");
?>

<!DOCTYPE html>
<html>
    <head></head>
    <body>
        <h2>Daftar Barang</h2>
        <table border="1" cellpadding="8" cellspacing="0">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Kode Barang</th>
                    <th>Nama Barang</th>
                    <th>Harga</th>
                    <th>Stok</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php while($row = mysqli_fetch_assoc($barang)): ?>
                <tr>
                    <td><?= $row['id']; ?></td>
                    <td><?= $row['kode_barang']; ?></td>
                    <td><?= $row['nama_barang']; ?></td>
                    <td>Rp <?= number_format($row['harga'], 0, ',', '.'); ?></td>
                    <td><?= $row['stok']; ?></td>
                    <td>
                        <a href="soal-03_hapus_data.php?action=hapus&id=<?= $row['id']; ?>" 
                           onclick="return confirm('Apakah Anda yakin ingin menghapus barang ini?')">Hapus</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </body>
</html>