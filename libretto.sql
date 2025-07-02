CREATE SCHEMA `libretto_jspeleca14`;

CREATE TABLE `libretto_jspeleca14`.`authors` (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE `libretto_jspeleca14`.`books` (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    author_id INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (author_id) REFERENCES `libretto_jspeleca14`.`authors`(id) ON DELETE CASCADE
);

CREATE TABLE `libretto_jspeleca14`.`reviews` (
    id INT AUTO_INCREMENT PRIMARY KEY,
    book_id INT NOT NULL,
    review_text TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (book_id) REFERENCES `libretto_jspeleca14`.`books`(id) ON DELETE CASCADE
);

CREATE TABLE `libretto_jspeleca14`.`genres` (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE `libretto_jspeleca14`.`book_genre` (
    book_id INT NOT NULL,
    genre_id INT NOT NULL,
    PRIMARY KEY (book_id, genre_id),
    FOREIGN KEY (book_id) REFERENCES `libretto_jspeleca14`.`books`(id) ON DELETE CASCADE,
    FOREIGN KEY (genre_id) REFERENCES `libretto_jspeleca14`.`genres`(id) ON DELETE CASCADE
);