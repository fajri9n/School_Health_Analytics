<?php
session_start();
include "../config/koneksi.php";
if (!isset($_SESSION['id'])) { header("Location: ../auth/login.php"); exit; }
if ($_SERVER['REQUEST_METHOD'] !== 'POST') { header("Location: buat.php"); exit; }
$kunjungan_id = filter_input(INPUT_POST, 'kunjungan_id', FILTER_VALIDATE_INT);
$jenis = $_POST['jenis_surat'] ?? '';
$tanggal = $_POST['tanggal_surat'] ?? '';
$alasan = trim($_POST['alasan'] ?? '');
$tujuan = trim($_POST['tujuan_rujukan'] ?? '');
$guru = trim($_POST['nama_guru_piket'] ?? '');
if (!$kunjungan_id || !in_array($jenis, ['pulang','rujukan'], true) || !$alasan || !$guru || !preg_match('/^\d{4}-\d{2}-\d{2}$/',$tanggal) || ($jenis==='rujukan' && !$tujuan)) {
    header("Location: buat.php?error=".urlencode("Data belum lengkap atau tidak valid.")); exit;
}
$cek=mysqli_prepare($conn,"SELECT id FROM kunjungan WHERE id=?");
mysqli_stmt_bind_param($cek,"i",$kunjungan_id); mysqli_stmt_execute($cek); $ada=mysqli_stmt_get_result($cek);
if (!$ada || !mysqli_num_rows($ada)) { header("Location: buat.php?error=".urlencode("Kunjungan tidak ditemukan.")); exit; }
$nomor = "UKS/".date('Y')."/".str_pad((string)(time()%1000000),6,'0',STR_PAD_LEFT);
$stmt=mysqli_prepare($conn,"INSERT INTO surat_uks (kunjungan_id, nomor_surat, jenis_surat, tanggal_surat, alasan, tujuan_rujukan, petugas_id, nama_guru_piket) VALUES (?,?,?,?,?,?,?,?)");
$petugas=(int)$_SESSION['id'];
mysqli_stmt_bind_param($stmt,"isssssis",$kunjungan_id,$nomor,$jenis,$tanggal,$alasan,$tujuan,$petugas,$guru);
if(!mysqli_stmt_execute($stmt)){ exit("Gagal menyimpan surat. Pastikan SQL tambahan sudah dijalankan. ".htmlspecialchars(mysqli_error($conn))); }
header("Location: cetak.php?id=".mysqli_insert_id($conn)); exit;
?>