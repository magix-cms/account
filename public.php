<?php
require_once __DIR__ . '/recaptcha/autoload.php';
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
 * @category   advantage
 * @package    plugins
 * @copyright  MAGIX CMS Copyright (c) 2008 - 2018 Gerits Aurelien,
 * http://www.magix-cms.com,  http://www.magix-cjquery.com
 * @license    Dual licensed under the MIT or GPL Version 3 licenses.
 * @version    2.0
 * Author: Salvatore Di Salvo
 * Date: 17-12-15
 * Time: 10:38
 * @name plugins_advantage_public
 * Le plugin advantage
 */
class plugins_account_public extends plugins_account_db
{
	/**
	 * @var object
	 */
    protected $template,
		$data,
		$header,
		$message,
		$lang,
		$session,
		$settings,
		$module,
		$activeMods,
		$about,
		$sanitize,
		$modelDomain,
		$mail;

	/**
	 * @var array $config
	 */
    private $config;

	/**
	 * Session var
	 * @var string $email_session
	 * @var string $keyuniqid_session
	 * @var integer $id_account_session
	 */
	private $email_session,
		$keyuniqid_session,
		$id_account_session;

	/**
	 * Les variables globales
	 * @var integer $edit
	 * @var string $action
	 * @var string $tabs
	 */
	public $edit = 0,
		$action = '',
		$hash = '',
		$key = '',
		$tab = '';

	/**
	 * Les variables plugin
	 * @var array $v_email
	 * @var array $account
	 * @var array $address
	 * @var string $gRecaptchaResponse
	 */
    public $v_email,
		$account,
		$address,
		$socials,
		$gRecaptchaResponse;

    // public $name_rcp,$img_rcp,$level_rcp,$preparation_rcp,$number_rcp,$budget_rcp,$content_rcp,$statut_rcp;
    // public $name_ing;
    // public $period_sub,$amount_sub;

