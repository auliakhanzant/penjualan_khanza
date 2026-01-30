<?php
session_start();
include '../koneksi.php';

if (isset($_POST['bayar']) && !empty($_SESSION['keranjang'])) {
    $id_user = $_SESSION['user_id'];
    $tanggal = date("Y-m-d H:i:s");
    $berhasil = 0;

    // Loop setiap item di keranjang dan simpan sebagai transaksi terpisah (karena struktur tabel penjualan user flattend/menyatu)
    foreach ($_SESSION['keranjang'] as $item) {
        $id_barang = $item['id'];
        $jumlah = $item['jumlah'];
        $harga = $item['harga'];
        $subtotal = $harga * $jumlah; // total_harga untuk item ini

        // 1. Simpan ke tabel penjualan (Satu baris per item)
        $query_insert = mysqli_query($koneksi, "INSERT INTO penjualan (tgl_jual, user_id, id_barang, jumlah_jual, total_harga) 
                                                VALUES ('$tanggal', '$id_user', '$id_barang', '$jumlah', '$subtotal')");
        
        if ($query_insert) {
            // 2. Kurangi Stok
            mysqli_query($koneksi, "UPDATE barang SET stok = stok - $jumlah WHERE id_barang = '$id_barang'");
            $berhasil++;
        }
    }

    if ($berhasil > 0) {
        // Reset Keranjang & Redirect
        $_SESSION['keranjang'] = [];
        echo "<script>alert('Transaksi Berhasil Disimpan! ($berhasil item)'); window.location='transaksi.php';</script>";
    } else {
        echo "<script>alert('Transaksi Gagal!'); window.location='transaksi.php';</script>";
    }
} else {
    header("Location: transaksi.php");
}
?>
