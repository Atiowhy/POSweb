<?php
$host  = 'localhost';
$username = 'root';
$password = '';
$dbname = 'angkatan3_kasir';

$connection = mysqli_connect($host, $username, $password, $dbname);

if (!$connection) {
    echo 'connection failed...';
}
