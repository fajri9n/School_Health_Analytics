<?php
session_start();
include "../config/koneksi.php";

// Cek apakah user sudah login
if (!isset($_SESSION['id'])) {
    header("Location: ../auth/login.php");
    exit;
}

// Cek apakah request menggunakan metode POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: index.php");
    exit;
}

if (!$conn) {
    exit("<h3>Koneksi ke database gagal. Pastikan database online terhubung.</h3><p><a href='tambah.php'>Kembali</a></p>");
}

try {
    $nama     = trim($_POST['nama'] ?? '');
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    $role     = trim($_POST['role'] ?? 'user');

    // Validasi field wajib
    if ($nama === '' || $username === '' || $password === '') {
        echo "<script>
                alert('Semua field wajib diisi!');
                window.location='tambah.php';
              </script>";
        exit;
    }

    // Validasi role (hanya admin atau user)
    if (!in_array($role, ['admin', 'user'], true)) {
        $role = 'user';
    }

    // Cek apakah username sudah digunakan
    $stmt_cek = mysqli_prepare($conn, "SELECT id FROM users WHERE username = ?");
    if ($stmt_cek) {
        mysqli_stmt_bind_param($stmt_cek, "s", $username);
        mysqli_stmt_execute($stmt_cek);
        mysqli_stmt_store_result($stmt_cek);

        if (mysqli_stmt_num_rows($stmt_cek) > 0) {
            mysqli_stmt_close($stmt_cek);
            echo "<script>
                    alert('Username sudah digunakan! Silakan gunakan username lain.');
                    window.location='tambah.php';
                  </script>";
            exit;
        }
        mysqli_stmt_close($stmt_cek);
    } else {
        // Fallback jika prepare gagal
        $user_esc = mysqli_real_escape_string($conn, $username);
        $res_cek  = mysqli_query($conn, "SELECT id FROM users WHERE username = '$user_esc'");
        if ($res_cek && mysqli_num_rows($res_cek) > 0) {
            echo "<script>
                    alert('Username sudah digunakan! Silakan gunakan username lain.');
                    window.location='tambah.php';
                  </script>";
            exit;
        }
    }

    // Hash password sebelum disimpan
    $password_hash = password_hash($password, PASSWORD_DEFAULT);

    // Simpan user baru menggunakan prepared statement
    $stmt_insert = mysqli_prepare($conn, "INSERT INTO users (nama, username, password, role) VALUES (?, ?, ?, ?)");
    if ($stmt_insert) {
        mysqli_stmt_bind_param($stmt_insert, "ssss", $nama, $username, $password_hash, $role);
        $execute = mysqli_stmt_execute($stmt_insert);
        mysqli_stmt_close($stmt_insert);
    } else {
        // Fallback jika prepare gagal (misal driver hosting tertentu)
        $nama_esc = mysqli_real_escape_string($conn, $nama);
        $user_esc = mysqli_real_escape_string($conn, $username);
        $pass_esc = mysqli_real_escape_string($conn, $password_hash);
        $role_esc = mysqli_real_escape_string($conn, $role);

        $sql_fallback = "INSERT INTO users (nama, username, password, role) VALUES ('$nama_esc', '$user_esc', '$pass_esc', '$role_esc')";
        $execute      = mysqli_query($conn, $sql_fallback);
    }

    if ($execute) {
        if (!headers_sent()) {
            header("Location: index.php?pesan=tambah_berhasil");
        } else {
            echo "<script>window.location='index.php?pesan=tambah_berhasil';</script>";
        }
        exit;
    } else {
        $error_msg = mysqli_error($conn);
        echo "<script>
                alert('Gagal menyimpan user: " . addslashes($error_msg) . "');
                window.location='tambah.php';
              </script>";
        exit;
    }

} catch (Throwable $e) {
    error_log("Error tambah user: " . $e->getMessage());
    echo "<div style='font-family: Arial, sans-serif; max-width: 600px; margin: 40px auto; padding: 20px; border: 1px solid #e2e8f0; border-radius: 8px; background: #fff;'>";
    echo "<h3 style='color: #dc2626;'>Gagal Menyimpan Data User</h3>";
    echo "<p><strong>Pesan Kesalahan:</strong> " . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8') . "</p>";
    echo "<p><a href='tambah.php' style='display: inline-block; padding: 8px 16px; background: #4f46e5; color: #fff; text-decoration: none; border-radius: 4px;'>← Kembali ke Formulir Tambah User</a></p>";
    echo "</div>";
    exit;
}
