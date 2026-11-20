<?php
    include 'soal2_navbar.php';
    include 'koneksi.php';

    // mengambil data barang & pelanggan untuk pilihan transaksi
    $barang_result = mysqli_query($conn, "SELECT kode_barang, nama_barang, harga, stok FROM barang");
    if (!$barang_result) {
        die("Query barang gagal: " . mysqli_error($conn));
    }

    $pelanggan_result = mysqli_query($conn, "SELECT id, nama FROM pelanggan");
    if (!$pelanggan_result) {
        die("Query pelanggan gagal: " . mysqli_error($conn));
    }

    $barang_list = [];
    while ($row = mysqli_fetch_assoc($barang_result)) {
        $barang_list[] = $row;
    }

    $pelanggan_list = [];
    while ($row = mysqli_fetch_assoc($pelanggan_result)) {
        $pelanggan_list[] = $row;
    }

    if (!isset($_SESSION['keranjang'])) {
        $_SESSION['keranjang'] = [];
    }

    $pesan = "";

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $aksi = $_POST['aksi'] ?? '';

        if ($aksi === 'tambah') {
            $kode = $_POST['kode_barang'] ?? '';
            $qty  = (int) ($_POST['qty'] ?? 1);

            foreach ($barang_list as $b) {
                if ($b['kode_barang'] === $kode) {
                    if ($qty <= 0) {
                        $pesan = "Qty minimal 1.";
                    } elseif ($qty > (int) $b['stok']) {
                        $pesan = "Stok tidak mencukupi (tersedia: " . (int) $b['stok'] . ").";
                    } else {
                        $_SESSION['keranjang'][] = [
                            'kode_barang' => $b['kode_barang'],
                            'nama_barang' => $b['nama_barang'],
                            'harga'       => (float) $b['harga'],
                            'qty'         => $qty,
                            'subtotal'    => (float) $b['harga'] * $qty
                        ];
                        $pesan = "Barang berhasil ditambahkan ke daftar transaksi.";
                    }
                    break;
                }
            }
        } elseif ($aksi === 'reset') {
            $_SESSION['keranjang'] = [];
            $pesan = "Daftar transaksi dikosongkan.";
        }
    }

    $total_belanja = 0;
    foreach ($_SESSION['keranjang'] as $item) {
        $total_belanja += $item['subtotal'];
    }
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Transaksi - Sistem Penjualan</title>
        <style>
            body { 
                font-family: Arial, sans-serif; 
                margin: 0; 
            }
            .konten { 
                padding: 24px; 
            }
            .form-row { 
                display: flex; 
                flex-wrap: wrap; 
                gap: 12px; 
                margin-bottom: 16px; 
                align-items: end; 
            }
            label { 
                display: block; 
                font-size: 13px; 
                margin-bottom: 4px; 
            }
            select, input[type="number"] { 
                padding: 8px; 
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
            button.btn-reset { 
                background: #c0392b; 
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
                background-color: #f2f4f8; 
            }
            .info { 
                background: #eef6ff; 
                padding: 10px 14px; 
                border-radius: 4px; 
                margin-bottom: 14px; 
            }
        </style>
    </head>
    <body>
        <div class="konten">
            <h2>Halaman Transaksi</h2>

            <?php if ($pesan !== ""): ?>
                <div class="info"><?= htmlspecialchars($pesan) ?></div>
            <?php endif; ?>

            <form method="POST" action="">
                <input type="hidden" name="aksi" value="tambah">
                <div class="form-row">
                    <div>
                        <label for="pelanggan">Pelanggan</label>
                        <select id="pelanggan" name="pelanggan_id">
                            <?php foreach ($pelanggan_list as $p): ?>
                                <option value="<?= htmlspecialchars($p['id']) ?>">
                                    <?= htmlspecialchars($p['id'] . " - " . $p['nama']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div>
                        <label for="kode_barang">Pilih Barang</label>
                        <select id="kode_barang" name="kode_barang">
                            <?php foreach ($barang_list as $b): ?>
                                <option value="<?= htmlspecialchars($b['kode_barang']) ?>">
                                    <?= htmlspecialchars($b['nama_barang']) ?> (Stok: <?= (int) $b['stok'] ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div>
                        <label for="qty">Qty</label>
                        <input type="number" id="qty" name="qty" min="1" value="1" required>
                    </div>

                    <div>
                        <button type="submit">Tambah ke Transaksi</button>
                    </div>
                </div>
            </form>

            <table>
                <thead>
                    <tr>
                        <th>Nama Barang</th>
                        <th>Harga Satuan</th>
                        <th>Qty</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($_SESSION['keranjang'])): ?>
                        <?php foreach ($_SESSION['keranjang'] as $item): ?>
                            <tr>
                                <td><?= htmlspecialchars($item['nama_barang']) ?></td>
                                <td>Rp <?= number_format($item['harga'], 0, ',', '.') ?></td>
                                <td><?= (int) $item['qty'] ?></td>
                                <td>Rp <?= number_format($item['subtotal'], 0, ',', '.') ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="4">Belum ada item transaksi.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>

            <p style="margin-top: 14px; font-size: 16px;">
                <strong>Total: Rp <?= number_format($total_belanja, 0, ',', '.') ?></strong>
            </p>

            <form method="POST" action="" style="display:inline;">
                <input type="hidden" name="aksi" value="reset">
                <button type="submit" class="btn-reset">Reset Daftar</button>
            </form>
        </div>
    </body>
</html>