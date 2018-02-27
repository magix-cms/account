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
 * MAGIX CMS
 * @category   account
 * @package    plugins
 * @copyright  MAGIX CMS Copyright (c) 2008 - 2015 Gerits Aurelien,
 * http://www.magix-cms.com,  http://www.magix-cjquery.com
 * @license    Dual licensed under the MIT or GPL Version 3 licenses.
 * @version    2.0
 * Author: Salvatore Di Salvo
 * Date: 16-12-15
 * Time: 14:00
 * @name plugins_account_admin
 * Le plugin account
 */
class plugins_account_admin extends plugins_account_db {
	/**
	 * @var object
	 */
	protected $controller,
		$data,
		$template,
		$message,
		$plugins,
		$modelLanguage,
		$collectionLanguage,
		$header,
		$settings,
		$setting;

	/**
	 * Les variables globales
	 * @var integer $edit
	 * @var string $action
	 * @var string $tabs
	 */
	public $edit = 0,
		$action = '',
		$tabs = '';

	/**
	 * Les variables plugin
	 * @var array $account
	 * @var array $address
	 * @var array $config
	 * @var integer $id
	 */
	public
		$account = array(),
		$address = array(),
		$config = array(),
		$id = 0;


    /**
     * Modules
	 * @var $module
	 * @var $activeMods
	 * @var $cartpayModule
	 * @var $country
     */
    //protected $module, $activeMods, $cartpayModule;

	/**
	 * plugins_account_admin constructor.
	 */
    public function __construct(){
		$this->template = new backend_model_template();
		$this->plugins = new backend_controller_plugins();
		$this->message = new component_core_message($this->template);
		$this->modelLanguage = new backend_model_language($this->template);
		$this->collectionLanguage = new component_collections_language();
		$this->data = new backend_model_data($this);
		$this->settings = new backend_model_setting();
		$this->setting = $this->settings->getSetting();
		$this->header = new http_header();

		$formClean = new form_inputEscape();

		// --- GET
		if(http_request::isGet('controller')) {
			$this->controller = $formClean->simpleClean($_GET['controller']);
		}
		if (http_request::isGet('edit')) {
			$this->edit = (int)$formClean->numeric($_GET['edit']);
		}
		if (http_request::isGet('action')) {
			$this->action = $formClean->simpleClean($_GET['action']);
		} elseif (http_request::isPost('action')) {
			$this->action = $formClean->simpleClean($_POST['action']);
		}
		if (http_request::isGet('tabs')) {
			$this->tabs = $formClean->simpleClean($_GET['tabs']);
		}

		/*if (class_exists('plugins_profil_cartpay')) {
			$this->cartpayModule = new plugins_profil_cartpay();
		}
		if(class_exists('plugins_profil_module')) {
			$this->module = new plugins_profil_module();
		}*/

        // --- ADD or EDIT
		if (http_request::isPost('account')) {
			$this->account = (array)$formClean->arrayClean($_POST['account']);
		}
		elseif (http_request::isGet('account')) {
			$this->account = (array)$formClean->arrayClean($_GET['account']);
		}
		if (http_request::isPost('address')) {
			$this->address = (array)$formClean->arrayClean($_POST['address']);
		}
		if (http_request::isPost('acConfig')) {
			$this->config = (array)$formClean->arrayClean($_POST['acConfig']);
		}
		if (http_request::isPost('id')) {
			$this->id = (int)$formClean->simpleClean($_POST['id']);
		}
    }

	/**
	 * Method to override the name of the plugin in the admin menu
	 * @return string
	 */
	public function getExtensionName()
	{
		return $this->template->getConfigVars('account_plugin');
	}

	/**
	 * Assign data to the defined variable or return the data
	 * @param string $type
	 * @param string|int|null $id
	 * @param string $context
	 * @param boolean $assign
	 * @return mixed
	 */
	private function getItems($type, $id = null, $context = null, $assign = true) {
		return $this->data->getItems($type, $id, $context, $assign);
	}

	/**
	 * Insert data
	 * @param array $config
	 */
	private function add($config)
	{
		switch ($config['type']) {
			case 'account':
				parent::insert(
					array('type' => $config['type']),
					$config['data']
				);
				break;
		}
	}

	/**
	 * Update data
	 * @param array $config
	 */
	private function upd($config)
	{
		switch ($config['type']) {
			case 'accountActive':
			case 'account':
			case 'address':
			case 'accountConfig':
			case 'pwd':
			case 'config':
				parent::update(
					array('type' => $config['type']),
					$config['data']
				);
				break;
		}
	}

	/**
	 * Delete a record
	 * @param $config
	 */
	private function del($config)
	{
		switch ($config['type']) {
			case 'account':
				parent::delete(
					array('type' => $config['type']),
					$config['data']
				);
				$this->message->json_post_response(true,'delete',array('id' => $this->id));
				break;
		}
	}

