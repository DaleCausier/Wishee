DROP TABLE IF EXISTS `#__wishee_gifts`;

CREATE TABLE `#__wishee_gifts` (
    `gift_id`             int(11)         NOT NULL AUTO_INCREMENT,
    `user_id`             int(11)         NOT NULL,
    `store_id`            int(11)         NOT NULL,
    `product_id`          varchar(100)    NOT NULL,
    `product_name`        varchar(250)    NOT NULL,
    `product_price`       varchar(100)    NOT NULL,
    `product_category`    varchar(250)    NOT NULL,
    `product_image_url`   varchar(250)    NOT NULL,
    `product_store_url`   varchar(250)    NOT NULL,
    `purchased`           tinyint(1)      NOT NULL DEFAULT '0',
    PRIMARY KEY (`gift_id`)
) ENGINE=MyISAM AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `#__wishee_stores`;

CREATE TABLE `#__wishee_stores` (
    `store_id`            int(11)         NOT NULL,
    `store_name`          varchar(100)    NOT NULL,
    PRIMARY KEY (`store_id`)
) ENGINE=MyISAM AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;