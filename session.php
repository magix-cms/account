<?php
require_once('model/session/Session.php');
require_once('model/account/Account.php');
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
 * @category plugins
 * @package account_session
 * @copyright MAGIX CMS Copyright (c) 2008 - 2018 Gerits Aurelien, http://www.magix-cms.com,  http://www.magix-cjquery.com
 * @license Dual licensed under the MIT or GPL Version 3 licenses.
 * @version 1.0
 * @author: Salvatore Di Salvo
 * @name plugins_account_session
 */
class plugins_account_session extends plugins_account_db {
	/**
	 * @var frontend_model_template $template
	 * @var frontend_model_data $data
	 * @var frontend_model_module $module
	 * @var frontend_model_setting $settings
	 * @var http_session $httpSession
	 * @var Session $session
	 */
	protected frontend_model_template $template;
	protected frontend_model_data $data;
	protected frontend_model_module $module;
	protected frontend_model_setting $settings;
	protected http_session $httpSession;
	protected Session $session;

    /**
     * @var bool $ssl
     */
    private bool $ssl;

    /**
     * @var bool $logged
     */
    protected bool $logged = false;

    /**
     * @var array $mods
     * @var array $accountData
     */
    protected array
		$mods,
		$accountData;

    /**
     * @var int $id
     * @var int $id_account
     */
    public int
        $id,
        $id_account;

    /**
     * @var string $email
     * @var string $keyuniqid
     */
    public string
        $email,
        $keyuniqid;

    /**
     * @var array $current
     */
    public array $current;

	/**
	 * @param frontend_model_template|null $t
	 */
	public function __construct(frontend_model_template $t = null) {
        $this->template = $t instanceof frontend_model_template ? $t : new frontend_model_template();
        $this->data = new frontend_model_data($this, $this->template);
        //$this->settings = new frontend_model_setting();
        //$this->ssl = (bool)$this->settings->getSetting('ssl')['value'];
        $this->httpSession = new http_session($this->template->ssl);
        $this->session = new Session($this->template->ssl);

        $this->securePage();

        // --- Session
        $this->email = http_request::isSession('email_ac') ? form_inputEscape::simpleClean($_SESSION['email_ac']) : '';
        if(http_request::isSession('keyuniqid_ac')) $this->keyuniqid = form_inputEscape::simpleClean($_SESSION['keyuniqid_ac']);
        if(http_request::isSession('id_account')) $this->id_account = (int)form_inputEscape::simpleClean($_SESSION['id_account']);
	}

    /**
     * Assign data to the defined variable or return the data
     * @param string $type
     * @param string|int|null $id
     * @param null|string $context
     * @param bool|string $assign
     * @return mixed
     */
    private function getItems(string $type, $id = null, string $context = null) {
        return $this->data->getItems($type, $id, $context, false);
    }

	/**
	 *
	 */
	private function loadModules() {
		if(!isset($this->module)) $this->module = new frontend_model_module($this->template);
		if(empty($this->mods)) $this->mods = $this->module->load_module('account');
	}

    /**
     * @return void
     */
    private function securePage() {
        $ssid = $this->httpSession->start('mc_account');
        $this->httpSession->token('token_ac');
        $cparams = session_get_cookie_params();

        $array_sess = [
            'id_account'   => $_SESSION['id_account'] ?? null,
            'keyuniqid_ac' => $_SESSION['keyuniqid_ac'] ?? null,
            'email_ac'     => $_SESSION['email_ac'] ?? null
        ];
        $this->httpSession->run($array_sess);

        if(isset($_SESSION['keyuniqid_ac']) && !empty($_SESSION['keyuniqid_ac'])) {
            $this->current = $this->getItems('accountSession',['keyuniqid_ac' => $_SESSION['keyuniqid_ac']],'one');
            $hash = $_SESSION['keyuniqid_ac'].$ssid;
            if(isset($_COOKIE[$hash])) {
                $array_sess = [
                    'id_account' => $_SESSION['id_account'],
                    'keyuniqid_ac' => $_SESSION['keyuniqid_ac'],
                    'email_ac' => $_SESSION['email_ac']
                ];
                setcookie('mc_account',$ssid,$_COOKIE[$hash],'/',$cparams['domain'], $this->ssl, true);
                $this->httpSession->run($array_sess);
            }
        }
        else {
            $sess = $this->getItems('session',['id_session' => $ssid],'one');
            if($sess) {
                $account = $this->getItems('account_from_key',['keyuniqid_admin' => $sess['keyuniqid_admin']],'one');

                $this->httpSession->regenerate($sess['expires']);
                $this->session->openSession([
                    'id_account' => $_SESSION['id_account'],
                    'keyuniqid_ac' => $_SESSION['keyuniqid_ac'],
                    'email_ac' => $_SESSION['email_ac'],
                    'expires' => $sess['expires']
                ]);
                $hash = $account['keyuniqid_ac'].session_id();
                setcookie($hash,$sess['expires'],0,'/',$cparams['domain'], $this->ssl, true);

                $array_sess = [
                    'id_account' => $_SESSION['id_account'],
                    'keyuniqid_ac' => $_SESSION['keyuniqid_ac'],
                    'email_ac' => $_SESSION['email_ac']
                ];
                $this->httpSession->run($array_sess);
            }
        }

        if(isset($_SESSION['keyuniqid_ac']) && isset($_SESSION["email_ac"]) && !empty($_SESSION['email_ac']) && $this->session->compareSessionId()) $this->logged = true;

		/*if(
			!$this->logged &&
			isset($_GET['controller']) &&
			((!in_array($_GET['controller'],['home','account','about','pages'])) ||
			($_GET['controller'] === 'account' && (!isset($_GET['action']) || !in_array($_GET['action'],['access','login','signup','rstpwd','newpwd']))))
		) {
			$uri = '/';
			if(isset($_GET['strLangue'])) $uri .= $_GET['strLangue'].'/';
			header('location: '.http_url::getUrl().$uri);
			exit;
		}*/
		/*if(!$this->logged && isset($_GET['controller']) && form_inputEscape::simpleClean($_GET['controller']) !== 'account') {
			$uri = '/';
			if(isset($_GET['strLangue'])) $uri .= $_GET['strLangue'].'/';
			header('location: '.http_url::getUrl().$uri);
			exit;
		}*/
    }

