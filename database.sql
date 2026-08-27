-- =========================================================
-- FARM MANAGEMENT SYSTEM DATABASE
-- MySQL
-- =========================================================

CREATE DATABASE IF NOT EXISTS farm_management_system;

USE farm_management_system;


-- =========================================================
-- 1. USERS / LOGIN
-- =========================================================

CREATE TABLE users (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    full_name VARCHAR(100) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);


-- =========================================================
-- 2. FARMERS
-- =========================================================

CREATE TABLE farmers (
    farmer_id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    phone VARCHAR(20),
    address VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);


-- =========================================================
-- 3. FARMS
-- =========================================================

CREATE TABLE farms (
    farm_id INT AUTO_INCREMENT PRIMARY KEY,
    farmer_id INT NOT NULL,
    farm_name VARCHAR(100) NOT NULL,
    location VARCHAR(150),
    farm_size DECIMAL(10,2) NOT NULL,

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT fk_farm_farmer
        FOREIGN KEY (farmer_id)
        REFERENCES farmers(farmer_id)
        ON DELETE CASCADE
        ON UPDATE CASCADE
);


-- =========================================================
-- 4. CROPS
-- =========================================================

CREATE TABLE crops (
    crop_id INT AUTO_INCREMENT PRIMARY KEY,
    crop_name VARCHAR(100) NOT NULL,
    crop_type VARCHAR(100),
    season VARCHAR(50),

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);


-- =========================================================
-- 5. FIELDS
-- =========================================================

CREATE TABLE fields (
    field_id INT AUTO_INCREMENT PRIMARY KEY,
    farm_id INT NOT NULL,
    field_name VARCHAR(100) NOT NULL,
    soil_type VARCHAR(100),
    field_size DECIMAL(10,2) NOT NULL,

    CONSTRAINT fk_field_farm
        FOREIGN KEY (farm_id)
        REFERENCES farms(farm_id)
        ON DELETE CASCADE
        ON UPDATE CASCADE
);


-- =========================================================
-- 6. CROP PRODUCTION
-- =========================================================

CREATE TABLE production (
    production_id INT AUTO_INCREMENT PRIMARY KEY,

    field_id INT NOT NULL,
    crop_id INT NOT NULL,

    planting_date DATE,
    harvest_date DATE,

    quantity DECIMAL(10,2) NOT NULL,
    unit VARCHAR(30) DEFAULT 'kg',

    CONSTRAINT fk_production_field
        FOREIGN KEY (field_id)
        REFERENCES fields(field_id)
        ON DELETE CASCADE
        ON UPDATE CASCADE,

    CONSTRAINT fk_production_crop
        FOREIGN KEY (crop_id)
        REFERENCES crops(crop_id)
        ON DELETE CASCADE
        ON UPDATE CASCADE
);


-- =========================================================
-- 7. LIVESTOCK
-- =========================================================

CREATE TABLE livestock (
    animal_id INT AUTO_INCREMENT PRIMARY KEY,

    farm_id INT NOT NULL,

    animal_type VARCHAR(50) NOT NULL,
    breed VARCHAR(100),

    date_of_birth DATE,

    gender ENUM('Male','Female') DEFAULT 'Female',

    health_status ENUM(
        'Healthy',
        'Sick',
        'Under Treatment',
        'Recovered'
    ) DEFAULT 'Healthy',

    quantity INT DEFAULT 1,

    CONSTRAINT fk_livestock_farm
        FOREIGN KEY (farm_id)
        REFERENCES farms(farm_id)
        ON DELETE CASCADE
        ON UPDATE CASCADE
);


-- =========================================================
-- 8. WORKERS
-- =========================================================

CREATE TABLE workers (
    worker_id INT AUTO_INCREMENT PRIMARY KEY,

    farm_id INT NOT NULL,

    name VARCHAR(100) NOT NULL,
    phone VARCHAR(20),

    position VARCHAR(100),

    salary DECIMAL(10,2),

    hire_date DATE,

    CONSTRAINT fk_worker_farm
        FOREIGN KEY (farm_id)
        REFERENCES farms(farm_id)
        ON DELETE CASCADE
        ON UPDATE CASCADE
);


-- =========================================================
-- 9. EQUIPMENT
-- =========================================================

