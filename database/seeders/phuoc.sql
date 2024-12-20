-- Thêm vào bảng Publisher
INSERT INTO Publisher (publisher_id, name, address, phone, email, website, created_at, updated_at) VALUES
(3, 'Oxford University Press', 'Great Clarendon St, Oxford, UK', '789789789', 'info@oup.com', 'https://oup.com', NOW(), NOW()),
(4, 'Simon & Schuster', '1230 Avenue of the Americas, New York, USA', '654654654', 'contact@simonandschuster.com', 'https://simonandschuster.com', NOW(), NOW());
(5, 'Macmillan Publishers', '175 Fifth Ave, New York, USA', '321321321', 'info@macmillan.com', 'https://macmillan.com', NOW(), NOW()),
(6, 'Random House', '1745 Broadway, New York, USA', '987987987', 'contact@randomhouse.com', 'https://randomhouse.com', NOW(), NOW());


-- Thêm vào bảng Book
INSERT INTO Book (book_id, title, publisher_id, publication_year, edition, pages, language, description, cover_image, quantity, available_quantity, price, status, created_at, updated_at) VALUES
(3, 'The Hobbit', 3, 1937, '1st', 310, 'English', 'Fantasy novel by J.R.R. Tolkien', NULL, 7, 7, 15.99, 'available', NOW(), NOW()),
(4, 'Dune', 2, 1965, '1st', 412, 'English', 'Science fiction novel by Frank Herbert', NULL, 8, 8, 17.99, 'available', NOW(), NOW()),
(5, '1984', 4, 1949, '1st', 328, 'English', 'Dystopian novel by George Orwell', NULL, 6, 6, 13.99, 'available', NOW(), NOW());
(6, 'Pride and Prejudice', 5, 1813, '1st', 279, 'English', 'Classic novel by Jane Austen', NULL, 12, 12, 11.99, 'available', NOW(), NOW()),
(7, 'The Catcher in the Rye', 6, 1951, '1st', 214, 'English', 'Novel by J.D. Salinger', NULL, 10, 10, 12.99, 'available', NOW(), NOW()),
(8, 'To Kill a Mockingbird', 4, 1960, '1st', 281, 'English', 'Novel by Harper Lee', NULL, 9, 9, 14.99, 'available', NOW(), NOW()),
(9, 'Brave New World', 3, 1932, '1st', 311, 'English', 'Dystopian novel by Aldous Huxley', NULL, 7, 7, 13.49, 'available', NOW(), NOW()),
(10, 'The Great Gatsby', 2, 1925, '1st', 218, 'English', 'Classic novel by F. Scott Fitzgerald', NULL, 8, 8, 10.99, 'available', NOW(), NOW());

-- Thêm vào bảng Book_Author
INSERT INTO Book_Author (book_id, author_id, role) VALUES
(3, 1, 'author'),
(4, 2, 'author'),
(5, 1, 'author');
(6, 1, 'author'), 
(7, 2, 'author'), 
(8, 2, 'author'), 
(9, 1, 'author'), 
(10, 2, 'author'); 

-- Thêm vào bảng Book_Category
INSERT INTO Book_Category (book_id, category_id) VALUES
(3, 1), 
(4, 3), 
(5, 2); 
(6, 2), 
(7, 1), 
(8, 1), 
(9, 2), 
(10, 1); 