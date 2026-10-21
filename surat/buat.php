
<?php
session_start();
include "../config/koneksi.php";

if (!isset($_SESSION['id'])) {
    header("Location: ../auth/login.php");
    exit;
}

$kunjungan = mysqli_query($conn, "
    SELECT k.id, k.tanggal, k.jam, s.nama AS siswa
    FROM kunjungan k
    JOIN siswa s ON s.id = k.siswa_id
    ORDER BY k.tanggal DESC, k.jam DESC
");

$pesan = $_GET['error'] ?? '';
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Buat Surat UKS</title>
<link rel="stylesheet" href="../assets/css/dashboard.css">

<style>
.form-wrap {
    max-width: 850px;
    margin: 28px auto;
    padding: 0 16px;
}
.form-card {
    background: #fff;
    border-radius: 12px;
    padding: 24px;
    box-shadow: 0 4px 18px #0001;
}
.grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 16px;
}
.field {
    display: flex;
    flex-direction: column;
    gap: 7px;
    margin-bottom: 15px;
}
.field input, .field select, .field textarea {
    font: inherit;
    padding: 11px;
    border: 1px solid #cbd5e1;
    border-radius: 7px;
    width: 100%;
    box-sizing: border-box;
}
.field textarea { min-height: 90px; }
.wide { grid-column: 1 / -1; }

.surat-btn {
    display: inline-block;
    background: #d9232e;
    color: #fff;
    padding: 11px 16px;
    border-radius: 7px;
    text-decoration: none;
    border: 0;
    cursor: pointer;
}
.btn-batal {
    display: inline-block;
    background: #64748b;
    color: #fff;
    padding: 11px 16px;
    border-radius: 7px;
    text-decoration: none;
    border: 0;
    cursor: pointer;
    font: inherit;
    font-size: 14px;
    transition: background 0.2s ease;
}

