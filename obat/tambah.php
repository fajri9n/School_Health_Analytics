<?php
include "../config/koneksi.php";
?>

<!DOCTYPE html>

<html>

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Data Obat</title>

    <link
        rel="stylesheet"
        href="../assets/css/dashboard.css"
    >

</head>

<!-- NAVBAR -->
<header class="navbar">
    <div class="navbar-logo">
        <span class="logo-icon">🏥</span>
        <span>School Health Analytics</span>
    </div>

    <div class="navbar-title">
        Manajemen Data Obat 
    </div>

    <a href="../dashboard.php" class="btn-dashboard">
        ← Dashboard
    </a>
</header>


</div>

    </div>
</div>

        </form>
        <link
        rel="stylesheet"
        href="../assets/css/dashboard.css"
    >

        <form
            action="proses_tambah.php"
            method="POST"
        >

            <div class="form-group">

                <label>
                    Nama Obat
                </label>

                <input
                    type="text"
                    name="nama_obat"
                    placeholder="Masukkan nama obat"
                    required
                >

            </div>


            <div class="form-group">

                <label>
                    Kategori
                </label>

                <input
                    type="text"
                    name="kategori"
                    placeholder="Contoh: Tablet, Sirup, Salep"
                    required
                >

            </div>


            <div class="form-group">

                <label>
                    Stok
                </label>

                <input
                    type="number"
                    name="stok"
                    placeholder="Masukkan jumlah stok"
                    min="0"
                    required
                >

            </div>


            <div class="form-group">

                <label>
                    Satuan
                </label>

                <input
                    type="text"
                    name="satuan"
                    placeholder="Contoh: Tablet, Botol, Strip"
                    required
                >

            </div>


            <div class="form-group">

                <label>
                    Tanggal Expired
                </label>

                <input
                    type="date"
                    name="expired"
                    required
                >

            </div>


            <div class="form-action">

                <button
                    type="submit"
                    name="simpan"
                    class="btn-save"
                >

                    Simpan Data

                </button>

                <a href="index.php"  class="btn-cancel" > Batal </a>

            </div>

        </form>

    </div>

</div>

</body>

</html>