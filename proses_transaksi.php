<?php
session_start();
include '../koneksi.php';

if (isset($_POST['bayar']) && !empty($_SESSION['keranjang'])) {
    $id_user = $_SESSION['user_id'];
    $tanggal = date("Y-m-d H:i:s");
    $berhasil = 0;

    foreach ($_SESSION['keranjang'] as $item) {
        $id_barang = $item['id'];
        $jumlah = $item['jumlah'];
        $harga = $item['harga'];
        $subtotal = $harga * $jumlah; 

        $query_insert = mysqli_query($koneksi, "INSERT INTO penjualan (tgl_jual, user_id, id_barang, jumlah_jual, total_harga) VALUES ('$tanggal', '$id_user', '$id_barang', '$jumlah', '$subtotal')");
        
        if ($query_insert) {
            mysqli_query($koneksi, "UPDATE barang SET stok = stok - $jumlah WHERE id_barang = '$id_barang'");
            $berhasil++;
        }
    }

    if ($berhasil > 0) {
        $_SESSION['keranjang'] = [];
        echo "<script>
            if (confirm('Transaksi Berhasil! Apakah ingin mencetak nota?')) {
                window.open('nota.php?tgl=$tanggal', '_blank');
            }
            window.location='transaksi.php';
        </script>";
    } else {
        echo "<script>alert('Transaksi Gagal!'); window.location='transaksi.php';</script>";
    }
} else {
    header("Location: transaksi.php");
}
?>
