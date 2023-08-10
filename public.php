<?php
require_once('model/account/Account.php');
require_once('db.php');
require_once('session.php');
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
 * @copyright  MAGIX CMS Copyright (c) 2008 - 2021 Gerits Aurelien,
 * http://www.magix-cms.com,  http://www.magix-cjquery.com
 * @license Dual licensed under the MIT or GPL Version 3 licenses.
 * @version 3.0
 * @author: Salvatore Di Salvo
 * @name plugins_account_public
 */
class plugins_account_public extends plugins_account_db {
    /**
	 * @var frontend_model_template $template
	 * @var frontend_model_data $data
     * @var component_core_message $message
	 * @var filter_sanitize $sanitize
	 * @var frontend_model_mail $mail
     * @var frontend_model_setting $settings
	 * @var frontend_model_seo $seo
	 * @var frontend_model_module $module
	 * @var component_files_images $imagesComponent
	 * @var frontend_model_logo $logo
	 * @var plugins_account_session $session
     */
    protected frontend_model_template $template;
    protected frontend_model_data $data;
    protected component_core_message $message;
    protected filter_sanitize $sanitize;
    protected frontend_model_mail $mail;
    protected frontend_model_setting $settings;
    protected frontend_model_seo $seo;
    protected frontend_model_module $module;
    protected component_files_images $imagesComponent;
    protected component_files_upload $upload;
    protected frontend_model_logo $logo;
	protected plugins_account_session $session;

	/**
	 * @var string $lang
	 */
	protected string
		$lang;

	/**
	 * @var array $ssl
	 * @var array $mods
	 */
	protected array
		$ssl,
		$mods;

    /**
     * @var int $id_account_session
     */
    private int $id_account_session;

    /**
     * @var bool $logged
     */
    private bool $logged;

    /**
     * @var array $config
     */
    //private array $config;

    /**
     * @var int $edit
     * @var int $id
     * @var int $id_img
     */
    public int
        $edit,
        $id,
        $id_img;

    /**
     * @var string $country_billing
     * @var string $country_delivery
     * @var string $action
     * @var string $subaction
     * @var string $hash
     * @var string $key
     * @var string $tab
     * @var string $gRecaptchaResponse
     * @var string $v_email
     * @var string $email_session
	 * @var string $img
	 * @var string $img_multiple
     * @var string $referrer
     */
    public string
        $country_billing,
        $country_delivery,
        $action,
        $subaction,
        $hash,
        $key,
        $tab,
        $gRecaptchaResponse,
		$v_email,
        $email_session,
		$img,
		$img_multiple,
		$referrer;

	/**
	 * @var array $accountData
	 * @var array $account
	 * @var array $billing
	 * @var array $delivery
	 * @var array $alert
	 * @var array $socials
	 * @var array $current
	 */
	public array
		$accountData,
		$account,
		$billing,
		$delivery,
		$alert,
		$socials,
		$current;

    /**
     * plugins_account_public constructor.
     * @param null|frontend_model_template $t
     */
	public function __construct(frontend_model_template $t = null) {
        $this->template = $t instanceof frontend_model_template ? $t : new frontend_model_template();
        $this->data = new frontend_model_data($this,$this->template);
        $this->settings = new frontend_model_setting($this->template);
        $this->ssl = $this->settings->getSetting('ssl');
        $this->message = new component_core_message($this->template);
        $this->lang = $this->template->lang;

        $this->session = new plugins_account_session();
        $this->logged = $this->session->isLogged();

        // --- Session
		if($this->logged) {
			$this->current = $this->session->current;
			$this->email_session = $this->session->email;
			$this->id_account_session = $this->session->id_account;
		}
	}

    /**
     * Assign data to the defined variable or return the data
     * @param string $type
     * @param int|array $id
     * @param string $context
     * @param bool|string $assign
     * @return mixed
     */
    private function getItems(string $type, $id = [], string $context = 'all', $assign = true) {
        return $this->data->getItems($type, $id, $context, $assign);
    }

    /**
     * Load account modules
     */
    private function loadModules() {
        if(!isset($this->module)) $this->module = new frontend_model_module($this->template);
        if(empty($this->mods)) $this->mods = $this->module->load_module('account');
    }

	// --- Signup
	/**
	 * Check if required field are filled
	 * @return bool
	 */
	private function checkRequired(): bool {
		$data_validate = [
			'account' => [
				'firstname_ac',
				'lastname_ac',
				'email_ac',
				'phone_ac',
				'company_ac',
				//'vat_ac',
				'passwd'
			],
			'cond_gen'
		];

        foreach($data_validate as $key => $input){
            if(is_array($input)) {
                foreach ($input as $k) {
                    if (empty($_POST[$key][$k])) {
                        return false;
                    }
                }
            }
            elseif (empty($_POST[$input])) {
                return false;
            }
        }
        return true;
	}
	// --------------------