CREATE TABLE equipment (
    equipment_id INT AUTO_INCREMENT PRIMARY KEY,

    farm_id INT NOT NULL,

    equipment_name VARCHAR(100) NOT NULL,

    purchase_date DATE,

    status ENUM(
        'Working',
        'Maintenance',
        'Damaged'
    ) DEFAULT 'Working',

    purchase_price DECIMAL(10,2),

    CONSTRAINT fk_equipment_farm
        FOREIGN KEY (farm_id)
        REFERENCES farms(farm_id)
        ON DELETE CASCADE
        ON UPDATE CASCADE
);


-- =========================================================
-- 10. EXPENSES
-- =========================================================

CREATE TABLE expenses (
    expense_id INT AUTO_INCREMENT PRIMARY KEY,

    farm_id INT NOT NULL,

    expense_type VARCHAR(100) NOT NULL,

    description VARCHAR(255),

    amount DECIMAL(10,2) NOT NULL,

    expense_date DATE NOT NULL,

    CONSTRAINT fk_expense_farm
        FOREIGN KEY (farm_id)
        REFERENCES farms(farm_id)
        ON DELETE CASCADE
        ON UPDATE CASCADE
);


-- =========================================================
-- 11. SALES
-- =========================================================

CREATE TABLE sales (
    sale_id INT AUTO_INCREMENT PRIMARY KEY,

    farm_id INT NOT NULL,

    product_name VARCHAR(100) NOT NULL,

    quantity DECIMAL(10,2) NOT NULL,

    unit VARCHAR(30) DEFAULT 'kg',

    price DECIMAL(10,2) NOT NULL,

    sale_date DATE NOT NULL,

    customer_name VARCHAR(100),

    CONSTRAINT fk_sale_farm
        FOREIGN KEY (farm_id)
        REFERENCES farms(farm_id)
        ON DELETE CASCADE
        ON UPDATE CASCADE
);


-- =========================================================
-- SAMPLE DATA
-- =========================================================


-- ---------------------------------------------------------
-- USERS
-- ---------------------------------------------------------
-- IMPORTANT:
-- The password should be generated using PHP password_hash().
-- Do NOT insert "admin123" directly as plain text.
--
-- Create the admin account using create_admin.php
-- ---------------------------------------------------------


-- ---------------------------------------------------------
-- FARMERS
-- ---------------------------------------------------------

INSERT INTO farmers
(name, phone, address)
VALUES
('Rahim Ahmed', '01711111111', 'Chattogram'),
('Karim Hasan', '01822222222', 'Dhaka'),
('Abdul Karim', '01933333333', 'Cumilla'),
('Hasan Mahmud', '01644444444', 'Rajshahi'),
('Jamal Uddin', '01555555555', 'Rangpur');


-- ---------------------------------------------------------
-- FARMS
-- ---------------------------------------------------------

INSERT INTO farms
(farmer_id, farm_name, location, farm_size)
VALUES
(1, 'Green Valley Farm', 'Chattogram', 25.50),
(2, 'Sunrise Farm', 'Dhaka', 18.00),
(3, 'Golden Farm', 'Cumilla', 30.00),
(4, 'Fresh Harvest Farm', 'Rajshahi', 22.50),
(5, 'Green Field Farm', 'Rangpur', 15.00);


-- ---------------------------------------------------------
-- CROPS
-- ---------------------------------------------------------

INSERT INTO crops
(crop_name, crop_type, season)
VALUES
('Rice', 'Cereal', 'Monsoon'),
('Potato', 'Vegetable', 'Winter'),
('Tomato', 'Vegetable', 'Winter'),
('Corn', 'Cereal', 'Summer'),
('Wheat', 'Cereal', 'Winter'),
('Chili', 'Vegetable', 'Summer'),
('Onion', 'Vegetable', 'Winter');


-- ---------------------------------------------------------
-- FIELDS
-- ---------------------------------------------------------

INSERT INTO fields
(farm_id, field_name, soil_type, field_size)
VALUES
(1, 'Field A', 'Loamy', 10.50),
(1, 'Field B', 'Clay', 8.00),
(2, 'Field A', 'Sandy', 7.50),
(2, 'Field B', 'Loamy', 6.50),
(3, 'Field A', 'Clay', 12.00),
(4, 'Field A', 'Loamy', 10.00),
(5, 'Field A', 'Sandy', 8.00);


-- ---------------------------------------------------------
-- PRODUCTION
-- ---------------------------------------------------------

INSERT INTO production
(
    field_id,
    crop_id,
    planting_date,
    harvest_date,
    quantity,
    unit
)
VALUES
(
    1,
    1,
    '2026-06-01',
    '2026-10-01',
    5000,
    'kg'
),

