set FOREIGN_KEY_CHECKS = 0;

DROP TABLE IF EXISTS Customer;
DROP TABLE IF EXISTS Users;
DROP TABLE IF EXISTS userType;
DROP TABLE IF EXISTS custUser;

DROP TABLE IF EXISTS Orders;
DROP TABLE IF EXISTS OrderStatus;
DROP TABLE IF EXISTS OrderStatusEvent;

DROP TABLE IF EXISTS Pallet;
DROP TABLE IF EXISTS OrderSpec;
DROP TABLE IF EXISTS OrderPallet;

DROP TABLE IF EXISTS Recipe;
DROP TABLE IF EXISTS Ingredient;
DROP TABLE IF EXISTS RawMaterial;
DROP TABLE IF EXISTS StockEvent;

#Kanske eventuellt lägga till id som primary key om det skulle vara två kunder med samma namn

CREATE TABLE userType(
name varchar(30) PRIMARY KEY NOT NULL
);

CREATE TABLE users(
pNbr integer PRIMARY KEY NOT NULL,
name varchar(30) NOT NULL,
type varchar(30) NOT NULL,
foreign key (type) references userType(name)
);

CREATE TABLE Customer(
	name varchar(30) PRIMARY KEY NOT NULL,
	address varchar(30) NOT NULL,
	telephone varchar(30)
);

CREATE TABLE custUser(
	pNbr integer,
	name varchar(30),
	FOREIGN KEY(pNbr) REFERENCES users(pNbr),
	FOREIGN KEY(name) REFERENCES Customer(name),
	PRIMARY KEY (pNbr, name),
	CONSTRAINT cons UNIQUE (pNbr,name)
);

CREATE TABLE Orders(
	id int PRIMARY KEY NOT NULL AUTO_INCREMENT,
	customerName varchar(30) NOT NULL,
	createdTime time NOT NULL,
	createdDate date NOT NULL,
	deliveryDate date NOT NULL,
	deliveryTime time NOT NULL,
	FOREIGN KEY(customerName) REFERENCES Customer(name)
);

CREATE TABLE OrderStatus(
	name varchar(30) PRIMARY KEY NOT NULL
);

CREATE TABLE OrderStatusEvent(
	id int PRIMARY KEY NOT NULL AUTO_INCREMENT,
 /*   createdTime time NOT NULL,
    createdDate date NOT NULL, */
    orderId int NOT NULL, 
    statusName varchar(30) NOT NULL,
    FOREIGN KEY(orderId) REFERENCES Orders(id) ON DELETE CASCADE,
    FOREIGN KEY(statusName) REFERENCES OrderStatus(name)
);

CREATE TABLE Pallet(
	id int PRIMARY KEY NOT NULL AUTO_INCREMENT,
    barcodeId int NOT NULL,
	createdTime time NOT NULL,
	createdDate date NOT NULL,
	blocked boolean default false,
	recipeName varchar(30) NOT NULL,
	FOREIGN KEY(recipeName) REFERENCES Recipe(name) ON DELETE CASCADE
);

CREATE TABLE OrderSpec(
	id int PRIMARY KEY NOT NULL AUTO_INCREMENT,
	orderId int NOT NULL,
	recipeName varchar(30) NOT NULL,
	quantity int NOT NULL,
	FOREIGN KEY(recipeName) REFERENCES Recipe(name) ON DELETE CASCADE,
	FOREIGN KEY(orderId) REFERENCES Orders(id) ON DELETE CASCADE,
	CONSTRAINT cons UNIQUE (recipeName, orderId)
 );

CREATE TABLE OrderPallet(
	palletId int NOT NULL,
    orderId int NOT NULL, 
    FOREIGN KEY(palletId) REFERENCES Pallet(id),
    FOREIGN KEY(orderId) REFERENCES Orders(id),
    CONSTRAINT cons UNIQUE (palletId, orderId)
);

CREATE TABLE Recipe(
	name varchar(30) PRIMARY KEY NOT NULL
);