    public function __construct(){
        $this->template = new frontend_model_template();
		$this->session = new http_session();
		$this->data = new frontend_model_data($this);
		$this->header = new http_header();
		$this->message = new component_core_message($this->template);
		$this->lang = $this->template->currentLanguage();
		$this->sanitize = new filter_sanitize();
		$this->mail = new mail_swift('mail');
		$this->modelDomain = new frontend_model_domain($this->template);
		$this->settings = new frontend_model_setting();

		$this->session->start('mc_account');
		$this->session->token('token_ac');

		/*if(class_exists('plugins_profil_module')) {
			$this->module = new plugins_profil_module();
		}*/

		$formClean = new form_inputEscape();

		// --- Session
		if(http_request::isSession('email_ac')){
			$this->email_session = $formClean->simpleClean($_SESSION['email_ac']);
		}
		if(http_request::isSession('keyuniqid_ac')){
			$this->keyuniqid_session = $formClean->simpleClean($_SESSION['keyuniqid_ac']);
		}
		if(http_request::isSession('id_account')){
			$this->id_account_session = (int)$formClean->simpleClean($_SESSION['id_account']);
		}

		// --- Get
		if (http_request::isGet('action')) {
			$this->action = $formClean->simpleClean($_GET['action']);
		}
		if (http_request::isGet('hash')) {
			$this->hash = $formClean->simpleClean($_GET['hash']);
		}
		if (http_request::isGet('tab')) {
			$this->tab = $formClean->simpleClean($_GET['tab']);
		}

		if (http_request::isGet('v_email')) {
			$this->v_email = $formClean->simpleClean($_GET['v_email']);
		}

		// --- Post
		if (http_request::isPost('account')) {
			$this->account = (array)$formClean->arrayClean($_POST['account']);
		}
		if (http_request::isPost('address')) {
			$this->address = (array)$formClean->arrayClean($_POST['address']);
		}
		if (http_request::isPost('socials')) {
			$this->socials = (array)$formClean->arrayClean($_POST['socials']);
		}
		// - Google reCaptcha
		if(http_request::isPost('g-recaptcha-response')){
			$this->gRecaptchaResponse = $formClean->simpleClean($_POST['g-recaptcha-response']);
		}

        // --- Lost password
		if (http_request::isGet('key')) {
			$this->key = $formClean->simpleClean($_GET['key']);
		}
        /*if(magixcjquery_filter_request::isGet('lostpassword')){
            $this->lostpassword = magixcjquery_form_helpersforms::inputClean($_GET['lostpassword']);
        }*/

        // Recipes
        /*if(magixcjquery_filter_request::isGet('json')){
            $this->json = magixcjquery_form_helpersforms::inputClean($_GET['json']);
        }
        if(magixcjquery_filter_request::isPost('name_rcp')){
            $this->name_rcp = magixcjquery_form_helpersforms::inputClean($_POST['name_rcp']);
        }
        if(isset($_FILES['img_rcp']["name"])){
            $this->img_rcp = magixcjquery_url_clean::rplMagixString($_FILES['img_rcp']["name"]);
        }
        if(magixcjquery_filter_request::isPost('preparation_rcp')){
            $this->preparation_rcp = magixcjquery_form_helpersforms::inputClean($_POST['preparation_rcp']);
        }
        if(magixcjquery_filter_request::isPost('number_rcp')){
            $this->number_rcp = magixcjquery_form_helpersforms::inputClean($_POST['number_rcp']);
        }
        if(magixcjquery_filter_request::isPost('budget_rcp')){
            $this->budget_rcp = magixcjquery_form_helpersforms::inputClean($_POST['budget_rcp']);
        }
        if(magixcjquery_filter_request::isPost('preparation_rcp')){
            $this->preparation_rcp = magixcjquery_form_helpersforms::inputClean($_POST['preparation_rcp']);
        }
        if(magixcjquery_filter_request::isPost('level_rcp')){
            $this->level_rcp = magixcjquery_form_helpersforms::inputClean($_POST['level_rcp']);
        }
        if(magixcjquery_filter_request::isPost('content_rcp')){
            $this->content_rcp = magixcjquery_form_helpersforms::inputClean($_POST['content_rcp']);
        }
        if(magixcjquery_filter_request::isPost('statut_rcp')){
            $this->statut_rcp = magixcjquery_form_helpersforms::inputClean($_POST['statut_rcp']);
        }
        if(magixcjquery_filter_request::isPost('name_ing')){
            $this->name_ing = magixcjquery_form_helpersforms::inputClean($_POST['name_ing']);
        }
        if(isset($_FILES['img_rcp']["name"])){
            $this->img_rcp = magixcjquery_url_clean::rplMagixString($_FILES['img_rcp']["name"]);
        }
        //PRO
        if(magixcjquery_filter_request::isPost('period_sub')){
            $this->period_sub = magixcjquery_form_helpersforms::inputClean($_POST['period_sub']);
        }
        if(magixcjquery_filter_request::isPost('amount_sub')){
            $this->amount_sub = magixcjquery_form_helpersforms::inputClean($_POST['amount_sub']);
        }*/
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
	 *
	 */
	public function getform()
	{
		$this->template->configLoad();
		return $this->template->fetch('forms/order.tpl','profil');
    }

    // --- Session
    /**
     * clean old register 2 days
     * @access private
     * @return void
     */
    private function dbClean() {
        //On supprime les enregistrements de plus de deux jours
        $date = new DateTime('NOW');
        $date->modify('-1 day');
        $limit = $date->format('Y-m-d H:i:s');
        $this->del(array(
        	'type' => 'lastSessions',
			'data' => array(
				'limit' => $limit
			)
		));
    }

	/**
	 * close session
	 * @return void
	 */
	private function closeSession() {
		$this->del(array(
			'type' => 'session',
			'data' => array(
				'id_session' => session_id()
			)
		));
	}

    /**
     * Open session
     * @param $session_id
     * @param $id_account
     * @param $keyuniqid
     * @internal param $userid
     * @return true
     */
    private function openSession($session_id, $id_account, $keyuniqid) {
        $this->del(array(
        	'type' => 'currentSession',
			'data' => array(
				'id_account' => $id_account
			)
		));
        $this->dbClean();
		$this->add(array(
			'type' => 'session',
			'data' => array(
				'id_session' => $session_id,
				'id_account' => $id_account,
				'keyuniqid_ac' => $keyuniqid,
				'ip_session' => $this->session->ip(),
				'browser_session' => $this->session->browser()
			)
		));

		return true;
    }

	/**
	 * @return mixed
	 */
    private function compareSessionId() {
        return $this->getItems('session',array('id_session' => session_id()),'one',false);
    }

    /**
     * Réécriture des url pour la connexion des membres
     * @static
     * @param $lang
     * @param array $options
     * options = edit : root, config, ask, immo, subscription, capaigns, statistics
     * options = level : login, newlogin, logout
     * @return string
     * @example
     * Classic URL
     * $options = array('level'=>'login');
     * EDIT URL
     * $options = array('hashuri'=>'fgdg4d564gdfg456dg5d4fgd56','edit'=>'config');
     * plugins_member_public::seoURLProfil('fr',$options)
     */
    public static function seoURLProfil($lang,array $options) {
		try {
			if(is_array($options)){
				$baseurl = '/'.$lang.'/account/';
				if(array_key_exists('hashuri',$options)) {
					if($options['hashuri'] != null) {
						$basedit = $baseurl.$options['hashuri'].'/';
						if(array_key_exists('action',$options)) {
							switch($options['action']) {
								case 'root':
									$uri = $basedit;
									break;
								case 'logout':
									$uri = $basedit.'logout/';
									break;
								default:
									$uri = $basedit;
									break;
							}
						}
					} else {
						throw new Exception('Error seoURLProfil in Hashuri is null');
					}
				} else {
					switch($options['level']) {
						case 'login':
							$uri = $baseurl.'login/';
							break;
						case 'signup':
							$uri = $baseurl.'signup/';
							break;
						case 'activate':
							$uri = $baseurl.'activate/';
							break;
						case 'newlogin':
							$uri = $baseurl.'newlogin/';
							break;
						default:
							$uri = $baseurl;
							break;
					}
				}
				if($lang != null) {
					return $uri;
				} else {
					throw new Exception('Error seoURLProfil in language is not found');
				}
			} else {
				throw new Exception('Error seoURLProfil in params is not array');
			}
		} catch(Exception $e) {
			$logger = new debug_logger(MP_LOG_DIR);
			$logger->log('php', 'error', 'An error has occured : '.$e->getMessage(), debug_logger::LOG_MONTH);
		}
    }

	/**
	 * Sécurise l'espace membre
	 * @param bool $debug
	 */
    public function securePage($debug = false){
        $array_sess = array(
			'id_account'   =>  $_SESSION['id_account'],
			'keyuniqid_ac' =>  $_SESSION['keyuniqid_ac'],
			'email_ac'     =>  $_SESSION['email_ac']
		);
		$this->session->run($array_sess);

		if($debug) $this->session->debug();
    }

	/**
	 * @access private
	 * Système de session pour la connexion
	 */
	private function authSession() {
		$agtoken = $this->session->token('token_ac');
		$this->template->assign('hashpass',$agtoken);

		if ( !empty($this->account) ) {
			if( strcasecmp($this->account['hashtoken'], $agtoken) == 0 ) {
				$account = $this->getItems('pwdcryptEmail',array('email_ac' => $this->account['email_ac']),'one',false);

				if(password_verify($this->account['passwd_ac'], $account['passcrypt_ac'])) {
					$authExist = $this->getItems('auth', array('email_ac' => $this->account['email_ac'],'passwd_ac' => $account['passcrypt_ac']), 'one',false);

					if (count($authExist['id_account'])) {
						$account = $this->getItems('accountEmail',array('email_ac' => $this->account['email_ac']),'one',false);

						$this->session->regenerate();

						$this->openSession(session_id(), $account['id_account'], $account['keyuniqid_ac']);

						$array_sess = array(
							'id_account'   => $account['id_account'],
							'email_ac'     => $this->account['email_ac'],
							'keyuniqid_ac' => $account['keyuniqid_ac']
						);
						$this->session->run($array_sess);

						$current = $this->getItems('accountSession',array('keyuniqid_ac' => $_SESSION['keyuniqid_ac']),'one',false);

						$urlparams = array(
							'hashuri' => filter_rsa::hashEncode('sha1',$current['id_session']),
							'action'  => 'root'
						);

						if(!headers_sent()) {
							header('location: '. $this->seoURLProfil($this->lang, $urlparams));
							exit();
						}
					}
				}
				else {
					$this->message->getNotify('error_connect',array('method' => 'fetch', 'assignFetch' => 'message', 'template' => 'account/message.tpl'));
				}
			}
			else {
				$this->message->getNotify('error_hash',array('method' => 'fetch', 'assignFetch' => 'message'));
			}
		}
	}

	/**
	 * Ferme la session et supprime les cookies
	 * @param bool $redirect
	 */
    private function closeCurrentSession($redirect = true){
		if (isset($_SESSION['email_ac']) AND isset($_SESSION['keyuniqid_ac'])){
			$this->closeSession();
			$this->session->close('mc_account');
			if($redirect) header('location: '. $this->seoURLProfil($this->lang, array('level'=>'login'))); exit;
		}
    }

    /**
     * Retourne L'url de l'espace membre ou du login suivant la session
     * @return string
     */
    public function hashUrl() {
        if(isset($this->keyuniqid_session)) {
			$current_uri = $this->getItems('accountSession',array('keyuniqid_ac' => $_SESSION['keyuniqid_ac']),'one',false);
			$urlparams = array(
            	'hashuri' => filter_rsa::hashEncode('sha1',$current_uri['id_session']),
				'action'  => 'root'
			);
        }
        else {
			$urlparams = array('level'=>'login');
        }
        return $this->seoURLProfil($this->template->currentLanguage(), $urlparams);
    }

	/**
	 * Get account data
	 */
	public function accountData()
	{
		if(isset($this->id_account_session))
			return $this->getItems('account',$this->id_account_session,'one',false);
		else
			return null;
    }
	// --------------------

    // --- Signup
	/**
	 * Check if required field are filled
	 * @return bool
	 */
    private function checkRequired() {
        $data_validate = array(
			'account' => array(
				'firstname_ac',
				'lastname_ac',
				'email_ac',
				'passwd'
			),
			'cond_gen'
        );

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
            else {
                return true;
            }
        }
    }
    // --------------------

