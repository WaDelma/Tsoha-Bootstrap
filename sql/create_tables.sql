-- Lisää CREATE TABLE lauseet tähän tiedostoon
CREATE TABLE Admin
(
id SERIAL PRIMARY KEY,
name varchar(30),
email varchar(30),
hash int NOT NULL,
salt int NOT NULL
);

CREATE TABLE Board
(
id SERIAL PRIMARY KEY,
name varchar(30) NOT NULL,
description varchar(10000)
);

CREATE TABLE BoardAdmin
(
adminId INTEGER REFERENCES Admin(id),
boardId INTEGER REFERENCES Board(id),
PRIMARY KEY(adminId, boardId)
);

CREATE TABLE Useri
(
id SERIAL PRIMARY KEY,
ip varchar(25) NOT NULL
);

CREATE TABLE Thread
(
id SERIAL PRIMARY KEY,
boardId INTEGER REFERENCES Board(id)
);

CREATE TABLE Post
(
id SERIAL PRIMARY KEY,
threadId INTEGER REFERENCES Thread(id),
userId INTEGER REFERENCES Useri(id),
content varchar(10000) NOT NULL
);

CREATE TABLE Banned
(
ip varchar(25) PRIMARY KEY
);