CREATE TABLE Ingredient(
	id int PRIMARY KEY NOT NULL AUTO_INCREMENT,
	rawMaterialName varchar(30) NOT NULL,
	quantity double NOT NULL,
	recipeName varchar(30) NOT NULL,
	FOREIGN KEY(recipeName) REFERENCES Recipe(name) ON DELETE CASCADE,
	FOREIGN KEY(rawMaterialName) REFERENCES RawMaterial(name) ON DELETE CASCADE,
	CONSTRAINT cons UNIQUE (recipeName,rawMaterialName)

);

CREATE TABLE RawMaterial(
	name varchar(30) PRIMARY KEY NOT NULL,
    quantityStock int NOT NULL,
	unit varchar(30) NOT NULL
);

CREATE TABLE StockEvent(
	id int PRIMARY KEY NOT NULL AUTO_INCREMENT,
    quantity int NOT NULL,
    createdTime time NOT NULL, 
    createdDate date NOT NULL, 
    rawMaterialName varchar(30) NOT NULL,
    FOREIGN KEY(rawMaterialName) REFERENCES RawMaterial(name)
);


#-------------------------------------

set FOREIGN_KEY_CHECKS = 1;

Start transaction;
-- Create Recipies --
INSERT INTO Recipe(name) VALUES 
('Nut ring'), 
('Nut cookie'), 
('Amneris'), 
('Tango'), 
('Almond delight'), 
('Berliner');


INSERT INTO userType(name) VALUES 
('OrdersDelivers'),
('StockManager'),
('Production'),
('Customer'),
('superUser');


INSERT INTO users(pNbr, name, type) VALUES 
('650101125', 'Oscar', 'Customer'),
('911010101', 'Erik', 'Customer'),
('911010102', 'Kasper', 'Customer'),
('911010106', 'Josefin', 'Customer'),
('911010116', 'Sara', 'Customer'),
('911010104', 'Katarina', 'Customer'),
('911010112', 'Marie', 'Customer'),
('911010131', 'Albert', 'Customer'),
('910328233', 'Claes', 'Production'),
('011101231','Per', 'StockManager' ),
('421101241', 'Henrik', 'OrdersDelivers'),
('550505155', 'Bosse', 'superUser');

INSERT INTO Customer(name, address) VALUES
('Finkakor AB', 'Helsingborg'),
('Småbröd AB', 'Malmö'),
('Kaffebröd AB', 'Landskrona'),
('Bjudkakor AB', 'Ystad'),
('Kalaskakor AB', 'Trelleborg'),
('Partkakor AB', 'Kristianstad'),
('Gästkakor AB', 'Hässleholm'),
('Skånekakor AB', 'Perstorp');

INSERT INTO custUser(pNbr, name) VALUES
('650101125', 'Finkakor AB'),
('911010102', 'Kaffebröd AB'),
('911010101', 'Bjudkakor AB'),
('911010116', 'Kalaskakor AB'),
('911010106', 'Partkakor AB'),
('911010104', 'Gästkakor AB'),
('911010112', 'Skånekakor AB'),
('911010131', 'Småbröd AB');


-- Create RawMaterials --
INSERT INTO RawMaterial(name, quantityStock, unit) VALUES 
('Flour', 1000000, 'g'),
('Butter', 1000000, 'g'),
('Icing sugar', 1000000, 'g'),
('Roasted, chopped nuts', 1000000, 'g'),
('Fine-ground nuts', 1000000, 'g'),
('Ground, roasted nuts', 1000000, 'g'),
('Bread crumbs', 1000000, 'g'),
('Sugar', 1000000, 'g'),
('Egg whites', 1000000, 'dl'),
('Chocolate', 1000000, 'g'),
('Marzipan', 1000000, 'g'),
('Eggs', 1000000, 'g'),
('Potato starch', 1000000, 'g'),
('Wheat flour', 1000000, 'g'),
('Sodium bicarbonate', 1000000, 'g'),
('Vanilla', 1000000, 'g'),
('Chopped almonds', 1000000, 'g'),
('Cinnamon', 1000000, 'g'),
('Vanilla sugar', 1000000, 'g');

