<?php
include "../config/koneksi.php";

$cari = "";

// Pesan dari proses hapus
if (isset($_GET['pesan'])) {

    if ($_GET['pesan'] == 'hapus_berhasil') {
        echo "<script>alert('Obat berhasil dihapus.');</script>";
    }

    if ($_GET['pesan'] == 'hapus_gagal') {
        echo "<script>alert('Obat gagal dihapus.');</script>";
    }

    if ($_GET['pesan'] == 'obat_masih_digunakan') {
        echo "<script>alert('Obat tidak dapat dihapus karena masih digunakan dalam data kunjungan.');</script>";
    }
}

if (isset($_GET['cari'])) {
    $cari = $_GET['cari'];

    $query = mysqli_query(
        $conn,
        "SELECT * FROM obat
        WHERE nama_obat LIKE '%$cari%'
        OR keterangan LIKE '%$cari%'
        ORDER BY id DESC"
    );
} else {
    $query = mysqli_query(
        $conn,
        "SELECT * FROM obat
        ORDER BY id DESC"
    );
}
?>

<!DOCTYPE html>

<html>

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Data Obat</title>

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


<!-- ACTION BAR -->
<div class="action-bar">

    <a href="tambah.php" class="btn-tambah">
        + Tambah Obat
    </a>

    <form method="GET" action="" class="search-form">

    <input
        type="text"
        name="cari"
        placeholder="Cari nama atau kategori..."
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

                        <th>Nama Obat</th>

                        <th>Kategori</th>

                        <th>Stok</th>

                        <th>Satuan</th>

                        <th>Tanggal Expired</th>

                        <th>Aksi</th>

                    </tr>

                </thead>


                <tbody>

                <?php

                if (
                    $query &&
                    mysqli_num_rows($query) > 0
                ) {

                    $no = 1;

                    while (
                        $data =
                        mysqli_fetch_assoc($query)
                    ) {

                ?>

                    <tr>

                        <td>

                            <?php
                            echo $no++;
                            ?>

                        </td>


                        <td>

                            <?php

                            echo htmlspecialchars(
                                $data['nama_obat']
                            );

                            ?>

                        </td>


                        <td>

                            <?php

                            echo htmlspecialchars(
                                $data['kategori']
                            );

                            ?>

                        </td>


                        <td>

                            <?php

                            echo htmlspecialchars(
                                $data['stok']
                            );

                            ?>

                        </td>


                        <td>

                            <?php

                            echo htmlspecialchars(
                                $data['satuan']
                            );

                            ?>

                        </td>


                        <td>

                            <?php

                            echo htmlspecialchars(
                                $data['expired']
                            );

                            ?>

                        </td>


                        <td>

                            <a
                                href="edit.php?id=<?php
                                echo $data['id'];
                                ?>"
                                class="btn-edit"
                            >

                                Edit

                            </a>


                            <a
                                href="hapus.php?id=<?php
                                echo $data['id'];
                                ?>"
                                class="btn-delete"
                                onclick="return confirm(
                                'Yakin ingin menghapus obat ini?'
                                )"
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

                        <td
                            colspan="7"
                            class="empty-table"
                        >

                            Data obat belum tersedia.

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