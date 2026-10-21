
<?php
session_start();
include "../config/koneksi.php";

if (!isset($_SESSION['id'])) {
    header("Location: ../auth/login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: index.php");
    exit;
}

$id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);

if (!$id) {
    header("Location: index.php?error=ID+surat+tidak+valid");
    exit;
}

$stmt = mysqli_prepare($conn, "DELETE FROM surat_uks WHERE id = ?");
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);

$deleted = mysqli_stmt_affected_rows($stmt);
mysqli_stmt_close($stmt);

if ($deleted > 0) {
    header("Location: index.php?success=Surat+berhasil+dihapus");
} else {
    header("Location: index.php?error=Surat+tidak+ditemukan");
}
exit;