(
    2,
    2,
    '2026-01-10',
    '2026-04-10',
    3000,
    'kg'
),

(
    3,
    3,
    '2026-02-01',
    '2026-05-01',
    1500,
    'kg'
),

(
    4,
    4,
    '2026-05-01',
    '2026-08-15',
    4000,
    'kg'
),

(
    5,
    5,
    '2026-01-05',
    '2026-04-20',
    3500,
    'kg'
);


-- ---------------------------------------------------------
-- LIVESTOCK
-- ---------------------------------------------------------

INSERT INTO livestock
(
    farm_id,
    animal_type,
    breed,
    date_of_birth,
    gender,
    health_status,
    quantity
)
VALUES
(
    1,
    'Cow',
    'Holstein',
    '2024-05-10',
    'Female',
    'Healthy',
    1
),

(
    1,
    'Cow',
    'Jersey',
    '2023-08-15',
    'Female',
    'Healthy',
    1
),

(
    2,
    'Chicken',
    'Broiler',
    '2026-02-01',
    'Female',
    'Healthy',
    50
),

(
    3,
    'Goat',
    'Black Bengal',
    '2025-01-10',
    'Male',
    'Healthy',
    3
),

(
    4,
    'Cow',
    'Local',
    '2023-06-20',
    'Female',
    'Under Treatment',
    2
);


-- ---------------------------------------------------------
-- WORKERS
-- ---------------------------------------------------------

INSERT INTO workers
(
    farm_id,
    name,
    phone,
    position,
    salary,
    hire_date
)
VALUES
(
    1,
    'Hasan Ali',
    '01744444444',
    'Farm Worker',
    15000,
    '2025-01-10'
),

(
    1,
    'Rafiq Mia',
    '01855555555',
    'Field Worker',
    14000,
    '2025-03-15'
),

(
    2,
    'Jamal Khan',
    '01966666666',
    'Farm Worker',
    16000,
    '2025-02-20'
),

(
    3,
    'Sakib Ahmed',
    '01677777777',
    'Supervisor',
    22000,
    '2024-12-01'
);


-- ---------------------------------------------------------
-- EQUIPMENT
-- ---------------------------------------------------------

INSERT INTO equipment
(
    farm_id,
    equipment_name,
    purchase_date,
    status,
    purchase_price
)
VALUES
(
    1,
    'Tractor',
    '2025-01-15',
    'Working',
    850000
),

(
    1,
    'Water Pump',
    '2025-03-10',
    'Working',
    45000
),

(
    2,
    'Cultivator',
    '2024-12-20',
    'Maintenance',
    120000
),

(
    3,
    'Harvester',
    '2025-02-10',
    'Working',
    650000
);


-- ---------------------------------------------------------
-- EXPENSES
-- ---------------------------------------------------------

INSERT INTO expenses
(
    farm_id,
    expense_type,
    description,
    amount,
    expense_date
)
VALUES
(
    1,
    'Fertilizer',
    'Rice field fertilizer',
    25000,
    '2026-07-01'
),

(
    1,
    'Labor',
    'Monthly worker salary',
    30000,
    '2026-07-05'
),

(
    1,
    'Seeds',
    'Rice seeds',
    12000,
    '2026-06-01'
),

(
    2,
    'Seeds',
    'Vegetable seeds',
    15000,
    '2026-07-10'
),

(
    3,
    'Animal Feed',
    'Cattle feed',
    18000,
    '2026-07-15'
);


-- ---------------------------------------------------------
-- SALES
-- ---------------------------------------------------------

INSERT INTO sales
(
    farm_id,
    product_name,
    quantity,
    unit,
    price,
    sale_date,
    customer_name
)
VALUES
(
    1,
    'Rice',
    2000,
    'kg',
    55,
    '2026-08-01',
    'Local Market'
),

(
    1,
    'Potato',
    1000,
    'kg',
    35,
    '2026-08-05',
    'Chattogram Wholesale Market'
),

(
    2,
    'Tomato',
    500,
    'kg',
    60,
    '2026-08-10',
    'Dhaka Vegetable Market'
),

(
    3,
    'Wheat',
    1500,
    'kg',
    45,
    '2026-08-12',
    'Cumilla Market'
),

(
    4,
    'Corn',
    1000,
    'kg',
    40,
    '2026-08-15',
    'Rajshahi Market'
);