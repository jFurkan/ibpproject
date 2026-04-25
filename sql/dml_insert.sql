-- Ornek veriler

USE dataset_site;

INSERT INTO users (username, email, password) VALUES
('ahmet', 'ahmet@mail.com', SHA('ahmet123')),
('ayse', 'ayse@mail.com', SHA('ayse123')),
('mehmet', 'mehmet@mail.com', SHA('mehmet123')),
('fatma', 'fatma@mail.com', SHA('fatma123'));

INSERT INTO categories (cat_name) VALUES
('Makine Ogrenimi'),
('Saglik'),
('Ekonomi'),
('Egitim'),
('Spor');

INSERT INTO datasets (user_id, cat_id, title, description, filename, filesize) VALUES
(1, 1, 'Iris Flower Dataset', 'Cicek siniflandirma verisi', 'iris.csv', 4500),
(2, 2, 'Diyabet Verisi', 'Hasta diyabet olcum verileri', 'diabetes.csv', 12000),
(3, 3, 'Borsa Verileri 2023', 'Yillik borsa fiyat verileri', 'stock2023.csv', 35000),
(1, 4, 'Ogrenci Basari Verileri', 'Sinav notlari ve devamsizlik', 'students.csv', 8000),
(4, 5, 'Futbol Istatistikleri', 'Supertlig 2023 oyuncu istatistikleri', 'football.csv', 22000);

INSERT INTO tags (tag_name) VALUES
('csv'), ('classification'), ('regression'), ('medical'), ('finance'), ('sports'), ('education');

INSERT INTO dataset_tags (dataset_id, tag_id) VALUES
(1, 1), (1, 2),
(2, 1), (2, 4),
(3, 1), (3, 5),
(4, 1), (4, 7),
(5, 1), (5, 6);

INSERT INTO comments (dataset_id, user_id, comment_text) VALUES
(1, 2, 'Cok guzel bir dataset, tesekkurler'),
(1, 3, 'Makine ogrenimi icin ideal'),
(2, 1, 'Saglik arastirmasi icin kullandim'),
(3, 4, 'Finans projemde ise yaradi'),
(4, 2, 'Odev icin cok faydali oldu');

INSERT INTO ratings (dataset_id, user_id, rating) VALUES
(1, 2, 5), (1, 3, 4),
(2, 1, 5), (2, 4, 3),
(3, 1, 4), (3, 2, 5),
(4, 3, 4), (4, 4, 5),
(5, 1, 3), (5, 2, 4);

INSERT INTO downloads (dataset_id, user_id) VALUES
(1, 2), (1, 3), (1, 4),
(2, 1), (2, 3),
(3, 1), (3, 2), (3, 4),
(4, 2), (4, 3),
(5, 1), (5, 2), (5, 3), (5, 4);
