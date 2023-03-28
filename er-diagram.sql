CREATE TABLE category
(
    id           INT AUTO_INCREMENT NOT NULL,
    name         VARCHAR(255)                   NOT NULL,
    description  LONGTEXT         DEFAULT NULL,
        PRIMARY KEY (id)
) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB;

CREATE TABLE product
(
    id           INT AUTO_INCREMENT NOT NULL,
    name         VARCHAR(255)                   NOT NULL,
    description  LONGTEXT         DEFAULT NULL,
    manufacturer VARCHAR(255)     DEFAULT NULL,
    price        DOUBLE PRECISION DEFAULT '0' NOT NULL,
    category_id  INT NOT NULL,
    image_1      VARCHAR(255) NULL,
    image_2      VARCHAR(255) NULL,
    image_3      VARCHAR(255) NULL,
    PRIMARY KEY (id),
    FOREIGN KEY (category_id) REFERENCES category(id)
) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB;

/*
 Polia image_1, image_2, image_3 spĺňajú zadanie, ale takáto implementácia nie je optimálna
 Lepšie riešenie je využitie 1:N relácie a počet položiek obmedziť na programovej úrovni
 */
