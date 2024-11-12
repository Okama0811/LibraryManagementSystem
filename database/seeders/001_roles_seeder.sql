-- database/seeders/001_roles_seeder.sql
INSERT INTO Roles (name, description) VALUES
('admin', 'Quản trị viên hệ thống'),
('librarian', 'Nhân viên thư viện'),
('member', 'Thành viên thư viện');

-- Thêm các quyền cơ bản
INSERT INTO Permissions (name, description) VALUES
('manage_users', 'Quản lý người dùng'),
('manage_books', 'Quản lý sách'),
('manage_categories', 'Quản lý thể loại'),
('manage_loans', 'Quản lý mượn trả'),
('view_reports', 'Xem báo cáo'),
('manage_readers', 'Quản lý độc giả');

-- Gán quyền cho các role
INSERT INTO RolePermissions (role_id, permission_id) 
SELECT 
    (SELECT role_id FROM Roles WHERE name = 'admin'),
    permission_id 
FROM Permissions;

INSERT INTO RolePermissions (role_id, permission_id)
SELECT 
    (SELECT role_id FROM Roles WHERE name = 'librarian'),
    permission_id 
FROM Permissions 
WHERE name IN ('manage_books', 'manage_loans', 'manage_readers', 'view_reports');

-- database/seeders/002_admin_user_seeder.sql
INSERT INTO Users (username, password, email, full_name, role_id) VALUES
('admin', '$2a$10$somehashedpassword', 'admin@library.com', 'Admin User', 
 (SELECT role_id FROM Roles WHERE name = 'admin'));