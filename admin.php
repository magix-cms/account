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
	 * @var array $config
	 * @var integer $id
	 */
	public
		$account = array(),
		$config = array(),
		$id = 0;


    /**
     * Modules
	 * @var $module
	 * @var $activeMods
	 * @var $cartpayModule
	 * @var $country
     */
    //protected $module, $activeMods, $cartpayModule, $country;

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
			$this->edit = $formClean->numeric($_GET['edit']);
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
		}
        //$this->translation = new backend_controller_template();
        $this->country = new backend_controller_country();

        //GET
        if(http_request::isGet('page')) {
            // si numéric
            if(is_numeric($_GET['page'])){
                $this->getpage = intval($_GET['page']);
            }else{
                // Sinon retourne la première page
                $this->getpage = 1;
            }
        }else {
            $this->getpage = 1;
        }*/

        // --- ADD or EDIT
		if (http_request::isPost('account')) {
			$this->account = $formClean->arrayClean($_POST['account']);
		}
		if (http_request::isPost('config')) {
			$this->config = $formClean->arrayClean($_POST['config']);
		}
		if (http_request::isPost('id')) {
			$this->id = $formClean->simpleClean($_POST['id']);
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
			case 'adv':
			case 'advContent':
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
			case 'adv':
				parent::delete(
					array('type' => $config['type']),
					$config['data']
				);
				$this->header->set_json_headers();
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
							$this->account['keyuniqid_ac'] = filter_rsa::randUI();
							$this->account['active_ac'] = isset($this->account['active_ac']) ? 1 : 0;
							unset($this->account['passwd']);
							unset($this->account['repeat_passwd']);

							$this->add(array(
									'type' => 'account',
									'data' => $this->account
								)
							);

							$this->header->set_json_headers();
							$this->message->json_post_response(true,'add_redirect');
						}
					}
					else {
						$this->modelLanguage->getLanguage();

						$this->template->display('add.tpl');
					}
					break;
				case 'edit':
					if(!empty($this->tabs)) {
						switch ($this->tabs) {
							case 'account': break;
							case 'address': break;
							case 'socials': break;
						}

						$this->header->set_json_headers();
						$this->message->json_post_response(true,'update');
					}
					else {
						$this->modelLanguage->getLanguage();

						$this->getItems('account',$this->edit,'one');
						$this->getItems('address',$this->edit,'one');

						$country = new component_collections_country();
						try {
							$this->template->assign('countries',$country->getCountries());
						} catch(Exception $e) {
							$logger = new debug_logger(MP_LOG_DIR);
							$logger->log('php', 'error', 'An error has occured : '.$e->getMessage(), debug_logger::LOG_MONTH);
						}

						/*try {
							$this->template->assign('edit', true);
						} catch(Exception $e) {
							$logger = new debug_logger(MP_LOG_DIR);
							$logger->log('php', 'error', 'An error has occured : '.$e->getMessage(), debug_logger::LOG_MONTH);
						}*/

						$this->template->display('edit.tpl');
					}
					break;
				case 'delete':
					if(isset($this->id) && !empty($this->id)) {
						$this->del(
							array(
								'type' => 'adv',
								'data' => array(
									'id' => $this->id
								)
							)
						);
					}
					break;
				case 'order':
					if (isset($this->advantage) && is_array($this->advantage)) {
						$this->order();
					}
					break;
			}
		}
		else {
			$this->modelLanguage->getLanguage();
			//$defaultLanguage = $this->collectionLanguage->fetchData(array('context'=>'one','type'=>'default'));
			$this->getItems('accounts');
			$assign = array(
				'id_account',
				'email_ac',
				'firstname_ac',
				'lastname_ac',
				'active_ac' => array('title' => 'active_ac', 'type' => 'bin', 'input' => null, 'class' => ''),
				'date_create'
			);
			$this->data->getScheme(array('mc_accounte','mc_account_info','mc_lang'),array('id_account','iso_lang','email_ac','firstname_ac','lastname_ac','active_ac','date_create'),$assign);
			$this->template->display('index.tpl');
		}
    }
}