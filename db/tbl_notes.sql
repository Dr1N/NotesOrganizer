CREATE TABLE tbl_notes
(
    id INT NOT NULL AUTO_INCREMENT,
    user_id INT NOT NULL,
    color_id INT DEFAULT 0,
    title VARCHAR(64) NOT NULL,
    note VARCHAR(2048),
    is_shared INT(1) NOT NULL DEFAULT 0,
    datetime INT DEFAULT NULL,
    PRIMARY KEY(id),
    FOREIGN KEY (user_id) REFERENCES tbl_users(id) 
    	ON DELETE CASCADE 
        ON UPDATE CASCADE,
    FOREIGN KEY (color_id) REFERENCES tbl_colors(id) 
    	ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;