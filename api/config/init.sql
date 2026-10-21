SET NAMES utf8mb4;
USE automotora;

CREATE TABLE MARCAS (
    id_marca INT AUTO_INCREMENT PRIMARY KEY,
    nombre_marca VARCHAR(50) NOT NULL,
    pais_origen VARCHAR(50)
);

CREATE TABLE VEHICULOS (
    id_vehiculo INT AUTO_INCREMENT PRIMARY KEY,
    id_marca INT,
    modelo VARCHAR(50) NOT NULL,
    anio INT,
    precio DECIMAL(10, 2),
    estado VARCHAR(20),
    FOREIGN KEY (id_marca) REFERENCES MARCAS(id_marca)
);

CREATE TABLE CLIENTES (
    id_cliente INT AUTO_INCREMENT PRIMARY KEY,
    nombre_completo VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE,
    telefono VARCHAR(20)
);

CREATE TABLE EMPLEADOS (
    id_empleado INT AUTO_INCREMENT PRIMARY KEY,
    nombre_completo VARCHAR(100) NOT NULL,
    cargo VARCHAR(50),
    salario DECIMAL(10, 2)
);

CREATE TABLE VENTAS (
    id_venta INT AUTO_INCREMENT PRIMARY KEY,
    id_vehiculo INT UNIQUE,
    id_cliente INT,
    id_empleado INT,
    fecha_venta DATE,
    monto_total DECIMAL(10, 2),
    FOREIGN KEY (id_vehiculo) REFERENCES VEHICULOS(id_vehiculo),
    FOREIGN KEY (id_cliente) REFERENCES CLIENTES(id_cliente),
    FOREIGN KEY (id_empleado) REFERENCES EMPLEADOS(id_empleado)
);

INSERT INTO MARCAS (nombre_marca, pais_origen) VALUES
('Toyota', 'Japón'),
('Volkswagen', 'Alemania'),
('Ford', 'Estados Unidos'),
('Chevrolet', 'Estados Unidos'),
('Tesla', 'Estados Unidos');

INSERT INTO VEHICULOS (id_marca, modelo, anio, precio, estado) VALUES
(1, 'Corolla', 2023, 25000.00, 'Nuevo'),
(1, 'Hilux', 2019, 32000.00, 'Usado'),
(2, 'Golf', 2018, 18000.00, 'Usado'),
(2, 'Taos', 2024, 35000.00, 'Nuevo'),
(3, 'Mustang', 2021, 45000.00, 'Usado'),
(3, 'Ranger', 2024, 40000.00, 'Nuevo'),
(4, 'Onix', 2022, 15000.00, 'Usado');

INSERT INTO CLIENTES (nombre_completo, email, telefono) VALUES
('Martín Silva', 'martin.silva@email.com', '099123456'),
('Ana López', 'ana.lopez@email.com', NULL),
('Carlos Rodríguez', 'carlos.r@email.com', '098765432'),
('Laura Martínez', 'laura.m@email.com', '091555666'),
('Pedro Gómez', 'pedro.gomez@email.com', NULL);

INSERT INTO EMPLEADOS (nombre_completo, cargo, salario) VALUES
('Roberto Sánchez', 'Vendedor Junior', 30000.00),
('Sofía Acosta', 'Vendedora Senior', 45000.00),
('Diego Fernández', 'Gerente de Ventas', 70000.00);

INSERT INTO VENTAS (id_vehiculo, id_cliente, id_empleado, fecha_venta, monto_total) VALUES
(1, 1, 2, '2024-01-15', 25000.00),
(3, 2, 1, '2024-02-10', 17500.00),
(5, 4, 2, '2024-03-22', 45000.00),
(2, 3, 3, '2024-04-05', 31000.00);