.btn-batal:hover {
    background: #475569;
}
.notice {
    background: #fff7ed;
    border: 1px solid #fed7aa;
    padding: 12px;
    border-radius: 8px;
    margin-bottom: 16px;
}
.exam-panel {
    display: none;
    grid-column: 1 / -1;
    padding: 18px;
    margin-bottom: 15px;
    background: #f0f7ff;
    border: 1px solid #c9def7;
    border-radius: 10px;
}
.exam-panel h3 { margin-top: 0; }
.exam-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 12px;
}
.exam-item {
    padding: 12px;
    background: #fff;
    border-radius: 7px;
    border: 1px solid #e2e8f0;
    overflow-wrap: anywhere;
}
.exam-item strong {
    display: block;
    margin-bottom: 5px;
    color: #475569;
    font-size: 13px;
}
.exam-item span { color: #172554; }
.exam-status { margin-bottom: 12px; }

@media(max-width:650px) {
    .grid, .exam-grid { grid-template-columns: 1fr; }
    .wide, .exam-panel { grid-column: auto; }
}
</style>
</head>

<body>
<header class="navbar">
    <div class="navbar-logo">
        <span class="logo-icon">🏥</span>
        <span>School Health Analytics</span>
    </div>
    <div class="navbar-title">Buat Surat UKS</div>
    <a href="index.php" class="btn-dashboard">← Daftar Surat</a>
</header>

<main class="form-wrap">
<section class="form-card">
    <h2>Buat Surat Baru</h2>
    <p>Data pemeriksaan diambil dari kunjungan UKS yang dipilih.</p>

    <?php if ($pesan): ?>
        <div class="notice"><?= htmlspecialchars($pesan) ?></div>
    <?php endif; ?>

    <form action="simpan.php" method="post">
        <div class="grid">

            <div class="field wide">
                <label for="kunjungan_id">Pilih kunjungan siswa</label>
                <select id="kunjungan_id" name="kunjungan_id" required>
                    <option value="">-- Pilih kunjungan --</option>
                    <?php while ($k = mysqli_fetch_assoc($kunjungan)): ?>
                        <option value="<?= (int)$k['id'] ?>">
                            <?= htmlspecialchars(
                                $k['tanggal'].' '.$k['jam'].' — '.$k['siswa']
                            ) ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>

            <!-- Panel otomatis khusus surat rujukan -->
            <div class="exam-panel" id="dataPemeriksaan">
                <h3>🩺 Data Pemeriksaan UKS</h3>
                <p>Data ini diambil otomatis dari kunjungan yang dipilih.</p>
                <div class="exam-status" id="statusPemeriksaan">
                    Pilih kunjungan siswa terlebih dahulu.
                </div>
                <div class="exam-grid" id="isiPemeriksaan"></div>
            </div>

            <div class="field">
                <label for="jenis_surat">Jenis surat</label>
                <select id="jenis_surat" name="jenis_surat" required>
                    <option value="pulang">Surat Izin Pulang</option>
                    <option value="rujukan">Surat Rujukan Puskesmas</option>
                </select>
            </div>

            <div class="field">
                <label for="tanggal_surat">Tanggal surat</label>
                <input id="tanggal_surat" type="date"
                       name="tanggal_surat"
                       value="<?= date('Y-m-d') ?>" required>
            </div>

            <div class="field wide">
                <label for="alasan">Alasan pulang / rujukan</label>
                <textarea id="alasan" name="alasan" required
                    placeholder="Isi berdasarkan kondisi dan pertimbangan petugas..."></textarea>
            </div>

            <div class="field wide" id="tujuanWrap">
                <label for="tujuan_rujukan">Tujuan puskesmas (khusus rujukan)</label>
                <input id="tujuan_rujukan" name="tujuan_rujukan"
                       maxlength="150" placeholder="Nama puskesmas">
            </div>

            <div class="field wide">
                <label for="nama_guru_piket">Nama guru piket yang mengesahkan</label>
                <input id="nama_guru_piket" name="nama_guru_piket"
                       maxlength="100" required
                       placeholder="Nama lengkap guru piket">
            </div>

        </div>

      <div class="form-actions">
    <button class="surat-btn" type="submit">
        Simpan dan Pratinjau
    </button>

    <a href="index.php" class="btn-batal">
        Batal
    </a>
</div>
    </form>
</section>
</main>

<script>
const jenis = document.getElementById('jenis_surat');
const kunjungan = document.getElementById('kunjungan_id');
const wrap = document.getElementById('tujuanWrap');
const tujuan = document.getElementById('tujuan_rujukan');
const panel = document.getElementById('dataPemeriksaan');
const statusPemeriksaan = document.getElementById('statusPemeriksaan');
const isi = document.getElementById('isiPemeriksaan');

let requestAktif = 0;

function toggleForm() {
    const rujukan = jenis.value === 'rujukan';

    wrap.style.display = rujukan ? 'flex' : 'none';
    tujuan.required = rujukan;
    panel.style.display = rujukan ? 'block' : 'none';

    if (rujukan) {
        loadPemeriksaan();
    }
}

function loadPemeriksaan() {
    const id = kunjungan.value;
    const requestId = ++requestAktif;

    isi.replaceChildren();

    if (jenis.value !== 'rujukan') return;

    if (!id) {
        statusPemeriksaan.textContent =
            'Pilih kunjungan siswa terlebih dahulu.';
        return;
    }

    statusPemeriksaan.textContent = 'Memuat data pemeriksaan...';

    fetch('data_kunjungan.php?id=' + encodeURIComponent(id))
        .then(response => {
            if (!response.ok) throw new Error('Gagal mengakses server');
            return response.json();
        })
        .then(data => {
            if (requestId !== requestAktif) return;

            if (data.error) {
                statusPemeriksaan.textContent = data.error;
                return;
            }

            const fields = [
                ['Nama siswa', data.nama],
                ['Tanggal kunjungan', data.tanggal],
                ['Keluhan', data.nama_keluhan],
                ['Suhu tubuh', data.suhu],
                ['Tekanan darah', data.tekanan_darah],
                ['Obat', data.nama_obat],
                ['Tindakan', data.tindakan],
                ['Kondisi siswa', data.kondisi],
                ['Jam masuk', data.jam],
                ['Jam keluar', data.jam_keluar],
                ['Catatan pemeriksaan', data.catatan]
            ];

            fields.forEach(([label, value]) => {
                const item = document.createElement('div');
                item.className = 'exam-item';

                const title = document.createElement('strong');
                title.textContent = label;

                const content = document.createElement('span');
                content.textContent =
                    value !== null && value !== undefined && value !== ''
                    ? value : '-';

                item.append(title, content);
                isi.appendChild(item);
            });

            statusPemeriksaan.textContent =
                'Data kunjungan berhasil dimuat.';
        })
        .catch(() => {
            if (requestId !== requestAktif) return;

            statusPemeriksaan.textContent =
                'Data gagal dimuat. Periksa data_kunjungan.php dan nama kolom database.';
        });
}

jenis.addEventListener('change', toggleForm);
kunjungan.addEventListener('change', loadPemeriksaan);

toggleForm();
</script>
</body>
</html>
