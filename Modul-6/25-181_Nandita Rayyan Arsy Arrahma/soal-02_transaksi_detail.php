<?php
    include 'koneksi.php';

    $transaksi_id = isset($_GET['id']) ? $_GET['id'] : 1;

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $transaksi_id = $_POST['transaksi_id'];
        $barang_id    = $_POST['barang_id'];
        $qty          = $_POST['qty'];

        // mengambil harga barang dari tabel barang
        $query_harga = mysqli_query($conn, "SELECT harga FROM barang WHERE id = '$barang_id'");
        $data_harga  = mysqli_fetch_assoc($query_harga);
        $harga        = $data_harga['harga'];

        // menyimpan item ke tabel transaksi_detail
        $query_insert = "INSERT INTO transaksi_detail (transaksi_id, barang_id, harga, qty) 
                         VALUES ('$transaksi_id', '$barang_id', '$harga', '$qty')";
        
        if (mysqli_query($conn, $query_insert)) {
            echo "<script>
                    alert('Item berhasil ditambahkan ke transaksi!');
                    window.location.href='soal-02_transaksi_detail.php?id=$transaksi_id';
                  </script>";
            exit;
        } else {
            echo "<script>
                    alert('Gagal menyimpan detail transaksi: " . mysqli_error($conn) . "');
                  </script>";
        }
    }

    // master transaksi
    $query_master = "SELECT t.*, p.nama AS nama_pelanggan 
                     FROM transaksi t 
                     LEFT JOIN pelanggan p ON t.pelanggan_id = p.id 
                     WHERE t.id = '$transaksi_id'";
    $master = mysqli_fetch_assoc(mysqli_query($conn, $query_master));

    // list baran yang di drop down
    $query_barang = "SELECT b.id, b.nama_barang, b.harga, 
                            IF(td.barang_id IS NOT NULL, 1, 0) AS is_selected
                     FROM barang b
                     LEFT JOIN transaksi_detail td 
                            ON b.id = td.barang_id AND td.transaksi_id = '$transaksi_id'";
    $list_barang = mysqli_query($conn, $query_barang);

    // list item yang sudah ada
    $query_detail = "SELECT td.*, b.nama_barang, (td.harga * td.qty) AS subtotal
                     FROM transaksi_detail td
                     JOIN barang b ON td.barang_id = b.id
                     WHERE td.transaksi_id = '$transaksi_id'";
    $list_detail = mysqli_query($conn, $query_detail);
?>

<!DOCTYPE html>
<html>
    <head></head>
    <body>
        <h2>Master Transaksi (<?php echo $master['id']; ?>)</h2>
        <p>Tanggal: <?php echo $master['waktu_transaksi']; ?> | Pelanggan: <?php echo $master['nama_pelanggan']; ?></p>
        <p><strong>Total Transaksi: Rp <?php echo number_format($master['total'], 0, ',', '.'); ?></strong></p>
        <hr>
        <h3>Form Tambah Detail Transaksi</h3>
        <form action="soal-02_transaksi_detail.php?id=<?php echo $transaksi_id; ?>" method="POST">
            <input type="hidden" name="transaksi_id" value="<?php echo $transaksi_id; ?>">
            <label>Pilih Barang:</label>
            <select name="barang_id" required>
                <option value="">-- Pilih Barang --</option>
                <?php while($b = mysqli_fetch_assoc($list_barang)): ?>
                <option value="<?php echo $b['id']; ?>" <?php echo ($b['is_selected'] == 1) ? 'disabled style="color: red;"' : ''; ?>>
                    <?php echo $b['nama_barang']; ?> - Rp <?php echo number_format($b['harga']); ?>
                    <?php echo ($b['is_selected'] == 1) ? ' (Sudah dipilih)' : ''; ?>
                </option>
                <?php endwhile; ?>
            </select>
            <br><br>
            <label>Qty:</label>
            <input type="number" name="qty" min="1" value="1" required>
            <br><br>
            <button type="submit">Tambah Item</button>
        </form>
        <br><hr>
        <h3>Rincian Barang Transaksi Ini</h3>
        <table border="1" cellpadding="8" cellspacing="0">
            <thead>
                <tr>
                    <th>Nama Barang</th>
                    <th>Harga Satuan</th>
                    <th>Qty</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
                <?php while($d = mysqli_fetch_assoc($list_detail)): ?>
                <tr>
                    <td><?php echo $d['nama_barang']; ?></td>
                    <td>Rp <?php echo number_format($d['harga'], 0, ',', '.'); ?></td>
                    <td><?php echo $d['qty']; ?></td>
                    <td>Rp <?php echo number_format($d['subtotal'], 0, ',', '.'); ?></td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </body>
</html>