    /**
     *
     */
    public function run(){
		/*if(isset($this->module)) {
			$this->activeMods = $this->module->load_module(true);
		}*/

		if($this->action) {
			switch ($this->action) {
				case 'add':
					if(is_array($this->account) && !empty($this->account)) {
						if($this->account['passwd'] === $this->account['repeat_passwd']) {
							$this->account['passcrypt_ac'] = password_hash($this->account['passwd'], PASSWORD_DEFAULT);
							$this->account['keyuniqid_ac'] = filter_rsa::uniqID();
							$this->account['active_ac'] = isset($this->account['active_ac']) ? 1 : 0;
							unset($this->account['passwd']);
							unset($this->account['repeat_passwd']);

							$this->add(array(
									'type' => 'account',
									'data' => $this->account
								)
							);
							$this->message->json_post_response(true,'add_redirect');
						}
					}
					else {
						$this->modelLanguage->getLanguage();

						$this->template->display('add.tpl');
					}
					break;
				case 'edit':
					$status = false;
					$notify = 'error';
					if(!empty($this->tabs)) {
						switch ($this->tabs) {
							case 'account':
								$this->account['id'] = $this->id;
								$this->address['id'] = $this->id;

								$this->upd(array(
									'type' => 'account',
									'data' => array_map(function($v) { return $v === '' ? null : $v; }, $this->account)
								));
								$this->upd(array(
									'type' => 'address',
									'data' => array_map(function($v) { return $v === '' ? null : $v; }, $this->address)
								));
								$status = true;
								$notify = 'update';
								break;
							case 'accountConfig':
								$upd = true;
								$account = $this->getItems('account',$this->account['id'],'one',false);
								if($this->account['email_ac'] !== $account['email_ac']) {
									$invalidmail = $this->getItems('searchmail',array('email_ac' => $this->account['email_ac']),'one',false);

									if($invalidmail) { $upd = false; }
								}

								if($upd) {
									$this->account['active_ac'] = isset($this->account['active_ac']) ? 1 : 0;

									$this->upd(array(
										'type' => 'accountConfig',
										'data' => $this->account
									));

									$status = true;
									$notify = 'update';
								}
								break;
							case 'pwd':
								if($this->account['new_passwd'] === $this->account['repeat_passwd']) {
									$this->upd(array(
										'type' => 'pwd',
										'data' => array(
											'passcrypt_ac' => password_hash($this->account['new_passwd'], PASSWORD_DEFAULT),
											'id' => $this->account['id']
										)
									));

									$status = true;
									$notify = 'update';
								}

								break;
							case 'config':
								$config = $this->getItems('config',$this->edit,'one',false);
								$this->config['id'] = $config['id_config'];
								$this->config['links'] = isset($this->config['links']) ? 1 : 0;
								$this->config['cartpay'] = isset($this->config['cartpay']) ? 1 : 0;
								//$this->config['address'] = isset($this->config['address']) ? 1 : 0;
								$this->config['google_recaptcha'] = isset($this->config['google_recaptcha']) ? 1 : 0;
								$this->config['recaptchaApiKey'] = $this->config['recaptchaApiKey'] === '' ? null : $this->config['recaptchaApiKey'];
								$this->config['recaptchaSecret'] = $this->config['recaptchaSecret'] === '' ? null : $this->config['recaptchaSecret'];

								$this->upd(array(
									'type' => 'config',
									'data' => $this->config
								));

								$status = true;
								$notify = 'update';
								break;
						}
						$this->message->json_post_response($status,$notify);
					}
					else {
						$this->modelLanguage->getLanguage();

						$this->getItems('account',$this->edit,'one');

						$country = new component_collections_country();
						$this->template->assign('countries',$country->getCountries());

						$this->template->display('edit.tpl');
					}
					break;
				case 'delete':
					if(isset($this->id) && !empty($this->id)) {
						$this->del(
							array(
								'type' => 'account',
								'data' => array(
									'id' => $this->id
								)
							)
						);
					}
					break;
				case 'active-selected':
				case 'unactive-selected':
					if(!empty($this->account)) {
						$this->upd(
							array(
								'type' => 'accountActive',
								'data' => array(
									'active_ac' => ($this->action === 'active-selected'?1:0),
									'id' => implode($this->account, ',')
								)
							)
						);
					}

					$this->message->getNotify('update',array('method'=>'fetch','assignFetch'=>'message'));

					$this->modelLanguage->getLanguage();
					$langs = $this->modelLanguage->setLanguage();
					$opts = array();
					foreach ($langs as $id => $iso) {
						$opts[] = array(
							'v' => $id,
							'name' => $iso
						);
					}
					$this->getItems('accounts');
					$assign = array(
						'id_account',
						'iso_lang' => array(
							'title' => 'lang',
							'class' => 'fixed-td-md',
							'input' => array(
								'type' => 'select',
								'var' => false,
								'values' => $opts
							)
						),
						'email_ac',
						'firstname_ac',
						'lastname_ac',
						'active_ac',
						'date_create'
					);
					$this->data->getScheme(array('mc_account','mc_lang'),array('id_account','iso_lang','email_ac','firstname_ac','lastname_ac','active_ac','date_create'),$assign);
					$this->template->display('index.tpl');
					break;
			}
		}
		else {
			$this->modelLanguage->getLanguage();
			$langs = $this->modelLanguage->setLanguage();
			$opts = array();
			foreach ($langs as $id => $iso) {
				$opts[] = array(
					'v' => $id,
					'name' => $iso
				);
			}
			$this->getItems('accounts');
			$this->getItems('config',$this->edit,'one');
			$assign = array(
				'id_account',
				'iso_lang' => array(
					'title' => 'lang',
					'class' => 'fixed-td-md',
					'input' => array(
						'type' => 'select',
						'var' => false,
						'values' => $opts
					)
				),
				'email_ac',
				'firstname_ac',
				'lastname_ac',
				'active_ac',
				'date_create'
			);
			$this->data->getScheme(array('mc_account','mc_lang'),array('id_account','iso_lang','email_ac','firstname_ac','lastname_ac','active_ac','date_create'),$assign);
			$this->template->display('index.tpl');
		}
    }
}