    // --- Google Recaptcha
	/**
	 * @return bool
	 */
    private function getRecaptcha(){
        if($this->config['google_recaptcha'] == '1') {
            if (isset($this->gRecaptchaResponse)) {
                // If the form submission includes the "g-captcha-response" field
                // Create an instance of the service using your secret
                $recaptcha = new \ReCaptcha\ReCaptcha($this->config['recaptchaSecret']);
                // Make the call to verify the response and also pass the user's IP address
                $resp = $recaptcha->verify($this->gRecaptchaResponse, $_SERVER['REMOTE_ADDR']);
                if ($resp->isSuccess()) {
                    return true;
                } else {
                    return false;
                }
            }
        }
    }
	// --------------------

    // --- Mail
	/**
	 * @param $type
	 * @return string
	 */
	private function setTitleMail($type){
		$about = new frontend_model_about($this->template);
		$collection = $about->getCompanyData();

		switch ($type) {
			default: $title = $this->template->getConfigVars($type.'_title');
		}

		return sprintf($title, $collection['name']);
	}

	/**
	 * @param string $tpl
	 * @param bool $debug
	 * @return string
	 */
	private function getBodyMail($tpl, $debug = false){
		$cssInliner = $this->settings->getSetting('css_inliner');
		$this->template->assign('getDataCSSIColor',$this->settings->fetchCSSIColor());

		$data = array();

		switch ($tpl) {
			default: $data = $this->account;
		}

		if(!empty($data)) $this->template->assign('data',$data);

		$bodyMail = $this->template->fetch('account/mail/'.$tpl.'.tpl');
		if ($cssInliner['value']) {
			$bodyMail = $this->mail->plugin_css_inliner($bodyMail,array(component_core_system::basePath().'skin/'.$this->template->themeSelected().'/mail/css' => 'mail.css'));
		}

		if($debug) {
			print $bodyMail;
		}
		else {
			return $bodyMail;
		}
	}

