<?php
include '../config/db.php';

if (isset($_GET['id_barang'])) {
    $id_barang = $_GET['id_barang'];
    $query = mysqli_query($connection, "SELECT qty, harga FROM barang WHERE id = '$id_barang'");

    if (mysqli_num_rows($query) > 0) {
        $item = mysqli_fetch_assoc($query);
        header('Content-Type: application');
        echo json_encode($item);
    }
}
