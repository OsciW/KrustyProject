
set FOREIGN_KEY_CHECKS = 0;

DROP TABLE IF EXISTS Pallet;
DROP TABLE IF EXISTS Recipe;
DROP TABLE IF EXISTS Ingredient;
DROP TABLE IF EXISTS RawMaterial;
DROP TABLE IF EXISTS Customer;
DROP TABLE IF EXISTS Orders;
DROP TABLE IF EXISTS OrderSpec;


CREATE TABLE Recipe(
	name varchar(20) PRIMARY KEY NOT NULL
);

CREATE TABLE RawMaterial(
	name varchar(30) PRIMARY KEY NOT NULL,
	quantity int NOT NULL
);

CREATE TABLE Pallet(
	id int PRIMARY KEY NOT NULL AUTO_INCREMENT,
	tid time NOT NULL,
	datum date NOT NULL,
	blocked boolean default false,
	RName varchar(20) NOT NULL,
	FOREIGN KEY(RName) REFERENCES Recipe(name)
);

CREATE TABLE Ingredient(
	id int PRIMARY KEY NOT NULL AUTO_INCREMENT,
	rawName varchar(30) NOT NULL,
	quantity double NOT NULL,
	RName varchar(20) NOT NULL,
	FOREIGN KEY(RName) REFERENCES Recipe(name),
	FOREIGN KEY(rawName) REFERENCES RawMaterial(name),
	CONSTRAINT cons UNIQUE (RName,rawName)
);

CREATE TABLE Customer(
	name varchar(20) PRIMARY KEY NOT NULL,
	address varchar(20) NOT NULL,
	region varchar(20)  /* check this later*/
);

CREATE TABLE Orders(
	id int PRIMARY KEY NOT NULL AUTO_INCREMENT,
	CName varchar(20) NOT NULL,
	tid time NOT NULL,
	datum date NOT NULL,
	FOREIGN KEY(CName) REFERENCES Customer(name)
);

CREATE TABLE OrderSpec(
	id int PRIMARY KEY NOT NULL AUTO_INCREMENT,
	orderId int NOT NULL,
	RName varchar(20) NOT NULL,
	quantity int NOT NULL,
	FOREIGN KEY(RName) REFERENCES Recipe(name),
	FOREIGN KEY(orderId) REFERENCES Orders(id),
	CONSTRAINT cons UNIQUE (RName, orderId)
 );

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


-- Insert Customers --
INSERT INTO Customer(name, address) VALUES
('Finkakor AB', 'Helsingborg'),
('Småbröd AB', 'Malmö'),
('Kaffebröd AB', 'Landskrona'),
('Bjudkakor AB', 'Ystad'),
('Kalaskakor AB', 'Trelleborg'),
('Partkakor AB', 'Kristianstad'),
('Gästkakor AB', 'Hässleholm'),
('Skånekakor AB', 'Perstorp');

-- Create RawMaterials --
INSERT INTO RawMaterial(name, quantity) VALUES 
('Flour', 100000),
('Butter', 100000),
('Icing sugar', 100000),
('Roasted, chopped nuts', 100000),
('Fine-ground nuts', 100000),
('Ground, roasted nuts', 100000),
('Bread crumbs', 100000),
('Sugar', 100000),
('Egg whites', 100000),
('Chocolate', 100000),
('Marzipan', 100000),
('Eggs', 100000),
('Potato starch', 100000),
('Wheat flour', 100000),
('Sodium bicarbonate', 100000),
('Vanilla', 100000),
('Chopped almonds', 100000),
('Cinnamon', 100000),
('Vanilla sugar', 100000);

-- Create Recipe ingredients --
INSERT INTO Ingredient(rawName, quantity, RName) VALUES
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

INSERT INTO Ingredient(rawName, quantity, RName) VALUES
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

commit;








