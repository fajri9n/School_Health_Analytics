    <?php
    session_start();
    include "../config/koneksi.php";
    if (!isset($_SESSION['id'])) { header("Location: ../auth/login.php"); exit; }
    $id=filter_input(INPUT_GET,'id',FILTER_VALIDATE_INT);
    if(!$id){http_response_code(400);exit("ID surat tidak valid.");}
    $sql="SELECT su.*,k.tanggal AS tanggal_kunjungan,k.jam,k.jam_keluar,k.suhu,k.tekanan_darah,k.tindakan,k.catatan,k.kondisi,
    s.nama AS siswa,s.kelas,s.nis,kl.nama_keluhan,o.nama_obat,u.nama AS petugas
    FROM surat_uks su
    JOIN kunjungan k ON k.id=su.kunjungan_id
    LEFT JOIN siswa s ON s.id=k.siswa_id
    LEFT JOIN keluhan kl ON kl.id=k.keluhan_id
    LEFT JOIN obat o ON o.id=k.obat_id
    LEFT JOIN users u ON u.id=su.petugas_id
    WHERE su.id=?";
    $stmt=mysqli_prepare($conn,$sql);mysqli_stmt_bind_param($stmt,"i",$id);mysqli_stmt_execute($stmt);$res=mysqli_stmt_get_result($stmt);$d=mysqli_fetch_assoc($res);
    if(!$d){http_response_code(404);exit("Surat tidak ditemukan.");}
    function e($v){return htmlspecialchars((string)($v!==null&&$v!==''?$v:'-'),ENT_QUOTES,'UTF-8');}
    function tgl($v){return $v?date('d F Y',strtotime($v)):'-';}
    $rujukan=$d['jenis_surat']==='rujukan';
    ?>
    <!DOCTYPE html><html lang="id"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title><?= $rujukan?'Surat Rujukan':'Surat Izin Pulang' ?></title>
    <style>
    @page{size:A4;margin:15mm}*{box-sizing:border-box}body{font-family:Arial,sans-serif;color:#171717;margin:0;background:#eee;font-size:12pt}.toolbar{max-width:210mm;margin:15px auto;display:flex;gap:10px}.toolbar button,.toolbar a{border:0;border-radius:6px;background:#c8202d;color:white;padding:10px 15px;text-decoration:none;font-size:14px;cursor:pointer}.toolbar a{background:#475569}.paper{background:white;width:210mm;min-height:297mm;margin:0 auto;padding:15mm;box-shadow:0 2px 15px #0002}.letterhead{height:38mm;border-bottom:2px solid #c8202d;margin-bottom:20px;display:flex;align-items:center;justify-content:center;text-align:center}.letterhead img{max-width:100%;max-height:37mm;object-fit:contain}.school-name{font-size:19px;font-weight:bold}.small{font-size:10px;line-height:1.4}.title{text-align:center;font-weight:bold;text-decoration:underline;margin:20px 0 5px;font-size:15px}.center{text-align:center}.meta{margin-bottom:18px}.info{border-collapse:collapse;width:100%;margin:12px 0}.info td{padding:4px 3px;vertical-align:top}.section{font-weight:bold;margin:13px 0 5px}.paragraph{line-height:1.55;text-align:justify}.signatures{display:grid;grid-template-columns:1fr 1fr;gap:40px;margin-top:35px;text-align:center}.sign-space{height:70px}.line{border-top:1px solid #222;display:inline-block;min-width:190px;padding-top:5px}.note{font-size:10px;color:#555}@media print{body{background:#fff}.toolbar{display:none}.paper{margin:0;width:auto;min-height:auto;padding:0;box-shadow:none}.letterhead{break-inside:avoid}}@media screen and (max-width:800px){.paper{width:100%;min-height:100vh;padding:18px}.toolbar{padding:0 12px}}
    </style></head><body>
    <div class="toolbar"><button onclick="window.print()">Cetak / Simpan PDF</button><a href="index.php">Kembali ke Daftar Surat</a></div>
    <main class="paper">
    <div class="letterhead">
    <?php if(file_exists(__DIR__.'/kop-sekolah.pn')): ?><img src="kop-sekolah.png" alt="Kop SMK Telkom Lampung"><?php else: ?><div><div class="school-name">YAYASAN PENDIDIKAN TELKOM</div><div class="school-name">SMK TELKOM LAMPUNG</div><div class="small">Jl. Raya Gadingrejo, Kec. Gadingrejo, Kab. Pringsewu, Lampung 35372</div><div class="small">NPSN: 69944770 | www.smktelkom-lpg.sch.id</div><div class="note">Kop gambar resmi dapat ditambahkan sebagai surat/kop-sekolah.png</div></div><?php endif; ?>
    </div>
    <div class="center"><div class="title"><?= $rujukan?'SURAT RUJUKAN PEMERIKSAAN KESEHATAN':'SURAT IZIN PULANG' ?></div><div>Nomor: <?= e($d['nomor_surat']) ?></div></div>
    <p>Kepada Yth. <?= $rujukan?'Petugas Puskesmas '.e($d['tujuan_rujukan']):'Orang Tua/Wali Siswa' ?> di tempat.</p>
    <p class="paragraph"><?= $rujukan?'Dengan ini kami merujuk siswa berikut untuk mendapatkan pemeriksaan kesehatan lebih lanjut.':'Dengan ini kami menerangkan bahwa siswa berikut diizinkan pulang berdasarkan kondisi dan pertimbangan petugas UKS.' ?></p>
    <div class="section">A. Identitas Siswa</div><table class="info">
    <tr><td width="34%">Nama</td><td width="3%">:</td><td><?= e($d['siswa']) ?></td></tr>
    <tr><td>Kelas</td><td>:</td><td><?= e($d['kelas'] ?? '') ?></td></tr><tr><td>NIS</td><td>:</td><td><?= e($d['nis'] ?? '') ?></td></tr>
    <tr><td>Tanggal Kunjungan</td><td>:</td><td><?= e(tgl($d['tanggal_kunjungan'])) ?></td></tr><tr><td>Jam Masuk UKS</td><td>:</td><td><?= e($d['jam']) ?></td></tr><tr><td>Jam Keluar UKS</td><td>:</td><td><?= e($d['jam_keluar']) ?></td></tr>
    </table>
    <div class="section">B. Keluhan dan Pemeriksaan Awal UKS</div><table class="info">
    <tr><td width="34%">Keluhan</td><td width="3%">:</td><td><?= e($d['nama_keluhan']) ?></td></tr><tr><td>Suhu Tubuh</td><td>:</td><td><?= e($d['suhu']) ?> °C</td></tr><tr><td>Tekanan Darah</td><td>:</td><td><?= e($d['tekanan_darah']) ?></td></tr><tr><td>Obat / Perlengkapan</td><td>:</td><td><?= e($d['nama_obat']) ?></td></tr><tr><td>Tindakan UKS</td><td>:</td><td><?= nl2br(e($d['tindakan'])) ?></td></tr><tr><td>Catatan Tambahan</td><td>:</td><td><?= nl2br(e($d['catatan'])) ?></td></tr><tr><td>Kondisi Terakhir</td><td>:</td><td><?= e($d['kondisi']) ?></td></tr><tr><td>Petugas UKS</td><td>:</td><td><?= e($d['petugas']) ?></td></tr>
    </table>
    <div class="section"><?= $rujukan?'C. Alasan dan Tujuan Rujukan':'C. Alasan Izin Pulang' ?></div><p class="paragraph"><?= nl2br(e($d['alasan'])) ?></p>
    <?php if($rujukan): ?><p class="paragraph">Informasi di atas merupakan catatan pemeriksaan awal UKS untuk membantu pemeriksaan lanjutan. Penilaian dan diagnosis selanjutnya dilakukan oleh tenaga kesehatan di fasilitas rujukan.</p><?php endif; ?>
    <p class="paragraph">Demikian surat ini dibuat untuk digunakan sebagaimana mestinya.</p>
    <p style="text-align:right;margin-top:25px;">Lampung, <?= e(tgl($d['tanggal_surat'])) ?></p>
    <div class="signatures"><div>Petugas UKS<div class="sign-space"></div><span class="line"><?= e($d['petugas']) ?></span></div><div>Guru Piket<div class="sign-space"></div><span class="line"><?= e($d['nama_guru_piket']) ?></span></div></div>
    <p class="note">Dokumen ini memuat data kesehatan siswa. Simpan dan distribusikan hanya kepada pihak yang berwenang.</p>
    </main></body></html>