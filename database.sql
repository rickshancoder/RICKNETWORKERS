CREATE DATABASE IF NOT EXISTS ricknetworks;
USE ricknetworks;

CREATE TABLE packages (
 id INT AUTO_INCREMENT PRIMARY KEY,
 name VARCHAR(100),
 duration_minutes INT,
 price INT
);

INSERT INTO packages (name, duration_minutes, price) VALUES
('1 Hour - 6 Bob', 60, 6),
('8.5 Hours - 15 Bob', 510, 15),
('12 Hours - 20 Bob', 720, 20),
('24 Hours - 28 Bob', 1440, 28),
('1 Week - 180 Bob', 10080, 180),
('1 Month - 480 Bob', 43200, 480),
('1 Month Unlimited - 550 Bob', 43200, 550),
('1 Month Personal Router - 1200 Bob', 43200, 1200);

CREATE TABLE vouchers (
 id INT AUTO_INCREMENT PRIMARY KEY,
 username VARCHAR(50) UNIQUE,
 password VARCHAR(50),
 package_id INT,
 phone VARCHAR(15),
 expiry DATETIME,
 status VARCHAR(20) DEFAULT 'active'
);

CREATE TABLE payments (
 id INT AUTO_INCREMENT PRIMARY KEY,
 phone VARCHAR(15),
 amount INT,
 package_id INT,
 mpesa_code VARCHAR(50),
 status VARCHAR(20),
 created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
