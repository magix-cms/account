CREATE TABLE IF NOT EXISTS `mc_account` (
  `id_account` INT(7) UNSIGNED NOT NULL AUTO_INCREMENT,
  `id_lang` SMALLINT(3) UNSIGNED NOT NULL,
  `email_ac` VARCHAR(150) NOT NULL UNIQUE,
  `passcrypt_ac` VARCHAR(120) NOT NULL,
  `keyuniqid_ac` VARCHAR(50) NOT NULL UNIQUE,
  `lastname_ac` VARCHAR(40) DEFAULT NULL,
  `firstname_ac` VARCHAR(40) DEFAULT NULL,
  `phone_ac` VARCHAR(45) DEFAULT NULL,
  `company_ac` VARCHAR(50) DEFAULT NULL,
  `vat_ac` VARCHAR(50) DEFAULT NULL,
  `website` VARCHAR(150) DEFAULT NULL,
  `facebook` VARCHAR(200) DEFAULT NULL,
  `instagram` VARCHAR(200) DEFAULT NULL,
  `pinterest` VARCHAR(200) DEFAULT NULL,
  `twitter` VARCHAR(200) DEFAULT NULL,
  `google` VARCHAR(200) DEFAULT NULL,
  `linkedin` VARCHAR(200) DEFAULT NULL,
  `viadeo` VARCHAR(200) DEFAULT NULL,
  `github` VARCHAR(200) DEFAULT NULL,
  `soundcloud` VARCHAR(200) DEFAULT NULL,
  `newsletter_ac` SMALLINT(1) UNSIGNED NOT NULL DEFAULT '0',
  `active_ac` SMALLINT(1) UNSIGNED NOT NULL DEFAULT '0',
  `date_create` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_account`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `mc_account_address` (
  `id_address` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `id_account` INT(7) UNSIGNED NOT NULL,
  `street_address` VARCHAR(150) DEFAULT NULL,
  `postcode_address` VARCHAR(10) DEFAULT NULL,
  `city_address` VARCHAR(60) DEFAULT NULL,
  `country_address` VARCHAR(40) DEFAULT NULL,
  PRIMARY KEY (`id_address`),
  KEY (`id_account`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

ALTER TABLE `mc_account_address`
  ADD CONSTRAINT `mc_account_address_ibfk_2` FOREIGN KEY (`id_account`) REFERENCES `mc_account` (`id_account`) ON DELETE CASCADE ON UPDATE CASCADE;

CREATE TABLE IF NOT EXISTS `mc_account_session` (
  `id_session` VARCHAR(150) NOT NULL,
  `id_account` INT(7) UNSIGNED NOT NULL,
  `keyuniqid_ac` VARCHAR(50) NOT NULL,
  `ip_session` VARCHAR(25) NOT NULL,
  `browser_session` VARCHAR(50) NOT NULL,
  `last_modified_session` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_session`),
  KEY (`id_account`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE `mc_account_session`
  ADD CONSTRAINT `mc_account_session_ibfk_2` FOREIGN KEY (`id_account`) REFERENCES `mc_account` (`id_account`) ON DELETE CASCADE ON UPDATE CASCADE;

CREATE TABLE IF NOT EXISTS `mc_account_config` (
  `id_config` SMALLINT(3) UNSIGNED NOT NULL AUTO_INCREMENT,
  `google_recaptcha` smallint(1) UNSIGNED NOT NULL DEFAULT '0',
  `recaptchaApiKey` VARCHAR(125) DEFAULT NULL,
  `recaptchaSecret` VARCHAR(125) DEFAULT NULL,
  `links` SMALLINT(1) UNSIGNED NOT NULL DEFAULT '0',
  `cartpay` SMALLINT(1) UNSIGNED NOT NULL DEFAULT '0',
  PRIMARY KEY (`id_config`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

TRUNCATE `mc_account_config`;

INSERT INTO `mc_account_config` (`google_recaptcha`, `recaptchaApiKey`, `recaptchaSecret`, `links`, `cartpay`) VALUES
(0, NULL, NULL, 0, 0);

CREATE TABLE IF NOT EXISTS `mc_account_module` (
  `id_module` SMALLINT(2) UNSIGNED NOT NULL AUTO_INCREMENT,
  `module_name` VARCHAR(150) NOT NULL,
  `active` SMALLINT(1) UNSIGNED NOT NULL DEFAULT '1',
  PRIMARY KEY (`id_module`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

INSERT INTO `mc_admin_access` (`id_role`, `id_module`, `view`, `append`, `edit`, `del`, `action`)
  SELECT 1, m.id_module, 1, 1, 1, 1, 1 FROM mc_module AS m WHERE name = 'account';