	/**
	 * Send a mail
	 * @param $email
	 * @param $tpl
	 * @return bool
	 */
	protected function send_email($email, $tpl) {
		if($email) {
			$this->template->configLoad();
			if(!$this->sanitize->mail($email)) {
				$this->message->json_post_response(false,'error_mail');
			}
			else {
				if($this->lang) {
					$noreply = '';

					$allowed_hosts = array_map(function($dom) { return $dom['url_domain']; },$this->modelDomain->getValidDomains());
					if (!isset($_SERVER['HTTP_HOST']) || !in_array($_SERVER['HTTP_HOST'], $allowed_hosts)) {
						header($_SERVER['SERVER_PROTOCOL'].' 400 Bad Request');
						exit;
					}
					else {
						$noreply = 'noreply@'.str_replace('www.','',$_SERVER['HTTP_HOST']);
					}

					if(!empty($noreply)) {

						$message = $this->mail->body_mail(
							self::setTitleMail($tpl),
							array($noreply),
							array($email),
							self::getBodyMail($tpl),
							false
						);

						if($this->mail->batch_send_mail($message)) {
							return true;
						}
						else {
							$this->message->json_post_response(false,'error');
							return false;
						}
					}
					else {
						$this->message->json_post_response(false,'error_config');
						return false;
					}
				}
			}
		}
	}
	// --------------------

