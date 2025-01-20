<?php
$title = 'Edit Data Pengguna';
require 'koneksi.php';

// Ambil role yang ada di database
$roleQuery = "SELECT DISTINCT role FROM user";
$roleResult = mysqli_query($conn, $roleQuery);

$id_user = $_GET['id'];
$query = "SELECT * FROM user WHERE id_user = '$id_user'";
$queryedit = mysqli_query($conn, $query);

if (isset($_POST['btn-simpan'])) {
    $nama = $_POST['nama_user'];
    $username = $_POST['username'];
    $role = $_POST['role'];

    // Check if password is being updated
    if (!empty($_POST['password'])) {
        // Generate a random salt
        $salt = bin2hex(random_bytes(32)); // 16 bytes = 32 hex characters
        $password = hash('sha512', $_POST['password'] . $salt); // Combine password + salt
        $query = "UPDATE user 
                  SET nama_user = '$nama', 
                      username = '$username', 
                      role = '$role', 
                      password = '$password', 
                      salt = '$salt' 
                  WHERE id_user = '$id_user'";
    } else {
        $query = "UPDATE user 
                  SET nama_user = '$nama', 
                      username = '$username', 
                      role = '$role' 
                  WHERE id_user = '$id_user'";
    }

    $update = mysqli_query($conn, $query);
    if ($update) {
        $_SESSION['msg'] = 'Berhasil Update ' . $role;
        header('location:pengguna.php');
        exit();
    } else {
        $_SESSION['msg'] = 'Gagal mengupdate data ' . $role . '!!!';
        header('location:pengguna.php');
        exit();
    }
}

require 'header.php';
?>
<div class="content">
    <div class="page-inner">
        <div class="page-header">
            <h4 class="page-title">Forms</h4>
            <ul class="breadcrumbs">
                <li class="nav-home">
                    <a href="index.php">
                        <i class="flaticon-home"></i>
                    </a>
                </li>
                <li class="separator">
                    <i class="flaticon-right-arrow"></i>
                </li>
                <li class="nav-item">
                    <a href="pengguna.php">Pengguna</a>
                </li>
                <li class="separator">
                    <i class="flaticon-right-arrow"></i>
                </li>
                <li class="nav-item">
                    <a href="#">Edit Pengguna</a>
                </li>
            </ul>
        </div>
        <div class="row">
            <div class="col-md-10">
                <div class="card">
                    <div class="card-header">
                        <div class="card-title"><?= $title; ?></div>
                    </div>
                    <?php while ($edit = mysqli_fetch_array($queryedit)) { ?>
                        <form action="" method="POST">
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="largeInput">Nama Pengguna</label>
                                    <input type="text" name="nama_user" class="form-control form-control" id="defaultInput" value="<?= $edit['nama_user']; ?>" placeholder="Nama...">
                                </div>
                                <div class="form-group">
                                    <label for="largeInput">Username</label>
                                    <input type="text" name="username" class="form-control form-control" id="defaultInput" value="<?= $edit['username']; ?>" placeholder="Username...">
                                </div>
                                <div class="form-group">
                                    <label for="largeInput">Password</label>
                                    <input type="text" name="password" class="form-control form-control" id="defaultInput">
                                    <small>Kosongkan jika tidak melakukan perubahan password</small>
                                </div>
                                <div class="form-group">
                                    <label for="defaultSelect">Role</label>
                                    <select name="role" class="form-control form-control" id="defaultSelect">
                                        <?php while ($roleRow = mysqli_fetch_array($roleResult)) : ?>
                                            <?php if ($roleRow['role'] == $edit['role']) : ?>
                                                <option value="<?= $roleRow['role'] ?>" selected><?= ucfirst($roleRow['role']) ?></option>
                                            <?php else : ?>
                                                <option value="<?= $roleRow['role'] ?>"><?= ucfirst($roleRow['role']) ?></option>
                                            <?php endif; ?>
                                        <?php endwhile; ?>
                                    </select>
                                </div>
                                <input type="hidden" name="id_user" value="<?= $edit['id_user']; ?>">
                                <div class="card-action">
                                    <button type="submit" name="btn-simpan" class="btn btn-success">Submit</button>
                                    <a href="javascript:void(0)" onclick="window.history.back();" class="btn btn-danger">Batal</a>
                                </div>
                            </div>
                        </form>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php require 'footer.php'; ?>
