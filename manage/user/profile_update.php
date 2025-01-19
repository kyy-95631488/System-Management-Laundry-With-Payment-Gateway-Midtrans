<?php
require 'koneksi.php'; 

$id_user = $_SESSION['user_id'] ?? null;

if (!$id_user) {
    echo json_encode(['success' => false, 'message' => 'ID pengguna tidak ditemukan!']);
    exit();
}

$nama_pelanggan = trim($_POST['nama_pelanggan']);
$alamat_pelanggan = trim($_POST['alamat_pelanggan']);
$jenis_kelamin = trim($_POST['jenis_kelamin']);
$telp_pelanggan = trim($_POST['telp_pelanggan']);
$no_ktp = trim($_POST['no_ktp']) ?: '0';
$password_baru = trim($_POST['password_baru']); // Password baru opsional

// Validasi server-side
if (empty($nama_pelanggan) || empty($alamat_pelanggan) || empty($jenis_kelamin) || empty($telp_pelanggan)) {
    echo json_encode(['success' => false, 'message' => 'Field wajib diisi kecuali Nomor KTP dan Password Baru!']);
    exit();
}

// Jika password diisi, hash dengan SHA-512 + salt
if (!empty($password_baru)) {
    $salt = bin2hex(random_bytes(16)); // Generate salt 16 byte
    $password_hashed = hash('sha512', $password_baru . $salt); // Hash password + salt
} else {
    $password_hashed = null; // Jika tidak ada perubahan password
}

// Cek apakah data pelanggan sudah ada
$query = "SELECT * FROM pelanggan WHERE id_pelanggan = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $id_user);
$stmt->execute();
$result = $stmt->get_result();
$pelanggan = $result->fetch_assoc();

// Update pelanggan
if ($pelanggan) {
    $update_query = "UPDATE pelanggan SET nama_pelanggan = ?, alamat_pelanggan = ?, jenis_kelamin = ?, telp_pelanggan = ?, no_ktp = ? WHERE id_pelanggan = ?";
    $update_stmt = $conn->prepare($update_query);
    $update_stmt->bind_param("sssssi", $nama_pelanggan, $alamat_pelanggan, $jenis_kelamin, $telp_pelanggan, $no_ktp, $id_user);
    $update_success = $update_stmt->execute();

    // Update password jika diisi
    if ($password_hashed) {
        $update_password_query = "UPDATE user SET password = ?, salt = ? WHERE id_user = ?";
        $update_password_stmt = $conn->prepare($update_password_query);
        $update_password_stmt->bind_param("ssi", $password_hashed, $salt, $id_user);
        $update_success = $update_password_stmt->execute();
    }

    if ($update_success) {
        echo json_encode(['success' => true, 'message' => 'Profil berhasil diperbarui!']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Gagal memperbarui data profil!']);
    }
} else {
    // Insert pelanggan baru
    $insert_query = "INSERT INTO pelanggan (id_pelanggan, nama_pelanggan, alamat_pelanggan, jenis_kelamin, telp_pelanggan, no_ktp) VALUES (?, ?, ?, ?, ?, ?)";
    $insert_stmt = $conn->prepare($insert_query);
    $insert_stmt->bind_param("isssss", $id_user, $nama_pelanggan, $alamat_pelanggan, $jenis_kelamin, $telp_pelanggan, $no_ktp);
    $insert_success = $insert_stmt->execute();

    // Tambahkan password jika diisi
    if ($insert_success && $password_hashed) {
        $insert_password_query = "UPDATE user SET password = ?, salt = ? WHERE id_user = ?";
        $insert_password_stmt = $conn->prepare($insert_password_query);
        $insert_password_stmt->bind_param("ssi", $password_hashed, $salt, $id_user);
        $insert_success = $insert_password_stmt->execute();
    }

    if ($insert_success) {
        echo json_encode(['success' => true, 'message' => 'Profil berhasil ditambahkan!']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Gagal menambahkan profil!']);
    }
}
?>
