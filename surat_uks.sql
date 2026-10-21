-- Jalankan di phpMyAdmin setelah memilih database school_health_analytics.
-- Tabel ini tambahan; tidak mengubah tabel lama.
CREATE TABLE IF NOT EXISTS surat_uks (
    id INT AUTO_INCREMENT PRIMARY KEY,
    kunjungan_id INT NOT NULL,
    nomor_surat VARCHAR(50) NOT NULL UNIQUE,
    jenis_surat ENUM('pulang','rujukan') NOT NULL,
    tanggal_surat DATE NOT NULL,
    alasan TEXT NOT NULL,
    tujuan_rujukan VARCHAR(150) DEFAULT NULL,
    petugas_id INT NOT NULL,
    nama_guru_piket VARCHAR(100) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_surat_kunjungan (kunjungan_id),
    INDEX idx_surat_tanggal (tanggal_surat)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
