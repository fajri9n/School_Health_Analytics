<?php
include "../config/koneksi.php";

if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit;
}

$id = $_GET['id'];

$query = mysqli_query(
    $conn,
    "SELECT * FROM siswa WHERE id='$id'"
);

$data = mysqli_fetch_assoc($query);

if (!$data) {
    echo "Data siswa tidak ditemukan.";
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Data Siswa</title>

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
        <form
            action="proses_edit.php"
            method="POST"
        >

            <input
                type="hidden"
                name="id"
                value="<?php echo $data['id']; ?>"
            >

            <div class="form-group">

                <label>NIS</label>

                <input
                    type="text"
                    name="nis"
                    value="<?php echo htmlspecialchars($data['nis']); ?>"
                    required
                >

            </div>

            <div class="form-group">

                <label>Nama Siswa</label>

                <input
                    type="text"
                    name="nama"
                    value="<?php echo htmlspecialchars($data['nama']); ?>"
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

                    <option
                        value="L"
                        <?php
                        if ($data['jenis_kelamin'] == "L") {
                            echo "selected";
                        }
                        ?>
                    >
                        Laki-laki
                    </option>

                    <option
                        value="P"
                        <?php
                        if ($data['jenis_kelamin'] == "P") {
                            echo "selected";
                        }
                        ?>
                    >
                        Perempuan
                    </option>

                </select>

            </div>

            <div class="form-group">

                <label>Kelas</label>

                <input
                    type="text"
                    name="kelas"
                    value="<?php echo htmlspecialchars($data['kelas']); ?>"
                    required
                >

            </div>

            <div class="form-group">

                <label>Tanggal Lahir</label>

                <input
                    type="date"
                    name="tanggal_lahir"
                    value="<?php echo $data['tanggal_lahir']; ?>"
                    required
                >

            </div>

            <div class="form-group">

                <label>Alamat</label>

                <textarea
                    name="alamat"
                    required
                ><?php echo htmlspecialchars($data['alamat']); ?></textarea>

            </div>

            <div class="form-action">

                <button
                    type="submit"
                    name="simpan"
                    class="btn-save"
                >
                    Simpan Perubahan
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