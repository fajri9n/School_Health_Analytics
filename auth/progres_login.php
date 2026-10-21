<?php
session_start();
include "../config/koneksi.php";

$username = $_POST['username'];
$password = $_POST['password'];

$query = mysqli_query($conn, "SELECT * FROM users WHERE username='$username'");

if(mysqli_num_rows($query) > 0){

    $data = mysqli_fetch_assoc($query);

    if(password_verify($password, $data['password'])){

        $_SESSION['id'] = $data['id'];
        $_SESSION['nama'] = $data['nama'];
        $_SESSION['role'] = $data['role'];

        header("Location: ../dashboard.php");
        exit();

    }else{

        echo "Password Salah!";

    }

}else{

    echo "Username Tidak Ditemukan!";

}