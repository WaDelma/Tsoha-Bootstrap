-- Lisää INSERT INTO lauseet tähän tiedostoon
INSERT INTO Admin (name, email, hash) VALUES ('minad', 'l33t@exam.ple', '$2a$07$raDj0hOULOZhSvY5DTuhEuu8rUREYzzbVjZveDQhUvAtyOMGkbhpS');
INSERT INTO Board (name, description) VALUES ('v', '!!vidya gaimz!!');
INSERT INTO Board (name, description) VALUES ('pol', 'Such correct');
INSERT INTO Thread (boardId) VALUES (1);
INSERT INTO Thread (boardId) VALUES (1);
INSERT INTO Thread (boardId) VALUES (2);
INSERT INTO Useri (ip) VALUES ('localhost');
INSERT INTO Useri (ip) VALUES ('localhost2');
INSERT INTO Post (threadId, userId, content) VALUES (1, 1, 'LoL');
INSERT INTO Post (threadId, userId, content) VALUES (1, 2, '< Dota');
INSERT INTO Post (threadId, userId, content) VALUES (3, 2, 'Hailstorm');
INSERT INTO Post (threadId, userId, content) VALUES (2, 1, 'Poe''s law');
