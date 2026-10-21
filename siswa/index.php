<?php
session_start();

include "../config/koneksi.php";

/* Cek apakah user sudah login */
if (!isset($_SESSION['id'])) {
    header("Location: ../auth/login.php");
    exit;
}

/* Ambil kata kunci pencarian */
$cari = "";

if (isset($_GET['cari'])) {
    $cari = mysqli_real_escape_string($conn, $_GET['cari']);
}

/* Query data siswa */
if ($cari != "") {

    $query = mysqli_query(
        $conn,
        "SELECT * FROM siswa
         WHERE nis LIKE '%$cari%'
         OR nama LIKE '%$cari%'
         OR kelas LIKE '%$cari%'
         ORDER BY id DESC"
    );

} else {

    $query = mysqli_query(
        $conn,
        "SELECT * FROM siswa
         ORDER BY id DESC"
    );

}
?>

<!DOCTYPE html>
<html>

<head>

    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Siswa - School Health Analytics</title>

    <link rel="stylesheet" href="../assets/css/dashboard.css">

</head>

<body>

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


<!-- ACTION BAR -->
<div class="action-bar">

    <a href="tambah.php" class="btn-tambah">
        + Tambah Siswa
    </a>

    <form method="GET" action="" class="search-form">

    <input
        type="text"
        name="cari"
        placeholder="Cari NIS, nama, atau kelas..."
        value="<?= htmlspecialchars($_GET['cari'] ?? '') ?>"
    >

    <button type="submit" class="btn-cari">
        Cari
    </button>

    <?php if ($cari != "") { ?>

        <a href="index.php" class="btn-reset">
            Reset
        </a>

    <?php } ?>

</form>

</div>

    </div>
</div>

        </form>


        <div class="table-wrapper">

            <table>

                <thead>

                    <tr>

                        <th>No</th>

                        <th>NIS</th>

                        <th>Nama</th>

                        <th>Jenis Kelamin</th>

                        <th>Kelas</th>

                        <th>Tanggal Lahir</th>

                        <th>Alamat</th>

                        <th>Aksi</th>

                    </tr>

                </thead>

                <tbody>

                <?php

                $no = 1;

                if (mysqli_num_rows($query) > 0) {

                    while ($data = mysqli_fetch_assoc($query)) {

                ?>

                    <tr>

                        <td>

                            <?php echo $no++; ?>

                        </td>

                        <td>

                            <?php
                            echo htmlspecialchars(
                                $data['nis']
                            );
                            ?>

                        </td>

                        <td>

                            <?php
                            echo htmlspecialchars(
                                $data['nama']
                            );
                            ?>

                        </td>

                        <td>

                            <?php

                            if (
                                $data['jenis_kelamin']
                                == "L"
                            ) {

                                echo "Laki-laki";

                            } else {

                                echo "Perempuan";

                            }

                            ?>

                        </td>

                        <td>

                            <?php
                            echo htmlspecialchars(
                                $data['kelas']
                            );
                            ?>

                        </td>

                        <td>

                            <?php

                            if (
                                !empty(
                                    $data['tanggal_lahir']
                                )
                            ) {

                                echo date(
                                    "d-m-Y",
                                    strtotime(
                                        $data[
                                            'tanggal_lahir'
                                        ]
                                    )
                                );

                            } else {

                                echo "-";

                            }

                            ?>

                        </td>

                        <td>

                            <?php

                            if (
                                !empty(
                                    $data['alamat']
                                )
                            ) {

                                echo htmlspecialchars(
                                    $data['alamat']
                                );

                            } else {

                                echo "-";

                            }

                            ?>

                        </td>

                        <td class="action">

                            <a
                                href="edit.php?id=<?php echo $data['id']; ?>"
                                class="btn-edit"
                            >

                                Edit

                            </a>

                            <a
                                href="hapus.php?id=<?php echo $data['id']; ?>"
                                class="btn-delete"
                                onclick="return confirm('Yakin ingin menghapus data siswa ini?')"
                            >

                                Hapus

                            </a>

                        </td>

                    </tr>

                <?php

                    }

                } else {

                ?>

                    <tr>

                        <td colspan="8" class="empty-table">

                            Data siswa belum tersedia.

                        </td>

                    </tr>

                <?php

                }

                ?>

                </tbody>

            </table>

        </div>

    </div>

</div>

</body>

</html>