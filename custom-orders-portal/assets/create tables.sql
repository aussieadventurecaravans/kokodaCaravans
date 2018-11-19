CREATE TABLE `custom_orders` (
  `order_id` mediumint(9) NOT NULL AUTO_INCREMENT,
  `quote_id` mediumint(9) NOT NULL,
  `customer_first_name` varchar(255) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  `customer_last_name` varchar(255) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  `product_id` varchar(255) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  `product_name` varchar(255) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  `custom_options` longtext COLLATE utf8mb4_unicode_520_ci,
  `add_on_accessories` longtext COLLATE utf8mb4_unicode_520_ci,
  `other_options` longtext COLLATE utf8mb4_unicode_520_ci,
  `product_cost` double NOT NULL DEFAULT '0',
  `orc_cost` double NOT NULL DEFAULT '0',
  `add_on_cost` double NOT NULL DEFAULT '0',
  `total_cost` double NOT NULL DEFAULT '0',
  `payment_method` varchar(80) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  `customer_address` varchar(255) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  `customer_city` varchar(255) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  `customer_postcode` varchar(255) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  `customer_state` varchar(255) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  `customer_email` varchar(255) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  `customer_phone` varchar(15) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  `dealer_id` mediumint(9) NOT NULL DEFAULT '0',
  `dealer_name` varchar(255) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  `dealer_email` varchar(255) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  `dealer_phone` varchar(15) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  `dealer_address` varchar(255) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  `dealer_city` varchar(255) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  `dealer_state` varchar(255) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  `dealer_postcode` varchar(255) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  `status` varchar(255) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT 'In Progress',
  `has_loan` tinyint(1) NOT NULL DEFAULT '0',
  `apply_loan_option` varchar(55) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT 'None',
  `loan_status` varchar(50) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT 'Pending',
  `loan_detail` longtext COLLATE utf8mb4_unicode_520_ci,
  `date_created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `date_modified` datetime NOT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`order_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;


CREATE TABLE `quotes` (
  `quote_id` mediumint(9) NOT NULL AUTO_INCREMENT,
  `web_quote_id` mediumint(9),
  `customer_first_name` varchar(255) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  `customer_last_name` varchar(255) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  `product_id` varchar(255) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  `product_name` varchar(255) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  `custom_options` longtext COLLATE utf8mb4_unicode_520_ci,
  `add_on_accessories` longtext COLLATE utf8mb4_unicode_520_ci,
  `other_options` longtext COLLATE utf8mb4_unicode_520_ci,
  `product_cost` double NOT NULL DEFAULT '0',
  `orc_cost` double NOT NULL DEFAULT '0',
  `add_on_cost` double NOT NULL DEFAULT '0',
  `total_cost` double NOT NULL DEFAULT '0',
  `payment_method` varchar(80) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  `customer_address` varchar(255) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  `customer_city` varchar(255) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  `customer_postcode` varchar(255) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  `customer_state` varchar(255) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  `customer_email` varchar(255) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  `customer_phone` varchar(15) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  `dealer_id` mediumint(9) NOT NULL DEFAULT '0',
  `dealer_name` varchar(255) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  `dealer_email` varchar(255) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  `dealer_phone` varchar(15) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  `dealer_address` varchar(255) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  `dealer_city` varchar(255) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  `dealer_state` varchar(255) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  `dealer_postcode` varchar(255) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  `status` varchar(255) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT 'In Progress',
  `has_loan` tinyint(1) NOT NULL DEFAULT '0',
  `apply_loan_option` varchar(55) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT 'None',
  `loan_status` varchar(50) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT 'Pending',
  `loan_detail` longtext COLLATE utf8mb4_unicode_520_ci,
  `date_created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `date_modified` datetime NOT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`quote_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;



CREATE TABLE `user` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_name` varchar(50) NOT NULL,
  `user_email` varchar(50) NOT NULL,
  `user_password` varchar(50) NOT NULL,
  `user_role` varchar(50) NOT NULL,
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


CREATE TABLE `products` (
  `product_id` int(11) NOT NULL,
  `product_title` varchar(50) NOT NULL,
  `status` varchar(50) NOT NULL,
  `product_type` varchar(50) NOT NULL,
  `price` double NOT NULL DEFAULT '0',
  `custom_options` longtext COLLATE utf8mb4_unicode_520_ci,
  PRIMARY KEY (`product_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


CREATE TABLE `product_manufacturer` (
  `pm_id` int(11) NOT NULL AUTO_INCREMENT,
  `product_id` int(11),
  `manufacturer_id` int(11),
   PRIMARY KEY (`pm_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


CREATE TABLE `manufacturers`
(
  `manufacturer_id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `phone_number` varchar(50) NOT NULL,
  PRIMARY KEY (`manufacturer_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


CREATE TABLE `accessories` (
  `accessories_id` int(11) NOT NULL,
  `title` varchar(50) NOT NULL,
  `accessories_type` varchar(50) NOT NULL,
  `cost` double NOT NULL DEFAULT '0',
  `status` varchar(50) NOT NULL DEFAULT 'None',
  PRIMARY KEY (`accessories_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4;
