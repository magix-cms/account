<?php
/*
 # -- BEGIN LICENSE BLOCK ----------------------------------
 #
 # This file is part of MAGIX CMS.
 # MAGIX CMS, The content management system optimized for users
 # Copyright (C) 2008 - 2018 magix-cms.com support[at]magix-cms[point]com
 #
 # OFFICIAL TEAM :
 #
 #   * Gerits Aurelien (Author - Developer) <aurelien@magix-cms.com> <contact@aurelien-gerits.be>
 #
 # Redistributions of files must retain the above copyright notice.
 # This program is free software: you can redistribute it and/or modify
 # it under the terms of the GNU General Public License as published by
 # the Free Software Foundation, either version 3 of the License, or
 # (at your option) any later version.
 #
 # This program is distributed in the hope that it will be useful,
 # but WITHOUT ANY WARRANTY; without even the implied warranty of
 # MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 # GNU General Public License for more details.
 #
 # You should have received a copy of the GNU General Public License
 # along with this program.  If not, see <http://www.gnu.org/licenses/>.
 #
 # -- END LICENSE BLOCK -----------------------------------
 #
 # DISCLAIMER
 #
 # Do not edit or add to this file if you wish to upgrade MAGIX CMS to newer
 # versions in the future. If you wish to customize MAGIX CMS for your
 # needs please refer to http://www.magix-cms.com for more information.
 */
class plugins_account_db {
	/**
	 * @param array $config
	 * @param array $params
	 * @return array|bool
	 */
	public function fetchData(array $config, array $params = []) {
		if ($config['context'] === 'all') {
			switch ($config['type']) {
				case 'accounts':
					$limit = '';
					if ($config['offset']) {
						$limit = ' LIMIT 0, ' . $config['offset'];
						if (isset($config['page']) && $config['page'] > 1) {
							$limit = ' LIMIT ' . (($config['page'] - 1) * $config['offset']) . ', ' . $config['offset'];
						}
					}
					$cond = '';

					if(isset($config['search'])) {
						if(is_array($config['search'])) {
							$nbc = 0;
							foreach ($config['search'] as $key => $q) {
								if($q !== '') {
									$cond .= !$nbc ? ' WHERE ' : 'AND ';
									switch ($key) {
										case 'id_account':
										case 'active_ac':
											$cond .= 'ma.'.$key.' = '.$q.' ';
											break;
										case 'iso_lang':
											$cond .= 'ml.id_lang = '.$q.' ';
											break;
										case 'email_ac':
										case 'firstname_ac':
										case 'lastname_ac':
											$cond .= "ma.".$key." LIKE '%".$q."%' ";
											break;
										case 'date_register':
											$dateFormat = new component_format_date();
											$q = $dateFormat->date_to_db_format($q);
											$cond .= "ma.".$key." LIKE '%".$q."%' ";
											break;
									}
									$nbc++;
								}
							}
						}
					}
					if(isset($params['where'])) {
						unset($params['where']);
					}

					$select = ['ma.id_account', 'ml.iso_lang', 'ma.email_ac', 'ma.firstname_ac', 'ma.lastname_ac', 'ma.active_ac', 'ma.date_create'];

					if(isset($params['select'])) {
						foreach ($params['select'] as $extendSelect) {
							$select = array_merge($select, $extendSelect);
						}
						unset($params['select']);
					}

					$joins = '';
					if(isset($params['join']) && is_array($params['join'])) {
						foreach ($params['join'] as $join) {
							$joins .= ' '.$join['type'].' '.$join['table'].' '.$join['as'].' ON ('.$join['on']['table'].'.'.$join['on']['key'].' = '.$join['as'].'.'.$join['on']['key'].') ';
						}
						unset($params['join']);
					}

					$query = 'SELECT '.implode(',', $select).'
							FROM `mc_account` ma
							JOIN `mc_lang` ml USING(`id_lang`)'.$joins.$cond. ' GROUP By id_account ORDER BY id_account DESC'. $limit;
					break;
				case 'search_account':
					$cond = '';
					$query = "SELECT 
       							ma.id_account
							FROM mc_account ma
							WHERE $cond AND
							ma.pseudo_ac LIKE :resume_needle
							ORDER BY ma.pseudo_ac DESC";
					unset($params['needle']);
					break;
				case 'search_ft_account':
					$cond = '';
					$query = "SELECT 
       							ma.id_account
							FROM mc_account ma
							WHERE $cond AND
							AND (
								ma.pseudo_ac LIKE :name_needle
							) ORDER BY ma.pseudo_ac DESC";
					break;
				default:
					return false;
			}

			try {
				return component_routing_db::layer()->fetchAll($query, $params);
			}
			catch (Exception $e) {
				if(!isset($this->logger)) $this->logger = new debug_logger(MP_LOG_DIR);
				$this->logger->log('statement','db',$e->getMessage(),$this->logger::LOG_MONTH);
			}
		}
		elseif ($config['context'] === 'one') {
			switch ($config['type']) {
                case 'account':
                    $query = 'SELECT 
								a.*,
       							asos.*
							FROM `mc_account` as a
							JOIN `mc_account_social` as asos USING(`id_account`)
							JOIN `mc_lang` as l USING(`id_lang`)
							WHERE `id_account` = :id';
                    break;
                case 'billing_address':
                    $query = "SELECT 
       							*
							FROM `mc_account_address`
							WHERE `id_account` = :id AND `type_address` = 'billing'";
                    break;
                case 'delivery_address':
					$query = "SELECT 
       							*
							FROM `mc_account_address`
							WHERE `id_account` = :id AND `type_address` = 'delivery'";
                    break;
				case 'config':
					$query = 'SELECT * FROM `mc_account_config` ORDER BY id_config DESC LIMIt 0,1';
					break;
				case 'accountEmail':
					$query = 'SELECT *
							FROM `mc_account` as a
							JOIN `mc_account_address` as aa USING(`id_account`)
							JOIN `mc_account_social` as asos USING(`id_account`)
							JOIN `mc_lang` as l USING(`id_lang`)
							WHERE `email_ac` = :email_ac';
					break;
                case 'accountFromKey':
                    $query = 'SELECT id_account, email_ac, active_ac FROM `mc_account` WHERE `keyuniqid_ac` = :keyuniqid';
                    break;
				case 'accountHashKey':
					$query = 'SELECT *
							FROM `mc_account` as a
							JOIN `mc_account_address` as aa USING(`id_account`)
							JOIN `mc_account_social` as asos USING(`id_account`)
							JOIN `mc_lang` as l USING(`id_lang`)
							WHERE `keyuniqid_ac` = :keyuniqid
							AND `change_pwd` = :token';
					break;
                case 'accountSession':
                    $query = 'SELECT *
							FROM mc_account_session
							WHERE `keyuniqid_ac` = :keyuniqid_ac';
                    break;
				case 'auth':
					$query = 'SELECT *
							FROM `mc_account` as a
							WHERE `email_ac` = :email_ac
							AND `passcrypt_ac` = :passwd_ac AND `active_ac` = 1';
					break;
				case 'idFromIso':
					$query = 'SELECT `id_lang` FROM `mc_lang` WHERE `iso_lang` = :iso';
					break;
				case 'pwdcryptEmail':
					$query = 'SELECT passcrypt_ac
							FROM `mc_account` as a
							WHERE `email_ac` = :email_ac';
					break;
                case 'searchmail':
                    $query = 'SELECT email_ac FROM `mc_account` WHERE `email_ac` = :email_ac';
                    break;
				default:
					return false;
			}

			try {
				return component_routing_db::layer()->fetch($query, $params);
			}
			catch (Exception $e) {
				if(!isset($this->logger)) $this->logger = new debug_logger(MP_LOG_DIR);
				$this->logger->log('statement','db',$e->getMessage(),$this->logger::LOG_MONTH);
			}
		}
		return false;
	}

