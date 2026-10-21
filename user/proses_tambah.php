<?php

include "../config/koneksi.php";

$nama = $_POST['nama'];
$username = $_POST['username'];
$password = password_hash($_POST['password'], PASSWORD_DEFAULT);
$role = $_POST['role'];

// Cek username sudah ada atau belum
$cek = mysqli_query($conn, "SELECT * FROM users WHERE username='$username'");

if(mysqli_num_rows($cek) > 0){
    echo "<script>
            alert('Username sudah digunakan!');
            window.location='tambah.php';
          </script>";
    exit;
}

mysqli_query($conn,"
INSERT INTO users
(
nama,
username,
password,
role
)
VALUES
(
'$nama',
'$username',
'$password',
'$role'
)
");

header("Location:index.php");
exit;
?>