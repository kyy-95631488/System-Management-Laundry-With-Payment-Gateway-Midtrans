<?php
    date_default_timezone_set('Asia/Jakarta');
    // Inisialisasi Midtrans
    require_once './../../../vendor/autoload.php';
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

    // Ambil ID transaksi untuk update detail transaksi
    $queryGetTransaction = "SELECT id_transaksi FROM transaksi WHERE kode_invoice = '$order_id' LIMIT 1";
    $resultTransaksi = mysqli_query($conn, $queryGetTransaction);
    if (mysqli_num_rows($resultTransaksi) > 0) {
        $rowTransaksi = mysqli_fetch_assoc($resultTransaksi);
        $id_transaksi = $rowTransaksi['id_transaksi'];

        // Jika status_bayar adalah 'dibayar', update total_bayar di detail_transaksi
        if ($status_bayar == 'dibayar') {
            // Ambil total bayar dari transaksi (misalnya dari total_harga yang sudah dihitung di proses_pembayaran.php)
            $queryTotalBayar = "SELECT total_harga FROM detail_transaksi WHERE id_transaksi = '$id_transaksi' LIMIT 1";
            $resultDetail = mysqli_query($conn, $queryTotalBayar);
            if (mysqli_num_rows($resultDetail) > 0) {
                $rowDetail = mysqli_fetch_assoc($resultDetail);
                $total_bayar = $rowDetail['total_harga']; // Total bayar sesuai transaksi
                
                // Update detail_transaksi
                $queryUpdateDetailTransaksi = "
                    UPDATE detail_transaksi
                    SET total_bayar = '$total_bayar'
                    WHERE id_transaksi = '$id_transaksi'
                ";

                if (mysqli_query($conn, $queryUpdateDetailTransaksi)) {
                    echo "Detail transaksi berhasil diperbarui.";
                } else {
                    echo "Gagal mengupdate detail transaksi: " . mysqli_error($conn);
                }
            } else {
                echo "Detail transaksi tidak ditemukan.";
            }
        }
    }

    if (mysqli_query($conn, $queryUpdateStatusBayar)) {
        echo "Status pembayaran berhasil diupdate.";
    } else {
        echo "Gagal mengupdate status pembayaran: " . mysqli_error($conn);
    }

    mysqli_close($conn);
?>