	// --- Database actions
	/**
	 * Insert data
	 * @param array $config
	 */
	private function add($config)
	{
		switch ($config['type']) {
			case 'account':
			case 'session':
				parent::insert(
					array('type' => $config['type']),
					$config['data']
				);
				break;
		}
	}

	/**
	 * Insert data
	 * @param array $config
	 */
	private function upd($config)
	{
		switch ($config['type']) {
			case 'account':
			case 'accountConfig':
			case 'pwd':
			case 'pwdTicket':
			case 'newPwd':
			case 'address':
			case 'activate':
				parent::update(
					array('type' => $config['type']),
					$config['data']
				);
				break;
		}
	}

	/**
	 * Delete data
	 * @param array $config
	 */
	private function del($config)
	{
		switch ($config['type']) {
			case 'session':
			case 'lastSessions':
			case 'currentSession':
				parent::delete(
					array('type' => $config['type']),
					$config['data']
				);
				break;
		}
    }
	// --------------------

    /**
     * Execute le plugin dans la partie public
     */
    public function run() {
		$this->securePage(false);
		/*if(isset($this->module)) {
			$this->activeMods = $this->module->load_module(false);
		}*/
		$breadplugin = array();
		$breadplugin[] = array('name' => $this->template->getConfigVars('account'));
		$this->config = $this->getItems('config',null,'one');

		if(isset($this->keyuniqid_session)){
			if (!isset($_SESSION["email_ac"]) || empty($_SESSION['email_ac'])) {
				if (!isset($this->email_ac)) {
					if (!headers_sent()) {
						header('location: '. $this->seoURLProfil($this->lang, array('level'=>'login')));
						exit;
					}
				}
			}
			elseif (!$this->compareSessionId()) {
				if (!headers_sent()) {
					header('location: '. $this->seoURLProfil($this->lang, array('level'=>'login')));
					exit;
				}
			}

			$current = $this->getItems('accountSession',array('keyuniqid_ac' => $_SESSION['keyuniqid_ac']),'one',false);
			$hash = filter_rsa::hashEncode('sha1',$current['id_session']);

			if(!$this->action) {
				if(!empty($current)) {
					$urlparams = array(
						'hashuri' => $hash,
						'action'  => 'root'
					);

					if(!headers_sent()) {
						header('location: '. $this->seoURLProfil($this->lang, $urlparams));
						exit;
					}
				}
				else {
					$this->closeCurrentSession();
				}
			}
			elseif($this->action && $this->action === $hash) {
				$this->template->assign('breadplugin', $breadplugin);
				$this->template->display('account/index.tpl');
			}
			else {
				switch ($this->action) {
					case 'infos':
						if(isset($this->account)) {
							$status = false;
							$notify = 'error';

							if(!empty($this->account)) {
								$this->account['id'] = $this->address['id'] = $this->socials['id'] = $this->id_account_session;

								$this->upd(array(
									'type' => 'account',
									'data' => array_map(function($v) { return $v === '' ? null : $v; }, $this->account)
								));
								$this->upd(array(
									'type' => 'address',
									'data' => array_map(function($v) { return $v === '' ? null : $v; }, $this->address)
								));
								if(isset($this->socials)) {
									$this->upd(array(
										'type' => 'socials',
										'data' => array_map(function($v) { return $v === '' ? null : $v; }, $this->socials)
									));
								}
								$status = true;
								$notify = 'update';
							}
							$this->message->json_post_response($status,$notify);
						}
						else {
							$this->getItems('account',$this->id_account_session,'one');
							$country = new component_collections_country();
							$this->template->assign('countries',$country->getCountries());
							$breadplugin[0] = array('url' => $this->hashUrl(), 'name' => $this->template->getConfigVars('account'), 'title' => $this->template->getConfigVars('account'));
							$breadplugin[1] = array('name' => $this->template->getConfigVars('global'));
							$this->template->assign('breadplugin', $breadplugin);
							$this->template->display('account/edit/account.tpl');
						}
						break;
					case 'config':
						$status = false;
						$notify = 'error';
						if (isset($this->v_email)) {
							$d = $this->getItems('searchmail',array('email_ac' => $this->v_email),'one',false);
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
										$invalidmail = $this->getItems('searchmail',array('email_ac' => $this->account['email_ac']),'one',false);

										if($invalidmail) { $upd = false; }
									}

									if($upd) {
										$this->send_email($account['email_ac'],'change_mail');

										$this->upd(array(
											'type' => 'accountConfig',
											'data' => $this->account
										));

										$status = true;
										$notify = 'update';

										$this->closeCurrentSession(false);
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

											$this->send_email($account['email_ac'],'change_pwd');

											$status = true;
											$notify = 'update';
										}
									}
									break;
							}
							$this->message->json_post_response($status,$notify);
						}
						else {
							$this->getItems('account',$this->id_account_session,'one');
							$breadplugin[0] = array('url' => $this->hashUrl(), 'name' => $this->template->getConfigVars('account'), 'title' => $this->template->getConfigVars('account'));
							$breadplugin[1] = array('name' => $this->template->getConfigVars('account_config'));
							$this->template->assign('breadplugin', $breadplugin);
							$this->template->display('account/edit/config.tpl');
						}
						break;
					case 'logout':
						$this->closeCurrentSession();
						break;
					/*case 'order':
						$cartpay = new plugins_cartpay_public();
						$this->template->assign('getCartData',$cartpay->setProfilOrder($_SESSION['idprofil']));
						$this->template->display('order.tpl');
						break;*/
					default:
						if(!empty($current)) {
							$urlparams = array(
								'hashuri' => $hash,
								'action'  => 'root'
							);

							if(!headers_sent()) {
								header('location: '. $this->seoURLProfil($this->lang, $urlparams));
								exit;
							}
						}
						else {
							$this->closeCurrentSession();
						}
				}
			}
        }
        else {
			if($this->action) {
				switch ($this->action) {
					case 'signup':
						$breadplugin[0] = array('name' => $this->template->getConfigVars('signup'));

						if (isset($this->v_email)) {
							$d = $this->getItems('searchmail',array('email_ac' => $this->v_email),'one',false);
							print(!empty($d['email_ac']) ? "false" : "true");
						}
						elseif (is_array($this->account) && !empty($this->account)) {
							// --- Check the required fields are filled
							if(!$this->checkRequired()) {
								$this->message->json_post_response(true,'empty');
								return;
							}

							// --- Check if the email already exist
							$vmail = $this->getItems('verify_email',array('email_ac' => $this->account['email_ac']),'one',false);

							if($vmail != null) {
								$this->message->json_post_response(true,'error_email_exist',null,array('template' => 'account/message.tpl'));
								return;
							}

							// --- Check if the password is correct
							if($this->account['passwd'] !== $this->account['repeat_passwd']) {
								$this->message->json_post_response(true,'error_pwd',null,array('template' => 'account/message.tpl'));
								return;
							}

							// --- Check the google captcha if needed
							if($this->config['google_recaptcha']) {
								if (!$this->getRecaptcha()) {
									$this->message->json_post_response(true,'error_captcha',null,array('template' => 'account/message.tpl'));
									return;
								}
							}

							$this->account['passcrypt_ac'] = password_hash($this->account['passwd'], PASSWORD_DEFAULT);
							$this->account['keyuniqid_ac'] = filter_rsa::uniqID();
							$this->account['active_ac'] = 0;
							$langData = $this->getItems('idFromIso',array('iso' => $this->lang),'one',false);
							$this->account['id_lang'] = $langData['id_lang'];
							unset($this->account['passwd']);
							unset($this->account['repeat_passwd']);

							$this->add(array(
									'type' => 'account',
									'data' => $this->account
								)
							);

							// --- Check the newsletter plugin
							if(!empty($this->activeMods)) {
								foreach ($this->activeMods as $name => $mod) {
									if(property_exists($mod,'newsletter')) {
										if (isset($this->account['newsletter']) && $this->account['newsletter'] == "on") {
											if(method_exists($mod,'subscribe')) {
												$mod->subscribe($this->account['email_ac'], null, null, false);
											}
										}
									};
								}
							}

							if($this->send_email($this->account['email_ac'],'signup')) {
								$this->message->json_post_response(true,'signup',null,array('template' => 'account/message.tpl'));
							}
						}
						else {
							$newsletter = false;
							if(!empty($this->activeMods)) {
								foreach ($this->activeMods as $name => $mod) {
									if(property_exists($mod,'newsletter')) $newsletter = $mod->newsletter;
								}
							}
							$this->template->assign('breadplugin', $breadplugin);
							$this->template->assign('newsletter', $newsletter);
							$this->template->display('account/signup.tpl');
						}
						break;
					case 'activate':
						if($this->hash){
							$this->account = $this->getItems('accountFromKey',array('keyuniqid' => $this->hash),'one',false);
							if(!empty($this->account)) {
								$this->upd(array(
									'type' => 'activate',
									'data' => array(
										'id' => $this->account['id_account']
									)
								));

								$this->send_email($this->account['email_ac'],'activate');

								$breadplugin[0] = array('name' => $this->template->getConfigVars('activate'));
								$this->template->assign('breadplugin', $breadplugin);
								$this->template->display('account/activate.tpl');
							}
						}
						break;
					case 'login':
						$this->authSession();
						$breadplugin[0] = array('name' => $this->template->getConfigVars('login'));
						$this->template->assign('breadplugin', $breadplugin);
						$this->template->display('account/login.tpl');
						break;
					case 'rstpwd':
						if (isset($this->v_email)) {
							$d = $this->getItems('searchmail',array('email_ac' => $this->v_email),'one',false);
							print(!empty($d['email_ac']) ? "true" : "false");
						}
						else {
							$status = false;
							$notify = 'empty';
							if(isset($this->account) && !empty($this->account)) {
								$notify = 'error_mail_account';
								$this->account = $this->getItems('accountEmail',array('email_ac' => $this->account['email_ac']),'one',false);

								if(!empty($this->account)){
									$data['id'] = $this->account['id_account'];
									$this->account['token'] = $data['token'] = filter_rsa::randString(32);
									$this->upd(array(
										'type' => 'pwdTicket',
										'data' => $data
									));
									$this->send_email($this->account['email_ac'],'rstpwd');
									$status = true;
									$notify = 'update';
								}
							}
							$this->message->json_post_response($status,$notify);
						}
						break;
					case 'newpwd':
						if(isset($this->key)){
							$this->account = $this->getItems('accountHashKey',array('keyuniqid' => $this->hash, 'token' => $this->key),'one',false);

							if(!empty($this->account)){
								$this->account['newpwd'] = filter_rsa::randMicroUI();

								$this->upd(array(
									'type' => 'newPwd',
									'data' => array(
										'newpwd' => password_hash($this->account['newpwd'], PASSWORD_DEFAULT),
										'id' => $this->account['id_account']
									)
								));

								$this->send_email($this->account['email_ac'],'newpwd');
								$this->message->getNotify('pwd_changed',array('method' => 'fetch','assignFetch' => 'message','template' => 'account/message.tpl'));
							}
							else {
								$this->message->getNotify('error_ticket',array('method' => 'fetch','assignFetch' => 'message','template' => 'account/message.tpl'));
							}
							$this->template->display('account/newpwd.tpl');
						}
						break;
				}
			}
		}
    }
}