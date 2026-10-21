<?php
session_start();
include "../config/koneksi.php";

$id = $_GET['id'];

// Admin tidak boleh menghapus akun sendiri
if($id == $_SESSION['id']){

echo "<script>

alert('Anda tidak dapat menghapus akun yang sedang digunakan!');

window.location='index.php';

</script>";

exit;

}

mysqli_query($conn,"
DELETE FROM users
WHERE id='$id'
");

header("Location:index.php");
exit;