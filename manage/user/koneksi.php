<?php
session_start();
if ($_SESSION) {
    if ($_SESSION['role'] == 'user') {
    } else {
        header("location: ../../login/");
    }
} else {
    header('location: ../../login/');
}

$conn = mysqli_connect("localhost", "root", "", "mikj2431_mikada-laundry");

if (mysqli_connect_error()) {
    echo "Koneksi ke database gagal : " . mysqli_connect_error();
}
