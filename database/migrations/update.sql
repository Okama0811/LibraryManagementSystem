
USE library_management_system;



CREATE TABLE cart (
   cart_id INT PRIMARY KEY AUTO_INCREMENT,
   user_id INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    status ENUM('active', 'checked_out', 'cancelled') DEFAULT 'active',
    FOREIGN KEY (user_id) REFERENCES user(user_id)
);


-- CREATE TABLE cart_items (
--     cart_item_id INT PRIMARY KEY AUTO_INCREMENT,
--    cart_id INT NOT NULL,
--     book_id INT NOT NULL,
--     quantity INT DEFAULT 1,
--   added_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
--     FOREIGN KEY (cart_id) REFERENCES cart(cart_id),
--     FOREIGN KEY (book_id) REFERENCES book(book_id)
-- );

-- ALTER TABLE cart
-- ADD COLUMN updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP;

-- ALTER TABLE cart_items
-- ADD COLUMN updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP;

-- ALTER TABLE cart_items
-- ADD COLUMN created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP;



ALTER TABLE fine
ADD CONSTRAINT fk_confirmed_by FOREIGN KEY (confirmed_by) REFERENCES user(user_id);





