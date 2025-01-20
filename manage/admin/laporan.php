<?php
$title = 'Data Laporan';
require 'koneksi.php';

// Check user role (Assuming session holds the role data)
$user_role = $_SESSION['role']; // Assuming 'role' is stored in the session (admin or user)

if ($user_role == 'admin') {
    // Admin can see all transactions
    $query = "SELECT transaksi.*, pelanggan.nama_pelanggan, detail_transaksi.total_harga, detail_transaksi.total_bayar,
              transaksi.biaya_tambahan, transaksi.pajak, transaksi.diskon
              FROM transaksi 
              INNER JOIN pelanggan ON pelanggan.id_pelanggan = transaksi.id_pelanggan 
              INNER JOIN detail_transaksi ON detail_transaksi.id_transaksi = transaksi.id_transaksi";
} else if ($user_role == 'user') {
    // Users can see only their own transactions, assuming user is identified by id_pelanggan
    $id_pelanggan = $_SESSION['id_pelanggan']; // Assuming 'id_pelanggan' is stored in the session
    $query = "SELECT transaksi.*, pelanggan.nama_pelanggan, detail_transaksi.total_harga, detail_transaksi.total_bayar,
              transaksi.biaya_tambahan, transaksi.pajak, transaksi.diskon
              FROM transaksi 
              INNER JOIN pelanggan ON pelanggan.id_pelanggan = transaksi.id_pelanggan 
              INNER JOIN detail_transaksi ON detail_transaksi.id_transaksi = transaksi.id_transaksi
              WHERE transaksi.id_pelanggan = '$id_pelanggan'";
}

$data = mysqli_query($conn, $query);

require 'header.php';
?>

<div class="panel-header bg-primary-gradient">
    <div class="page-inner py-5">
        <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row">
            <div>
                <h2 class="text-white pb-2 fw-bold">Dashboard</h2>
            </div>
        </div>
        <?php if (isset($_SESSION['msg']) && $_SESSION['msg'] <> '') { ?>
            <div class="alert alert-success" role="alert" id="msg">
                <?= $_SESSION['msg']; ?>
            </div>
        <?php }
        $_SESSION['msg'] = ''; ?>
    </div>
</div>
<div class="page-inner mt--5">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex align-items-center">
                        <h4 class="card-title"><?= $title; ?></h4>
                        <a href="cetak.php" target="_blank" class="btn btn-primary btn-round ml-auto">
                            <i class="fas fa-print"></i>
                            Cetak Laporan
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="basic-datatables" class="display table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th style="width: 7%">#</th>
                                    <th>Kode</th>
                                    <th>Nama Pelanggan</th>
                                    <th>Status</th>
                                    <th>Pembayaran</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $no = 1;
                                if (mysqli_num_rows($data) > 0) {
                                    while ($trans = mysqli_fetch_assoc($data)) {
                                        $total = ($trans['total_harga'] + $trans['biaya_tambahan'] + $trans['pajak']) * (1 - $trans['diskon']);
                                ?>

                                        <tr>
                                            <td><?= $no++; ?></td>
                                            <td><?= $trans['kode_invoice']; ?></td>
                                            <td><?= $trans['nama_pelanggan']; ?></td>
                                            <td><?= $trans['status']; ?></td>
                                            <td><?= $trans['status_bayar']; ?></td>
                                            <td><?= 'Rp ' . number_format($total); ?></td>
                                        </tr>
                                <?php }
                                } else {
                                    echo '<tr><td colspan="6">Tidak ada data transaksi.</td></tr>';
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
require 'footer.php';
?>
