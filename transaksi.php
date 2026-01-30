<?php
include 'header.php';
include '../koneksi.php';

// Inisialisasi Keranjang
if (!isset($_SESSION['keranjang'])) {
    $_SESSION['keranjang'] = [];
}

// Tambah Barang ke Keranjang
if (isset($_POST['tambah'])) {
    $id_barang = $_POST['id_barang'];
    $jumlah = $_POST['jumlah'];

    // Cek Stok
    $q_barang = mysqli_query($koneksi, "SELECT * FROM barang WHERE id_barang='$id_barang'");
    $d_barang = mysqli_fetch_assoc($q_barang);
    
    if ($jumlah > $d_barang['stok']) {
        echo "<script>alert('Stok tidak cukup! Sisa stok: " . $d_barang['stok'] . "');</script>";
    } else {
        // Cek jika barang sudah ada di keranjang
        $found = false;
        foreach ($_SESSION['keranjang'] as $key => $val) {
            if ($val['id'] == $id_barang) {
                $_SESSION['keranjang'][$key]['jumlah'] += $jumlah;
                $found = true;
                break;
            }
        }

        if (!$found) {
            $_SESSION['keranjang'][] = [
                'id' => $id_barang,
                'nama' => $d_barang['nama_barang'],
                'harga' => $d_barang['harga_jual'],
                'jumlah' => $jumlah
            ];
        }
    }
}

// Hapus Item Keranjang
if (isset($_GET['hapus'])) {
    $key = $_GET['hapus'];
    unset($_SESSION['keranjang'][$key]);
    $_SESSION['keranjang'] = array_values($_SESSION['keranjang']); // Reindex array
    echo "<script>window.location='transaksi.php';</script>";
}

// Reset Keranjang
if (isset($_POST['reset'])) {
    $_SESSION['keranjang'] = [];
    echo "<script>window.location='transaksi.php';</script>";
}
?>

<div class="row">
    <div class="col-md-5">
        <div class="card mb-3">
            <div class="card-header bg-primary text-white">Pilih Barang</div>
            <div class="card-body">
                <form action="" method="POST">
                    <div class="mb-3">
                        <label>Nama Barang</label>
                        <select name="id_barang" class="form-control" required>
                            <option value="">-- Pilih Barang --</option>
                            <?php
                            $q = mysqli_query($koneksi, "SELECT * FROM barang WHERE stok > 0");
                            while ($r = mysqli_fetch_assoc($q)) {
                                echo "<option value='$r[id_barang]'>$r[nama_barang] (Rp " . number_format($r['harga_jual']) . ") - Stok: $r[stok]</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label>Jumlah Beli</label>
                        <input type="number" name="jumlah" class="form-control" min="1" value="1" required>
                    </div>
                    <button type="submit" name="tambah" class="btn btn-primary w-100">Tambah ke Keranjang</button>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-7">
        <div class="card">
            <div class="card-header bg-secondary text-white">Keranjang Belanja</div>
            <div class="card-body">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Barang</th>
                            <th>Harga</th>
                            <th>Jml</th>
                            <th>Subtotal</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $no = 1;
                        $total_bayar = 0;
                        foreach ($_SESSION['keranjang'] as $key => $item) {
                            $subtotal = $item['harga'] * $item['jumlah'];
                            $total_bayar += $subtotal;
                        ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td><?= $item['nama'] ?></td>
                            <td><?= number_format($item['harga']) ?></td>
                            <td><?= $item['jumlah'] ?></td>
                            <td><?= number_format($subtotal) ?></td>
                            <td>
                                <a href="transaksi.php?hapus=<?= $key ?>" class="btn btn-danger btn-sm">X</a>
                            </td>
                        </tr>
                        <?php } ?>
                        
                        <?php if(empty($_SESSION['keranjang'])): ?>
                            <tr><td colspan="6" class="text-center">Keranjang Masih Kosong</td></tr>
                        <?php else: ?>
                            <tr>
                                <th colspan="4" class="text-end">Total Bayar</th>
                                <th colspan="2">Rp <?= number_format($total_bayar) ?></th>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>

                <?php if(!empty($_SESSION['keranjang'])): ?>
                    <form action="proses_transaksi.php" method="POST">
                        <input type="hidden" name="total_bayar" value="<?= $total_bayar ?>">
                        <div class="d-flex justify-content-between">
                            <button type="submit" name="reset" class="btn btn-warning text-white" formaction="transaksi.php">Reset Keranjang</button>
                            <button type="submit" name="bayar" class="btn btn-success">Selesaikan Transaksi</button>
                        </div>
                    </form>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

</body>
</html>
