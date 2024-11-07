<?php
session_start();
include '../config/db.php';
date_default_timezone_set("Asia/Jakarta");

// waktu:
$currentTime = date('Y-m-d');

if (empty($_SESSION['email'])) {
    header('location: ../login.php?access-failed');
}

function generateCode()
{
    $kode = date('ymdHis');
    return $kode;
}

// click count
if (empty($_SESSION['click_count'])) {
    $_SESSION['click_count'] = 0;
}

// get categori
$querykategori = mysqli_query($connection, "SELECT * FROM categori_barang");
$categories = mysqli_fetch_all($querykategori, MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/dist/css/bootstrap.min.css">
    <title>Tambah Transaksi</title>
</head>

<body>
    <?php include '../inc/navbar.php' ?>
    <div class="container">
        <div class="row mt-3">
            <div class="col-sm-1"></div>
            <div class="col-md-10 col-sm-12">
                <div class="card mt-5 shadow">
                    <div class="card-header">
                        <h1 class="text-center">Add Transaksi</h1>
                    </div>
                    <div class="card-body">
                        <form action="../controller/insertBarang.php" method="post">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <div class="mb-3">
                                            <label for="" class="form-label">Kode. Transaksi</label>
                                            <input type="text" name="kode_transaksi" value="<?= "TR-" . generateCode() ?>" id="kode_transaksi" class="form-control" readonly>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="" class="form-label">Tanggal Transaksi</label>
                                        <input type="date" name="tanggal_transaksi" value="<?= $currentTime ?>" id="tanggal_transaksi" class="form-control" readonly>
                                    </div>
                                </div>
                                <div class="btn-cta d-flex gap-3">
                                    <button class="btn btn-success" type="button" id="counterBtn">Tambah</button>
                                    <div class="showCounter col-md-1">
                                        <input type="number" class="form-control" name="counterDisplay" id="counterDisplay" value="<?= $_SESSION['click_count'] ?>">
                                    </div>
                                </div>
                            </div>
                            <div class="table table-responsive mt-5">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr class="text-center">
                                            <th>No</th>
                                            <th>Nama Kategori</th>
                                            <th>Nama Barang</th>
                                            <th>Jumlah</th>
                                            <th>Sisa Produk</th>
                                            <th>Harga</th>
                                        </tr>
                                    </thead>
                                    <tbody id="tbody" class="text-center">
                                    </tbody>
                                    <tfoot class="text-center">
                                        <tr>
                                            <th colspan="5">Total Harga</th>
                                            <td><input type="number" id="total_harga_keseluruhan" name="total_harga" class="form-control" readonly></td>
                                        </tr>
                                        <tr>
                                            <th colspan="5">Nominal Bayar</th>
                                            <td><input type="number" name="nominal_bayar" id="nominal_bayar" class="form-control" required></td>
                                        </tr>
                                        <tr>
                                            <th colspan="5">Kembalian</th>
                                            <td><input type="number" class="form-control" id="kembalian" name="kembalian" readonly></td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>

                            <div class="mb-3">
                                <input type="submit" class="btn btn-primary" name="simpan" value="hitung">
                                <a href="kasir.php" class="btn btn-danger">Kembali</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-sm-1"></div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const button = document.getElementById('counterBtn')
            const countDisplay = document.getElementById('counterDisplay')
            const tbody = document.getElementById('tbody')

            button.addEventListener('click', function() {
                let currentCount = parseInt(countDisplay.value) || 0;
                currentCount++
                countDisplay.value = currentCount

                // tambah table
                let newRow = "<tr>";
                newRow += "<td>" + currentCount + "</td>";
                newRow += "<td><select class='form-control category-select' name='id_kategori[]' required>";
                newRow += "<option value=''>--pilih kategori--</option>"
                <?php foreach ($categories as $category) { ?>
                    newRow += "<option value='<?= $category['id'] ?>'><?= $category['nama_kategori'] ?></option>"
                <?php } ?>
                newRow += "<td><select class='form-control item-select' name='barang[]' required>";
                newRow += "<option value=''>--pilih barang--</option>"
                newRow += "</select></td>"
                newRow += "<td><input type='number' name='jumlah[]' class='form-control jumlah-input' value='0' required></td>"
                newRow += "<td><input type='number' name='sisa_produk[]' class='form-control' readonly></td>"
                newRow += "<td><input type='number' name='harga[]' class='form-control' readonly></td>"
                newRow += "</tr>"
                tbody.insertAdjacentHTML('beforeend', newRow);

                attachCategoryChangeListener()
                attactItemChangeListener()
                attachJumlahChangeListener()
            })

            const attachCategoryChangeListener = () => {
                const categorySelects = document.querySelectorAll('.category-select');
                categorySelects.forEach(Select => {
                    Select.addEventListener('change', function() {
                        const categoryId = this.value
                        const itemSelect = this.closest('tr').querySelector('.item-select')
                        console.log(categoryId);
                        console.log(itemSelect);

                        if (categoryId) {
                            fetch(`../controller/get-product-dari-category.php?id_kategori=${categoryId}`)
                                .then(response => response.json())
                                .then(data => {
                                    console.log(data);

                                    itemSelect.innerHTML = "<option value=''>--pilih barang--</option>"
                                    data.forEach(item => {
                                        itemSelect.innerHTML += `<option value='${item.id}'>${item.nama_barang}</option>`
                                    })

                                })
                        } else {
                            itemSelect.innerHTML = "<option value=''>--pilih barang--</option>"
                        }
                    })
                })
            }

            const attactItemChangeListener = () => {
                const itemSelects = document.querySelectorAll('.item-select')
                itemSelects.forEach(select => {
                    select.addEventListener('change', function() {
                        const itemId = this.value
                        const row = this.closest('tr')
                        const sisaProdukInput = row.querySelector('input[name="sisa_produk[]"]')
                        const hargaInput = row.querySelector('input[name = "harga[]"]')

                        if (itemId) {
                            fetch(`../controller/get-barang-details.php?id_barang=${itemId}`)
                                .then(response => response.json())
                                .then(data => {
                                    console.log(data);
                                    sisaProdukInput.value = data.qty
                                    hargaInput.value = data.harga
                                })
                        } else {
                            sisaProdukInput.value = ''
                            hargaInput.value = ''
                        }
                    })
                })
            }

            // untuk menentukan jumlah barang yang diinput
            const total_harga = document.getElementById('total_harga_keseluruhan');
            const nominalBayarKeseluruhanInput = document.getElementById('nominal_bayar')
            const kembalianKeseluruhan = document.getElementById('kembalian')

            const attachJumlahChangeListener = () => {
                const jumlahInputs = document.querySelectorAll('.jumlah-input')
                jumlahInputs.forEach(input => {
                    input.addEventListener('input', function() {
                        const row = this.closest('tr')
                        const sisaProdukInput = row.querySelector('input[name="sisa_produk[]"]')
                        const hargaInput = row.querySelector('input[name="harga[]"]')
                        const totalHargaInput = document.getElementById('total_harga_keseluruhan');
                        const nominalBayarInput = document.getElementById('nominal_bayar');
                        const kembalianInput = document.getElementById('kembalian')

                        const jumlah = parseInt(this.value) || 0;
                        const sisaProduk = parseInt(sisaProdukInput.value) || 0;
                        const harga = parseFloat(hargaInput.value) || 0;

                        if (jumlah > sisaProduk) {
                            alert('Jumlah melebihi sisa produk')
                            this.value = sisaProduk;
                            return;
                        }
                        updateTotalKeseluruhan()
                    })
                })
            }

            const updateTotalKeseluruhan = () => {
                let totalKeseluruhan = 0
                const jumlahInput = document.querySelectorAll('.jumlah-input')
                jumlahInput.forEach(input => {
                    const row = input.closest('tr')
                    const hargaInput = row.querySelector('input[name="harga[]"]')
                    const harga = parseFloat(hargaInput.value) || 0
                    const jumlah = parseInt(input.value) || 0
                    totalKeseluruhan += jumlah * harga
                })
                total_harga.value = totalKeseluruhan
                // console.log(total_harga.value);
            }

            // membuat kembalian...
            nominalBayarKeseluruhanInput.addEventListener('input', function() {
                const nominalBayar = parseFloat(this.value) || 0
                const totalHarga = parseFloat(total_harga.value) || 0
                kembalianKeseluruhan.value = nominalBayar - totalHarga
            })
        })
    </script>
    <script src="../assets/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>