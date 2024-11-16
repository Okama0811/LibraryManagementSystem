
INSERT INTO Role (role_id, name, description, created_at, updated_at) VALUES
(1, 'Admin', 'System administrator with full access', NOW(), NOW()),
(2, 'Librarian', 'Handles book management and user queries', NOW(), NOW()),
(3, 'Member', 'Regular user with book borrowing rights', NOW(), NOW());

INSERT INTO Permission (permission_id, name, description, created_at, updated_at) VALUES
(1, 'view_books', 'Permission to view books', NOW(), NOW()),
(2, 'borrow_books', 'Permission to borrow books', NOW(), NOW()),
(3, 'manage_users', 'Permission to manage user accounts', NOW(), NOW());

INSERT INTO Role_Permission (role_id, permission_id, created_at) VALUES
(1, 1, NOW()),
(1, 2, NOW()),
(1, 3, NOW()),
(2, 1, NOW()),
(2, 2, NOW()),
(3, 1, NOW());

INSERT INTO User (user_id, role_id, username, password, email, full_name, date_of_birth, gender, phone, address, member_type, expiry_date, max_books, status, note, created_at, updated_at) VALUES
(1, 1, 'admin', 'adminpass', 'admin@example.com', 'System Admin', '1980-01-01', 'male', '123456789', '123 Admin Street', 'Admin', '2030-01-01', 0, 'active', NULL, NOW(), NOW()),
(2, 2, 'librarian', 'libpass', 'librarian@example.com', 'John Doe', '1990-05-12', 'male', '987654321', '45 Library Ave', 'Staff', '2030-01-01', 10, 'active', NULL, NOW(), NOW()),
(3, 3, 'member1', 'memberpass', 'member1@example.com', 'Jane Smith', '2000-02-20', 'female', '555666777', '78 Member Lane', 'Member', '2025-01-01', 5, 'active', 'Good standing', NOW(), NOW());

INSERT INTO Category (category_id, name, description, created_at) VALUES
(1, 'Fiction', 'Fictional works including novels and stories', NOW()),
(2, 'Non-fiction', 'Non-fictional works including biographies and essays', NOW()),
(3, 'Science', 'Books about science and research', NOW());

INSERT INTO Author (author_id, name, biography, birth_date, nationality, created_at, updated_at) VALUES
(1, 'J.K. Rowling', 'British author, best known for Harry Potter series', '1965-07-31', 'British', NOW(), NOW()),
(2, 'Isaac Asimov', 'American author and professor of biochemistry', '1920-01-02', 'American', NOW(), NOW());

INSERT INTO Publisher (publisher_id, name, address, phone, email, website, created_at, updated_at) VALUES
(1, 'Penguin Books', '80 Strand, London, UK', '123123123', 'contact@penguinbooks.com', 'https://penguinbooks.com', NOW(), NOW()),
(2, 'HarperCollins', '195 Broadway, New York, USA', '456456456', 'info@harpercollins.com', 'https://harpercollins.com', NOW(), NOW());

INSERT INTO Book (book_id, title, publisher_id, publication_year, edition, pages, language, description, cover_image, quantity, available_quantity, price, status, created_at, updated_at) VALUES
(1, 'Harry Potter and the Philosopher Stone', 1, 1997, '1st', 223, 'English', 'Fantasy novel', NULL, 10, 10, 19.99, 'available', NOW(), NOW()),
(2, 'Foundation', 2, 1951, '1st', 255, 'English', 'Science fiction novel', NULL, 5, 5, 14.99, 'available', NOW(), NOW());

INSERT INTO Book_Author (book_id, author_id, role) VALUES
(1, 1, 'author'),
(2, 2, 'author');

INSERT INTO Book_Category (book_id, category_id) VALUES
(1, 1),
(2, 3);

INSERT INTO Reservation (reservation_id, book_id, user_id, reservation_date, expiry_date, fulfilled_date, status, notes, created_at, updated_at) VALUES
(1, 1, 3, NOW(), DATE_ADD(NOW(), INTERVAL 7 DAY), NULL, 'pending', 'Awaiting approval', NOW(), NOW());

INSERT INTO Loan (loan_id, book_id, issued_by, returned_to, issued_date, due_date, returned_date, status, notes, created_at, updated_at) VALUES
(1, 1, 2, NULL, NOW(), DATE_ADD(NOW(), INTERVAL 14 DAY), NULL, 'active', 'Loan in good standing', NOW(), NOW());

INSERT INTO Book_Condition (condition_id, book_id, loan_id, condition_before, condition_after, damage_description, assessed_by, assessed_date, notes, created_at) VALUES
(1, 1, 1, 'Good', NULL, NULL, 2, NOW(), 'Initial loan condition assessment', NOW());

INSERT INTO Fine (fine_id, loan_id, returned_to, issued_date, due_date, returned_date, status, notes, created_at, updated_at) VALUES
(1, 1, NULL, NOW(), DATE_ADD(NOW(), INTERVAL 7 DAY), NULL, 'unpaid', 'Late return fine', NOW(), NOW());

INSERT INTO Fine_Payment (payment_id, fine_id, amount, payment_date, payment_method, receive_by, notes, created_at) VALUES
(1, 1, 5.00, NOW(), 'cash', 2, 'Fine settled in cash', NOW());