	// --- Mail
	/**
	 * Send a mail
     * @param string $email
     * @param string $tpl
     * @param array $data
     * @return bool
	 */
    protected function send_email(string $email, string $tpl, array $data = []): bool {
        if(!empty($email)) {
            $this->template->configLoad();
            $this->sanitize = new filter_sanitize();

            if(!$this->sanitize->mail($email)) {
                $this->notify('warning','mail');
                return false;
            }
            else {
                $from = $this->settings->getSetting('mail_sender');
                if(!empty($from)) {
                    $this->mail = new frontend_model_mail($this->template,'account');
                    $this->mail->send_email(
                        $email,
                        $tpl,
                        $data,
                        '',
                        '',
                        $from['value'],
                        null
                    );
                    return true;
                }
                else {
                    $this->notify('error','error_config');
                    return false;
                }
            }
        }
        else {
            $this->notify('error','error_mail');
            return false;
        }
    }
	// --------------------

	// --- Database actions
	/**
	 * Insert data
	 * @param array $config
	 * @return bool|string|array|void
	 */
	private function add(array $config) {
		switch ($config['type']) {
			case 'account':
				return parent::insert(
					['type' => $config['type']],
					$config['data']
				);
			case 'newImg':
			case 'imgDefault':
			case 'session':
				parent::insert(
					['type' => $config['type']],
					$config['data']
				);
				break;
		}
	}

	/**
	 * Insert data
	 * @param array $config
	 */
	private function upd(array $config) {
		switch ($config['type']) {
			case 'account':
			case 'accountConfig':
			case 'accountOptions':
			case 'pwd':
			case 'pwdTicket':
			case 'newPwd':
			case 'address':
			case 'alert':
			case 'activate':
			case 'socials':
			case 'img':
			case 'sameAddress':
			case 'firstImageDefault':
				parent::update(
					['type' => $config['type']],
					$config['data']
				);
				break;
			case 'imageDefault':
				parent::update(
					['type' => $config['type']],
					$config['data']
				);
				$this->message->json_post_response(true,'update');
				break;
			case 'imgDefault':
		}
	}

	/**
	 * Delete data
	 * @param array $config
	 */
	private function del(array $config) {
		switch ($config['type']) {
			case 'images':
				// Initiate the image library components
				$this->upload = new component_files_upload();
				$this->imagesComponent = new component_files_images($this->template);
				$makeFiles = new filesystem_makefile();
				$imgArray = explode(',',$config['data']['id']);
				$fetchConfig = $this->imagesComponent->getConfigItems(['module_img'=>'catalog','attribute_img'=>'product']);
				$imgPrefix = $this->imagesComponent->prefix();
				// Define functional variables
				$defaultErased = false;
				$id_pro = false;
				$extwebp = 'webp';
				// Array of images to erased at the end
				$toRemove = [];

				foreach($imgArray as $key => $value) {
					// Get images stored information
					$img = $this->getItems('img',$value,'one',false);

					if(!empty($img) && !empty($img['id_pro']) && !empty($img['name_img'])) {
                        // Get the pro's id
                        if(!$id_pro) $id_pro = $img['id_pro'];
                        // Check if it's the default image that's going to be erased
                        if($img['default_img']) $defaultErased = true;
                        // Concat the image directory path
                        $imgPath = $this->upload->dirFileUpload(['upload_root_dir' => 'upload/account', 'upload_dir' => $id_pro, 'fileBasePath'=>true]);

                        // Original file of the image
                        $original = $imgPath.$img['name_img'];
                        if(file_exists($original)) $toRemove[] = $original;

                        // Loop over each version of the image
                        foreach ($fetchConfig as $configKey => $confiValue) {
                            $image = $imgPath.$imgPrefix[$confiValue['type_img']].$img['name_img'];
                            if(file_exists($image)) $toRemove[] = $image;

                            // Check if the image with webp extension exist
                            $imgData = pathinfo($img['name_img']);
                            $filename = $imgData['filename'];
                            $webpImg = $imgPath.$imgPrefix[$confiValue['type_img']].$filename.'.'.$extwebp;
                            if(file_exists($webpImg)) $toRemove[] = $webpImg;
                        }
                    }
				}

				// If files had been found
				if(!empty($toRemove)) {
					// Erased images
					$makeFiles->remove($toRemove);

					// Remove from database
					parent::delete(
						['type' => $config['type']],
						$config['data']
					);

					// Count the remaining images
					$imgs = $this->getItems('countImages',$id_pro,'one',false);

					// If there is at leats one image left and the default image has been erased, set the first remaining image as default
					if($imgs['tot'] > 0 && $defaultErased) {
						$this->upd([
							'type' => 'firstImageDefault',
							'data' => ['id' => $id_pro]
						]);
					}
				}
				break;
		}
	}
	// --------------------

