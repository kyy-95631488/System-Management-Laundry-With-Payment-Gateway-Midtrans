<?php 
    require 'koneksi.php'; 
    require 'header.php'; 

    $id_user = $_SESSION['user_id'] ?? null;

    if (!$id_user) {
        echo "<script>alert('ID pengguna tidak ditemukan!'); window.location.href = 'login.php';</script>";
        exit();
    }

    $query = "SELECT * FROM pelanggan WHERE id_pelanggan = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id_user);
    $stmt->execute();
    $result = $stmt->get_result();
    $pelanggan = $result->fetch_assoc();

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $nama_pelanggan = trim($_POST['nama_pelanggan']);
        $alamat_pelanggan = trim($_POST['alamat_pelanggan']);
        $jenis_kelamin = trim($_POST['jenis_kelamin']);
        $telp_pelanggan = trim($_POST['telp_pelanggan']);
        $no_ktp = trim($_POST['no_ktp']) ?: '0';
    
        // Validasi server-side untuk memastikan data tidak kosong
        if (empty($nama_pelanggan) || empty($alamat_pelanggan) || empty($jenis_kelamin) || empty($telp_pelanggan)) {
            echo "<script>alert('Semua field wajib diisi kecuali Nomor KTP!'); window.history.back();</script>";
            exit();
        } else {
            if (!$pelanggan) {
                $insert_query = "INSERT INTO pelanggan (id_pelanggan, nama_pelanggan, alamat_pelanggan, jenis_kelamin, telp_pelanggan, no_ktp) 
                                VALUES (?, ?, ?, ?, ?, ?)";
                $insert_stmt = $conn->prepare($insert_query);
                $insert_stmt->bind_param("isssss", $id_user, $nama_pelanggan, $alamat_pelanggan, $jenis_kelamin, $telp_pelanggan, $no_ktp);
                
                if ($insert_stmt->execute()) {
                    echo "<script>alert('Profil pengguna berhasil ditambahkan!');</script>";
                } else {
                    echo "<script>alert('Gagal menambahkan profil pengguna!');</script>";
                }
            } else {
                $update_query = "UPDATE pelanggan SET nama_pelanggan = ?, alamat_pelanggan = ?, jenis_kelamin = ?, telp_pelanggan = ?, no_ktp = ? WHERE id_pelanggan = ?";
                $update_stmt = $conn->prepare($update_query);
                $update_stmt->bind_param("sssssi", $nama_pelanggan, $alamat_pelanggan, $jenis_kelamin, $telp_pelanggan, $no_ktp, $id_user);
                
                if ($update_stmt->execute()) {
                    echo "<script>alert('Data profil berhasil diperbarui!');</script>";
                } else {
                    echo "<script>alert('Gagal memperbarui data profil!');</script>";
                }
            }
        }
    }    
?>

<style>
    .container {
        margin-top: 30px;
        margin-bottom: 30px;
    }

    .card {
        background-color: #fff;
        border-radius: 8px;
        padding: 20px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        margin-top: 20px;
    }

    .card h2 {
        text-align: center;
        margin-bottom: 20px;
    }

    .form-group {
        margin-bottom: 15px;
    }

    .form-control {
        border-radius: 5px;
        padding: 10px;
        width: 100%;
        border: 1px solid #ccc;
    }

    .btn-primary {
        background-color: #007bff;
        border: none;
        padding: 10px 15px;
        border-radius: 5px;
        color: white;
        cursor: pointer;
    }

    .btn-primary:hover {
        background-color: #0056b3;
    }
    #togglePassword {
        font-size: 18px;
        color: #666;
        padding: 5px;
    }
    #togglePassword:hover {
        color: #333;
    }

</style>

<div class="container">
    <h2>Profil Pengguna</h2>
    <div class="card">
        <form id="profileForm">
            <div class="form-group">
                <label for="nama_pelanggan">Nama Lengkap:</label>
                <input type="text" class="form-control" id="nama_pelanggan" name="nama_pelanggan" value="<?= isset($pelanggan['nama_pelanggan']) ? $pelanggan['nama_pelanggan'] : ''; ?>" required>
            </div>
            <div class="form-group">
                <label for="alamat_pelanggan">Alamat:</label>
                <textarea class="form-control" id="alamat_pelanggan" name="alamat_pelanggan" required><?= isset($pelanggan['alamat_pelanggan']) ? $pelanggan['alamat_pelanggan'] : ''; ?></textarea>
            </div>
            <div class="form-group">
                <label for="jenis_kelamin">Jenis Kelamin:</label>
                <select class="form-control" id="jenis_kelamin" name="jenis_kelamin" required>
                    <option value="L" <?= (isset($pelanggan['jenis_kelamin']) && $pelanggan['jenis_kelamin'] == 'L') ? 'selected' : ''; ?>>Laki-laki</option>
                    <option value="P" <?= (isset($pelanggan['jenis_kelamin']) && $pelanggan['jenis_kelamin'] == 'P') ? 'selected' : ''; ?>>Perempuan</option>
                </select>
            </div>
            <div class="form-group">
                <label for="telp_pelanggan">Nomor Telepon:</label>
                <input type="text" class="form-control" id="telp_pelanggan" name="telp_pelanggan" value="<?= isset($pelanggan['telp_pelanggan']) ? $pelanggan['telp_pelanggan'] : ''; ?>" required>
            </div>
            <div class="form-group">
                <label for="no_ktp">Nomor KTP (Opsional):</label>
                <input type="text" class="form-control" id="no_ktp" name="no_ktp" value="<?= isset($pelanggan['no_ktp']) ? $pelanggan['no_ktp'] : ''; ?>">
            </div>
            <div class="form-group">
                <label for="password_baru">Password Baru (Opsional):</label>
                <div style="position: relative;">
                    <input type="password" class="form-control" id="password_baru" name="password_baru">
                    <span id="togglePassword" style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); cursor: pointer;">
                        👁️
                    </span>
                </div>
            </div>
            <button type="submit" class="btn btn-primary">Update Profil</button>
        </form>
    </div>
</div>

<script>
    document.getElementById('profileForm').addEventListener('submit', function(event) {
        event.preventDefault();

        var nama = document.getElementById('nama_pelanggan').value.trim();
        var alamat = document.getElementById('alamat_pelanggan').value.trim();
        var jenisKelamin = document.getElementById('jenis_kelamin').value;
        var telp = document.getElementById('telp_pelanggan').value.trim();

        var passwordBaru = document.getElementById('password_baru').value.trim();
        if (passwordBaru && passwordBaru.length < 8) {
            alert('Password baru harus minimal 8 karakter!');
            return;
        }

        if (!nama || !alamat || !jenisKelamin || !telp) {
            alert('Semua field wajib diisi kecuali Nomor KTP!');
            return;
        }

        var formData = new FormData(this);

        fetch('profile_update.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Profil berhasil diperbarui!');
            } else {
                alert('Gagal memperbarui profil!');
            }
        })
        .catch(error => console.error('Error:', error));
    });
</script>

<script>
    document.getElementById('togglePassword').addEventListener('click', function () {
    var passwordField = document.getElementById('password_baru');
    var type = passwordField.getAttribute('type') === 'password' ? 'text' : 'password';
    passwordField.setAttribute('type', type);

    // Ganti ikon berdasarkan tipe input
    this.textContent = type === 'password' ? '👁️' : '🙈';
});
</script>

<br>
<br>
<br>

<?php require 'footer.php'; ?>
