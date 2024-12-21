
-- Insert roles
INSERT INTO Role (role_id, name, description, created_at, updated_at) 
VALUES 
(1, 'Admin', 'Quản trị viên hệ thống với quyền truy cập đầy đủ', NOW(), NOW()),
(2, 'Librarian', 'Thủ thư phụ trách quản lý sách và xử lý yêu cầu của người dùng', NOW(), NOW()),
(3, 'Member', 'Người dùng thông thường với quyền mượn sách', NOW(), NOW());

-- Insert all permissions
INSERT INTO Permission (permission_id, name, description, created_at, updated_at) 
VALUES 
(1, 'manage_users', 'Quyền quản lý tài khoản người dùng', NOW(), NOW()),
(2, 'manage_authors', 'Quyền quản lý thông tin tác giả', NOW(), NOW()),
(3, 'manage_books', 'Quyền quản lý sách', NOW(), NOW()),
(4, 'manage_loans', 'Quyền quản lý mượn trả sách', NOW(), NOW()),
(5, 'manage_categories', 'Quyền quản lý danh mục sách', NOW(), NOW()),
(6, 'manage_publishers', 'Quyền quản lý nhà xuất bản', NOW(), NOW()),
(7, 'manage_reservations', 'Quyền quản lý đặt sách', NOW(), NOW()),
(8, 'manage_bookConditions', 'Quyền quản lý tình trạng sách', NOW(), NOW()),
(9, 'manage_members', 'Quyền quản lý thành viên', NOW(), NOW());;

-- Assign permissions to roles
INSERT INTO Role_Permission (role_id, permission_id, created_at) 
VALUES 
-- Admin gets all permissions
(1, 1, NOW()), -- manage_users
(1, 2, NOW()), -- manage_authors
(1, 3, NOW()), -- manage_books
(1, 4, NOW()), -- manage_loans
(1, 5, NOW()), -- manage_categories
(1, 6, NOW()), -- manage_publishers
(1, 7, NOW()), -- manage_reservations
(1, 8, NOW()), -- manage_bookConditions
(1, 9, NOW()),
-- Librarian gets all management permissions except user management
(2, 2, NOW()), -- manage_authors
(2, 3, NOW()), -- manage_books
(2, 4, NOW()), -- manage_loans
(2, 5, NOW()), -- manage_categories
(2, 6, NOW()), -- manage_publishers
(2, 7, NOW()), -- manage_reservations
(2, 8, NOW()),
(2, 9, NOW()); 

INSERT INTO User (user_id, role_id, username, password, email, full_name, date_of_birth, gender, phone, address, member_type, expiry_date, max_books, status, note, created_at, updated_at) VALUES
(1, 1, 'admin', 'adminpass', 'admin@example.com', 'System Admin', '1980-01-01', 'male', '123456789', '123 Admin Street', 'Admin', '2030-01-01', 0, 'active', NULL, NOW(), NOW()),
(2, 2, 'librarian', 'libpass', 'librarian@example.com', 'John Doe', '1990-05-12', 'male', '987654321', '45 Library Ave', 'Staff', '2030-01-01', 10, 'active', NULL, NOW(), NOW()),
(3, 3, 'member1', 'memberpass', 'member1@example.com', 'Jane Smith', '2000-02-20', 'female', '555666777', '78 Member Lane', 'Member', '2025-01-01', 5, 'active', 'Good standing', NOW(), NOW());

INSERT INTO Category (category_id, name, description, created_at) 
VALUES 
(1, 'Tiểu thuyết', 'Các tác phẩm hư cấu bao gồm tiểu thuyết và truyện ngắn', NOW()),
(2, 'Phi tiểu thuyết', 'Các tác phẩm phi hư cấu bao gồm tiểu sử và các bài luận', NOW()),
(3, 'Khoa học', 'Sách về khoa học và nghiên cứu', NOW()),
(4, 'Văn học', 'Các tác phẩm văn học Việt Nam và nước ngoài', NOW()),
(5, 'Giáo trình', 'Sách giáo khoa và tài liệu học tập các cấp', NOW()),
(6, 'Thiếu nhi', 'Sách dành cho trẻ em và thanh thiếu niên', NOW()),
(7, 'Kinh tế', 'Sách về kinh doanh, tài chính và quản lý', NOW()),
(8, 'Kỹ năng sống', 'Sách về phát triển bản thân và kỹ năng sống', NOW()),
(9, 'Công nghệ', 'Sách về công nghệ thông tin và kỹ thuật', NOW()),
(10, 'Tâm lý - Tôn giáo', 'Sách về tâm lý học và các tôn giáo', NOW()),
(11, 'Nghệ thuật', 'Sách về hội họa, âm nhạc và các loại hình nghệ thuật', NOW()),
(12, 'Lịch sử', 'Sách về lịch sử Việt Nam và thế giới', NOW()),
(13, 'Y học - Sức khỏe', 'Sách về y học, sức khỏe và dinh dưỡng', NOW()),
(14, 'Ngoại ngữ', 'Sách học ngoại ngữ và từ điển', NOW()),
(15, 'Chính trị - Pháp luật', 'Sách về chính trị, pháp luật và các văn bản quy phạm', NOW());

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
