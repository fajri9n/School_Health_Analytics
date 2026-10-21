
<?php
$host = "ftpupload.net";
$user = "if0_43235745";
$pass = "8dsl0NqGODUu1";
$db   = "if0_43235745_school_health_analytics";

$conn = mysqli_connect($host, $user, $pass, $db);

if (!$conn) {
    error_log("Database connection failed: " . mysqli_connect_error());
    http_response_code(500);
    exit("Koneksi database gagal. Periksa konfigurasi hosting.");
}

mysqli_set_charset($conn, "utf8mb4");

$koneksi = $conn;
