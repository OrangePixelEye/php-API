CREATE DATABASE NiceBank;

USE NiceBank;

CREATE TABLE people(
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    document_id INT NOT NULL,
    born_date DATE NOT NULL,
    type ENUM('adm', 'manager', 'client'),
    status ENUM('available', 'on-analysis'),
    password INT(4) NOT NULL
);

CREATE TABLE accounts(
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    id_client INT,
    password INT(4) NOT NULL,
    money FLOAT NULL,
    type ENUM('savings', 'current'),
    FOREIGN KEY (id_client) REFERENCES people(id)
);

CREATE TABLE transference(
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    amount FLOAT NOT NULL,
    origin INT,
    receiver INT,
    FOREIGN KEY (origin) REFERENCES accounts(id),
    FOREIGN KEY (receiver) REFERENCES accounts(id)
);

INSERT INTO people (name, document_id, born_date, type, status, password) VALUES ("Maria Smith", 99999999 , "2001-02-02", "client", "available", 1212);
INSERT INTO people (name, document_id, born_date, type, status, password) VALUES ("John Smith", 99999979 , "1999-04-03" , "client", "on-analysis", 1213);
INSERT INTO people (name, document_id, born_date, type, status, password) VALUES ("Richard Smith", 99499999 , "1987-03-05", "client", "available", 3214);
INSERT INTO people (name, document_id, born_date, type, status, password) VALUES ("Joel Smith", 99991999 , "1985-08-02", "manager", "available", 5733);
INSERT INTO people (name, document_id, born_date, type, status, password) VALUES ("Jane Baptista", 99939999 , "1978-12-12", "adm", "available", 6346);

INSERT INTO accounts (id_client, password, money, type) VALUES (1, 1212, 20, "savings");
INSERT INTO accounts (id_client, password, money, type) VALUES (2, 2525, 2000.2, "savings");
INSERT INTO accounts (id_client, password, money, type) VALUES (1, 2520, 220, "current");

INSERT INTO transference(amount, origin, receiver) VALUES (2, 2, 1);
