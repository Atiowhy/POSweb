<?php
session_start();
include '../config/db.php';

if (empty($_SESSION['email'])) {
    header('location: ../index.php?access-failed');
}

$queryData = mysqli_query($connection, "SELECT * FROM transaksi ORDER BY id DESC")
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/dist/css/bootstrap.min.css">
    <title>Document</title>
</head>

<body>
    <?php include '../inc/navbar.php' ?>
    <div class="container">
        <div class="row mt-3">
            <div class="col-sm-2"></div>
            <div class="col-md-8 col-sm-12">
                <div class="card shadow">
                    <div class="card-header text-center">
                        <h1>Menage Kasir</h1>
                    </div>
                    <div class="card-body">
                        <div class="table table-responsive">
                            <div class="mt-2 mb-2">
                                <a href="tambah-transaksi.php" class="col-md-2 col-sm-2 btn btn-outline-warning">Kasir</a>
                            </div>
                            <table class="table table-bordered">
                                <thead>
                                    <tr class="text-center">
                                        <th>No</th>
                                        <th>Kode Transaksi</th>
                                        <th>Tanggal Transaksi</th>
                                        <th>Struk Pembayaran</th>
                                        <th>Status Pembayaran</th>
                                        <th>Settings</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $no = 1;
                                    while ($resultDataPenjualan = mysqli_fetch_assoc($queryData)):
                                    ?>
                                        <tr>
                                            <td><?= $no++ ?></td>
                                            <td><?= $resultDataPenjualan['kode_transaksi'] ?></td>
                                            <td><?= $resultDataPenjualan['tanggal_transaksi'] ?></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                        </tr>
                                    <?php endwhile ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-2"></div>
        </div>
    </div>

    <script src="../assets/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>