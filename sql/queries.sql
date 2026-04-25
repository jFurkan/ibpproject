-- DML Sorgu Ornekleri

-- 1) SUBQUERY: En cok indirilen dataseti bul
SELECT title, filename FROM datasets
WHERE dataset_id = (
    SELECT dataset_id FROM downloads
    GROUP BY dataset_id
    ORDER BY COUNT(*) DESC
    LIMIT 1
);

-- 2) JOIN: Dataset baslikini, yukleyen kullanicinin adini ve kategori adini goster
SELECT d.title, u.username, c.cat_name, d.upload_date
FROM datasets d
JOIN users u ON d.user_id = u.user_id
JOIN categories c ON d.cat_id = c.cat_id;

-- 3) GROUP BY: Her kategoride kac tane dataset var
SELECT c.cat_name, COUNT(d.dataset_id) AS dataset_sayisi
FROM categories c
LEFT JOIN datasets d ON c.cat_id = d.cat_id
GROUP BY c.cat_id, c.cat_name;

-- 4) DATE FUNCTION: Son 30 gun icinde yuklenen datasetler
SELECT title, upload_date, DATE_FORMAT(upload_date, '%d/%m/%Y') AS tarih
FROM datasets
WHERE upload_date >= DATE_SUB(NOW(), INTERVAL 30 DAY);

-- 5) CHARACTER FUNCTION: Kullanici adlarini buyuk harfe cevir ve email domainini goster
SELECT UPPER(username) AS buyuk_ad, SUBSTRING(email, INSTR(email,'@')+1) AS domain
FROM users;
