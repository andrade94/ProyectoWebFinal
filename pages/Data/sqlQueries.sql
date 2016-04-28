CREATE DATABASE EPOSS;
use EPOSS;

CREATE TABLE DETAIL (
  idQuotation INT NOT NULL,
  idProduct INT NOT NULL,
  quantity INT NOT NULL,
  price FLOAT NOT NULL,
  total FLOAT NOT NULL,
  PRIMARY KEY (idQuotation,idProduct)
);

INSERT INTO DETAIL(idQuotation, idProduct, quantity, price, total)
VALUES ('1','1','10','100','500'),
       ('2','2','10','120','550');


CREATE TABLE EMPLOYEE (
  id VARCHAR(20) PRIMARY KEY,
  password VARCHAR(250) NOT NULL,
  type VARCHAR(1) NOT NULL,
  name VARCHAR(50) NOT NULL,
  lastname VARCHAR(50) NOT NULL,
  birthdate DATE NOT NULL,
  phone VARCHAR(20) NOT NULL,
  address VARCHAR(100) NOT NULL
);

INSERT INTO EMPLOYEE(id, password, type, name, lastname, birthdate, phone, address)
VALUES ('admin','9tjX3ZNozLSlaLEv1/g1UA6nHsq1ejJg3jyKbJeiekw=','A','Eduardo','Andrade','2016-12-12','8116907350','drago'),
       ('2','9tjX3ZNozLSlaLEv1/g1UA6nHsq1ejJg3jyKbJeiekw=','E','Francisco','Canseco','2016-10-12','811348939','delbosque'),
       ('employee','9tjX3ZNozLSlaLEv1/g1UA6nHsq1ejJg3jyKbJeiekw=','E','Julian','Orozco','2016-10-12','81134349','cumbres');

CREATE TABLE PRODUCT (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(40) NOT NULL,
  price FLOAT NOT NULL,
  imageDir VARCHAR(2083) NOT NULL,
  stock INT NOT NULL
);

INSERT INTO PRODUCT(id,name,price,imageDir,stock)
VALUES ('1','Leche','100','http://cdn.lopezdoriga.com/wp-content/uploads/2015/09/leche.jpg','2'),
       ('2','Gansito','50','http://www.nutriciongrupobimbo.com/assets/new_images/nuestras_marcas/detalle-producto/galletas/marinela/Bimbo-galleta-gansito.png','4'),
       ('3','Papitas','120','http://www.producomercio.com/web/images/papasFritas.jpg','10');

CREATE TABLE QUOTATION (
  id INT AUTO_INCREMENT PRIMARY KEY,
  idEmployee VARCHAR(50) NOT NULL,
  card VARCHAR(4) NOT NULL,
  date DATE NOT NULL,
  total FLOAT NOT NULL
);

INSERT INTO QUOTATION(id,idEmployee,card,date,total)
VALUES ('1','1','1040','2016-10-10','24'),
       ('2','2','5450','2016-09-14','20'),
       ('3','3','1420','2015-10-10','10');