<?php
date_default_timezone_set('Asia/Jakarta');
require_once '../../../vendor/autoload.php'; // Pastikan path ke autoload benar

// Set konfigurasi Midtrans
\Midtrans\Config::$serverKey = 'SB-Mid-server-if2r_gpnCp6LSSfzF8bibmAr'; // Ganti dengan Server Key Anda
\Midtrans\Config::$isProduction = false; // Ubah menjadi true jika di production
\Midtrans\Config::$isSanitized = true;
\Midtrans\Config::$is3ds = true;

// Memulai session untuk mengambil data pengguna yang sedang login
session_start();

if (!isset($_SESSION['user_id'])) {
    echo "User belum login.";
    exit;
}

// Ambil kode_invoice dari URL
$kode_invoice = isset($_GET['kode_invoice']) ? $_GET['kode_invoice'] : null;

if (!$kode_invoice) {
    echo "Kode Invoice tidak ditemukan.";
    exit;
}

// Koneksi ke database
$conn = mysqli_connect("localhost", "root", "", "mikj2431_mikada-laundry");

if (mysqli_connect_error()) {
    die("Koneksi ke database gagal: " . mysqli_connect_error());
}

// Ambil data transaksi berdasarkan kode_invoice
$query = "SELECT t.kode_invoice, t.status_bayar, SUM(dt.total_harga) AS total_harga
          FROM transaksi t
          JOIN detail_transaksi dt ON t.id_transaksi = dt.id_transaksi
          WHERE t.kode_invoice = '$kode_invoice'
          GROUP BY t.kode_invoice";
$result = mysqli_query($conn, $query);
if (mysqli_num_rows($result) === 0) {
    echo "Transaksi tidak ditemukan.";
    exit;
}

$row = mysqli_fetch_assoc($result);
$total_harga = $row['total_harga'];
$status_bayar = $row['status_bayar'];

if ($status_bayar === 'Dibayar') {
    echo "Transaksi sudah dibayar.";
    exit;
} elseif ($status_bayar === 'Pending') {
    echo "Pembayaran Anda sedang diproses. Silakan tunggu beberapa saat.";
    // Anda bisa memilih untuk menunggu atau melakukan tindakan lain di sini
    exit;
}

// Buat data transaksi Midtrans
$transaction_details = [
    'order_id' => $kode_invoice,
    'gross_amount' => $total_harga, // Total harga
];

$item_details = [
    [
        'id' => $kode_invoice,
        'price' => $total_harga,
        'quantity' => 1,
        'name' => 'Pembayaran Laundry',
    ]
];

// Informasi pelanggan
$customer_details = [
    'first_name' => 'Customer', // Ubah sesuai data pelanggan
    'last_name' => 'Name',
    'email' => 'customer@example.com', // Ubah sesuai data pelanggan
    'phone' => '08123456789', // Ubah sesuai data pelanggan
];

$params = [
    'transaction_details' => $transaction_details,
    'item_details' => $item_details,
    'customer_details' => $customer_details,
];

try {
    // Buat Snap Token
    $snapToken = \Midtrans\Snap::getSnapToken($params);

    // Tampilkan halaman pembayaran
    echo "<script src='https://app.sandbox.midtrans.com/snap/snap.js' data-client-key='SB-Mid-client-38A7i8F3vVxUCLYD'></script>";
    echo "<script>snap.pay('$snapToken');</script>";
} catch (Exception $e) {
    echo "<p>Terjadi kesalahan: " . $e->getMessage() . "</p>";
}

mysqli_close($conn);
?>