	/**
	 * @param array $config
	 * @param array $params
	 * @return bool|string
	 */
	public function insert(array $config, array $params = []) {
		switch ($config['type']) {
            case 'account':
				$datetime = new component_format_date();
				$params['birthdate_ac'] = empty($params['birthdate_ac']) ? NULL : $datetime->date_to_db_format($params['birthdate_ac']);
                $queries = [
                    ['request' => 'INSERT INTO `mc_account` (
                          `id_lang`, 
                          `email_ac`, 
                          `passcrypt_ac`, 
                          `keyuniqid_ac`, 
                          `firstname_ac`, 
                          `lastname_ac`, 
                          `birthdate_ac`, 
                          `phone_ac`, 
                          `company_ac`, 
                          `vat_ac`, 
                          `active_ac`, 
                          `date_create`
                          ) 
					VALUES (
							:id_lang, 
					        :email_ac, 
					        :passcrypt_ac, 
					        :keyuniqid_ac, 
					        :firstname_ac, 
					        :lastname_ac, 
					        :birthdate_ac, 
					        :phone_ac, 
					        :company_ac, 
					        :vat_ac,
					        :active_ac, 
					        NOW()
					        )', 'params' => $params],
                    ['request' => 'SELECT @account_id := LAST_INSERT_ID() as id_account', 'params' => [], 'fetch' => true],
                    ['request' => "INSERT INTO `mc_account_address` (`id_account`,`type_address`) VALUE (@account_id,'billing')", 'params' => []],
                    ['request' => "INSERT INTO `mc_account_address` (`id_account`,`type_address`) VALUE (@account_id,'delivery')", 'params' => []],
                    ['request' => 'INSERT INTO `mc_account_social` (`id_account`) VALUE (@account_id)', 'params' => []]
                ];

                try {
                    $results = component_routing_db::layer()->transaction($queries);
                    return $results[1];
                }
                catch (Exception $e) {
                    return 'Exception reçue : '.$e->getMessage();
                }
			default:
				return false;
		}

		try {
			component_routing_db::layer()->insert($query,$params);
			return true;
		}
		catch (Exception $e) {
			return 'Exception reçue : '.$e->getMessage();
		}
	}

