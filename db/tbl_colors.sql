CREATE TABLE tbl_colors
(
	id INT NOT NULL AUTO_INCREMENT,
    value VARCHAR(6) DEFAULT NULL,
    name VARCHAR(32) NOT NULL,
    PRIMARY KEY(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

INSERT INTO `tbl_colors` (`id`, `value`, `name`) VALUES
(0, NULL, 'Нет цвета'),
(1, 'FFFFFF', 'Белый'),
(2, 'FFFF00', 'Жёлтый'),
(3, 'FFD700', 'Золотой'),
(4, 'FFC0CB', 'Розовый'),
(5, '87CEFA', 'Голубой'),
(6, '00FFFF', 'Аква'),
(7, '32CD32', 'Зелёный');