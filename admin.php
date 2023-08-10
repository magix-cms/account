<?php
require_once('db.php');
/*
 # -- BEGIN LICENSE BLOCK ----------------------------------
 #
 # This file is part of MAGIX CMS.
 # MAGIX CMS, The content management system optimized for users
 # Copyright (C) 2008 - 2022 magix-cms.com <support@magix-cms.com>
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
 * @category plugins
 * @package account
 * @copyright  MAGIX CMS Copyright (c) 2008 - 2022 Gerits Aurelien,
 * http://www.magix-cms.com,  http://www.magix-cjquery.com
 * @license Dual licensed under the MIT or GPL Version 3 licenses.
 * @version 3.0
 * @author: Salvatore Di Salvo
 * @name plugins_account_admin
 */
class plugins_account_admin extends plugins_account_db {
	/**
	 * @var backend_model_template$template
	 * @var backend_model_data $data
	 * @var component_core_message $message
	 * @var backend_controller_plugins $plugins
	 * @var backend_model_language $modelLanguage
	 * @var component_collections_language $collectionLanguage
	 * @var backend_model_setting $settings
	 * @var backend_controller_tableform $tableform
	 * @var backend_controller_module $module
	 */
	protected backend_model_template $template;
	protected backend_model_data $data;
	protected component_core_message $message;
	protected backend_model_plugins $plugins;
	protected backend_model_language $modelLanguage;
	protected component_collections_language $collectionLanguage;
	protected backend_model_setting $settings;
    protected backend_controller_tableform $tableform;
    protected backend_controller_module $module;

	/**
	 * @var array $setting
	 */
	protected array $setting;

	/**
	 * @var string $controller
	 * @var string $action
	 * @var string $tableaction
	 * @var string $tabs
	 */
	public string
		$controller,
		$action,
		$tableaction,
		$tabs,
		$img;

	/**
	 * @var int $edit
	 * @var int $offset
	 * @var int $page
	 * @var int $id
	 */
	public int
		$edit,
		$offset,
		$page,
		$id,
		$del_img;

	/**
	 * @var array $mods
	 * @var array $account
	 * @var array $billing
	 * @var array $delivery
	 * @var array $config
	 * @var array $assign
	 * @var array $tables
	 * @var array $columns
	 * @var array $search
	 */
	public array
		$mods,
		$account,
		$billing,
		$delivery,
		$config,
		$assign,
		$tables,
		$columns,
		$search;


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
    public function __construct() {
		$this->template = new backend_model_template();
		$this->data = new backend_model_data($this);
		$this->plugins = new backend_model_plugins();
		$this->message = new component_core_message($this->template);
		$this->modelLanguage = new backend_model_language($this->template);
		$this->collectionLanguage = new component_collections_language();
		$this->settings = new backend_model_setting();
		$this->setting = $this->template->settings;

		// --- GET
		if (http_request::isGet('controller')) $this->controller = form_inputEscape::simpleClean($_GET['controller']);
		if (http_request::isGet('edit')) $this->edit = (int)form_inputEscape::numeric($_GET['edit']);
		if (http_request::isGet('tabs')) $this->tabs = form_inputEscape::simpleClean($_GET['tabs']);
		if (http_request::isGet('page')) $this->page = intval(form_inputEscape::simpleClean($_GET['page']));
		$this->offset = (http_request::isGet('offset')) ? intval(form_inputEscape::simpleClean($_GET['offset'])) : 25;
		if (http_request::isRequest('action')) $this->action = form_inputEscape::simpleClean($_REQUEST['action']);

        if (http_request::isGet('tableaction')) {
            $this->tableaction = form_inputEscape::simpleClean($_GET['tableaction']);
            $this->tableform = new backend_controller_tableform($this, $this->template);
        }

        // --- Search
        if (http_request::isGet('search')) {
            $this->search = form_inputEscape::arrayClean($_GET['search']);
            $this->search = array_filter($this->search, function ($value) {
                return $value !== '';
            });
        }
        // --- ADD or EDIT
		if (http_request::isRequest('account')) $this->account = form_inputEscape::arrayClean($_REQUEST['account']);
		if (http_request::isPost('billing')) $this->billing = form_inputEscape::arrayClean($_POST['billing']);
		if (http_request::isPost('delivery')) $this->delivery = form_inputEscape::arrayClean($_POST['delivery']);
		if (http_request::isPost('acConfig')) $this->config = form_inputEscape::arrayClean($_POST['acConfig']);
		if (http_request::isPost('id')) $this->id = form_inputEscape::simpleClean($_POST['id']);
    }

