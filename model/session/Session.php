<?php
require_once('db.php');
/*
 # -- BEGIN LICENSE BLOCK ----------------------------------
 #
 # This file is part of MAGIX CMS.
 # MAGIX CMS, The content management system optimized for users
 # Copyright (C) 2008 - 2013 magix-cms.com <support@magix-cms.com>
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
/**
 * @category Model
 * @package Session
 * @copyright MAGIX CMS Copyright (c) 2008 - 2018 Gerits Aurelien, http://www.magix-cms.com,  http://www.magix-cjquery.com
 * @license Dual licensed under the MIT or GPL Version 3 licenses.
 * @version 1.0
 * @author: Salvatore Di Salvo
 * @name Session
 */
class Session extends SessionData {
    /**
     * @var http_session $httpSession
     */
	private http_session $httpSession;

    /**
     * @param bool $ssl
     */
	public function __construct(bool $ssl) {
		$this->httpSession = new http_session($ssl);
	}

	/**
	 * clean old register 2 days
	 * @param array $data
	 */
	private function cleanOldSession(array $data) {
		//On supprime les enregistrements de plus de deux jours
		$date = new DateTime('NOW');
		$date->modify('-1 day');
		$limit = $date->format('Y-m-d H:i:s');
		parent::delete(['type'=>'lastSessions'],['limit'=>$limit,'id_account'=>$data['id_account']]);
	}

	/**
	 * Open session
	 * @param array $data
	 * @return true
	 */
	public function openSession(array $data): bool {
		$detect = new Mobile_Detect();
		$device = 'desktop';
		$mobile = $detect->isMobile();
		$tablet = $detect->isTablet();

		if($mobile || $tablet) {
			$device = $tablet ? 'tablet' : 'mobile';

			if( $detect->isiOS() ){
				if($mobile) {
					if($detect->is('iphone')) {
						$device = ' iphone';
					}
				}
				else {
					if($detect->is('ipad')) {
						$device = ' ipad';
					}
				}
			}
			elseif( $detect->isAndroidOS() ){
				$device = ' Android';
			}
		}
		$ip_session = $this->httpSession->getIp();
		$browser = $this->httpSession->getBrowser();

		parent::delete(['type' => 'currentSession'],[
			'ip_session' => $ip_session,
			'browser_session' => $browser,
			'device_session' => $device,
			'id_account' => $data['id_account']
		]);
		$this->cleanOldSession(['id_account'=>$data['id_account']]);
		//On ajoute un nouvel identifiant de session dans la table
		parent::insert(
			['type'=>'session'],
			[
                'id_session'  	  => $data['id_session'],
                'id_account'      => $data['id_account'],
                'expires' 		  => $data['expires'] ?: null,
				'ip_session' 	 => $ip_session,
				'browser_session' => $browser,
				'device_session' => $device,
                'keyuniqid_ac' 	  => $data['keyuniqid_ac']
            ]
		);
		return true;
	}

	/**
	 * close session
	 * @return void
	 */
	public function closeSession() {
		parent::delete(['type'=>'session'],['id_session'=>session_id()]);
	}

    /**
     * @return bool
     */
	public function compareSessionId(): bool {
		return !empty(parent::fetchData(['context' => 'one', 'type' => 'session'],['id_session' => session_id()]));
	}
}