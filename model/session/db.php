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
class SessionData {
	/**
	 * @param array $config
	 * @param null|array $params
	 * @return mixed|null
	 * @throws Exception
	 */
	public function fetchData(array $config, array $params = []) {
		$sql = '';

		if ($config['context'] === 'all') {
			switch ($config['type']) {}

			return $sql ? component_routing_db::layer()->fetchAll($sql, $params) : null;
		}
		elseif ($config['context'] === 'one') {
			switch ($config['type']) {
				case 'session':
					$sql = 'SELECT *
							FROM mc_account_session
							WHERE id_session = :id_session';
					break;
			}

			return $sql ? component_routing_db::layer()->fetch($sql, $params) : null;
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
			case 'session':
				$sql = 'INSERT INTO `mc_account_session` (`id_session`,`id_account`,`keyuniqid_ac`,`ip_session`,`browser_session`,`device_session`,`expires`)
						VALUES (:id_session,:id_account,:keyuniqid_ac,:ip_session,:browser_session,:device_session,:expires)';
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
	public function delete(array $config, array $params = []) {
		$sql = '';

		switch ($config['type']) {
			case 'session':
				$sql = 'DELETE FROM `mc_account_session`
						WHERE `id_session` = :id_session';
				break;
			case 'lastSessions':
				$sql = "DELETE FROM `mc_account_session`
						WHERE TO_DAYS(DATE_FORMAT(NOW(), '%Y%m%d')) - TO_DAYS(DATE_FORMAT(last_modified_session, '%Y%m%d')) > :limit AND id_account = :id_account";
				break;
			case 'currentSession':
				$sql = 'DELETE FROM `mc_account_session`
						WHERE `id_account` = :id_account 
						  AND `ip_session` = :ip_session 
						  AND `device_session` = :device_session 
						  AND `browser_session` = :browser_session';
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