-- Create Recipe ingredients --
INSERT INTO Ingredient(rawMaterialName, quantity, recipeName) VALUES
('Flour', 450, 'Nut ring'),
('Butter', 450, 'Nut ring'),

('Icing sugar', 190, 'Nut ring'),
('Roasted, chopped nuts', 225, 'Nut ring'),
('Fine-ground nuts', 750, 'Nut cookie'),
('Ground, roasted nuts', 625, 'Nut cookie'),
('Bread crumbs', 125, 'Nut cookie'),
('Sugar', 375, 'Nut cookie'),
('Egg whites', 3.5, 'Nut cookie'),
('Chocolate', 50, 'Nut cookie'),
('Marzipan', 750, 'Amneris'),
('Butter', 250, 'Amneris'),
('Eggs', 250, 'Amneris');

INSERT INTO Ingredient(rawMaterialName, quantity, recipeName) VALUES
('Potato starch', 25, 'Amneris'),
('Wheat flour', 25, 'Amneris'),
('Butter', 200, 'Tango'),
('Sugar', 250, 'Tango'),
('Flour', 300, 'Tango'),
('Sodium bicarbonate', 4, 'Tango'),
('Vanilla', 2, 'Tango'),
('Butter', 400, 'Almond delight'),
('Sugar', 270, 'Almond delight'),
('Chopped almonds', 279, 'Almond delight'),
('Flour', 400, 'Almond delight'),
('Cinnamon', 10, 'Almond delight'),
('Flour', 350, 'Berliner'),
('Butter', 250, 'Berliner'),
('Icing sugar', 100, 'Berliner'),
('Eggs', 50, 'Berliner'),
('Vanilla sugar', 5, 'Berliner'),
('Chocolate', 50, 'Berliner');


INSERT INTO orderStatus(name) VALUES 
('Recieved'),
('Canceled'),
('Delivered');



INSERT INTO pallet(barcodeId, createdTime, createdDate, blocked, recipeName) VALUES
(1, curtime(), curdate(), false, 'Amneris'),
(2, curtime(), curdate(), false, 'Amneris'),
(3, curtime(), curdate(), false, 'Amneris'),
(4, curtime(), curdate(), false, 'Amneris'),
(5, curtime(), curdate(), false, 'Almond delight'),
(6, curtime(), curdate(), false, 'Almond delight'),
(7, curtime(), curdate(), false, 'Almond delight'),
(8, curtime(), curdate(), false, 'Almond delight'),
(9, curtime(), curdate(), false, 'Berliner'),
(10, curtime(), curdate(), false, 'Berliner'),
(11, curtime(), curdate(), false, 'Berliner'),
(12, curtime(), curdate(), false, 'Berliner'),
(13, curtime(), curdate(), false, 'Nut cookie'),
(14, curtime(), curdate(), false, 'Nut cookie'),
(15, curtime(), curdate(), false, 'Nut cookie'),
(16, curtime(), curdate(), false, 'Nut cookie'),
(17, curtime(), curdate(), false, 'Nut ring'),
(18, curtime(), curdate(), false, 'Nut ring'),
(19, curtime(), curdate(), false, 'Nut ring'),
(20, curtime(), curdate(), false, 'Tango'),
(21, curtime(), curdate(), false, 'Tango'),
(22, curtime(), curdate(), false, 'Tango'),
(23, curtime(), curdate(), false, 'Tango');


