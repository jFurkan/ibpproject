-- Dataset Paylasim Sitesi - DDL (Oracle)

CREATE TABLE users (
    user_id    NUMBER GENERATED ALWAYS AS IDENTITY PRIMARY KEY,
    username   VARCHAR2(50)  NOT NULL,
    email      VARCHAR2(100) NOT NULL,
    password   VARCHAR2(64)  NOT NULL,
    created_at DATE DEFAULT SYSDATE
);

CREATE TABLE categories (
    cat_id   NUMBER GENERATED ALWAYS AS IDENTITY PRIMARY KEY,
    cat_name VARCHAR2(100) NOT NULL
);

CREATE TABLE datasets (
    dataset_id  NUMBER GENERATED ALWAYS AS IDENTITY PRIMARY KEY,
    user_id     NUMBER,
    cat_id      NUMBER,
    title       VARCHAR2(200) NOT NULL,
    description VARCHAR2(4000),
    filename    VARCHAR2(255),
    filesize    NUMBER,
    upload_date DATE DEFAULT SYSDATE,
    CONSTRAINT fk_ds_user FOREIGN KEY (user_id) REFERENCES users(user_id),
    CONSTRAINT fk_ds_cat  FOREIGN KEY (cat_id)  REFERENCES categories(cat_id)
);

CREATE TABLE tags (
    tag_id   NUMBER GENERATED ALWAYS AS IDENTITY PRIMARY KEY,
    tag_name VARCHAR2(50) NOT NULL
);

CREATE TABLE dataset_tags (
    dataset_id NUMBER,
    tag_id     NUMBER,
    PRIMARY KEY (dataset_id, tag_id),
    CONSTRAINT fk_dt_dataset FOREIGN KEY (dataset_id) REFERENCES datasets(dataset_id),
    CONSTRAINT fk_dt_tag     FOREIGN KEY (tag_id)     REFERENCES tags(tag_id)
);

CREATE TABLE comments (
    comment_id   NUMBER GENERATED ALWAYS AS IDENTITY PRIMARY KEY,
    dataset_id   NUMBER,
    user_id      NUMBER,
    comment_text VARCHAR2(4000) NOT NULL,
    comment_date DATE DEFAULT SYSDATE,
    CONSTRAINT fk_cm_dataset FOREIGN KEY (dataset_id) REFERENCES datasets(dataset_id),
    CONSTRAINT fk_cm_user    FOREIGN KEY (user_id)    REFERENCES users(user_id)
);

CREATE TABLE ratings (
    rating_id  NUMBER GENERATED ALWAYS AS IDENTITY PRIMARY KEY,
    dataset_id NUMBER,
    user_id    NUMBER,
    rating     NUMBER CHECK (rating BETWEEN 1 AND 5),
    CONSTRAINT fk_rt_dataset FOREIGN KEY (dataset_id) REFERENCES datasets(dataset_id),
    CONSTRAINT fk_rt_user    FOREIGN KEY (user_id)    REFERENCES users(user_id)
);

CREATE TABLE downloads (
    download_id   NUMBER GENERATED ALWAYS AS IDENTITY PRIMARY KEY,
    dataset_id    NUMBER,
    user_id       NUMBER,
    download_date DATE DEFAULT SYSDATE,
    CONSTRAINT fk_dl_dataset FOREIGN KEY (dataset_id) REFERENCES datasets(dataset_id),
    CONSTRAINT fk_dl_user    FOREIGN KEY (user_id)    REFERENCES users(user_id)
);
