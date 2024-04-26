CREATE TABLE `reading_list` (
    `list_id` INT AUTO_INCREMENT PRIMARY KEY,
    `user_id` INT,
    `book_id` INT,
    FOREIGN KEY (`user_id`) REFERENCES `users`(`user_id`),
    FOREIGN KEY (`book_id`) REFERENCES `books`(`book_id`),
    UNIQUE (`user_id`, `book_id`)
);