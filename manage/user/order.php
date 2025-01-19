<?php

$title = 'Layanan Laundry';
require 'koneksi.php';
require 'header.php';

if (!isset($_SESSION['user_id'])) {
    echo "<p style='text-align: center; font-size: 18px;'>Anda harus login terlebih dahulu.</p>";
    exit;
}

$user_id = $_SESSION['user_id'];

$queryCheckUser = "SELECT id_pelanggan FROM pelanggan WHERE id_pelanggan = '$user_id'";
$resultCheckUser = mysqli_query($conn, $queryCheckUser);

if (mysqli_num_rows($resultCheckUser) == 0) {
    echo "<p style='text-align: center; font-size: 18px;'>Anda belum terdaftar sebagai pelanggan. Silakan <a href='profile.php'>perbarui profil Anda</a> terlebih dahulu.</p>";
    exit;
}
?>

<style>
    body {
        font-family: 'Arial', sans-serif;
        background-color: #f8f9fa;
        color: #333;
    }

    .container {
        max-width: 1200px;
        margin: 20px auto;
        padding: 20px;
        background-color: #fff;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        border-radius: 8px;
        display: flex;
        flex-direction: column;
        align-items: center;
    }

    h1 {
        text-align: center;
        margin-bottom: 20px;
        font-size: 24px;
        color: #007bff;
    }

    .product-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 20px;
        width: 100%;
        max-width: 1200px;
    }

    .product-card {
        border: 1px solid #ddd;
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        transition: transform 0.3s ease;
        background-color: #fff;
    }

    .product-card:hover {
        transform: translateY(-5px);
    }

    .product-image {
        width: 100%;
        height: 200px;
        object-fit: cover;
    }

    .product-info {
        padding: 15px;
    }

    .product-title {
        font-size: 18px;
        font-weight: bold;
        color: #333;
        margin-bottom: 10px;
    }

    .product-price {
        font-size: 16px;
        color: #28a745;
        margin-bottom: 10px;
    }

    .dropdown {
        margin-top: 10px;
    }

    .dropdown label {
        display: block;
        font-size: 14px;
        color: #555;
        margin-bottom: 5px;
    }

    .dropdown select, .dropdown input {
        width: 100%;
        padding: 12px;
        font-size: 14px;
        color: #333;
        background-color: #f9f9f9;
        border: 1px solid #ddd;
        border-radius: 5px;
        box-shadow: inset 0 1px 3px rgba(0, 0, 0, 0.1);
        transition: border-color 0.3s ease, box-shadow 0.3s ease;
    }

    .dropdown select:focus, .dropdown input:focus {
        border-color: #28a745;
        box-shadow: 0 0 5px rgba(40, 167, 69, 0.5);
        outline: none;
    }

    .dropdown select:hover, .dropdown input:hover {
        background-color: #f1f1f1;
    }

    .pay-button {
        display: inline-block;
        padding: 12px 20px;
        font-size: 16px;
        font-weight: bold;
        color: #fff;
        background-color: #28a745;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        margin-top: 15px;
        text-align: center;
        text-decoration: none;
        transition: background-color 0.3s ease;
    }

    .pay-button:hover {
        background-color: #218838;
    }

    @media (max-width: 768px) {
        h1 {
            font-size: 20px;
        }

        .product-image {
            height: 180px;
        }

        .pay-button {
            width: 100%;
        }
    }

</style>

<div class="panel-header bg-primary-gradient">
    <div class="page-inner py-5">
        <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row">
            <div>
                <h2 class="text-white pb-2 fw-bold">Layanan Laundry</h2>
            </div>
        </div>
        <?php if (isset($_GET['msg'])) : ?>
            <div class="alert alert-success" id="msg"><?= $_GET['msg'] ?></div>
        <?php endif ?>
    </div>
</div>

<div class="container">
    <h1>Daftar Paket</h1>
    <div class="product-grid">
        <?php
        $conn = mysqli_connect("localhost", "root", "", "mikj2431_mikada-laundry");

        if (mysqli_connect_error()) {
            echo "Koneksi ke database gagal: " . mysqli_connect_error();
            exit;
        }

        $queryJenis = "SELECT DISTINCT jenis_paket FROM paket_cuci";
        $resultJenis = mysqli_query($conn, $queryJenis);

        if (mysqli_num_rows($resultJenis) > 0) {
            while ($jenisRow = mysqli_fetch_assoc($resultJenis)) {
                $jenis = $jenisRow['jenis_paket'];

                $queryPaket = "SELECT nama_paket, harga FROM paket_cuci WHERE jenis_paket = '$jenis' AND (nama_paket LIKE '%paket normal%' OR nama_paket LIKE '%Paket Besok Ambil%' OR nama_paket LIKE '%Paket 1 Hari Selesai%')";
                $resultPaket = mysqli_query($conn, $queryPaket);
                ?>
                <div class="product-card">
                    <img src="../assets/img/product_thumbnail_new.png" alt="<?= htmlspecialchars($jenis); ?>" class="product-image">
                    <div class="product-info">
                        <h2 class="product-title"><?= htmlspecialchars($jenis); ?></h2>
                        
                        <div class="dropdown">
                            <label for="paket-<?= htmlspecialchars($jenis); ?>">Pilih Paket:</label>
                            <select id="paket-<?= htmlspecialchars($jenis); ?>" name="paket">
                                <?php
                                if (mysqli_num_rows($resultPaket) > 0) {
                                    while ($paketRow = mysqli_fetch_assoc($resultPaket)) {
                                        echo '<option value="' . htmlspecialchars($paketRow['nama_paket']) . '">' . htmlspecialchars($paketRow['nama_paket']) . ' - Rp ' . number_format($paketRow['harga'], 0, ',', '.') . '</option>';
                                    }
                                } else {
                                    echo '<option value="">Tidak ada paket tersedia</option>';
                                }
                                ?>
                            </select>
                        </div>

                        <div class="dropdown">
                            <label for="jumlah-<?= htmlspecialchars($jenis); ?>">Jumlah Pesanan:</label>
                            <input type="number" id="jumlah-<?= htmlspecialchars($jenis); ?>" name="jumlah" min="1" value="1">
                        </div>

                        <form action="proses_pembayaran.php" method="POST">
                            <input type="hidden" name="jenis_paket" value="<?= htmlspecialchars($jenis); ?>">
                            <input type="hidden" name="paket" id="paket_input-<?= htmlspecialchars($jenis); ?>" value="">
                            <input type="hidden" name="jumlah" id="jumlah_input-<?= htmlspecialchars($jenis); ?>" value="1">
                            <button type="submit" class="pay-button" onclick="setPaymentDetails('paket-<?= htmlspecialchars($jenis); ?>', 'jumlah-<?= htmlspecialchars($jenis); ?>')">Order</button>
                        </form>
                    </div>
                </div>
                <?php
            }
        } else {
            echo "<p style='text-align: center; font-size: 18px;'>Tidak ada data paket ditemukan.</p>";
        }

        mysqli_close($conn);
        ?>
    </div>
</div>

<script>
    function setPaymentDetails(paketId, jumlahId) {
        const paket = document.getElementById(paketId).value;
        const jumlah = document.getElementById(jumlahId).value;

        document.getElementById('paket_input-' + paketId.split('-')[1]).value = paket;
        document.getElementById('jumlah_input-' + jumlahId.split('-')[1]).value = jumlah;
    }
</script>

<br>
<br>

<?php
require 'footer.php';
?>
