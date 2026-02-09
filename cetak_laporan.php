<?php
include '../koneksi.php';

$tgl_awal = $_GET['tgl_awal'];
$tgl_akhir = $_GET['tgl_akhir'];

?>
<!DOCTYPE html>
<html>
<head>
    <title>Cetak Laporan Penjualan</title>
    <style>
        body { font-family: sans-serif; }
        table { width: 100%; border-collapse: collapse; }
        table, th, td { border: 1px solid black; }
        th, td { padding: 8px; text-align: left; }
        th, td { padding: 8px; text-align: left; }
        .header { text-align: center; margin-bottom: 20px; }
        .text-end { text-align: right; }
        @media print {
            .no-print { display: none; }
        }
    </style>
</head>
<body onload="window.print()">

    <div class="no-print" style="margin-bottom: 20px;">
        <button onclick="window.close()" style="padding: 10px 20px; background-color: #dc3545; color: white; border: none; cursor: pointer;">Keluar</button>
    </div>

    <div class="header">
        <h2>LAPORAN PENJUALAN TOKO KHANZA</h2>
        <p>Periode: <?= date('d-m-Y', strtotime($tgl_awal)) ?> s/d <?= date('d-m-Y', strtotime($tgl_akhir)) ?></p>
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Tanggal</th>
                <th>Nama Barang</th>
                <th>Harga</th>
                <th>Jumlah</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $no = 1;
            $total_pendapatan = 0;
            $query = mysqli_query($koneksi, "SELECT penjualan.*, barang.nama_barang, barang.harga_jual 
                                            FROM penjualan 
                                            JOIN barang ON penjualan.id_barang = barang.id_barang 
                                            WHERE penjualan.tgl_jual BETWEEN '$tgl_awal 00:00:00' AND '$tgl_akhir 23:59:59'
                                            ORDER BY penjualan.tgl_jual ASC");
            
            while ($r = mysqli_fetch_assoc($query)) {
                $total_pendapatan += $r['total_harga'];
            ?>
            <tr>
                <td><?= $no++ ?></td>
                <td><?= $r['tgl_jual'] ?></td>
                <td><?= $r['nama_barang'] ?></td>
                <td>Rp <?= number_format($r['harga_jual']) ?></td>
                <td><?= $r['jumlah_jual'] ?></td>
                <td>Rp <?= number_format($r['total_harga']) ?></td>
            </tr>
            <?php } ?>
        </tbody>
        <tfoot>
            <tr>
                <th colspan="5" class="text-end">Total Pendapatan</th>
                <th>Rp <?= number_format($total_pendapatan) ?></th>
            </tr>
        </tfoot>
    </table>

</body>
</html>
