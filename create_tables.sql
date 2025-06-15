CREATE DATABASE IF NOT EXISTS bookverse;
USE bookverse;

-- Drop tables in reverse dependency order to avoid FK constraint errors
DROP TABLE IF EXISTS orderDetails;
DROP TABLE IF EXISTS `Order`;
DROP TABLE IF EXISTS books;
DROP TABLE IF EXISTS author;
DROP TABLE IF EXISTS customer;

-- Author table
CREATE TABLE author (
    authorID INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    bio TEXT
);

-- Books table
CREATE TABLE books (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    genre VARCHAR(100),
    price DECIMAL(6,2),
    quantity INT DEFAULT 0,
    image VARCHAR(255),
    authorID INT,
    FOREIGN KEY (authorID) REFERENCES author(authorID)
);

-- Customer table
CREATE TABLE customer (
    customerID INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL
);

-- Order table
CREATE TABLE `Order` (
    orderID INT AUTO_INCREMENT PRIMARY KEY,
    customerID INT,
    orderDate DATE,
    FOREIGN KEY (customerID) REFERENCES customer(customerID)
);

-- OrderDetails table
CREATE TABLE orderDetails (
    orderDetailID INT AUTO_INCREMENT PRIMARY KEY,
    orderID INT,
    bookID INT,
    quantity INT,
    FOREIGN KEY (orderID) REFERENCES `Order`(orderID),
    FOREIGN KEY (bookID) REFERENCES books(id)
);

-- Insert authors
INSERT INTO author (name, bio) VALUES
('Paulo Coelho', 'Brazilian lyricist and novelist, best known for "The Alchemist".'),
('James Clear', 'Author of "Atomic Habits", focused on self-improvement.'),
('Tara Westover', 'Memoirist known for "Educated".'),
('Frank Herbert', 'Science fiction author of "Dune".'),
('Michelle Obama', 'Former First Lady and author of "Becoming".'),
('George Orwell', 'English novelist and journalist, author of "1984".'),
('Andrew Hunt & David Thomas', 'Co-authors of "The Pragmatic Programmer".'),
('J.K. Rowling', 'British author best known for the "Harry Potter" series.');

-- Insert books
INSERT INTO books (title, genre, price, quantity, image, authorID) VALUES
('The Alchemist', 'Fiction', 12.99, 10, 'https://covers.openlibrary.org/b/id/8155036-L.jpg', 1),
('Atomic Habits', 'Self-Help', 17.99, 15, 'https://covers.openlibrary.org/b/id/10093555-L.jpg', 2),
('Educated', 'Biography', 15.50, 8, 'https://covers.openlibrary.org/b/id/8955621-L.jpg', 3),
('Dune', 'Science Fiction', 14.25, 12, 'https://covers.openlibrary.org/b/id/8106660-L.jpg', 4),
('Becoming', 'Biography', 18.99, 5, 'https://covers.openlibrary.org/b/id/9259025-L.jpg', 5),
('1984', 'Fiction', 11.00, 20, 'https://covers.openlibrary.org/b/id/7222246-L.jpg', 6),
('The Pragmatic Programmer', 'Technology', 32.50, 7, 'https://covers.openlibrary.org/b/id/8228698-L.jpg', 7),
('Harry Potter and the Sorcerers Stone', 'Fantasy', 9.99, 25, 'https://covers.openlibrary.org/b/id/7984916-L.jpg', 8);

-- Insert customer
INSERT INTO customer (name, email, password) VALUES
('Abdullah tahir', 'abdullah@example.com', 'pass123');