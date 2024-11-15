<?php
require_once __DIR__ . '/../../config/database.php';

try {
    $pdo = new PDO(
        "mysql:host={$config['host']};charset={$config['charset']}", 
        $config['username'], 
        $config['password']
    );
    
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Create database if not exists
    $pdo->exec("CREATE DATABASE IF NOT EXISTS {$config['database']} CHARACTER SET {$config['charset']} COLLATE {$config['collation']}");
    $pdo->exec("USE {$config['database']}");
    
    $migrations = [
        // Roles table
        "CREATE TABLE IF NOT EXISTS roles (
            role_id INT PRIMARY KEY AUTO_INCREMENT,
            name VARCHAR(100) NOT NULL, 
            description TEXT,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        )",

        // Permissions table
        "CREATE TABLE IF NOT EXISTS permissions (
            permission_id INT PRIMARY KEY AUTO_INCREMENT,
            name VARCHAR(100) NOT NULL,
            description TEXT,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        )",

        // RolePermissions table
        "CREATE TABLE IF NOT EXISTS role_permissions (
            role_id INT,
            permission_id INT,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (role_id, permission_id),
            FOREIGN KEY (role_id) REFERENCES roles(role_id) ON DELETE CASCADE,
            FOREIGN KEY (permission_id) REFERENCES permissions(permission_id) ON DELETE CASCADE
        )",

        // Users table
        "CREATE TABLE IF NOT EXISTS users (
            user_id INT PRIMARY KEY AUTO_INCREMENT,
            role_id INT,
            username VARCHAR(50) UNIQUE NOT NULL,
            password VARCHAR(255) NOT NULL,
            email VARCHAR(100) UNIQUE NOT NULL,
            full_name VARCHAR(100) NOT NULL,
            date_of_birth DATE,
            gender ENUM('male', 'female', 'other'),
            phone VARCHAR(20),
            address TEXT,
            member_type VARCHAR(50),
            expiry_date DATE,
            max_books INT DEFAULT 5,
            status ENUM('active', 'inactive') DEFAULT 'active',
            note TEXT,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            FOREIGN KEY (role_id) REFERENCES roles(role_id)
        )",

        // Publishers table
        "CREATE TABLE IF NOT EXISTS publishers (
            publisher_id INT PRIMARY KEY AUTO_INCREMENT,
            name VARCHAR(100) NOT NULL,
            address TEXT,
            phone VARCHAR(20),
            email VARCHAR(100),
            website VARCHAR(255),
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        )",

        // Authors table
        "CREATE TABLE IF NOT EXISTS authors (
            author_id INT PRIMARY KEY AUTO_INCREMENT,
            name VARCHAR(100) NOT NULL,
            biography TEXT,
            birth_date DATE,
            nationality VARCHAR(50),
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        )",

        // Books table
        "CREATE TABLE IF NOT EXISTS books (
            book_id INT PRIMARY KEY AUTO_INCREMENT,
            publisher_id INT,
            title VARCHAR(255) NOT NULL,
            publication_year INT,
            edition VARCHAR(50),
            pages INT,
            language VARCHAR(50),
            description TEXT,
            cover_image VARCHAR(255),
            quantity INT DEFAULT 0,
            available_quantity INT DEFAULT 0,
            price DECIMAL(10,2),
            status ENUM('available', 'unavailable') DEFAULT 'available',
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            FOREIGN KEY (publisher_id) REFERENCES publishers(publisher_id)
        )",

        // BookAuthors table
        "CREATE TABLE IF NOT EXISTS book_authors (
            book_id INT,
            author_id INT,
            role VARCHAR(50),
            PRIMARY KEY (book_id, author_id),
            FOREIGN KEY (book_id) REFERENCES books(book_id) ON DELETE CASCADE,
            FOREIGN KEY (author_id) REFERENCES authors(author_id) ON DELETE CASCADE
        )",

        // Categories table
            "CREATE TABLE IF NOT EXISTS categories (
                category_id INT PRIMARY KEY AUTO_INCREMENT,
                name VARCHAR(100) NOT NULL,
                description TEXT,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
            )",

        // BookCategories table
        "CREATE TABLE IF NOT EXISTS book_categories (
            book_id INT,
            category_id INT,
            PRIMARY KEY (book_id, category_id),
            FOREIGN KEY (book_id) REFERENCES books(book_id) ON DELETE CASCADE,
            FOREIGN KEY (category_id) REFERENCES categories(category_id) ON DELETE CASCADE
        )",

        // Loans table
        "CREATE TABLE IF NOT EXISTS loans (
            loan_id INT PRIMARY KEY AUTO_INCREMENT,
            book_id INT,
            issued_by INT,
            returned_to INT,
            issued_date DATE NOT NULL,
            due_date DATE NOT NULL,
            returned_date DATE,
            status ENUM('issued', 'returned', 'overdue') DEFAULT 'issued',
            notes TEXT,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            FOREIGN KEY (book_id) REFERENCES books(book_id),
            FOREIGN KEY (issued_by) REFERENCES users(user_id),
            FOREIGN KEY (returned_to) REFERENCES users(user_id)
        )",

        // Fines table
        "CREATE TABLE IF NOT EXISTS fines (
            fine_id INT PRIMARY KEY AUTO_INCREMENT,
            loan_id INT,
            user_id INT,
            returned_to INT,
            issued_date DATE NOT NULL,
            due_date DATE NOT NULL,
            returned_date DATE,
            status ENUM('paid', 'unpaid') DEFAULT 'unpaid',
            notes TEXT,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            FOREIGN KEY (loan_id) REFERENCES loans(loan_id),
            FOREIGN KEY (user_id) REFERENCES users(user_id),
            FOREIGN KEY (returned_to) REFERENCES users(user_id)
        )",

        // FinePayments table
        "CREATE TABLE IF NOT EXISTS fine_payments (
            payment_id INT PRIMARY KEY AUTO_INCREMENT,
            fine_id INT,
            amount DECIMAL(10,2) NOT NULL,
            payment_date DATE NOT NULL,
            payment_method VARCHAR(50),
            receive_by INT,
            notes TEXT,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (fine_id) REFERENCES fines(fine_id),
            FOREIGN KEY (receive_by) REFERENCES users(user_id)
        )",

        // BookConditions table
        "CREATE TABLE IF NOT EXISTS book_conditions (
            condition_id INT PRIMARY KEY AUTO_INCREMENT,
            book_id INT,
            loan_id INT,
            condition_before TEXT,
            condition_after TEXT,
            damage_description TEXT,
            assessed_by INT,
            assessed_date DATE,
            notes TEXT,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (book_id) REFERENCES books(book_id),
            FOREIGN KEY (loan_id) REFERENCES loans(loan_id),
            FOREIGN KEY (assessed_by) REFERENCES users(user_id)
        )",

        // Reservations table
        "CREATE TABLE IF NOT EXISTS reservations (
            reservation_id INT PRIMARY KEY AUTO_INCREMENT,
            book_id INT,
            user_id INT,
            reservation_date DATE NOT NULL,
            expiry_date DATE NOT NULL,
            fulfilled_date DATE,
            status ENUM('pending', 'fulfilled', 'cancelled', 'expired') DEFAULT 'pending',
            notes TEXT,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            FOREIGN KEY (book_id) REFERENCES books(book_id),
            FOREIGN KEY (user_id) REFERENCES users(user_id)
        )"
    ];

    // Execute each migration
    foreach ($migrations as $migration) {
        $pdo->exec($migration);
    }

    echo "Database and tables created successfully!\n";

} catch (PDOException $e) {
    die("Error creating database and tables: " . $e->getMessage() . "\n");
}