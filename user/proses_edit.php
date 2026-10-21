<?php

include "../config/koneksi.php";

$id = $_POST['id'];
$nama = $_POST['nama'];
$username = $_POST['username'];
$role = $_POST['role'];
$password = $_POST['password'];

// Cek username dipakai user lain
$cek = mysqli_query($conn,"
SELECT * FROM users
WHERE username='$username'
AND id != '$id'
");

if(mysqli_num_rows($cek) > 0){

echo "<script>

alert('Username sudah digunakan!');

window.location='edit.php?id=$id';

</script>";

exit;

}

if(!empty($password)){

$password = password_hash($password,PASSWORD_DEFAULT);

mysqli_query($conn,"
UPDATE users SET

nama='$nama',

username='$username',

password='$password',

role='$role'

WHERE id='$id'

");

}else{

mysqli_query($conn,"
UPDATE users SET

nama='$nama',

username='$username',

role='$role'

WHERE id='$id'

");

}

header("Location:index.php");
exit;