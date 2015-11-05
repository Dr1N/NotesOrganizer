CREATE TABLE tbl_tags
(
	id INT NOT NULL AUTO_INCREMENT,
	user_id INT DEFAULT NULL,
    name VARCHAR(64) NOT NULL,
    image VARCHAR(256) DEFAULT NULL,
    PRIMARY KEY(id),
    FOREIGN KEY (user_id) REFERENCES tbl_users(id) 
    	ON DELETE CASCADE 
        ON UPDATE CASCADE 
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

INSERT INTO tbl_tags (name) VALUES ('День рождения');
INSERT INTO tbl_tags (name) VALUES ('Выполнить');
INSERT INTO tbl_tags (name) VALUES ('Купить');
INSERT INTO tbl_tags (name) VALUES ('Встреча');
INSERT INTO tbl_tags (name) VALUES ('Поездка');
