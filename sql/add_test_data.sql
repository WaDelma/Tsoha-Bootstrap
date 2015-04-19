-- Lisää INSERT INTO lauseet tähän tiedostoon
INSERT INTO Admin (name, email, hash, super) VALUES ('minad', 'l33t@exam.ple', '$2a$07$pKTBHcqlznn37hRGQOOFzu9.i.CxpUU8n0cVS/aXg8QVY99Z0fEgW', false);
INSERT INTO Admin (name, email, hash, super) VALUES ('ad_min', 'l33test@exam.ple', '$2a$07$nQ4gUb2auEHq0h8AHiLYFueChXdBhJlgswJ46sF.S4UJVg6Ex7o5G', true);
INSERT INTO Board (name, description) VALUES ('v', '!!vidya gaimz!!');
INSERT INTO Board (name, description) VALUES ('pol', 'Such correct');
INSERT INTO AdminBoard (adminId, boardId) VALUES (1, 1);
INSERT INTO Thread (boardId) VALUES (1);
INSERT INTO Thread (boardId) VALUES (1);
INSERT INTO Thread (boardId) VALUES (2);
INSERT INTO Useri (ip) VALUES ('localhost');
INSERT INTO Useri (ip) VALUES ('localhost2');
INSERT INTO Post (threadId, userId, content) VALUES (1, 1, 'LoL');
INSERT INTO Post (threadId, userId, content) VALUES (1, 2, '< Dota');
INSERT INTO Post (threadId, userId, content) VALUES (3, 2, 'Hailstorm');
INSERT INTO Post (threadId, userId, content) VALUES (2, 1, 'Poe''s law');