	/**
	 * Method to override the name of the plugin in the admin menu
	 * @return string
	 */
	public function getExtensionName(): string {
		return $this->template->getConfigVars('account_plugin');
	}

    /**
     * Assign data to the defined variable or return the data
     * @param string $type
     * @param array|int|null $id
     * @param string|null $context
     * @param boolean|string $assign
     * @param boolean $pagination
     * @return mixed
     */
    private function getItems(string $type, $id = null, string $context = null, $assign = true, bool $pagination = false) {
        return $this->data->getItems($type, $id, $context, $assign, $pagination);
    }

	/**
	 *
	 */
	private function loadModules() {
		$this->module = $this->module ?? new backend_controller_module();
		if(empty($this->mods)) $this->mods = $this->module->load_module('account');
	}

	/**
	 * @return void
	 */
	public function setTablesArray() {
		if(!isset($this->tables)) {
			$this->tables = ['mc_account','mc_lang'];
			$this->loadModules();
			if(!empty($this->mods)) {
				foreach ($this->mods as $mod){
					if(method_exists($mod,'extendTablesArray')) {
						$this->tables = array_merge($this->tables,$mod->extendTablesArray());
					}
				}
			}
		}
	}

	/**
	 * @return void
	 */
	public function setColumnsArray() {
		if(!isset($this->columns)) {
			$this->columns = ['id_account','iso_lang','email_ac','firstname_ac','lastname_ac','active_ac','date_create'];
			$this->loadModules();
			if(!empty($this->mods)) {
				foreach ($this->mods as $mod){
					if(method_exists($mod,'extendColumnsArray')) {
						$this->columns = array_merge($this->columns,$mod->extendColumnsArray());
					}
				}
			}
		}
	}

	/**
	 * @return void
	 */
	public function setAssignArray() {
		if(!isset($this->assign)) {
			$this->assign = [
				'id_account' => ['title' => 'id', 'type' => 'text', 'class' => 'fixed-td-md text-center'],
				'iso_lang' => [
					'title' => 'lang',
					'type' => 'text',
					'class' => 'fixed-td-md',
					'input' => [
						'type' => 'select',
						'var' => false,
						'values' => $this->modelLanguage->getTableformLanguages()
					]
				],
				'email_ac',
				'firstname_ac',
				'lastname_ac',
				'active_ac',
				'date_create'
			];
            $this->loadModules();
            if(!empty($this->mods)) {
				$extendArray = [];
                foreach ($this->mods as $name => $mod){
                    if(method_exists($mod,'extendAssignArray')) {
                        $extendArray[] = $mod->extendAssignArray();
                    }
                }
                $newAssignArray = [];
                foreach ($extendArray as $cols) {
					foreach ($cols as $pos => $item) {
						$i = 1;
						foreach ($this->assign as $key => $col) {
							if($i === $pos) {
								if(is_array($item)) $newAssignArray = array_merge($newAssignArray,$item);
								else $newAssignArray[] = $item;
							}
							if(is_string($key)) $newAssignArray[$key] = $col;
							else $newAssignArray[] = $col;
							$i++;
						}
						$this->assign = $newAssignArray;
					}
                }
            }
		}
	}

    /**
     * @param bool $ajax
     * @return array
     */
    public function tableSearch(bool $ajax = false): array {
        $params = [];
        $results = $this->getItems('accounts',NULL,'all',false, true);
		$this->setAssignArray();

        if($ajax) {
            $params['section'] = 'accounts';
            $params['idcolumn'] = 'id_account';
            $params['activation'] = true;
            $params['sortable'] = false;
            $params['checkbox'] = true;
            $params['edit'] = true;
            $params['dlt'] = true;
            $params['readonly'] = [];
            $params['cClass'] = 'plugins_account_admin';
        }

        $this->data->getScheme(['mc_account','mc_lang'],['id_account','iso_lang','email_ac','firstname_ac','lastname_ac','level','content_pc','img_pr','date_end','active_ac','date_create'],$this->assign);

        return [
			'data' => $results,
			'var' => 'accounts',
			'tpl' => 'index.tpl',
			'params' => $params
		];
    }

	/**
	 * Insert data
	 * @param array $config
	 */
	private function add(array $config) {
		switch ($config['type']) {
			case 'account':
				parent::insert(
					['type' => $config['type']],
					$config['data']
				);
				break;
		}
	}