    // --- Methods
    /**
     * Set the notification
     * @param string $type
     * @param string $message
     * @return array
     */
    private function setNotify(string $type, string $message): array {
        $content = null;
		$about = new frontend_model_about();
		$companyData = $about->getCompanyData();
        if(in_array($type,['success','warning','error'])) {
            $success = [
                'signup' => sprintf($this->template->getConfigVars('request_success_signup'),$companyData['name']),
                'pro-pending' => $this->template->getConfigVars('request_success_pro_pending'),
                'pwd_changed' => $this->template->getConfigVars('request_pwd_changed')
            ];
            $warning = [
                'empty' =>  $this->template->getConfigVars('request_empty'),
                'captcha' =>  $this->template->getConfigVars('request_error_captcha'),
                'email_exist' =>  $this->template->getConfigVars('request_error_mail_exist'),
                'pwd' =>  $this->template->getConfigVars('request_error_pwd')
            ];
            $error = [
                'signup_error' => $this->template->getConfigVars('request_error_signup'),
                'error_ticket' => $this->template->getConfigVars('request_error_ticket'),
                'error_config' => $this->template->getConfigVars('request_error_ticket'),
                'error_mail' => $this->template->getConfigVars('request_error_ticket')
            ];
            $content = ${$type}[$message];
        }
        return $content === null ? [] : ['type' => $type, 'content' => $content];
    }

    /**
     * Display the notification
     * @param string $type
     * @param null|string $subContent
     * @param bool $fetch
     */
    private function notify(string $type, string $subContent = null, bool $fetch = false) {
        $message = $this->setNotify($type, $subContent);
        if(!empty($message)) {
            $this->template->assign('message',$message);
            if(!$fetch) $this->template->display('account/message.tpl');
            else {
                $messageTpl = $this->template->fetch('account/message.tpl');
                $this->template->assign('message',$messageTpl);
            }
        }
    }

	/**
	 * @param array $params
	 * @param array $options
	 * @return array
	 */
    /*public function search(array $params, array $options = []): array {
        $limit = 30;
		$conf = [
			'id_tc' => $params['id_tc'],
			'iso_lang' => $this->template->lang,
			'rating' => $params['rating'],
			'offset' => $params['page'] === 1 ? 0 : ($params['page'] -1) * $limit,
			'limit' => $limit
		];

		if($params['radius']) {
			$account = $this->accountData();
			$geoTool = new collections_geoTools();
			$geoData = $geoTool->getBoxLimits($account['address']['lat'],$account['address']['lon'],$params['radius']);
			$conf = array_merge($conf,$geoData);
			$searchResult = $this->getItems('searchProsFromThematicInRadius',$conf,'all',false);
			$pros = $searchResult[9];
			$nbp = $searchResult[10][0]['nbp'];
		}
		else {
			$searchResult = $this->getItems('searchProsFromThematic',$conf,'all',false);
			$pros = $searchResult[2];
			$nbp = $searchResult[3][0]['nbp'];
		}
        $lists = [
            'mapConf' => [],
            'proPlus' => [],
            'pro' => []
        ];

        return $lists;
    }*/

	/**
	 *
	 */
	public function setBreadcrumb() {
		$iso = $this->template->lang;
		$breadplugin = [];

		if(isset($this->action) && !empty($this->action)) {
			if($this->logged && isset($this->hash) && !empty($this->hash)) {
				$breadplugin[0] = ['name' => $this->template->getConfigVars('account_root')];
				$breadplugin[0]['title'] = $this->template->getConfigVars('account_root');
				$breadplugin[0]['url'] = http_url::getUrl().'/'.$iso.'/account/'.$this->hash.'/';
			}
			$breadplugin[] = ['name' => $this->template->getConfigVars('account_'.$this->action)];

			if(!empty($this->subaction)) {
				$breadplugin[1]['url'] = http_url::getUrl().'/'.$iso.'/account/'.$this->hash.'/'.$this->action.'/';
				$breadplugin[] = ['name' => $this->template->getConfigVars('account_'.$this->action.'_'.$this->subaction)];
			}
		}

		$this->template->assign('breadplugin', $breadplugin);
	}

	/**
	 * Get module tabs link
	 * @return array
	 */
	public function getTabs(): array {
		$tabs = [];

		if($this->logged) {
			$this->hash = filter_rsa::hashEncode('sha1', $this->current['id_session']);
			$this->loadModules();
			if(!empty($this->mods)) {
				foreach ($this->mods as $name => $mod){
					if(method_exists($mod,'getTabLink')) $tabs[$name] = $mod->getTabLink($this->template, $this->hash);
				}
			}
		}

		return $tabs;
	}

    /**
     * @return array
     */
    public function accountData(): array {
		if(isset($this->id_account_session)) {
			$account = new Account($this->id_account_session);
			return $account->getAccountData();
		}
        return [];
    }
    // ---

    // -- Access Account Class
    /**
	 * @param int|null $id
     * @return null|Account
     */
    public function getAccountModel(int $id = null) {
        $id = $id ?: $this->id_account_session;
		return empty($id) ? null : new Account($id);
    }
    // ---