    /**
     * @access private
     * Système de session pour la connexion
	 * @return bool
     */
    public function authSession(array $post, bool $stay_logged = false): bool {
        $agtoken = $this->hashpass();
        $this->template->assign('hashpass',$agtoken);

        if (!empty($post)) {
            if( strcasecmp($post['hashtoken'], $agtoken) == 0 ) {
                $account = $this->getItems('pwdcryptEmail',['email_ac' => $post['email_ac']],'one');

                if(password_verify($post['passwd_ac'], $account['passcrypt_ac'])) {
                    $authExist = $this->getItems('auth', ['email_ac' => $post['email_ac'],'passwd_ac' => $account['passcrypt_ac']], 'one');

                    if (!empty($authExist['id_account'])) {
                        $account = $this->getItems('accountEmail',['email_ac' => $post['email_ac']],'one');

                        $expires = $stay_logged ? strtotime("+13 month") : 0;
                        $this->httpSession->regenerate($expires);
                        if($expires) {
                            $hash = $account['keyuniqid_ac'].session_id();
                            $cparams = session_get_cookie_params();
                            setcookie($hash,$expires,0,'/',$cparams['domain'], $this->ssl, true);
                        }

                        $this->session->openSession([
                            'id_account' => $account['id_account'],
                            'id_session' => session_id(),
                            'keyuniqid_ac' => $account['keyuniqid_ac'],
                            'expires' => ($stay_logged ? $expires : null)
                        ]);

                        $array_sess = [
                            'id_account'   => $account['id_account'],
                            'email_ac'     => $post['email_ac'],
                            'keyuniqid_ac' => $account['keyuniqid_ac']
                        ];
                        $this->httpSession->run($array_sess);

                        $this->current = $this->getItems('accountSession',['keyuniqid_ac' => $_SESSION['keyuniqid_ac']],'one');
                        return true;
                    }
                }
            }
        }
        return false;
    }

    /**
     * @return string
     */
    public function hashpass(): string {
        return $this->httpSession->token('token_ac');
    }

    /**
     * Retourne L'url de l'espace membre ou du login suivant la session
     * @return string
     */
    public function hashUrl(): string {
        $url = http_url::getUrl().'/'.$this->template->lang.($this->template->is_amp() ? '/amp/':'/');
        if(isset($this->current) && $this->current['id_session'] !== '') $url = $url.'account/'.filter_rsa::hashEncode('sha1',$this->current['id_session']).'/';
        return $url;
    }

	/**
	 * @return array
	 */
	public function getAccountConfig(): array {
		$config = $this->getItems('config',null,'one',false);
		$this->loadModules();
		if(!empty($this->mods)) {
			$plugin = new frontend_model_plugins();
			if(key_exists('recaptcha',$this->mods)) {
				$config['recaptcha'] = $plugin->isInstalled('recaptcha');
			}
			foreach ($this->mods as $name => $mod) {
				if(property_exists($mod,'newsletter')) $config['newsletter'] = true;
			}
		}
		return $config;
	}

	/**
	 * @return array
	 */
	public function getTabs(): array {
		$tabs = [];

		//$current = null;
		if($this->logged) {
			//$current = $this->getItems('accountSession', ['keyuniqid_ac' => $this->keyuniqid_session], 'one', false);
			if (isset($this->current) && !empty($this->current)) {
				$this->hash = filter_rsa::hashEncode('sha1', $this->current['id_session']);
				$this->loadModules();
				if(!empty($this->mods)) {
					foreach ($this->mods as $name => $mod){
						if(method_exists($mod,'getTabLink')) {
							$tabs[$name] = $mod->getTabLink($this->template, $this->hash);
						}
					}
				}
			}
		}

		return $tabs;
	}

    /**
     * Get account data
     * @return array
     */
    public function accountData() {
        $this->accountData = [];
        if(isset($this->id_account)) {
            $account = new Account($this->id_account);
            $this->accountData = $account->getAccountData();
        }
        return $this->accountData;
    }

    /**
     * @return bool
     */
    public function isLogged() {
        return $this->logged;
    }

	/**
	 * @param false|string $redirect
	 */
	public function logout($redirect = false){
		if (isset($_SESSION['email_ac']) AND isset($_SESSION['keyuniqid_ac'])){
			$this->session->closeSession();
			$this->httpSession->close('mc_account');
			//setcookie('pushModalClosed', '', time() - 3600, '/');
			if($redirect) {
				header('location: ' . $redirect);
				exit;
			}
		}
	}
}