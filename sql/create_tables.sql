-- Lisää CREATE TABLE lauseet tähän tiedostoon
CREATE TABLE Admin
(
name varchar(30) PRIMARY KEY,
email varchar(30),
hash int NOT NULL,
salt int NOT NULL
);

CREATE TABLE BoardAdmin
(
adminId varchar(30) FOREIGN KEY,
boardId varchar(30) FOREIGN KEY
PRIMARY KEY(adminId, boardId)
);

CREATE TABLE Board
(
id varchar(30) PRIMARY KEY,
description varchar(10000)
);

CREATE TABLE Post
(
id int,
threadId int FOREIGN KEY,
userId int FOREIGN KEY,
content varchar(10000) NOT NULL
PRIMARY KEY(id, threadId)
);

CREATE TABLE User
(
id int PRIMARY KEY,
ip String varchar(25)
);

CREATE TABLE Thread
(
id int,
boardId FOREIGN KEY,
creationTime timestamp
PRIMARY KEY(id, boardId)
);

CREATE TABLE Banne
(
ip varchar(25) PRIMARY KEY
);