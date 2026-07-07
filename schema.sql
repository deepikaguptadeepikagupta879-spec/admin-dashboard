CREATE DATABASE IF NOT EXISTS laptop_store;

USE laptop_store;


CREATE TABLE users(

id INT AUTO_INCREMENT PRIMARY KEY,

name VARCHAR(100) NOT NULL,

email VARCHAR(150) UNIQUE NOT NULL,

phone VARCHAR(20),

password VARCHAR(255) NOT NULL,

role ENUM('admin','seller','customer') DEFAULT 'customer',

created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP

);



CREATE TABLE categories(

id INT AUTO_INCREMENT PRIMARY KEY,

name VARCHAR(100) NOT NULL,

created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP

);



CREATE TABLE products(

id INT AUTO_INCREMENT PRIMARY KEY,

name VARCHAR(255) NOT NULL,

brand VARCHAR(100) NOT NULL,

category_id INT,

processor VARCHAR(255),

ram VARCHAR(100),

storage VARCHAR(100),

graphics VARCHAR(255),

display_size VARCHAR(100),

operating_system VARCHAR(100),

battery VARCHAR(100),

color VARCHAR(100),

weight VARCHAR(50),

warranty VARCHAR(100),

price DECIMAL(10,2) NOT NULL,

stock INT DEFAULT 0,

image VARCHAR(255),

description TEXT,

created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,


FOREIGN KEY(category_id)

REFERENCES categories(id)

ON DELETE SET NULL

);

CREATE TABLE orders(

id INT AUTO_INCREMENT PRIMARY KEY,

order_no VARCHAR(50) UNIQUE NOT NULL,

user_id INT NOT NULL,

total DECIMAL(10,2) NOT NULL,

payment_method VARCHAR(50),

address TEXT,

status ENUM(
'Pending',
'Processing',
'Shipped',
'Delivered',
'Cancelled'
)
DEFAULT 'Pending',

created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,


FOREIGN KEY(user_id)

REFERENCES users(id)

ON DELETE CASCADE

);



CREATE TABLE order_items(

id INT AUTO_INCREMENT PRIMARY KEY,

order_id INT NOT NULL,

product_id INT NOT NULL,

quantity INT DEFAULT 1,

price DECIMAL(10,2) NOT NULL,


FOREIGN KEY(order_id)

REFERENCES orders(id)

ON DELETE CASCADE,


FOREIGN KEY(product_id)

REFERENCES products(id)

ON DELETE CASCADE

);



INSERT INTO categories(name)

VALUES

('Gaming Laptop'),

('Business Laptop'),

('Student Laptop'),

('Professional Laptop');



INSERT INTO users
(
name,
email,
phone,
password,
role
)

VALUES

(
'Admin',
'admin@gmail.com',
'9999999999',
'admin123',
'admin'
);