<?php
session_start();
include "../config/koneksi.php";

if(!isset($_SESSION['id'])){
    header("Location: ../auth/login.php");
    exit;
}
?>

<!DOCTYPE html>
<html>

<head>

<meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Tambah User</title>

    <link rel="stylesheet" href="../assets/css/dashboard.css">

</head>

<!-- NAVBAR -->
<header class="navbar">
    <div class="navbar-logo">
        <span class="logo-icon">🏥</span>
        <span>School Health Analytics</span>
    </div>

    <div class="navbar-title">
        Manajemen Data User
    </div>

    <a href="../dashboard.php" class="btn-dashboard">
        ← Dashboard
    </a>
</header>


</div>

    </div>
</div>

        </form>

    <div class="section form-card">

        <form action="proses_tambah.php" method="POST">

            <label>Nama</label>

            <input
            type="text"
            name="nama"
            required>

            <label>Username</label>

            <input
            type="text"
            name="username"
            required>

            <label>Password</label>

            <input
            type="password"
            name="password"
            required>

            <label>Role</label>

            <select
            name="role"
            required>

                <option value="">-- Pilih Role --</option>

                <option value="admin">
                    Administrator
                </option>

                <option value="user">
                    Petugas UKS
                </option>

            </select>

            <br><br>

            <button
            type="submit"
            class="btn-save">

                Simpan

            </button>

            <a
            href="index.php"
            class="btn-cancel">

                Batal

            </a>

        </form>

    </div>

</div>

</body>
</html>