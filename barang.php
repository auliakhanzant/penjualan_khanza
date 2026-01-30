<?php
include 'header.php';
include '../koneksi.php';

// Logic CRUD Barang
$mode = 'add';
$id_edit = '';
$nama_barang = '';
$harga_beli = '';
$harga_jual = '';
$stok = '';

if (isset($_GET['edit'])) {
    $mode = 'edit';
    $id_edit = $_GET['edit'];
    $q = mysqli_query($koneksi, "SELECT * FROM barang WHERE id_barang='$id_edit'");
    $d = mysqli_fetch_assoc($q);
    $nama_barang = $d['nama_barang'];
    $harga_beli = $d['harga_beli'];
    $harga_jual = $d['harga_jual'];
    $stok = $d['stok'];
}

if (isset($_GET['delete'])) {
    $id_del = $_GET['delete'];
    mysqli_query($koneksi, "DELETE FROM barang WHERE id_barang='$id_del'");
    echo "<script>alert('Barang dihapus!'); window.location='barang.php';</script>";
}

if (isset($_POST['simpan'])) {
    $nama = $_POST['nama_barang'];
    $hbeli = $_POST['harga_beli'];
    $hjual = $_POST['harga_jual'];
    $stk = $_POST['stok'];

    if ($mode == 'add') {
        mysqli_query($koneksi, "INSERT INTO barang (nama_barang, harga_beli, harga_jual, stok) VALUES ('$nama', '$hbeli', '$hjual', '$stk')");
    } else {
        mysqli_query($koneksi, "UPDATE barang SET nama_barang='$nama', harga_beli='$hbeli', harga_jual='$hjual', stok='$stk' WHERE id_barang='$id_edit'");
    }
    echo "<script>alert('Data Barang tersimpan!'); window.location='barang.php';</script>";
}
?>

<div class="row">
    <div class="col-md-4">
        <div class="card mb-3">
            <div class="card-header bg-primary text-white">
                <?= ($mode == 'edit') ? 'Edit Barang' : 'Tambah Barang' ?>
            </div>
            <div class="card-body">
                <form action="" method="POST">
                    <div class="mb-3">
                        <label>Nama Barang</label>
                        <input type="text" name="nama_barang" class="form-control" value="<?= $nama_barang ?>" required>
                    </div>
                    <div class="mb-3">
                        <label>Harga Beli</label>
                        <input type="number" name="harga_beli" class="form-control" value="<?= $harga_beli ?>" required>
                    </div>
                    <div class="mb-3">
                        <label>Harga Jual</label>
                        <input type="number" name="harga_jual" class="form-control" value="<?= $harga_jual ?>" required>
                    </div>
                    <div class="mb-3">
                        <label>Stok</label>
                        <input type="number" name="stok" class="form-control" value="<?= $stok ?>" required>
                    </div>
                    <button type="submit" name="simpan" class="btn btn-success w-100">Simpan</button>
                    <?php if($mode=='edit'): ?>
                        <a href="barang.php" class="btn btn-secondary w-100 mt-2">Batal</a>
                    <?php endif; ?>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-8">
        <div class="card">
            <div class="card-header bg-secondary text-white">Data Barang</div>
            <div class="card-body">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Barang</th>
                            <th>Harga Beli</th>
                            <th>Harga Jual</th>
                            <th>Stok</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $q = mysqli_query($koneksi, "SELECT * FROM barang ORDER BY id_barang DESC");
                        $no = 1;
                        while ($r = mysqli_fetch_assoc($q)) {
                        ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td><?= $r['nama_barang'] ?></td>
                            <td>Rp <?= number_format($r['harga_beli']) ?></td>
                            <td>Rp <?= number_format($r['harga_beli']) ?></td>
                            <td><?= $r['stok'] ?></td>
                            <td>
                                <a href="barang.php?edit=<?= $r['id_barang'] ?>" class="btn btn-warning btn-sm">Edit</a>
                                <a href="barang.php?delete=<?= $r['id_barang'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin hapus?')">Hapus</a>
                            </td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

</body>
</html>