	/**
	 * @param array $config
	 * @param array $params
	 * @return bool|string
	 */
	public function update(array $config, array $params = []) {
		switch ($config['type']) {
			case 'account':
				$datetime = new component_format_date();
				$params['pseudo_ac'] = $params['pseudo_ac']?: NULL;
				$params['birthdate_ac'] = empty($params['birthdate_ac']) ? NULL : $datetime->date_to_db_format($params['birthdate_ac']);
				$query = 'UPDATE `mc_account`
						SET 
							`pseudo_ac` = :pseudo_ac,
							`lastname_ac` = :lastname_ac,
							`firstname_ac` = :firstname_ac,
							`birthdate_ac` = :birthdate_ac,
							`phone_ac` = :phone_ac,
							`company_ac` = :company_ac,
							`vat_ac` = :vat_ac
						WHERE `id_account` = :id';
				break;
			case 'sameAddress':
				$query = 'UPDATE `mc_account`
						SET `same_address` = :same_address
						WHERE `id_account` = :id';
				break;
            case 'accountActive':
                $query = 'UPDATE `mc_account` SET `active_ac` = :active_ac WHERE `id_account` IN ('.$params['id'].')';

                try {
                    component_routing_db::layer()->update($query,array('active_ac' => $params['active_ac']));
                    return true;
                }
                catch (Exception $e) {
                    return 'Exception reçue : '.$e->getMessage();
                }
			case 'accountAdminConfig':
				$query = 'UPDATE `mc_account`
						SET 
							id_lang = :id_lang,
							referral_ac = :referral_ac,
							active_ac = :active_ac,
							email_ac = :email_ac
						WHERE id_account = :id';
				break;
            case 'accountConfig':
                $query = 'UPDATE `mc_account`
						SET 
							`id_lang` = :id_lang,
							`active_ac` = :active_ac,
							`email_ac` = :email_ac
						WHERE `id_account` = :id';
                break;
            case 'activate':
                $query = 'UPDATE `mc_account` SET `active_ac` = 1 WHERE `id_account` = :id';
                break;
			case 'address':
				$query = 'UPDATE `mc_account_address`
						SET 
							`street_address` = :street_address,
							`postcode_address` = :postcode_address,
							`city_address` = :city_address,
							`country_address` = :country_address
						WHERE `id_account` = :id AND `type_address` = :type_address';
				break;
			case 'alert':
				$query = 'UPDATE `mc_account`
						SET `weekly_alert` = :weekly_alert,
							`monthly_alert` = :monthly_alert,
							`overbid_alert` = :overbid_alert,
							`endofsale_alert` = :endofsale_alert
						WHERE `id_account` = :id';
				break;
			case 'socials':
				$query = 'UPDATE `mc_account_social`
						SET 
							`website` = :website,
							`facebook` = :facebook,
							`twitter` = :twitter,
							`instagram` = :instagram,
							`tiktok` = :tiktok,
							`youtube` = :youtube,
							`pinterest` = :pinterest,
							`tumblr` = :tumblr,
							`linkedin` = :linkedin,
							`viadeo` = :viadeo,
							`github` = :github,
							`soundcloud` = :soundcloud
						WHERE `id_account` = :id_account';
				break;
			case 'config':
				$query = 'UPDATE `mc_account_config`
						SET 
							`pseudo` = :pseudo,
							`birthdate` = :birthdate,
							`links` = :links,
							`picture` = :picture,
							`public` = :public
						WHERE id_config = :id';
				break;
			case 'img':
				$query = 'UPDATE mc_account
                		SET img_ac = :img_ac
						WHERE id_account = :id_account';
				break;
			case 'newPwd':
				$query = 'UPDATE `mc_account`
						SET `change_pwd` = NULL,
							`passcrypt_ac` = :newpwd
						WHERE `id_account` = :id';
				break;
			case 'pwd':
				$query = 'UPDATE `mc_account`
						SET 
							`passcrypt_ac` = :passcrypt_ac
						WHERE `id_account` = :id';
				break;
			case 'pwdTicket':
				$query = 'UPDATE `mc_account`
						SET `change_pwd` = :token
						WHERE `id_account` = :id';
				break;
			default:
				return false;
		}

		try {
			component_routing_db::layer()->update($query,$params);
			return true;
		}
		catch (Exception $e) {
			return 'Exception reçue : '.$e->getMessage();
		}
	}

	/**
	 * @param array $config
	 * @param array $params
	 * @return bool|string
	 */
	public function delete(array $config, array $params = []) {
		switch ($config['type']) {
			case 'account':
				$query = 'DELETE FROM `mc_account` 
						WHERE `id_account` IN ('.$params['id'].')';
				$params = [];
				break;
			default:
				return false;
		}

		try {
			component_routing_db::layer()->delete($query,$params);
			return true;
		}
		catch (Exception $e) {
			return 'Exception reçue : '.$e->getMessage();
		}
	}
}