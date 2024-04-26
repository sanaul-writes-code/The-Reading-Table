CREATE TABLE `books` (
    `book_id` INT AUTO_INCREMENT PRIMARY KEY,
    `title` VARCHAR(255) NOT NULL,
    `author` VARCHAR(255) NOT NULL,
    `isbn` VARCHAR(20),
    `genre` VARCHAR(100),
    `year_published` YEAR,
    `description` TEXT,
    `image_url` VARCHAR(255)
);
