CREATE TABLE tbl_users
(
	id INT NOT NULL AUTO_INCREMENT,
    mail VARCHAR(64) NOT NULL,
    password VARCHAR(32) NOT NULL,
    name VARCHAR(32) NOT NULL,
    PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

INSERT INTO tbl_users (mail, password, name) VALUES ('john@hotmail.com', '123456', 'John Doe');
INSERT INTO tbl_users (mail, password, name) VALUES ('bill@hotmail.com', 'qwerty', 'Bill Gates');
INSERT INTO tbl_users (mail, password, name) VALUES ('test@test.com', 'qwerty', 'Test User');