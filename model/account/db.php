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
class AccountData {
	/**
	 * @param array $config
	 * @param null|array $params
	 * @return mixed|null
	 * @throws Exception
	 */
	public function fetchData(array $config, array $params = []) {
		$query = '';

		if ($config['context'] === 'all') {
			switch ($config['type']) {
				case 'accountSub':
					$query = "SELECT mas.id_an,
								   MAX(mas.date_end_an) date_end_an,
       								ms.type_sub
							FROM mc_account_subscription mas
							LEFT JOIN mc_subscription ms on ms.id_sub = mas.id_sub
							WHERE id_account = :id 
							  AND NOW() <= mas.date_end_an GROUP BY type_sub
							ORDER BY type_sub DESC";
					break;
			}

			return $query ? component_routing_db::layer()->fetchAll($query, $params) : null;
		}
		elseif ($config['context'] === 'one') {
			switch ($config['type']) {
				case 'account':
					$query = 'SELECT 
								ma.*,
       							maa.*
							FROM `mc_account` as ma
							JOIN `mc_account_address` as maa USING(`id_account`)
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
			}

			return $query ? component_routing_db::layer()->fetch($query, $params) : null;
		}
	}

	/**
	 * @param array $config
	 * @param array $params
	 * @return bool|string
	 */
	public function insert(array $config, array $params = []) {
		$sql = '';

		switch ($config['type']) {
			case 'data':
				$sql = '';
				break;
		}

		if($sql === '') return 'Unknown request asked';

		try {
			component_routing_db::layer()->insert($sql,$params);
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
		$sql = '';

		switch ($config['type']) {
            /*case 'accountSms':
                $sql = 'UPDATE mc_account
						SET sms_ac = :sms_ac
						WHERE id_account = :id_account';
                break;*/
			case 'alert':
				$sql = 'UPDATE `mc_account`
						SET `weekly_alert` = :weekly_alert,
							`monthly_alert` = :monthly_alert,
							`overbid_alert` = :overbid_alert,
							`endofsale_alert` = :endofsale_alert
						WHERE `id_account` = :id';
				break;
		}

		if($sql === '') return 'Unknown request asked';

		try {
			component_routing_db::layer()->update($sql,$params);
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
		$sql = '';

		switch ($config['type']) {
			case 'data':
				$sql = '';
				break;
		}

		if($sql === '') return 'Unknown request asked';

		try {
			component_routing_db::layer()->delete($sql,$params);
			return true;
		}
		catch (Exception $e) {
			return 'Exception reçue : '.$e->getMessage();
		}
	}
}