INSERT INTO orders(customerName, createdTime, createdDate, deliveryDate, deliveryTime) VALUES
('Kalaskakor AB', '12:00:00', '2016-01-01', '2016-04-11', '16:00:00'),
('Finkakor AB', '12:00:00', '2016-01-01', '2016-04-11', '16:00:00'),
('Partkakor AB', '12:00:00', '2016-01-01', '2016-04-11', '16:00:00'),
('Bjudkakor AB', '12:00:00', '2016-01-01', '2016-04-11', '16:00:00'),
('Gästkakor AB', '12:00:00', '2016-01-01', '2016-04-11', '16:00:00'),
('Skånekakor AB', '12:00:00', '2016-01-01', '2016-04-11', '16:00:00'),
('Kalaskakor AB', '12:00:00', '2016-01-01', '2016-04-11', '16:00:00'),
('Finkakor AB', '12:00:00', '2016-01-02', '2016-04-12', '16:00:00'),
('Partkakor AB', '12:00:00', '2016-01-02', '2016-04-12', '16:00:00'),
('Bjudkakor AB', '12:00:00', '2016-01-02', '2016-04-12', '16:00:00'),
('Gästkakor AB', '12:00:00', '2016-01-02', '2016-04-12', '16:00:00'),
('Skånekakor AB', '12:00:00', '2016-01-02', '2016-04-12', '16:00:00'),
('Kalaskakor AB', '12:00:00', '2016-01-02', '2016-04-12', '16:00:00'),
('Finkakor AB', '12:00:00', '2016-01-03', '2016-04-13', '16:00:00'),
('Partkakor AB', '12:00:00', '2016-01-03', '2016-04-13', '16:00:00'),
('Bjudkakor AB', '12:00:00', '2016-01-03', '2016-04-13', '16:00:00'),
('Gästkakor AB', '12:00:00', '2016-01-03', '2016-04-13', '16:00:00'),
('Skånekakor AB', '12:00:00', '2016-01-03', '2016-04-13', '16:00:00');


 INSERT INTO OrderStatusEvent(orderId, statusName) VALUES 
 (1, 'Recieved'),
 (2, 'Recieved'),
 (3, 'Recieved'),
 (4, 'Recieved'),
 (5, 'Recieved'),
 (6, 'Recieved'),
 (7, 'Canceled'),
 (8, 'Canceled'),
 (9, 'Canceled'),
 (10, 'Canceled'),
 (11, 'Canceled'),
 (12, 'Canceled'),
 (13, 'Delivered'),
 (14, 'Delivered'),
 (15, 'Delivered'),
 (16, 'Delivered'),
 (17, 'Delivered'),
 (18, 'Delivered');


 INSERT INTO orderPallet(palletId, orderId) VALUES
 (1,13),
 (2,14),
 (3,15),
 (4,16),
 (5,17),
 (6,18);


 INSERT INTO OrderSpec(orderId, recipeName, quantity) VALUES
 (1, 'Tango', 1),
 (2, 'Amneris', 1),
 (3, 'Almond delight', 1),
 (4, 'Nut cookie', 1),
 (5, 'Berliner', 1),
 (6, 'Nut ring', 1),
 (7, 'Tango', 1),
 (8, 'Amneris', 1),
 (9, 'Almond delight', 1),
 (10, 'Nut cookie', 1),
 (11, 'Berliner', 1),
 (12, 'Nut ring', 1),
 (2, 'Tango', 1),
 (3, 'Amneris', 1),
 (4, 'Almond delight', 1),
 (5, 'Nut cookie', 1),
 (6, 'Berliner', 1),
 (7, 'Nut ring', 1),
 (8, 'Tango', 1),
 (9, 'Amneris', 1),
 (10, 'Almond delight', 1),
 (11, 'Nut cookie', 1),
 (12, 'Berliner', 1),
 (1, 'Nut ring', 1),
 (3, 'Tango', 1),
 (4, 'Amneris', 1),
 (5, 'Almond delight', 1),
 (6, 'Nut cookie', 1),
 (7, 'Berliner', 1),
 (8, 'Nut ring', 1),
 (9, 'Tango', 1),
 (10, 'Amneris', 1),
 (11, 'Almond delight', 1),
 (12, 'Nut cookie', 1),
 (1, 'Berliner', 1),
 (2, 'Nut ring', 1);






commit;


#checks:
select * from Recipe;
select * from Customer;
select * from rawmaterial;
select * from ingredient;