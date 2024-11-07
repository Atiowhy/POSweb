<?php
session_start();
include '../config/db.php';

if (isset($_POST['simpan'])) {
    $email = $_SESSION['email'];

    $query = mysqli_query($connection, "SELECT id FROM user WHERE email = '$email'");
    $row = mysqli_fetch_assoc($query);

    $id_user = $row['id'];

    $kode_transaksi = $_POST['kode_transaksi'];
    $tanggal_transaksi = $_POST['tanggal_transaksi'];
    $total_harga = $_POST['total_harga'];
    $nominal_bayar = $_POST['nominal_bayar'];
    $kembalian = $_POST['kembalian'];

    $queryInsert = mysqli_query($connection, "INSERT INTO transaksi (id_user, kode_transaksi, tanggal_transaksi) VALUES ('$id_user', '$kode_transaksi', '$tanggal_transaksi')");

    $id_penjualan = mysqli_insert_id($connection);
    foreach ($_POST['barang'] as $key => $id_barang) {
        // print_r($id_barang);
        // die;
        $jumlah = $_POST['jumlah'][$key];

        // ambil stock dan harga barang
        $barang = mysqli_query($connection, "SELECT harga, qty FROM barang WHERE id = '$id_barang'");
        $barangData = mysqli_fetch_assoc($barang);

        $harga = $barangData['harga'];
        $qty = $barangData['qty'];

        $total_harga_detail = $jumlah * $harga;
        $detailPenjualan = mysqli_query($connection, "INSERT INTO detail_transaksi (id_transaksi, id_barang, jumlah, qty, harga, total_harga, nominal_bayar, kembalian) VALUES ('$id_penjualan', '$id_barang', '$jumlah', '$qty', '$harga', '$total_harga', '$nominal_bayar', '$kembalian')");
        // print_r($detailPenjualan);
        // die;

        $updateQty = mysqli_query($connection, "UPDATE barang SET qty = qty - $jumlah WHERE id = $id_barang");
        header('location: ../views/kasir.php?insert-success');
        exit();
    }


    // coding menyenangkan bukan saking menyenangkannya sampe membuat lambung menjerit hueek hueek

    // if ($queryInsert) {
    //     header('location: ../views/kasir.php?insert-berhasil');
    //     exit();
    // } else {
    //     header('location: ../views/tambah-transaksi.php?insert-gagal');
    //     exit();
    // }
}
