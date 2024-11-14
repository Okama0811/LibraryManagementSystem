<?php

require_once __DIR__ . '/../../config/database.php';

try {
    $pdo = new PDO(
        "mysql:host={$config['host']};dbname={$config['database']};charset={$config['charset']}", 
        $config['username'], 
        $config['password']
    );
    
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Seed Roles
    $roles = [
        ['name' => 'Admin', 'description' => 'System administrator'],
        ['name' => 'Librarian', 'description' => 'Library staff member'],
        ['name' => 'Member', 'description' => 'Library member']
    ];

    $stmt = $pdo->prepare("INSERT INTO roles (name, description) VALUES (?, ?)");
    foreach ($roles as $role) {
        $stmt->execute([$role['name'], $role['description']]);
    }

    // Seed Permissions
    $permissions = [
        ['name' => 'manage_users', 'description' => 'Can manage user accounts'],
        ['name' => 'manage_books', 'description' => 'Can manage books'],
        ['name' => 'manage_loans', 'description' => 'Can manage book loans'],
        ['name' => 'manage_fines', 'description' => 'Can manage fines'],
        ['name' => 'view_reports', 'description' => 'Can view reports']
    ];

    $stmt = $pdo->prepare("INSERT INTO permissions (name, description) VALUES (?, ?)");
    foreach ($permissions as $permission) {
        $stmt->execute([$permission['name'], $permission['description']]);
    }

    // Seed Role Permissions
    $rolePermissions = [
        ['role_name' => 'Admin', 'permission_name' => 'manage_users'],
        ['role_name' => 'Admin', 'permission_name' => 'manage_books'],
        ['role_name' => 'Admin', 'permission_name' => 'manage_loans'],
        ['role_name' => 'Admin', 'permission_name' => 'manage_fines'],
        ['role_name' => 'Admin', 'permission_name' => 'view_reports'],
        ['role_name' => 'Librarian', 'permission_name' => 'manage_books'],
        ['role_name' => 'Librarian', 'permission_name' => 'manage_loans'],
        ['role_name' => 'Librarian', 'permission_name' => 'manage_fines']
    ];

    $stmt = $pdo->prepare("
        INSERT INTO role_permissions (role_id, permission_id)
        SELECT r.role_id, p.permission_id
        FROM roles r
        JOIN permissions p
        WHERE r.name = ? AND p.name = ?
    ");

    foreach ($rolePermissions as $rp) {
        $stmt->execute([$rp['role_name'], $rp['permission_name']]);
    }

    // Seed Default Admin User
    $adminPassword = password_hash('admin123', PASSWORD_DEFAULT);
    $stmt = $pdo->prepare("
        INSERT INTO users (role_id, username, password, email, full_name, member_type, expiry_date)
        SELECT role_id, 'admin', ?, 'admin@library.com', 'System Administrator', 'staff', '2099-12-31'
        FROM roles WHERE name = 'Admin'
    ");
    $stmt->execute([$adminPassword]);

    echo "Sample data seeded successfully!\n";

} catch (PDOException $e) {
    die("Error seeding data: " . $e->getMessage() . "\n");
}