
<?php
session_start();
if (!isset($_SESSION['id'])) {
    header("Location: ../auth/login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Export Laporan UKS</title>
<link rel="stylesheet" href="../assets/css/dashboard.css">
</head>
<body>

<header class="navbar">
    <div class="navbar-logo">🏥 School Health Analytics</div>
    <div class="navbar-title">Export Laporan</div>
    <a href="index.php" class="btn-dashboard">← Kunjungan</a>
</header>

<div class="section form-section">
    <h2>Export Laporan Kunjungan</h2>
    <p>Pilih periode data yang ingin direkap.</p>

    <form method="GET">
        <label>Tanggal Mulai</label>
        <input type="date" name="mulai"
            value="<?=date('Y-m-01');?>" required>

        <br><br>

        <label>Tanggal Akhir</label>
        <input type="date" name="akhir"
            value="<?=date('Y-m-d');?>" required>

        <br><br>

        <button type="submit"
            formaction="export_pdf.php"
            class="btn-save">
            Export PDF
        </button>

        <button type="submit"
            formaction="export_excel.php"
            class="btn-tambah">
            Export Excel
        </button>
    </form>
</div>

</body>
</html>
