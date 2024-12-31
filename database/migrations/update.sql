
USE library_management_system;



CREATE TABLE cart (
   cart_id INT PRIMARY KEY AUTO_INCREMENT,
   user_id INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    status ENUM('active', 'checked_out', 'cancelled') DEFAULT 'active',
    FOREIGN KEY (user_id) REFERENCES user(user_id)
);


CREATE TABLE cart_items (
    cart_item_id INT PRIMARY KEY AUTO_INCREMENT,
   cart_id INT NOT NULL,
    book_id INT NOT NULL,
    quantity INT DEFAULT 1,
  added_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (cart_id) REFERENCES cart(cart_id),
    FOREIGN KEY (book_id) REFERENCES book(book_id)
);

ALTER TABLE cart
ADD COLUMN updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP;

ALTER TABLE cart_items
ADD COLUMN updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP;

ALTER TABLE cart_items
ADD COLUMN created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP;

--ALTER TABLE book
--DROP COLUMN price;
-- -- Thêm dữ liệu cho bảng book_author
-- INSERT INTO book_author (book_id, author_id, role) VALUES
-- (1, 1, 'Tác giả chính'),
-- (2, 2, 'Tác giả chính'),
-- (3, 3, 'Tác giả chính'),
-- (4, 4, 'Tác giả chính'),
-- (5, 5, 'Tác giả chính'),
-- (6, 6, 'Tác giả chính'),
-- (7, 7, 'Tác giả chính'),
-- (8, 8, 'Tác giả chính'),
-- (9, 9, 'Tác giả chính'),
-- (10, 10, 'Tác giả chính'),
-- (11, 11, 'Tác giả chính'),
-- (12, 12, 'Tác giả chính'),
-- (13, 13, 'Tác giả chính'),
-- (14, 14, 'Tác giả chính'),
-- (15, 15, 'Tác giả chính');

-- ALTER TABLE publisher
-- ADD avatar_url VARCHAR(255)


-- CREATE TABLE loan_detail (
--     detail_id INT PRIMARY KEY AUTO_INCREMENT,
--     loan_id INT NOT NULL,
--     book_id INT NOT NULL,
--     quantity INT DEFAULT 1,
--     status ENUM('issued', 'returned', 'lost', 'damaged') DEFAULT 'issued',
--     notes TEXT,
--     created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
--     FOREIGN KEY (loan_id) REFERENCES loan(loan_id) ON DELETE CASCADE,
--     FOREIGN KEY (book_id) REFERENCES book(book_id) ON DELETE CASCADE
-- );

-- INSERT INTO loan_detail (loan_id, book_id, quantity, status, notes, created_at)
-- SELECT loan_id, book_id, 1, status, notes, created_at
-- FROM loan;

--ALTER TABLE loan DROP FOREIGN KEY loan_ibfk_1;
-- Alter table loan drop column book_id
--ALTER TABLE loan
--ADD CONSTRAINT fk_user1
--FOREIGN KEY (user_id)
--REFERENCES user(user_id)
--ON DELETE CASCADE;

--ALTER TABLE loan ADD user_id int;
--ALTER TABLE author ADD avatar_url VARCHAR(255);



