<?php
session_start();

include "../config/koneksi.php";

/* Cek login */
if (!isset($_SESSION['id'])) {
    header("Location: ../auth/login.php");
    exit;
}
?>

<!DOCTYPE html>
<html>

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Data Siswa</title>

    <link rel="stylesheet" href="../assets/css/dashboard.css">

</head>

<!-- NAVBAR -->
<header class="navbar">
    <div class="navbar-logo">
        <span class="logo-icon">🏥</span>
        <span>School Health Analytics</span>
    </div>

    <div class="navbar-title">
        Manajemen Data Siswa
    </div>

    <a href="../dashboard.php" class="btn-dashboard">
        ← Dashboard
    </a>
</header>


</div>

    </div>
</div>

        </form>

    <div class="section form-section">

        <h2>Form Data Siswa</h2>

        <p>
            Lengkapi seluruh data di bawah ini.
        </p>


        <form
            action="proses_tambah.php"
            method="POST"
            class="data-form"
        >

            <div class="form-group">

                <label>NIS</label>

                <input
                    type="text"
                    name="nis"
                    placeholder="Masukkan NIS"
                    required
                >

            </div>


            <div class="form-group">

                <label>Nama Siswa</label>

                <input
                    type="text"
                    name="nama"
                    placeholder="Masukkan nama siswa"
                    required
                >

            </div>


            <div class="form-group">

                <label>Jenis Kelamin</label>

                <select
                    name="jenis_kelamin"
                    required
                >

                    <option value="">

                        -- Pilih Jenis Kelamin --

                    </option>

                    <option value="L">

                        Laki-laki

                    </option>

                    <option value="P">

                        Perempuan

                    </option>

                </select>

            </div>


            <div class="form-group">

                <label>Kelas</label>

                <input
                    type="text"
                    name="kelas"
                    placeholder="Contoh: X RPL 2"
                    required
                >

            </div>


            <div class="form-group">

                <label>Tanggal Lahir</label>

                <input
                    type="date"
                    name="tanggal_lahir"
                    required
                >

            </div>


            <div class="form-group">

                <label>Alamat</label>

                <textarea
                    name="alamat"
                    placeholder="Masukkan alamat siswa"
                    rows="5"
                    required
                ></textarea>

            </div>


            <div class="form-button">

                <button
                    type="submit"
                    class="btn-save"
                >

                    Simpan Data

                </button>


                <a
                    href="index.php"
                    class="btn-cancel"
                >

                    Batal

                </a>

            </div>

        </form>

    </div>

</div>

</body>

</html>