	/**
	 * Execute le plugin dans la partie public
	 */
	public function run() {
		$this->seo = new frontend_model_seo('account', '', '',$this->template);
		//$breadplugin = [];
		//$this->config = $this->getItems('config',null,'one');
		$config = $this->getItems('config',null,'one',false);
		$this->loadModules();
		if(key_exists('recaptcha',$this->mods)) $config['recaptcha'] = true;
		$this->template->assign('account_config',$config);

		// --- Get
        if (http_request::isGet('action')) $this->action = form_inputEscape::simpleClean($_GET['action']);
        if (http_request::isGet('mod')) $this->subaction = form_inputEscape::simpleClean($_GET['mod']);
        if (http_request::isGet('hash')) $this->hash = form_inputEscape::simpleClean($_GET['hash']);
        if (http_request::isGet('tab')) $this->tab = form_inputEscape::simpleClean($_GET['tab']);

        // --- Post
        if(http_request::isPost('currentpage')) $this->referrer = form_inputEscape::simpleClean($_POST['currentpage']);

        // - Google reCaptcha
        if(http_request::isPost('g-recaptcha-response'))$this->gRecaptchaResponse = form_inputEscape::simpleClean($_POST['g-recaptcha-response']);

		if(isset($this->action) && !empty($this->action)) {
            if($this->logged) {
				$tabs = $this->getTabs();
				$this->template->assign('tabs',$tabs);

                // logged
				$this->hash = filter_rsa::hashEncode('sha1', $this->current['id_session']);
				$this->template->assign('hash',$this->hash);
				$account = $this->accountData();
				$account['hash'] = $this->hash;

				if(key_exists($this->action,$this->mods)) {
					$mod = $this->mods[$this->action];
					if(method_exists($mod,'accountRun')) $mod->accountRun($account);
				}
				else {
					if(http_request::isMethod('GET')) {
						$this->setBreadcrumb();

						if (http_request::isGet('id')) $this->id = form_inputEscape::numeric($_GET['id']);
						if (http_request::isGet('v_email')) $this->v_email = form_inputEscape::simpleClean($_GET['v_email']);

						switch ($this->action) {
							case $this->hash:
								$this->template->display('account/admin/index.tpl');
								break;
							/*case 'infos':
								$this->getItems('account',$this->id_account_session,'one');
								$country = new component_collections_country();
								$this->template->assign('countries',$country->getAllowedCountries());
								$this->template->display('account/edit/account.tpl');
								break;
							case 'config':
								$this->getItems('account',$this->id_account_session,'one');
								$breadplugin[0] = ['url' => $this->session->hashUrl(), 'name' => $this->template->getConfigVars('account'), 'title' => $this->template->getConfigVars('account')];
								$breadplugin[1] = ['name' => $this->template->getConfigVars('account_config')];
								$this->template->assign('breadplugin', $breadplugin);
								$this->template->display('account/edit/config.tpl');
								break;*/
							case 'infos':
								$country = new component_collections_country();
								$this->template->assign('countries',$country->getAllowedCountries());
								$this->template->display('account/admin/edit/account.tpl');
								break;
							default:
								header('location: '. $this->session->hashUrl());
								exit();
						}
					}
					elseif(http_request::isMethod('POST')) {
						if (http_request::isPost('id')) $this->id = form_inputEscape::numeric($_POST['id']);
						if (http_request::isPost('account')) $this->account = form_inputEscape::arrayClean($_POST['account']);
						if (http_request::isPost('billing')) $this->billing = form_inputEscape::arrayClean($_POST['billing']);
						if (http_request::isPost('delivery')) $this->delivery = form_inputEscape::arrayClean($_POST['delivery']);
						if (http_request::isPost('alert')) $this->alert = form_inputEscape::arrayClean($_POST['alert']);
						if (http_request::isPost('country_billing_id')) $this->country_billing = form_inputEscape::simpleClean($_POST['country_billing_id']);
						if (http_request::isPost('country_delivery_id')) $this->country_delivery = form_inputEscape::simpleClean($_POST['country_delivery_id']);
						if (http_request::isPost('socials')) $this->socials = form_inputEscape::arrayClean($_POST['socials']);
						if (http_request::isPost('id_img')) $this->id_img = form_inputEscape::numeric($_POST['id_img']);
						if (isset($_FILES['img']["name"])) $this->img = ($_FILES['img']["name"]);
						if (isset($_FILES['img_multiple']["name"])) $this->img_multiple = ($_FILES['img_multiple']["name"]);

						switch ($this->action) {
							/*case 'infos':
								if(isset($this->account)) {
									$status = false;
									$notify = 'error';

									if(!empty($this->account)) {
										$this->account['id'] = $this->address['id'] = $this->id_account_session;
										$this->upd([
											'type' => 'account',
											'data' => array_map(function($v) { return $v === '' ? null : $v; }, $this->account)
										]);
										$this->upd([
											'type' => 'address',
											'data' => array_map(function($v) { return $v === '' ? null : $v; }, $this->address)
										]);
										if(isset($this->socials)) {
											$this->upd([
												'type' => 'socials',
												'data' => array_map(function($v) { return $v === '' ? null : $v; }, $this->socials)
											]);
										}
										$status = true;
										$notify = 'update';
									}
									$this->message->json_post_response($status,$notify);
								}
								break;
							case 'config':
								$status = false;
								$notify = 'error';
								if (isset($this->v_email)) {
									$d = $this->getItems('searchmail',['email_ac' => $this->v_email],'one',false);
									print(!empty($d['email_ac']) ? "false" : "true");
								}
								elseif(!empty($this->tab)) {
									switch ($this->tab) {
										case 'accountConfig':
											$upd = true;
											$account = $this->getItems('account',$this->id_account_session,'one',false);
											$this->account['id_lang'] = $account['id_lang'];
											$this->account['active_ac'] = $account['active_ac'];
											$this->account['id'] = $this->id_account_session;

											if($this->account['email_ac'] !== $account['email_ac']) {
												$invalidmail = $this->getItems('searchmail',['email_ac' => $this->account['email_ac']],'one',false);

												if($invalidmail) { $upd = false; }
											}

											if($upd) {
												$this->send_email($account['email_ac'],'change_mail', $this->account);

												$this->upd([
													'type' => 'accountConfig',
													'data' => $this->account
												]);

												$status = true;
												$notify = 'update';

												//$this->session->logout(http_url::getUrl().'/'.$this->lang.'/');
												$this->session->logout(false);
											}
											break;
										case 'pwd':
											$account = $this->getItems('account',$this->id_account_session,'one',false);

											if(password_verify($this->account['old_passwd'],$account['passcrypt_ac'])) {
												if($this->account['new_passwd'] === $this->account['repeat_passwd']) {
													$this->upd(array(
														'type' => 'pwd',
														'data' => array(
															'passcrypt_ac' => password_hash($this->account['new_passwd'], PASSWORD_DEFAULT),
															'id' => $this->id_account_session
														)
													));

													$this->send_email($account['email_ac'],'change_pwd', $this->account);

													$status = true;
													$notify = 'update';
												}
											}
											break;
										case 'image':
											if(isset($this->img) && !empty($this->img)) {
												$this->template->configLoad();
												$this->upload = new component_files_upload();
												$this->imagesComponent = new component_files_images($this->template);
												$resultUpload = $this->upload->setImageUpload(
													'img',
													[
														'name' => http_url::clean($this->accountData['pseudo_ac']).'_'.filter_rsa::randMicroUI(),
														'prefix_increment' => true,
														'prefix' => ['s_', 'm_', 'l_'],
														'module_img' => 'plugins',
														'attribute_img' => 'account',
														'original_remove' => false
													],
													['upload_root_dir' => 'upload/account', 'upload_dir' => $this->accountData['id_account']],
													false//(isset($_SESSION['id_account']) && $_SESSION['id_account'] === 88)
												);
												$filename = $resultUpload['file'];
												if ($filename !== '') {
													$this->upd([
														'type' => 'img',
														'data' => [
															'id_account' => $this->accountData['id_account'],
															'img_ac' => $filename
														]
													]);
												}
												$status = true;
												$notify = 'update';
											}
											break;
									}
									$this->message->json_post_response($status,$notify);
								}
								break;*/
							case 'infos':
								$status = false;
								$notify = 'error';
								if(isset($this->account)) {
									if(!empty($this->account)) {
										$this->account['id'] = $this->address['id'] = $this->id_account_session;
										$this->account['pseudo_ac'] = $this->account['pseudo_ac'] ?? NULL;
										//$this->address['country_address'] = $this->country;
										$this->upd([
											'type' => 'sameAddress',
											'data' => [
												'id' => $this->id_account_session,
												'same_address' => isset($this->account['same_address']) ? 1 : 0
											]
										]);
										unset($this->account['same_address']);
										$this->upd([
											'type' => 'account',
											'data' => array_map(function($v) { return $v === '' ? null : $v; }, $this->account)
										]);
										/*$this->upd([
											'type' => 'address',
											'data' => array_map(function($v) { return $v === '' ? null : $v; }, $this->address)
										]);*/
										if(!empty($this->billing)) {
											$this->billing['id'] = $this->id_account_session;
											$this->billing['country_address'] = $this->country_billing;
											$this->billing['type_address'] = 'billing';
											$this->upd([
												'type' => 'address',
												'data' => array_map(function($v) { return $v === '' ? null : $v; }, $this->billing)
											]);
										}
										if(!empty($this->delivery)) {
											$this->delivery['id'] = $this->id_account_session;
											$this->delivery['country_address'] = $this->country_delivery;
											$this->delivery['type_address'] = 'delivery';
											$this->upd([
												'type' => 'address',
												'data' => array_map(function($v) { return $v === '' ? null : $v; }, $this->delivery)
											]);
										}
										$status = true;
										$notify = 'update';
									}
									$this->message->json_post_response($status,$notify);
								}
								elseif(!empty($this->tab)) {
									switch ($this->tab) {
										case 'accountConfig':
											$upd = true;
											$account = $this->getItems('account',$this->id_account_session,'one',false);
											$this->account['id_lang'] = $account['id_lang'];
											$this->account['active_ac'] = $account['active_ac'];
											$this->account['id'] = $this->id_account_session;

											if($this->account['email_ac'] !== $account['email_ac']) {
												$invalidmail = $this->getItems('searchmail',['email_ac' => $this->account['email_ac']],'one',false);
												if($invalidmail) { $upd = false; }
											}

											if($upd) {
												$this->send_email($account['email_ac'],'change_mail');
												$this->upd([
													'type' => 'accountConfig',
													'data' => $this->account
												]);
												$status = true;
												$notify = 'update';
												$this->session->logout();
											}
											break;
										case 'accountNotif':
											$alert = [
												'id' => $this->id_account_session,
												'weekly_alert' => (int)isset($this->alert['weekly_alert']),
												'monthly_alert' => (int)isset($this->alert['monthly_alert']),
												'overbid_alert' => (int)isset($this->alert['overbid_alert']),
												'endofsale_alert' => (int)isset($this->alert['endofsale_alert'])
											];
											if(!empty($this->mods)) {
												// --- Check the newsletter plugin
												foreach ($this->mods as $name => $mod) {
													if(property_exists($mod,'newsletter')) {
														if($account['alert']['weekly'] !== $alert['weekly_alert'] || $account['alert']['monthly'] !== $alert['monthly_alert']) {
															$this->template->addConfigFile([component_core_system::basePath().'/plugins/'.$name.'/i18n/'], ['public_local_']);
															$this->template->configLoad();

															if($account['alert']['weekly'] !== $alert['weekly_alert']) {
																$weekly_change = ($alert['weekly_alert'] > $account['alert']['weekly']) ? 'subscribe' : 'unsubscribe';
															}
															if($account['alert']['monthly'] !== $alert['monthly_alert']) {
																$monthly_change = ($alert['monthly_alert'] > $account['alert']['monthly']) ? 'subscribe' : 'unsubscribe';
															}

															if(isset($weekly_change) && isset($monthly_change) && $weekly_change === $monthly_change) {
																if(method_exists($mod,$weekly_change)) $mod->$weekly_change($account['email']);
															}
															else {
																if(isset($weekly_change) && method_exists($mod,$weekly_change)) $mod->$weekly_change($account['email'],(int)$this->template->getConfigVars('id_weekly_list'));
																if(isset($monthly_change) && method_exists($mod,$monthly_change)) $mod->$monthly_change($account['email'],(int)$this->template->getConfigVars('id_monthly_list'));
															}
														}
													}
												}
											}
											$this->upd([
												'type' => 'alert',
												'data' => $alert
											]);
											$status = true;
											$notify = 'update';
											break;
										case 'pwd':
											$account = $this->getItems('account',$this->id_account_session,'one',false);

											if(password_verify($this->account['old_passwd'],$account['passcrypt_ac'])) {
												if($this->account['new_passwd'] === $this->account['repeat_passwd']) {
													$this->upd([
														'type' => 'pwd',
														'data' => [
															'passcrypt_ac' => password_hash($this->account['new_passwd'], PASSWORD_DEFAULT),
															'id' => $this->id_account_session
														]
													]);
													$this->send_email($account['email_ac'],'change_pwd');
													$status = true;
													$notify = 'update';
												}
											}
											break;
										case 'image':
											if(isset($this->img) && !empty($this->img)) {
												$this->template->configLoad();
												$this->upload = new component_files_upload();
												$this->imagesComponent = new component_files_images($this->template);
												$resultUpload = $this->upload->setImageUpload(
													'img',
													[
														'name' => !empty($account['pseudo']) ? http_url::clean($account['pseudo']).'_'.filter_rsa::randMicroUI() : filter_rsa::randMicroUI(),
														'prefix_increment' => true,
														'prefix' => ['s_', 'm_', 'l_'],
														'module_img' => 'plugins',
														'attribute_img' => 'account',
														'original_remove' => false
													],
													['upload_root_dir' => 'upload/account', 'upload_dir' => $account['id']],
													false
												);
												$filename = $resultUpload['file'];
												if ($filename !== '') {
													$this->upd([
														'type' => 'img',
														'data' => [
															'id_account' => $account['id'],
															'img_ac' => $filename
														]
													]);
												}
												$status = true;
												$notify = 'update';
											}
											break;
										case 'socials':
											if(isset($this->socials)) {
												$this->socials['id_account'] = $this->id_account_session;
												$this->upd([
													'type' => 'socials',
													'data' => array_map(function($v) { return $v === '' ? null : $v; }, $this->socials)
												]);
											}
											$status = true;
											$notify = 'update';
											break;
									}
									$this->message->json_post_response($status,$notify);
								}
								break;
							case 'logout':
								$redirect = false;
								if($this->referrer !== '') {
									$uri = $this->referrer;
									foreach(['/account/','/subscription/','/quote/','/credit/','/sms/','radius='] as $a) {
										if (stripos($this->referrer,$a) !== false) {
											$uri = '/'.$this->lang.'/';
											break;
										}
									}
									$redirect = http_url::getUrl().$uri;
								}
								$this->session->logout($redirect);
								break;
						}
					}
				}
            }
            else {
                // logged out
                if(http_request::isMethod('GET')) {
                    $this->setBreadcrumb();

                    if (http_request::isGet('id')) $this->id = form_inputEscape::numeric($_GET['id']);
                    if (http_request::isGet('v_email')) $this->v_email = form_inputEscape::simpleClean($_GET['v_email']);
                    // --- Lost password
                    if (http_request::isGet('key')) $this->key = form_inputEscape::simpleClean($_GET['key']);

					switch ($this->action) {
						case 'access':
							$this->template->display('account/access.tpl');
							break;
						case 'login':
							$this->template->display('account/login.tpl');
							break;
						case 'signup':
							if(!empty($this->v_email)) {
								$d = $this->getItems('searchmail',array('email_ac' => $this->v_email),'one',false);
								print(!empty($d['email_ac']) ? "false" : "true");
							}
							else {
								$this->template->display('account/signup.tpl');
							}
							break;
						case 'activate':
							if($this->hash){
								$this->account = $this->getItems('accountFromKey',['keyuniqid' => $this->hash],'one',false);
								//var_dump($this->account);
								if(!empty($this->account)) {
									if($this->account['active_ac'] === 0) {
										$this->upd([
											'type' => 'activate',
											'data' => [
												'id' => $this->account['id_account']
											]
										]);
										$this->send_email($this->account['email_ac'],'activate',$this->account);
									}
									$this->template->display('account/activate.tpl');
								}
							}
							break;
						case 'rstpwd':
							if (isset($this->v_email)) {
								$d = $this->getItems('searchmail',['email_ac' => $this->v_email],'one',false);
								print(!empty($d['email_ac']) ? "true" : "false");
							}
							break;
						case 'newpwd':
							if(isset($this->key)){
								$this->account = $this->getItems('accountHashKey',['keyuniqid' => $this->hash, 'token' => $this->key],'one',false);

								if(!empty($this->account)){
									$this->account['newpwd'] = filter_rsa::randMicroUI();

									$this->upd([
										'type' => 'newPwd',
										'data' => [
											'newpwd' => password_hash($this->account['newpwd'], PASSWORD_DEFAULT),
											'id' => $this->account['id_account']
										]
									]);

									$this->send_email($this->account['email_ac'],'newpwd',$this->account);
									//$this->message->getNotify('pwd_changed',['method' => 'fetch','assignFetch' => 'message','template' => 'account/message.tpl']);
									$this->notify('success','pwd_changed',true);
								}
								else {
									//$this->message->getNotify('error_ticket',['method' => 'fetch','assignFetch' => 'message','template' => 'account/message.tpl']);
									$this->notify('error','error_ticket',true);
								}
								$this->template->display('account/newpwd.tpl');
							}
							break;
						default:
							header('location: ' . http_url::getUrl().'/'.$this->lang.'/');
							exit;
					}
                }
                elseif(http_request::isMethod('POST')) {
                    if (http_request::isPost('id')) $this->id = form_inputEscape::numeric($_POST['id']);
                    if (http_request::isPost('account')) $this->account = form_inputEscape::arrayClean($_POST['account']);
					if (http_request::isPost('billing')) $this->billing = form_inputEscape::arrayClean($_POST['billing']);
					if (http_request::isPost('delivery')) $this->delivery = form_inputEscape::arrayClean($_POST['delivery']);
					if (http_request::isPost('country_billing_id')) $this->country_billing = form_inputEscape::simpleClean($_POST['country_billing_id']);
					if (http_request::isPost('country_delivery_id')) $this->country_delivery = form_inputEscape::simpleClean($_POST['country_delivery_id']);

                    switch ($this->action) {
                        case 'login':
                            $connected = $this->session->authSession($this->account, http_request::isPost('stay_logged'));
                            if($connected === true) {
                                /*if(!headers_sent()) {
                                    //header('location: '. ($this->referrer !== null ? http_url::getUrl().(strpos($this->referrer,'account') !== false ? '/'.$this->lang.'/' : $this->referrer) : $this->seoURLProfil($this->lang, $urlparams)));
                                    header('location: '. ($this->referrer !== null ? http_url::getUrl().$this->referrer : $this->session->hashUrl()));
                                    exit();
                                }*/
                                $this->message->json_post_response(true,null);
                            }
                            else {
                                //$this->message->getNotify('error_connect',['method' => 'fetch', 'assignFetch' => 'message', 'template' => 'account/message.tpl']);
                                $this->message->json_post_response(false,'error_login');
                                //$this->message->getNotify('error_connect',['method' => 'fetch', 'assignFetch' => 'message', 'template' => 'account/message.tpl']);
                                //$this->message->getNotify('error_hash',['method' => 'fetch', 'assignFetch' => 'message']);
                            }
                            break;
                        case 'signup':
                            if (!empty($this->account)) {
                                // --- Check the required fields are filled
                                if(!$this->checkRequired()) {
                                    $this->notify('warning','empty');
                                    return;
                                }

                                // --- Check the google captcha if needed
                                if (key_exists('recaptcha',$this->mods) && $this->mods['recaptcha']->active && !$this->mods['recaptcha']->getRecaptcha()) {
                                    $this->notify('warning','captcha');
                                    return;
                                }

                                // --- Check if the email already exist
                                $vmail = $this->getItems('searchmail',['email_ac' => $this->account['email_ac']],'one',false);
                                if(!empty($vmail)) {
                                    $this->notify('warning','email_exist');
                                    return;
                                }

                                // --- Check if the password is correct
                                if($this->account['passwd'] !== $this->account['repeat_passwd']) {
                                    $this->notify('warning','pwd');
                                    return;
                                }

                                $this->account['passcrypt_ac'] = password_hash($this->account['passwd'], PASSWORD_DEFAULT);
                                $this->account['keyuniqid_ac'] = filter_rsa::uniqID();
                                $this->account['active_ac'] = 0;
                                $newsletter = isset($this->account['newsletter']);
                                $langData = $this->getItems('idFromIso',['iso' => $this->lang],'one',false);
                                $this->account['id_lang'] = $langData['id_lang'];
                                unset($this->account['newsletter']);
                                unset($this->account['passwd']);
                                unset($this->account['repeat_passwd']);

                                /*$account = $this->add([
                                    'type' => 'account',
                                    'data' => $this->account
                                ]);*/
                                $account = $this->add([
                                    'type' => 'account',
                                    'data' => array_map(function($v) { return $v === '' ? null : $v; }, $this->account)
                                ]);
                                $this->account['id_account'] = $account[0]['id_account'];

								if(!empty($this->billing)) {
									$this->billing['id'] = $this->account['id_account'];
									$this->billing['country_address'] = $this->country_billing;
									$this->billing['type_address'] = 'billing';
									$this->upd([
										'type' => 'address',
										'data' => array_map(function($v) { return $v === '' ? null : $v; }, $this->billing)
									]);
								}
								if(!empty($this->delivery)) {
									$this->delivery['id'] = $this->account['id_account'];
									$this->delivery['country_address'] = $this->country_delivery;
									$this->delivery['type_address'] = 'delivery';
									$this->upd([
										'type' => 'address',
										'data' => array_map(function($v) { return $v === '' ? null : $v; }, $this->delivery)
									]);
								}

                                if(!empty($this->mods)) {
                                    // --- Check the newsletter plugin
									foreach ($this->mods as $mod) {
										if(property_exists($mod,'newsletter') && method_exists($mod,'subscribe') && $newsletter) $mod->subscribe($this->account['email_ac'],$this->account['firstname_ac'],$this->account['lastname_ac']);
										if(method_exists($mod,'extendAccountSignup')) $mod->extendAccountSignup($this->account);
									}
                                }

                                $this->send_email($this->account['email_ac'],'signup',$this->account);

								$contact = new plugins_contact_public();
								$admins = $contact->getContact();
								if(!empty($admins)) {
									foreach ($admins as $admin) {
										$this->send_email($admin['mail_contact'],'admin',$this->account);
									}
								}

                                $this->notify('success','signup');
                            }
                            break;
                        case 'rstpwd':
                            $status = false;
                            $notify = 'empty';
                            if(isset($this->account) && !empty($this->account)) {
                                $notify = 'error_mail_account';
                                $this->account = $this->getItems('accountEmail',['email_ac' => $this->account['email_ac']],'one',false);

                                if(!empty($this->account)){
                                    $data['id'] = $this->account['id_account'];
                                    $this->account['token'] = $data['token'] = filter_rsa::randString(32);
                                    $this->upd([
                                        'type' => 'pwdTicket',
                                        'data' => $data
                                    ]);
                                    $this->send_email($this->account['email_ac'],'rstpwd',$this->account);
                                    $status = true;
                                    $notify = 'request';
                                }
                            }
                            $this->message->json_post_response($status,$notify);
                            break;
                    }
                }
            }
        }
        elseif($config['public']) {
			if (http_request::isGet('id')) $this->id = form_inputEscape::numeric($_GET['id']);
			if(isset($this->id)) {
				//@ToDo handle public page
				print 'page publique';
			}
			else {
				//@ToDo handle root public page
				print 'root publique';
			}
		}
		else {
			header('location: ' . http_url::getUrl().'/'.$this->lang.'/account/access/');
			exit;
        }
	}
}