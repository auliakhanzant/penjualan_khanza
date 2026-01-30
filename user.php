<?php
include 'header.php';
include '../koneksi.php';

// Handle Add/Edit/Delete Logic
$mode = 'add';
$id_edit = '';
$user_nama = '';
$username = '';
$user_status = 1;

if (isset($_GET['edit'])) {
    $mode = 'edit';
    $id_edit = $_GET['edit'];
    $q_edit = mysqli_query($koneksi, "SELECT * FROM user WHERE user_id='$id_edit'");
    $d_edit = mysqli_fetch_assoc($q_edit);
    $user_nama = $d_edit['user_nama'];
    $username = $d_edit['username'];
    $user_status = $d_edit['user_status'];
}

if (isset($_GET['delete'])) {
    $id_del = $_GET['delete'];
    mysqli_query($koneksi, "DELETE FROM user WHERE user_id='$id_del'");
    echo "<script>alert('User dihapus!'); window.location='user.php';</script>";
}

if (isset($_POST['simpan'])) {
    $nama = $_POST['user_nama'];
    $user = $_POST['username'];
    $status = $_POST['user_status'];
    $pass = $_POST['password'];

    if ($mode == 'add') {
        $password = md5($pass);
        mysqli_query($koneksi, "INSERT INTO user (user_nama, username, password, user_status) VALUES ('$nama', '$user', '$password', '$status')");
    } else {
        if (!empty($pass)) {
            $password = md5($pass);
            mysqli_query($koneksi, "UPDATE user SET user_nama='$nama', username='$user', password='$password', user_status='$status' WHERE user_id='$id_edit'");
        } else {
            mysqli_query($koneksi, "UPDATE user SET user_nama='$nama', username='$user', user_status='$status' WHERE user_id='$id_edit'");
        }
    }
    echo "<script>alert('Data tersimpan!'); window.location='user.php';</script>";
}
?>

<div class="row">
    <div class="col-md-4">
        <div class="card mb-3">
            <div class="card-header bg-primary text-white">
                <?= ($mode == 'edit') ? 'Edit User' : 'Tambah User' ?>
            </div>
            <div class="card-body">
                <form action="" method="POST">
                    <div class="mb-3">
                        <label>Nama User (user_nama)</label>
                        <input type="text" name="user_nama" class="form-control" value="<?= $user_nama ?>" required>
                    </div>
                    <div class="mb-3">
                        <label>Username</label>
                        <input type="text" name="username" class="form-control" value="<?= $username ?>" required>
                    </div>
                    <div class="mb-3">
                        <label>Password <?= ($mode == 'edit') ? '<small>(Kosongkan jika tidak ubah)</small>' : '' ?></label>
                        <input type="password" name="password" class="form-control" <?= ($mode == 'add') ? 'required' : '' ?>>
                    </div>
                    <div class="mb-3">
                        <label>Status</label>
                        <select name="user_status" class="form-control">
                            <option value="1" <?= ($user_status == 1) ? 'selected' : '' ?>>Admin</option>
                            <option value="2" <?= ($user_status == 2) ? 'selected' : '' ?>>Kasir</option>
                        </select>
                    </div>
                    <button type="submit" name="simpan" class="btn btn-success w-100">Simpan</button>
                    <?php if($mode=='edit'): ?>
                        <a href="user.php" class="btn btn-secondary w-100 mt-2">Batal</a>
                    <?php endif; ?>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-8">
        <div class="card">
            <div class="card-header bg-secondary text-white">Data User</div>
            <div class="card-body">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nama User</th>
                            <th>Username</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $tampil = mysqli_query($koneksi, "SELECT * FROM user ORDER BY user_id DESC");
                        while ($r = mysqli_fetch_assoc($tampil)) {
                        ?>
                        <tr>
                            <td><?= $r['user_id'] ?></td>
                            <td><?= $r['user_nama'] ?></td>
                            <td><?= $r['username'] ?></td>
                            <td><?= ($r['user_status'] == 1) ? '<span class="badge bg-success">Admin</span>' : '<span class="badge bg-warning">Kasir</span>' ?></td>
                            <td>
                                <a href="user.php?edit=<?= $r['user_id'] ?>" class="btn btn-warning btn-sm">Edit</a>
                                <a href="user.php?delete=<?= $r['user_id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin hapus?')">Hapus</a>
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
