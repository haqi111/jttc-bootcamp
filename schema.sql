-- Skema & seed demo
DROP TABLE IF EXISTS atraksi;
DROP TABLE IF EXISTS kuliner;
DROP TABLE IF EXISTS destinasi;

CREATE TABLE destinasi (
  id_destinasi INT PRIMARY KEY AUTO_INCREMENT,
  nama_destinasi VARCHAR(100) NOT NULL,
  kota VARCHAR(100) NOT NULL,
  alamat VARCHAR(200)
);

CREATE TABLE atraksi (
  id_atraksi INT PRIMARY KEY AUTO_INCREMENT,
  id_destinasi INT NOT NULL,
  nama_atraksi VARCHAR(100) NOT NULL,
  CONSTRAINT fk_atraksi_destinasi
    FOREIGN KEY (id_destinasi) REFERENCES destinasi(id_destinasi)
);

CREATE TABLE kuliner (
  id_kuliner INT PRIMARY KEY AUTO_INCREMENT,
  id_destinasi INT NOT NULL,
  nama_kuliner VARCHAR(100) NOT NULL,
  kategori VARCHAR(50),
  CONSTRAINT fk_kuliner_destinasi
    FOREIGN KEY (id_destinasi) REFERENCES destinasi(id_destinasi)
);

CREATE TABLE users (
  id INT PRIMARY KEY AUTO_INCREMENT,
  username VARCHAR(50) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL 
);


INSERT INTO users (username, password) VALUES
('admin', 'admin123');

INSERT INTO destinasi (nama_destinasi, kota, alamat) VALUES
('Candi Borobudur', 'Magelang', 'Borobudur, Magelang'),
('Pantai Parangtritis', 'Bantul', 'Kretek, Bantul'),
('Dieng Plateau', 'Wonosobo', 'Dieng, Wonosobo');

INSERT INTO atraksi (id_destinasi, nama_atraksi) VALUES
(1, 'Stupa Utama'),
(1, 'Upacara Waisak'),
(2, 'Pasir Pantai'),
(3, 'Sunrise Spot'),
(3, 'Telaga Warna');

INSERT INTO kuliner (id_destinasi, nama_kuliner, kategori) VALUES
(1, 'Wedang Uwuh', 'Minuman'),
(1, 'Getuk Lindri', 'Camilan'),
(2, 'Gudeg Parangtritis', 'Makanan'),
(2, 'Sate Klathak', 'Makanan'),
(3, 'Carica Dieng', 'Camilan'),
(3, 'Serabi Kocor', 'Makanan');
