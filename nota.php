<?php
session_start();
include '../koneksi.php';

if (!isset($_SESSION['user_status'])) {
    echo "<script>alert('Anda belum login!'); window.location='../login.php';</script>";
    exit;
}

if (!isset($_GET['tgl'])) {
    echo "<script>alert('Data tidak ditemukan!'); window.location='transaksi.php';</script>";
    exit;
}

$tgl = $_GET['tgl'];
$id_user = isset($_GET['id_user']) ? $_GET['id_user'] : $_SESSION['user_id'];

// Ambil data transaksi berdasarkan tanggal dan user
$query = mysqli_query($koneksi, "SELECT penjualan.*, barang.nama_barang, barang.harga_jual 
                                 FROM penjualan 
                                 JOIN barang ON penjualan.id_barang = barang.id_barang 
                                 WHERE penjualan.tgl_jual = '$tgl' AND penjualan.user_id = '$id_user'");

$transaksi = [];
while ($row = mysqli_fetch_assoc($query)) {
    $transaksi[] = $row;
}

if (empty($transaksi)) {
    echo "<script>alert('Data transaksi tidak ditemukan!'); window.location='transaksi.php';</script>";
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Nota Penjualan</title>
    <style>
        body { font-family: monospace; }
        .container { width: 300px; margin: 0 auto; }
        .header { text-align: center; margin-bottom: 20px; }
        .item { display: flex; justify-content: space-between; }
        .total { border-top: 1px dashed black; margin-top: 10px; padding-top: 5px; font-weight: bold; }
        hr { border-top: 1px dashed black; }
        @media print {
            .no-print { display: none; }
        }
    </style>
</head>
<body onload="window.print()">
    <div class="container">
        <div class="header">
            <h3>TOKO KHANZA</h3>
            <p>Jalan Raya No. 123<br>Telp: 081234567890</p>
            <p><?= date('d/m/Y H:i', strtotime($tgl)) ?></p>
        </div>
        <hr>
        <?php 
        $total_bayar = 0;
        foreach ($transaksi as $item): 
            $subtotal = $item['jumlah_jual'] * $item['harga_jual'];
            $total_bayar += $subtotal;
        ?>
        <div class="item">
            <span><?= $item['nama_barang'] ?></span>
        </div>
        <div class="item">
            <span><?= $item['jumlah_jual'] ?> x <?= number_format($item['harga_jual']) ?></span>
            <span><?= number_format($subtotal) ?></span>
        </div>
        <?php endforeach; ?>
        <hr>
        <div class="item total">
            <span>Total Bayar</span>
            <span>Rp <?= number_format($total_bayar) ?></span>
        </div>
        <br>
        <div style="text-align: center;">
            <p>Terima Kasih atas Kunjungan Anda</p>
        </div>
        <div class="no-print" style="text-align: center; margin-top: 20px;">
            <button onclick="window.close()" style="padding: 10px 20px; background-color: #dc3545; color: white; border: none; cursor: pointer;">Keluar</button>
        </div>
    </div>
</body>
</html>
