-- Ornek veriler (Oracle)

INSERT INTO users (username, email, password) VALUES ('ahmet', 'ahmet@mail.com', RAWTOHEX(STANDARD_HASH('ahmet123', 'SHA1')));
INSERT INTO users (username, email, password) VALUES ('ayse', 'ayse@mail.com', RAWTOHEX(STANDARD_HASH('ayse123', 'SHA1')));
INSERT INTO users (username, email, password) VALUES ('mehmet', 'mehmet@mail.com', RAWTOHEX(STANDARD_HASH('mehmet123', 'SHA1')));
INSERT INTO users (username, email, password) VALUES ('fatma', 'fatma@mail.com', RAWTOHEX(STANDARD_HASH('fatma123', 'SHA1')));

INSERT INTO categories (cat_name) VALUES ('Makine Ogrenimi');
INSERT INTO categories (cat_name) VALUES ('Saglik');
INSERT INTO categories (cat_name) VALUES ('Ekonomi');
INSERT INTO categories (cat_name) VALUES ('Egitim');
INSERT INTO categories (cat_name) VALUES ('Spor');

INSERT INTO datasets (user_id, cat_id, title, description, filename, filesize) VALUES (1, 1, 'Iris Flower Dataset', 'Cicek siniflandirma verisi', 'iris.csv', 4500);
INSERT INTO datasets (user_id, cat_id, title, description, filename, filesize) VALUES (2, 2, 'Diyabet Verisi', 'Hasta diyabet olcum verileri', 'diabetes.csv', 12000);
INSERT INTO datasets (user_id, cat_id, title, description, filename, filesize) VALUES (3, 3, 'Borsa Verileri 2023', 'Yillik borsa fiyat verileri', 'stock2023.csv', 35000);
INSERT INTO datasets (user_id, cat_id, title, description, filename, filesize) VALUES (1, 4, 'Ogrenci Basari Verileri', 'Sinav notlari ve devamsizlik', 'students.csv', 8000);
INSERT INTO datasets (user_id, cat_id, title, description, filename, filesize) VALUES (4, 5, 'Futbol Istatistikleri', 'Supertlig 2023 oyuncu istatistikleri', 'football.csv', 22000);

INSERT INTO tags (tag_name) VALUES ('csv');
INSERT INTO tags (tag_name) VALUES ('classification');
INSERT INTO tags (tag_name) VALUES ('regression');
INSERT INTO tags (tag_name) VALUES ('medical');
INSERT INTO tags (tag_name) VALUES ('finance');
INSERT INTO tags (tag_name) VALUES ('sports');
INSERT INTO tags (tag_name) VALUES ('education');

INSERT INTO dataset_tags (dataset_id, tag_id) VALUES (1, 1);
INSERT INTO dataset_tags (dataset_id, tag_id) VALUES (1, 2);
INSERT INTO dataset_tags (dataset_id, tag_id) VALUES (2, 1);
INSERT INTO dataset_tags (dataset_id, tag_id) VALUES (2, 4);
INSERT INTO dataset_tags (dataset_id, tag_id) VALUES (3, 1);
INSERT INTO dataset_tags (dataset_id, tag_id) VALUES (3, 5);
INSERT INTO dataset_tags (dataset_id, tag_id) VALUES (4, 1);
INSERT INTO dataset_tags (dataset_id, tag_id) VALUES (4, 7);
INSERT INTO dataset_tags (dataset_id, tag_id) VALUES (5, 1);
INSERT INTO dataset_tags (dataset_id, tag_id) VALUES (5, 6);

INSERT INTO comments (dataset_id, user_id, comment_text) VALUES (1, 2, 'Cok guzel bir dataset, tesekkurler');
INSERT INTO comments (dataset_id, user_id, comment_text) VALUES (1, 3, 'Makine ogrenimi icin ideal');
INSERT INTO comments (dataset_id, user_id, comment_text) VALUES (2, 1, 'Saglik arastirmasi icin kullandim');
INSERT INTO comments (dataset_id, user_id, comment_text) VALUES (3, 4, 'Finans projemde ise yaradi');
INSERT INTO comments (dataset_id, user_id, comment_text) VALUES (4, 2, 'Odev icin cok faydali oldu');

INSERT INTO ratings (dataset_id, user_id, rating) VALUES (1, 2, 5);
INSERT INTO ratings (dataset_id, user_id, rating) VALUES (1, 3, 4);
INSERT INTO ratings (dataset_id, user_id, rating) VALUES (2, 1, 5);
INSERT INTO ratings (dataset_id, user_id, rating) VALUES (2, 4, 3);
INSERT INTO ratings (dataset_id, user_id, rating) VALUES (3, 1, 4);
INSERT INTO ratings (dataset_id, user_id, rating) VALUES (3, 2, 5);
INSERT INTO ratings (dataset_id, user_id, rating) VALUES (4, 3, 4);
INSERT INTO ratings (dataset_id, user_id, rating) VALUES (4, 4, 5);
INSERT INTO ratings (dataset_id, user_id, rating) VALUES (5, 1, 3);
INSERT INTO ratings (dataset_id, user_id, rating) VALUES (5, 2, 4);

INSERT INTO downloads (dataset_id, user_id) VALUES (1, 2);
INSERT INTO downloads (dataset_id, user_id) VALUES (1, 3);
INSERT INTO downloads (dataset_id, user_id) VALUES (1, 4);
INSERT INTO downloads (dataset_id, user_id) VALUES (2, 1);
INSERT INTO downloads (dataset_id, user_id) VALUES (2, 3);
INSERT INTO downloads (dataset_id, user_id) VALUES (3, 1);
INSERT INTO downloads (dataset_id, user_id) VALUES (3, 2);
INSERT INTO downloads (dataset_id, user_id) VALUES (3, 4);
INSERT INTO downloads (dataset_id, user_id) VALUES (4, 2);
INSERT INTO downloads (dataset_id, user_id) VALUES (4, 3);
INSERT INTO downloads (dataset_id, user_id) VALUES (5, 1);
INSERT INTO downloads (dataset_id, user_id) VALUES (5, 2);
INSERT INTO downloads (dataset_id, user_id) VALUES (5, 3);
INSERT INTO downloads (dataset_id, user_id) VALUES (5, 4);

COMMIT;