	/**
	 * Update data
	 * @param array $config
	 */
	private function upd(array $config) {
		switch ($config['type']) {
			case 'accountActive':
			case 'account':
			case 'address':
			case 'accountAdminConfig':
			case 'pwd':
			case 'img':
			case 'config':
				parent::update(
					['type' => $config['type']],
					$config['data']
				);
				break;
		}
	}

	/**
	 * Delete a record
	 * @param array $config
	 */
	private function del(array $config) {
		switch ($config['type']) {
			case 'account':
                parent::delete(
                    ['type' => $config['type']],
                    $config['data']
                );
                $this->message->json_post_response(true,'delete',['id' => $this->id]);
                break;
            case 'delSubs':
                parent::delete(
                    ['type' => $config['type']],
                    $config['data']
                );
                $this->message->json_post_response(true,'delete',$config['data']);
				break;
		}
	}

    /**
     * @param $data
     * @return array
     * @throws Exception
     */
    /*private function setDatePeriod($data){
        // ---- Ajout de ou des abonnements
        $newSubs = array();
        $subs = $this->getItems('accountSubs',array('id'=>$this->id,'id_sub'=>$data['id_sub']),'one',false);
        $dateFormat = new date_dateformat();
        if(!empty($subs)) {
            $newSubs['date_start_an'] = $subs['date_end_an'];
            $newSubs['date_end_an'] = $dateFormat->ovrAdd(
                array('interval'=>'+'.$data['period_sub'].' '.$data['date_type_sub'],'type'=>'string'),
                'Y-m-d H:i:s',
                $subs['date_end_an']
            );
            $newSubs['id_sub'] = $data['id_sub'];
            $newSubs['id_account'] = $this->id;

        }else{
            $newSubs['date_start_an'] = $dateFormat->SQLDateTime();
            $newSubs['date_end_an'] = $dateFormat->ovrAdd(
                array('interval'=>'+'.$data['period_sub'].' '.$data['date_type_sub'],'type'=>'string'),
                'Y-m-d H:i:s',
                $newSubs['date_start_an']
            );
            $newSubs['id_sub'] = $data['id_sub'];
            $newSubs['id_account'] = $this->id;
        }
        return $newSubs;
    }*/

	/**
	 * @param mixed $data
	 * @return bool|string|void
	 */
	private function setPostMail($data) {
		$url = http_url::getUrl().'/'.$this->template->lang.'/account/?send_mail_admin';

		$headers = ["Content-Type: application/x-www-form-urlencoded"];

		$data = http_build_query($data);

		$options = [
            CURLOPT_RETURNTRANSFER  => true,
            CURLINFO_HEADER_OUT     => true,
            CURLOPT_URL             => $url,
            CURLOPT_HTTPHEADER      => $headers,
            CURLOPT_TIMEOUT         => 300,
            CURLOPT_CONNECTTIMEOUT  => 300,
            CURLOPT_CUSTOMREQUEST   => "POST",
            CURLOPT_POSTFIELDS      => $data,
            CURLOPT_SSL_VERIFYPEER  => false
        ];

		$ch = curl_init();
		curl_setopt_array($ch, $options);

		$response = curl_exec($ch);
		$curlInfo = curl_getinfo($ch);
		curl_close($ch);
		if ($curlInfo['http_code'] == '200') {
			if ($response) {
				return $response;
			}
		}
	}

	/**
	 * @return void
	 */
	private function setTableformData() {
		$this->modelLanguage->getLanguage();
		$this->setTablesArray();
		$this->setColumnsArray();
		$this->setAssignArray();
		$params = [];
		$this->loadModules();
		if(!empty($this->mods)) {
			$extendQueryParams = [];
			foreach ($this->mods as $mod){
				if(method_exists($mod,'extendListingQuery')) {
					$extendQueryParams[] = $mod->extendListingQuery();
				}
			}
			if(!empty($extendQueryParams)) {
				foreach ($extendQueryParams as $extendParams) {
					if(isset($extendParams['select']) && !empty($extendParams['select'])) $params['select'][] = $extendParams['select'];
					if(isset($extendParams['join']) && !empty($extendParams['join'])) $params['join'][] = $extendParams['join'];
					if(isset($extendParams['where']) && !empty($extendParams['where'])) $params['where'][] = $extendParams['where'];
				}
			}
		}
		$this->getItems('accounts',$params,'all',true,true);
		$this->data->getScheme($this->tables,$this->columns,$this->assign);
	}

