<?php
    date_default_timezone_set('Asia/Jakarta');
    require_once '../../vendor/autoload.php'; // Pastikan path ke autoload benar

    // Set konfigurasi Midtrans
    \Midtrans\Config::$serverKey = 'SB-Mid-server-if2r_gpnCp6LSSfzF8bibmAr'; // Ganti dengan Server Key Anda
    \Midtrans\Config::$isProduction = false; // Ubah menjadi true jika di production
    \Midtrans\Config::$isSanitized = true;
    \Midtrans\Config::$is3ds = true;

    // Memulai session untuk mengambil data pengguna yang sedang login
    session_start();

    // Ambil data dari form
    $jenis_paket = isset($_POST['jenis_paket']) ? htmlspecialchars($_POST['jenis_paket']) : '';
    $paket = isset($_POST['paket']) ? htmlspecialchars($_POST['paket']) : '';
    $jumlah = isset($_POST['jumlah']) ? (int)$_POST['jumlah'] : 1;

    if (empty($jenis_paket) || empty($paket) || $jumlah < 1) {
        echo "<p>Data pembayaran tidak valid.</p>";
        exit;
    }

    // Koneksi ke database
    $conn = mysqli_connect("localhost", "root", "", "mikj2431_mikada-laundry");
    if (mysqli_connect_error()) {
        die("Koneksi ke database gagal: " . mysqli_connect_error());
    }

    // Ambil harga dan outlet_id dari database
    $query = "SELECT harga, outlet_id, id_paket FROM paket_cuci WHERE nama_paket = '" . mysqli_real_escape_string($conn, $paket) . "' LIMIT 1";
    $result = mysqli_query($conn, $query);
    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $harga = (int)$row['harga'];
        $outlet_id = $row['outlet_id']; // ID Outlet
        $id_paket = $row['id_paket']; // ID Paket
    } else {
        echo "<p>Data paket tidak ditemukan.</p>";
        exit;
    }

    // Ambil id_pelanggan dari session (id_user diganti dengan id_pelanggan)
    $id_pelanggan = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
    if (!$id_pelanggan) {
        echo "<p>User belum login atau sesi telah berakhir.</p>";
        exit;
    }

    // Hitung total harga
    $total_harga = $harga * $jumlah;

    // Generate kode_invoice
    $kode_invoice = 'CLN20' . strtoupper(uniqid());

    // Data tambahan untuk transaksi
    $tgl = date('Y-m-d H:i:s');
    $batas_waktu = date('Y-m-d H:i:s', strtotime('+3 days')); // Contoh batas waktu 3 hari
    $tgl_pembayaran = null; // Akan diisi saat pembayaran berhasil
    $status = 'Sedang Proses'; // Status saat transaksi dimulai
    $status_bayar = 'Pending'; // Status default adalah pending
    $id_user = $_SESSION['user_id']; // Ganti dengan ID user yang melakukan transaksi

    // Simpan data transaksi ke database
    $queryInsert = "
        INSERT INTO transaksi (
            outlet_id, kode_invoice, id_pelanggan, tgl, batas_waktu, tgl_pembayaran,
            biaya_tambahan, diskon, pajak, status, status_bayar, id_user
        ) VALUES (
            '$outlet_id', '$kode_invoice', '$id_pelanggan', '$tgl', '$batas_waktu', '$tgl_pembayaran',
            0, 0, 0, '$status', '$status_bayar', '$id_user'
        )
    ";

    if (mysqli_query($conn, $queryInsert)) {
        $last_id = mysqli_insert_id($conn); // Ambil ID transaksi terakhir
    } else {
        echo "<p>Gagal menyimpan data transaksi: " . mysqli_error($conn) . "</p>";
        exit;
    }

    $total_bayar = ($status_bayar === 'Dibayar') ? $total_harga : NULL;

    $queryInsertDetail = "
        INSERT INTO detail_transaksi (id_transaksi, id_paket, qty, total_harga, keterangan, total_bayar)
        VALUES ('$last_id', '$id_paket', '$jumlah', '$total_harga', NULL, '$total_bayar')
    ";

    if (mysqli_query($conn, $queryInsertDetail)) {
        // Data transaksi berhasil disimpan
    } else {
        echo "<p>Gagal menyimpan detail transaksi: " . mysqli_error($conn) . "</p>";
        exit;
    }

    // Buat data transaksi Midtrans
    $transaction_details = [
        'order_id' => $kode_invoice,
        'gross_amount' => $total_harga, // Total harga
    ];

    $item_details = [
        [
            'id' => $id_paket,
            'price' => $harga,
            'quantity' => $jumlah,
            'name' => $paket,
        ]
    ];

    // Ambil data pelanggan dan email dari database
    $query = "
    SELECT p.id_pelanggan, p.nama_pelanggan, p.telp_pelanggan, p.alamat_pelanggan, u.email
    FROM pelanggan p
    JOIN user u ON p.id_pelanggan = u.id_user
    WHERE p.id_pelanggan = '$id_pelanggan'
    ";
    
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) > 0) {
        $customer_data = mysqli_fetch_assoc($result);
        $nama_pelanggan = $customer_data['nama_pelanggan'];
        $telp_pelanggan = $customer_data['telp_pelanggan'];
        $email = $customer_data['email'];
    } else {
        echo "<p>Data pelanggan tidak ditemukan.</p>";
            exit;
    }

    // Informasi pelanggan
    $customer_details = [
        'first_name' => $nama_pelanggan, // Nama depan pelanggan
        'last_name' => '', // Jika tidak ada nama belakang, bisa dikosongkan
        'email' => $email, // Email pelanggan
        'phone' => $telp_pelanggan, // Telepon pelanggan
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

    // Update status pembayaran berdasarkan hasil transaksi Midtrans (contoh setelah pembayaran berhasil)
    $payment_status = 'pending'; // Status sementara, misalnya 'pending', 'failed', atau 'success'

    if ($payment_status === 'pending') {
        $status_bayar = 'Pending';
    } elseif ($payment_status === 'failed') {
        $status_bayar = 'Gagal';
    } elseif ($payment_status === 'success') {
        $status_bayar = 'Dibayar';
    }

    // Perbarui status pembayaran di database
    $queryUpdate = "
        UPDATE transaksi
        SET status_bayar = '$status_bayar'
        WHERE kode_invoice = '$kode_invoice'
    ";

    if (mysqli_query($conn, $queryUpdate)) {
        // Status pembayaran berhasil diperbarui
    } else {
        echo "<p>Gagal memperbarui status pembayaran: " . mysqli_error($conn) . "</p>";
        exit;
    }

    mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pembayaran Laundry</title>
    <!-- favicon -->
    <link rel="icon" href="../assets/img/Laundry.png" type="image/x-icon" />
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background: linear-gradient(to right, #6a11cb, #2575fc);
            color: #fff;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
        }
        .container {
            background: rgba(255, 255, 255, 0.1);
            padding: 20px 30px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
            max-width: 500px;
            width: 100%;
            text-align: center;
        }
        h1 {
            font-size: 1.8rem;
            margin-bottom: 10px;
        }
        p {
            font-size: 1rem;
            margin: 10px 0;
        }
        button {
            background-color: #ff6b6b;
            color: white;
            border: none;
            padding: 10px 20px;
            font-size: 1rem;
            border-radius: 5px;
            cursor: pointer;
            transition: background 0.3s ease;
        }
        button:hover {
            background-color: #ff4757;
        }
        .alert {
            margin: 20px 0;
            padding: 15px;
            border-radius: 5px;
        }
        .alert.success {
            background-color: rgba(40, 167, 69, 0.2);
            color: #28a745;
        }
        .alert.error {
            background-color: rgba(220, 53, 69, 0.2);
            color: #dc3545;
        }
        .alert.pending {
            background-color: rgba(255, 193, 7, 0.2);
            color: #ffc107;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Pembayaran Laundry</h1>

        <?php
        if ($status_bayar == 'Pending') {
            echo "<p class='alert pending'>Pembayaran Anda sedang diproses, harap tunggu beberapa saat...</p>";
        } elseif ($status_bayar == 'Gagal') {
            echo "<p class='alert error'>Pembayaran Anda gagal. Silakan coba lagi.</p>";
        } elseif ($status_bayar == 'Dibayar') {
            echo "<p class='alert success'>Pembayaran berhasil. Terima kasih!</p>";
        }
        ?>

        <button onclick="window.location.href='index.php'">Kembali ke Beranda</button>
    </div>
</body>
</html>
