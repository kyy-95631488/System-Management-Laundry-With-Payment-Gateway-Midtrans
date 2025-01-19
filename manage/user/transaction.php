<?php 
    $title = 'Data Transaction';
    require 'koneksi.php'; 
    require 'header.php'; 

    // Pastikan user telah login
    if (!isset($_SESSION['user_id'])) {
        header("Location: ../../../../login/");
        exit;
    }

    // Ambil user_id dari session
    $user_id = $_SESSION['user_id'];

    // Query untuk mendapatkan data transaksi hanya untuk user yang sedang login
    $sql = "SELECT 
                t.kode_invoice AS kode, 
                p.nama_pelanggan AS nama_pelanggan, 
                t.status AS status_orderan, 
                SUM(dt.total_harga) AS total_harga, 
                t.status_bayar, 
                t.tgl, 
                t.batas_waktu, 
                t.tgl_pembayaran,
                pk.jenis_paket, 
                SUM(dt.qty) AS qty -- Sum of quantity instead of grouping by qty
            FROM 
                transaksi t
            JOIN 
                pelanggan p ON t.id_pelanggan = p.id_pelanggan
            JOIN 
                detail_transaksi dt ON t.id_transaksi = dt.id_transaksi
            JOIN
                paket_cuci pk ON dt.id_paket = pk.id_paket
            WHERE 
                t.id_user = ?
            GROUP BY 
                t.kode_invoice, p.nama_pelanggan, t.status, t.status_bayar, t.tgl, t.batas_waktu, t.tgl_pembayaran, pk.jenis_paket"; // Ensure proper grouping

    // Persiapkan statement
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $user_id); // Bind parameter user_id
    $stmt->execute();
    $result = $stmt->get_result();
?>

<style>
    .status-dibayar {
        color: green;
    }
    .status-pending {
        color: orange;
    }
    .status-gagal {
        color: red;
    }
</style>

<div class="panel-header bg-primary-gradient">
    <div class="page-inner py-5">
        <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row">
            <div>
                <h2 class="text-white pb-2 fw-bold">Data Transaksi</h2>
            </div>
        </div>
        <?php if (isset($_GET['msg'])) : ?>
            <div class="alert alert-success" id="msg"><?= $_GET['msg'] ?></div>
        <?php endif ?>
    </div>
</div>

<div class="page-inner mt--5">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="basic-datatables" class="display table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th style="width: 7%">#</th>
                                    <th>Kode Invoice</th>
                                    <th>Nama Pelanggan</th>
                                    <th>Status Orderan</th>
                                    <th>Total Harga</th>
                                    <th>Status Pembayaran</th>
                                    <th>Tanggal Orderan</th>
                                    <th>Batas Waktu</th>
                                    <th>Tanggal Pembayaran</th>
                                    <th>Jenis Paket</th>
                                    <th>Qty</th>
                                    <!-- <th style="width: 15%;">Aksi</th> -->
                                </tr>
                            </thead>
                            <tbody>
                                <?php if ($result->num_rows > 0): ?>
                                    <?php $no = 1; ?>
                                    <?php while ($row = $result->fetch_assoc()): ?>
                                        <tr>
                                            <td><?= $no++ ?></td>
                                            <td><?= $row['kode'] ?></td>
                                            <td><?= $row['nama_pelanggan'] ?></td>
                                            <td><?= $row['status_orderan'] ?></td>
                                            <td><?= number_format($row['total_harga'], 2, ',', '.') ?></td>
                                            <td class="
                                                <?php
                                                    if ($row['status_bayar'] === 'Dibayar') {
                                                        echo 'status-dibayar';
                                                    } elseif ($row['status_bayar'] === 'Pending') {
                                                        echo 'status-pending';
                                                    } elseif ($row['status_bayar'] === 'Gagal') {
                                                        echo 'status-gagal';
                                                    }
                                                ?>
                                            ">
                                                <?= $row['status_bayar'] ?>
                                            </td>
                                            <td><?= $row['tgl'] ?></td>
                                            <td><?= $row['batas_waktu'] ?></td>
                                            <td><?= $row['tgl_pembayaran'] ?></td>
                                            <td><?= $row['jenis_paket'] ?></td>
                                            <td><?= $row['qty'] ?></td>
                                            <!-- <td>
                                                <div class="form-button-action">
                                                    <?php if ($row['status_bayar'] === 'Belum' || $row['status_bayar'] === 'Pending'): ?>
                                                        <a href="./v2/proses_pembayaran.php?kode_invoice=<?php echo $row['kode'] ?>" 
                                                        class="btn btn-success" 
                                                        data-toggle="tooltip" 
                                                        title="Bayar">
                                                            <i class="fas fa-money-bill-wave"></i> Bayar
                                                        </a>
                                                    <?php else: ?>
                                                        <span class="text-muted">Sudah Dibayar</span>
                                                    <?php endif; ?>
                                                </div>
                                            </td> -->

                                        </tr>
                                    <?php endwhile; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="10" class="text-center">Data tidak ditemukan</td>
                                    </tr>
                                <?php endif; ?>
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
