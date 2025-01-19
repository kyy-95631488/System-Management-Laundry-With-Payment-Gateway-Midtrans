<?php
    date_default_timezone_set('Asia/Jakarta');
    // Inisialisasi Midtrans
    require_once '../../../../vendor/autoload.php';
    \Midtrans\Config::$serverKey = 'SB-Mid-server-if2r_gpnCp6LSSfzF8bibmAr'; // Ganti dengan Server Key Anda
    \Midtrans\Config::$isProduction = false; // Ubah menjadi true jika di production

    // Ambil data notifikasi dari Midtrans
    $notif = new \Midtrans\Notification();

    // Ambil status pembayaran dari notifikasi
    $status_pembayaran = $notif->transaction_status;
    $order_id = $notif->order_id; // Kode invoice

    // Koneksi ke database
    $conn = mysqli_connect("localhost", "root", "", "mikj2431_mikada-laundry");
    if (mysqli_connect_error()) {
        die("Koneksi ke database gagal: " . mysqli_connect_error());
    }

    // Cek status pembayaran dan tentukan status_bayar serta tgl_pembayaran
    $status_bayar = 'Belum'; // Default status
    $tgl_pembayaran = null; // Default tgl_pembayaran

    if ($status_pembayaran == 'capture' || $status_pembayaran == 'settlement') {
        $status_bayar = 'dibayar';
        $tgl_pembayaran = date('Y-m-d H:i:s'); // Waktu saat pembayaran berhasil
    } else if ($status_pembayaran == 'pending') {
        $status_bayar = 'pending'; // Status pembayaran pending
    } else if ($status_pembayaran == 'cancel' || $status_pembayaran == 'deny') {
        $status_bayar = 'gagal'; // Status pembayaran gagal
    } else {
        $status_bayar = 'Gagal'; // Status pembayaran gagal
    }

    // Update status_bayar dan tgl_pembayaran di database
    $queryUpdateStatusBayar = "
        UPDATE transaksi 
        SET status_bayar = '$status_bayar', 
            tgl_pembayaran = " . ($tgl_pembayaran ? "'$tgl_pembayaran'" : "NULL") . " 
        WHERE kode_invoice = '$order_id'
    ";

    if (mysqli_query($conn, $queryUpdateStatusBayar)) {
        echo "Status pembayaran berhasil diupdate.";
    } else {
        echo "Gagal mengupdate status pembayaran: " . mysqli_error($conn);
    }

    mysqli_close($conn);
?>
