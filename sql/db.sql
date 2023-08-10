CREATE TABLE IF NOT EXISTS `mc_account` (
  `id_account` INT(7) UNSIGNED NOT NULL AUTO_INCREMENT,
  `id_lang` SMALLINT(3) UNSIGNED NOT NULL,
  `email_ac` VARCHAR(150) NOT NULL UNIQUE,
  `passcrypt_ac` VARCHAR(120) NOT NULL,
  `keyuniqid_ac` VARCHAR(50) NOT NULL UNIQUE,
  `pseudo_ac` VARCHAR(120) DEFAULT NULL,
  `img_ac` VARCHAR(80) DEFAULT NULL,
  `firstname_ac` VARCHAR(40) DEFAULT NULL,
  `lastname_ac` VARCHAR(40) DEFAULT NULL,
  `birthdate_ac` TIMESTAMP NULL DEFAULT NULL,
  `phone_ac` VARCHAR(45) DEFAULT NULL,
  `company_ac` VARCHAR(50) DEFAULT NULL,
  `vat_ac` VARCHAR(50) DEFAULT NULL,
  `referral_ac` VARCHAR(50) DEFAULT NULL,
  `newsletter_ac` SMALLINT(1) UNSIGNED NOT NULL DEFAULT '0',
  `active_ac` SMALLINT(1) UNSIGNED NOT NULL DEFAULT '0',
  `change_pwd` VARCHAR(32) DEFAULT NULL,
  `date_create` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_account`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `mc_account_address` (
  `id_address` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `id_account` INT(7) UNSIGNED NOT NULL,
  `type_address` ENUM('billing','delivery') NOT NULL DEFAULT 'billing',
  `street_address` VARCHAR(150) DEFAULT NULL,
  `postcode_address` VARCHAR(10) DEFAULT NULL,
  `city_address` VARCHAR(60) DEFAULT NULL,
  `country_address` VARCHAR(40) DEFAULT NULL,
  PRIMARY KEY (`id_address`),
  KEY (`id_account`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

ALTER TABLE `mc_account_address`
  ADD CONSTRAINT `mc_account_address_ibfk_2` FOREIGN KEY (`id_account`) REFERENCES `mc_account` (`id_account`) ON DELETE CASCADE ON UPDATE CASCADE;

CREATE TABLE IF NOT EXISTS `mc_account_social` (
  `id_social` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `id_account` INT(11) UNSIGNED NOT NULL,
  `website` varchar(200) DEFAULT NULL,
  `facebook` varchar(200) DEFAULT NULL,
  `twitter` varchar(200) DEFAULT NULL,
  `instagram` varchar(200) DEFAULT NULL,
  `tiktok` varchar(200) DEFAULT NULL,
  `youtube` varchar(200) DEFAULT NULL,
  `pinterest` varchar(200) DEFAULT NULL,
  `tumblr` varchar(200) DEFAULT NULL,
  `linkedin` varchar(200) DEFAULT NULL,
  `viadeo` varchar(200) DEFAULT NULL,
  `github` varchar(200) DEFAULT NULL,
  `soundcloud` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`id_social`),
  KEY (`id_account`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

ALTER TABLE `mc_account_social`
  ADD CONSTRAINT `mc_account_social_ibfk_2` FOREIGN KEY (`id_account`) REFERENCES `mc_account` (`id_account`) ON DELETE CASCADE ON UPDATE CASCADE;

CREATE TABLE IF NOT EXISTS `mc_account_session` (
  `id_session` VARCHAR(150) NOT NULL,
  `id_account` INT(7) UNSIGNED NOT NULL,
  `keyuniqid_ac` VARCHAR(50) NOT NULL,
  `ip_session` VARCHAR(25) NOT NULL,
  `browser_session` VARCHAR(50) NOT NULL,
  `device_session` VARCHAR(50) DEFAULT 'desktop',
  `last_modified_session` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `expires` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id_session`),
  KEY (`id_account`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

ALTER TABLE `mc_account_session`
  ADD CONSTRAINT `mc_account_session_ibfk_2` FOREIGN KEY (`id_account`) REFERENCES `mc_account` (`id_account`) ON DELETE CASCADE ON UPDATE CASCADE;

CREATE TABLE IF NOT EXISTS `mc_account_config` (
  `id_config` SMALLINT(3) UNSIGNED NOT NULL AUTO_INCREMENT,
  `pseudo` SMALLINT(1) UNSIGNED NOT NULL DEFAULT '0',
  `links` SMALLINT(1) UNSIGNED NOT NULL DEFAULT '0',
  `picture` SMALLINT(1) UNSIGNED NOT NULL DEFAULT '0',
  `public` SMALLINT(1) UNSIGNED NOT NULL DEFAULT '0',
  PRIMARY KEY (`id_config`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
TRUNCATE `mc_account_config`;
INSERT INTO `mc_account_config` (`pseudo`,`links`,`picture`,`public`) VALUES (0,0,0,0);

CREATE TABLE IF NOT EXISTS `mc_account_purchase` (
 `id_ap` int UNSIGNED NOT NULL AUTO_INCREMENT,
 `id_account` int UNSIGNED NOT NULL,
 `type_ap` VARCHAR(50) NOT NULL,
 `price_ap` float UNSIGNED NOT NULL,
 `currency_ap` VARCHAR(10) NOT NULL DEFAULT 'credit',
 `date_register` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
 PRIMARY KEY (`id_ap`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

ALTER TABLE `mc_account_purchase`
    ADD CONSTRAINT `mc_account_purchase_ibfk_1` FOREIGN KEY (`id_account`) REFERENCES `mc_account` (`id_account`) ON DELETE CASCADE ON UPDATE CASCADE;

INSERT INTO `mc_config_img` (`id_config_img`, `module_img`, `attribute_img`, `width_img`, `height_img`, `type_img`, `prefix_img`, `resize_img`) VALUES
(null, 'plugins', 'account', '100', '100', 'small', 's', 'adaptive'),
(null, 'plugins', 'account', '500', '500', 'medium', 'm', 'adaptive'),
(null, 'plugins', 'account', '1200', '1200', 'large', 'l', 'basic');