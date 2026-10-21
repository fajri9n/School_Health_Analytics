<?php
$host = "localhost";
$user = "root";
$pass = "";
$db   = "school_health_analytics";

$conn = @mysqli_connect($host, $user, $pass, $db);
if (!$conn) {
    // Fallback ke 127.0.0.1 jika socket localhost bermasalah
    $conn = @mysqli_connect("127.0.0.1", $user, $pass, $db);
}

if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

mysqli_set_charset($conn, "utf8mb4");

// Variabel alias untuk kompatibilitas
$koneksi = $conn;