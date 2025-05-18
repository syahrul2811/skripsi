-- Buat database jika belum ada
CREATE DATABASE IF NOT EXISTS db_saw CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE db_saw;

-- ====================================
-- TABEL: saw_users (Login Pengguna)
-- ====================================
DROP TABLE IF EXISTS saw_users;
CREATE TABLE saw_users (
    id_user INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    name VARCHAR(100),
    role ENUM('admin','siswa') DEFAULT 'siswa',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Menambahkan user admin (opsional)
INSERT INTO saw_users (username, email, password, name, role)
VALUES ('admin', 'admin@example.com', '21232f297a57a5a743894a0e4a801fc3', 'Administrator', 'admin');

-- ====================================
-- TABEL: saw_alternatives (Alternatif Ekstrakurikuler)
-- ====================================
DROP TABLE IF EXISTS saw_alternatives;
CREATE TABLE saw_alternatives (
    id_alternative SMALLINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Menambahkan beberapa alternatif ekstrakurikuler dari Al Amanah Al Bantani
INSERT INTO saw_alternatives (name) VALUES
('Pramuka'),
('Paskibra'),
('Basketball'),
('Futsal'),
('Musik Tradisional'),
('Teater'),
('Paduan Suara'),
('Volleyball');


-- ====================================
-- TABEL: saw_criterias (Kriteria Penilaian)
-- ====================================
DROP TABLE IF EXISTS saw_criterias;
CREATE TABLE saw_criterias (
    id_criteria TINYINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    criteria VARCHAR(100) NOT NULL,
    weight FLOAT NOT NULL,
    attribute ENUM('benefit','cost') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Menambahkan beberapa kriteria penilaian
INSERT INTO saw_criterias (criteria, weight, attribute) VALUES
('Penguasaan Aspek Teknis', 3, 'benefit'),
('Pengalaman Kerja', 5, 'cost'),
('Interpersonal Skill', 4, 'cost'),
('Usia', 4, 'cost'),
('Status Perkawinan', 5, 'benefit');

-- ====================================
-- TABEL: saw_evaluations (Penilaian Ekskul)
-- ====================================
DROP TABLE IF EXISTS saw_evaluations;
CREATE TABLE saw_evaluations (
    id_alternative SMALLINT UNSIGNED NOT NULL,
    id_criteria TINYINT UNSIGNED NOT NULL,
    value FLOAT NOT NULL,
    PRIMARY KEY (id_alternative, id_criteria),
    FOREIGN KEY (id_alternative) REFERENCES saw_alternatives(id_alternative) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (id_criteria) REFERENCES saw_criterias(id_criteria) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Menambahkan nilai penilaian untuk setiap alternatif berdasarkan kriteria
INSERT INTO saw_evaluations (id_alternative, id_criteria, value) VALUES
(1, 5, 25),
(1, 4, 10),
(1, 3, 12),
(1, 2, 200),
(1, 1, 2500),
(2, 5, 15),
(2, 4, 7),
(2, 3, 10),
(2, 2, 145),
(2, 1, 1950),
(3, 5, 14),
(3, 4, 6.5),
(3, 3, 13),
(3, 2, 160),
(3, 1, 1600),
(4, 5, 22),
(4, 4, 9),
(4, 3, 9),
(4, 2, 170),
(4, 1, 2100);