    /**
     *
     */
    public function run(){
		$this->loadModules();
		$this->getItems('config',[],'one');

		// --- Image Upload
		if (isset($_FILES['img']["name"])) $this->img = http_url::clean($_FILES['img']["name"]);
		if (http_request::isPost('del_img')) $this->del_img = form_inputEscape::simpleClean($_POST['del_img']);
		if (http_request::isGet('plugin')) $this->plugin = form_inputEscape::simpleClean($_GET['plugin']);

		if(isset($this->plugin)) {
			$defaultLanguage = $this->collectionLanguage->fetchData(['context' => 'one', 'type' => 'default']);
			$this->getItems('account', array(':default_lang' => $defaultLanguage['id_lang']), 'all');
			$this->plugins->getModuleTabs('account');
			// Initialise l'API menu des plugins core
			$this->modelLanguage->getLanguage();
			// Execute un plugin core
			$class = 'plugins_' . $this->plugin . '_core';
			if(file_exists(component_core_system::basePath().'plugins'.DIRECTORY_SEPARATOR.$this->plugin.DIRECTORY_SEPARATOR.'core.php') && class_exists($class) && method_exists($class, 'run')) {
				$executeClass =  new $class;
				if($executeClass instanceof $class){
					$executeClass->run();
				}
			}
		}
		else {
			if(isset($this->tableaction)) {
				$this->tableform->run();
			}
			elseif(isset($this->action)) {
				switch ($this->action) {
					case 'add':
						if(isset($this->account) && !empty($this->account)) {
							if($this->account['passwd'] === $this->account['repeat_passwd']) {
								$this->account['passcrypt_ac'] = password_hash($this->account['passwd'], PASSWORD_DEFAULT);
								$this->account['keyuniqid_ac'] = filter_rsa::uniqID();
								$this->account['active_ac'] = isset($this->account['active_ac']) ? 1 : 0;
								//$this->account['same_address'] = 0;
								$this->account['weekly_alert'] = 0;
								$this->account['monthly_alert'] = 0;
								$this->account['overbid_alert'] = 0;
								$this->account['endofsale_alert'] = 0;
								$this->account['firstname_ac'] = NULL;
								$this->account['lastname_ac'] = NULL;
								$this->account['phone_ac'] = NULL;
								$this->account['company_ac'] = NULL;
								$this->account['vat_ac'] = NULL;
								unset($this->account['passwd']);
								unset($this->account['repeat_passwd']);

								$this->add([
									'type' => 'account',
									'data' => $this->account
								]);
								$this->message->json_post_response(true,'add_redirect');
							}
						}
						else {
							/*if(!empty($this->tabs)) {
								switch ($this->tabs) {
									case 'subs':
										/*$v_pro = $this->getItems('v_pro',$this->id,'one',false);
										if($v_pro['id_pro'] == NULL){
											$defaultLanguage = $this->collectionLanguage->fetchData(array('context' => 'one', 'type' => 'default'));
											$proRoot = array(
												'id_account' => $this->id,
												'id_tc_1' => NULL,
												'id_tc_2' => NULL,
												'active_pro' => 1
											);
											$this->add(array(
												'type' => 'page',
												'data' => $proRoot
											));
											$newPro = $this->getItems('v_pro',$this->id,'one',false);
											$proContent = array(
												'id_pro'=> $newPro['id_pro'],
												'id_lang'=> $defaultLanguage['id_lang'],
												'name_pc'=> NULL,
												'resume_pc'=> NULL,
												'content_pc'=> NULL,
												'video_pc'=> NULL,
												'firstname_pc'=> NULL,
												'lastname_pc'=> NULL,
												'phone_pc'=> NULL,
												'email_pc'=> NULL,
												'address_pc'=> NULL,
												'id_tn'=> NULL,
												'country_pc'=> NULL,
												'schedule_pc'=> NULL,
												'published_pc'=> 1
											);
											$this->add(array(
												'type' => 'content',
												'data' => $proContent
											));
										}*/
							// --- Ajout d'abonnement gratuit
							/*$newSubs = $this->setDatePeriod($this->subData);
							/*@ToDo lier avec le plugin subscription plutôt qu'en dur ici*/
							//print_r($newSubs);
							/*$this->add(array(
								'type' => 'subs',
								'data' => $newSubs
							));
							$this->getItems('lastSubs',array('id'=>$this->id),'one','row');
							$display = $this->template->fetch('loop/subs.tpl');
							$this->message->json_post_response(true,'add',$display);*/
							/*break;
					/*}
				}
				else{*/
							$this->modelLanguage->getLanguage();
							$this->template->display('add.tpl');
							//}
						}
						break;
					case 'edit':
						$status = false;
						$notify = 'error';
						if(!empty($this->tabs)) {
							switch ($this->tabs) {
								case 'account':
									$this->account['id'] = $this->id;
									$this->upd([
										'type' => 'account',
										'data' => array_map(function($v) { return $v === '' ? null : $v; }, $this->account)
									]);
									if(!empty($this->billing)) {
										$this->billing['id'] = $this->id;
										$this->billing['type_address'] = 'billing';
										$this->upd([
											'type' => 'address',
											'data' => array_map(function($v) { return $v === '' ? null : $v; }, $this->billing)
										]);
									}
									if(!empty($this->delivery)) {
										$this->delivery['id'] = $this->id;
										$this->delivery['type_address'] = 'delivery';
										$this->upd([
											'type' => 'address',
											'data' => array_map(function($v) { return $v === '' ? null : $v; }, $this->delivery)
										]);
									}
									if(isset($this->social)){
										$this->upd([
											'type' => 'socials',
											'data' => array_map(function($v) { return $v === '' ? null : $v; }, $this->social)
										]);
									}
									$status = true;
									$notify = 'update';
									break;
								case 'accountConfig':
									$upd = true;
									$account = $this->getItems('account',$this->account['id'],'one',false);
									if($this->account['email_ac'] !== $account['email_ac']) {
										$invalidmail = $this->getItems('searchmail',['email_ac' => $this->account['email_ac']],'one',false);
										if($invalidmail) { $upd = false; }
									}
									if($upd) {
										$this->account['active_ac'] = isset($this->account['active_ac']) ? 1 : 0;
										$this->account['referral_ac'] = !empty($this->account['referral_ac']) ? $this->account['referral_ac'] : NULL;
										$this->upd([
											'type' => 'accountAdminConfig',
											'data' => $this->account
										]);
										$status = true;
										$notify = 'update';
									}
									break;
								case 'pwd':
									if($this->account['new_passwd'] === $this->account['repeat_passwd']) {
										$this->upd([
											'type' => 'pwd',
											'data' => [
												'passcrypt_ac' => password_hash($this->account['new_passwd'], PASSWORD_DEFAULT),
												'id' => $this->account['id']
											]
										]);
										$status = true;
										$notify = 'update';
									}
									break;
								case 'config':
									$config = $this->getItems('config',[],'one',false);
									$this->config['id'] = $config['id_config'];
									$this->config['pseudo'] = isset($this->config['pseudo']) ? 1 : 0;
									$this->config['birthdate'] = isset($this->config['birthdate']) ? 1 : 0;
									$this->config['links'] = isset($this->config['links']) ? 1 : 0;
									$this->config['picture'] = isset($this->config['picture']) ? 1 : 0;
									$this->config['public'] = isset($this->config['public']) ? 1 : 0;
									//$this->config['address'] = isset($this->config['address']) ? 1 : 0;

									$this->upd([
										'type' => 'config',
										'data' => $this->config
									]);

									$status = true;
									$notify = 'update';
									break;
								case 'img':
									if(isset($this->img)){
										$account = $this->getItems('account', $this->id, 'one', false);
										$upload = new component_files_upload();
										$resultUpload = $upload->setImageUpload(
											'img',
											[
												'name' => !empty($account['pseudo_ac']) ? http_url::clean($account['pseudo_ac']).'_'.filter_rsa::randMicroUI() : filter_rsa::randMicroUI(),
												'prefix_increment' => true,
												'prefix' => ['s_', 'm_', 'l_'],
												'module_img' => 'plugins',
												'attribute_img' => 'account',
												'original_remove' => false
											],
											['upload_root_dir' => 'upload/account', 'upload_dir' => $this->id],
											false);

										$filename = $resultUpload['file'];
										if($filename !== '') {
											$this->upd([
												'type' => 'img',
												'data' => [
													'id_account' => $this->id,
													'img_ac' => $filename
												]
											]);
											$status = true;
											$notify = 'update';
										}
									}
									break;
							}
							$this->message->json_post_response($status,$notify);
						}
						else {
							$this->plugins->getModuleTabs('account');
							$this->modelLanguage->getLanguage();
							//$defaultLanguage = $this->collectionLanguage->fetchData(['context'=>'one','type'=>'default']);
							$account = $this->getItems('account',$this->edit,'one',false);
							$account['billing'] = $this->getItems('billing_address',$this->edit,'one',false);
							$account['delivery'] = $this->getItems('delivery_address',$this->edit,'one',false);
							$this->template->assign('account',$account);
							/*if($account['id_pro']) {
								if(!$account['active_pro']) {
									$pro = $this->getItems('proContent',['id' => $this->edit,'iso' => $defaultLanguage['iso_lang']],'one',false);
									$thematic = $this->getItems('proThematic',['id' => $account['id_pro'],'id_lang' => $defaultLanguage['id_lang']],'one',false);
									$pro['id_tc'] = $thematic['name_tc'];
									$this->template->assign('proContent',$pro);
								}
							}*/
							$country = new component_collections_country();
							$this->template->assign('countries',$country->getAllowedCountries());
							/*$accessLevel = 0;
							$subsAccessLevel = [
								'pro' => 1,
								'gooddeal' => 2
							];
							$newSubs = array();
							$subs = $this->getItems('accountSubs',$this->edit,'all',false);*/
							/*$dateFormat = new date_dateformat();
							//$dateDiff = $dateFormat->dateDiff('now',$subs[0]['date_end_an']);
							//print_r($dateDiff);
							if(!empty($subs)) {
								foreach ($subs as $key => $val) {
									$newSubs[$key]['id_an'] = $val['id_an'];
									$newSubs[$key]['date_start'] = $val['date_start_an'];
									$newSubs[$key]['date_end'] = $val['date_end_an'];
									$newSubs[$key]['type_sub'] = $val['type_sub'];
									$newSubs[$key]['date_diff'] = $dateFormat->dateDiff('now',$val['date_end_an'],'%R%a');
									$accessLevel = max($accessLevel,$subsAccessLevel[$sub['type_sub']]);
									if($sub['type_sub'] === 'pro') {
										//$account['pro'] = $this->getItems('proInfo',$this->id_account_session,'one',false);
										//@To/Do calcul temps avant fin abo
									}

								}
							}*/
							//print_r($accessLevel);
							//$this->template->assign('accountSubs',$subs);
							$this->template->display('edit.tpl');
						}
						break;
					case 'delete':
						if(isset($this->id) && !empty($this->id)) {
							$this->del([
								'type' => 'account',
								'data' => ['id' => $this->id]
							]);
						}
						elseif(isset($this->del_img)) {
							$this->upd([
								'type' => 'img',
								'data' => [
									'id_account' => $this->del_img,
									'img_ac' => NULL
								]
							]);

							//$setEditData = $this->getItems('page',array('edit'=>$this->del_img),'all',false);
							//$setEditData = $this->setItemData($setEditData);
							$upload = new component_files_upload();
							$setImgDirectory = $upload->dirImgUpload(['upload_root_dir'=>'upload/account/'.$this->del_img,'imgBasePath'=>true]);

							if(file_exists($setImgDirectory)){
								$makeFiles = new filesystem_makefile();
								$finder = new file_finder();
								$setFiles = $finder->scanDir($setImgDirectory);
								if(is_array($setFiles) && !empty($setFiles)){
									foreach($setFiles as $file){
										$makeFiles->remove($setImgDirectory.$file);
									}
								}
							}
							//$this->template->assign('page',$setEditData[$this->del_img]);
							//$display = $this->template->fetch('news/brick/img.tpl');
							//$this->message->json_post_response(true, 'update',$display);
							$this->message->json_post_response(true, 'update');
						}
						break;
					case 'active-selected':
					case 'unactive-selected':
						if(!empty($this->account)) {
							$this->upd([
								'type' => 'accountActive',
								'data' => [
									'active_ac' => ($this->action === 'active-selected'?1:0),
									'id' => implode(',', $this->account)
								]
							]);
						}
						$this->message->getNotify('update',['method'=>'fetch','assignFetch'=>'message']);
						$this->setTableformData();
						$this->template->display('index.tpl');
						break;
				}
			}
			else {
				$this->setTableformData();
				$this->loadModules();
				if(!empty($this->mods)) {
					foreach ($this->mods as $name => $mod){
						if(method_exists($mod,'extendAssignArray')) {
							$this->template->addConfigFile([component_core_system::basePath().'plugins'.DIRECTORY_SEPARATOR.$name.DIRECTORY_SEPARATOR.'i18n'.DIRECTORY_SEPARATOR], [$name.'_admin_']);
						}
					}
				}
				$this->template->configLoad();
				$this->template->display('index.tpl');
			}
		}
    }
}