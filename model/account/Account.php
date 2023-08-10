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
 * @package Account
 * @copyright MAGIX CMS Copyright (c) 2008 - 2018 Gerits Aurelien, http://www.magix-cms.com,  http://www.magix-cjquery.com
 * @license Dual licensed under the MIT or GPL Version 3 licenses.
 * @version 1.0
 * @author: Salvatore Di Salvo
 * @name Account
 */
class Account extends AccountData {
    /**
	 * @var frontend_model_template $template
     * @var frontend_model_data $data
     * @var frontend_model_module $module
     * @var frontend_model_seo $seo
     * @var component_files_images $imagesComponent
     * @var frontend_model_logo $logo
     */
    protected frontend_model_template $template;
    protected frontend_model_data $data;
	protected frontend_model_module $module;
    protected frontend_model_seo $seo;
    protected component_files_images $imagesComponent;
    protected frontend_model_logo $logo;

    /**
     * @var string $lang
     */
    protected string $lang;

	/**
	 * @var array $mods
	 */
	protected array $mods;

    /**
     * @var int $edit
     * @var int $id
     * @var int $id_img
     */
    public int
        $edit = 0,
        $id,
        $id_img;

    /**
     * @var string $action
     * @var string $hash
     * @var string $key
     * @var string $tab
     */
    public string
        $action = '',
        $hash = '',
        $key = '',
        $tab = '';

    /**
     * Les variables plugin
     * @var array $fetchConfig
     * @var array $imagePlaceHolder
     * @var array $imgPrefix
     * @var array $account
	 * @var array $accountData
     * @var array $address
     */
    public array
        $fetchConfig,
        $imagePlaceHolder,
		$imgPrefix,
        $account,
        $accountData,
        $address;

    /**
     * Account constructor.
     * @param int $id
     */
    public function __construct(int $id) {
        $this->template = new frontend_model_template();
        $this->lang = $this->template->lang;
        $this->data = new frontend_model_data($this);
        $this->seo = new frontend_model_seo('account', '', '',$this->template);
		$this->id = $id;
        $this->setAccountData($id);
		$this->loadModules();
    }

    /**
     * Assign data to the defined variable or return the data
     * @param string $type
     * @param string|int|null $id
     * @param null|string $context
     * @param bool|string $assign
     * @return mixed
     */
    private function getItems(string $type, $id = null, string $context = null, $assign = true) {
        return $this->data->getItems($type, $id, $context, $assign);
    }

	/**
	 * Load account modules
	 */
	private function loadModules() {
		if(!isset($this->module)) $this->module = new frontend_model_module($this->template);
		if(empty($this->mods)) $this->mods = $this->module->load_module('account');
	}

	/**
	 * @return void
	 */
	private function initImageComponents() {
		if(!isset($this->imagesComponent)) $this->imagesComponent = new component_files_images($this->template);
		if(!isset($this->logo)) $this->logo = new frontend_model_logo();
		if(empty($this->imagePlaceHolder)) $this->imagePlaceHolder = $this->logo->getImagePlaceholder();
		if(empty($this->imgPrefix)) $this->imgPrefix = $this->imagesComponent->prefix();
		$this->fetchConfig = $this->imagesComponent->getConfigItems([
			'module_img' => 'plugins',
			'attribute_img' => 'account'
		]);
	}

	/**
	 * @param array $account
	 * @return array
	 */
	public function setImageData(array $account): array {
		$img = [];
		if(!empty($account)) {
			$this->initImageComponents();
			$extwebp = 'webp';

			if(!empty($account['img_ac'])) {
				// # return filename without extension
				$pathinfo = pathinfo($account['img_ac']);
				$filename = $pathinfo['filename'];
				$url = component_core_system::basePath();
				foreach ($this->fetchConfig as $key => $value) {
					if(file_exists($url.'/upload/account/'.$account['id_account'].'/'.$this->imgPrefix[$value['type_img']].$account['img_ac'])) {
						$imginfo = $this->imagesComponent->getImageInfos($url.'/upload/account/'.$account['id_account'].'/'.$this->imgPrefix[$value['type_img']].$account['img_ac']);
						$img[$value['type_img']]['src'] = '/upload/account/'.$account['id_account'].'/'.$this->imgPrefix[$value['type_img']].$account['img_ac'];
						$img[$value['type_img']]['src_webp'] = '/upload/account/'.$account['id_account'].'/'.$this->imgPrefix[$value['type_img']].$filename.'.'.$extwebp;
						$img[$value['type_img']]['w'] = $value['resize_img'] === 'basic' ? $imginfo['width'] : $value['width_img'];
						$img[$value['type_img']]['h'] = $value['resize_img'] === 'basic' ? $imginfo['height'] : $value['height_img'];
						$img[$value['type_img']]['crop'] = $value['resize_img'];
						$img[$value['type_img']]['ext'] = mime_content_type($url.'/upload/account/'.$account['id_account'].'/'.$this->imgPrefix[$value['type_img']].$account['img_ac']);
					}
				}
				$img['name'] = $account['img_ac'];
			}
		}

		return $img;
	}

    /**
     * @param int $id
     */
    private function setAccountData(int $id) {
		$this->loadModules();
        //$account = $this->getItems('account',$id,'one',false);
		$account = $this->getItems('account',$id,'one',false);
		$billing_address = $this->getItems('billing_address',$id,'one',false);
		$delivery_address =  $this->getItems('delivery_address',$id,'one',false);
		if(!empty($delivery_address)) {
			$delivery = [
				'street' => $delivery_address['street_address'],
				'postcode' => $delivery_address['postcode_address'],
				'town' => $delivery_address['city_address'],
				'country' => $delivery_address['country_address']
			];
		}
		else {
			$delivery = [];
		}
		if(!empty($billing_address)) {
			$billing = [
				'street' => $billing_address['street_address'],
				'postcode' => $billing_address['postcode_address'],
				'town' => $billing_address['city_address'],
				'country' => $billing_address['country_address']
			];
		}
		else {
			$billing = [];
		}

		/*$required = ['firstname_ac','lastname_ac','address','id_country'];
		$account['missingInfos'] = false;
		foreach ($required as $r) {
			if(!isset($account[$r])) $account['missingInfos'] = true;
		}*/

        if(!empty($account)) {
            $this->accountData = [
                'hash' => $account['keyuniqid_ac'].session_id(),
                'level' => 0,
                'id' => $account['id_account'],
                'email' => $account['email_ac'],
                'pseudo' => $account['pseudo_ac'],
                'firstname' => $account['firstname_ac'],
                'lastname' => $account['lastname_ac'],
                'birthdate' => $account['birthdate_ac'],
                'phone' => $account['phone_ac'],
                'company' => $account['company_ac'],
                'vat' => $account['vat_ac'],
                /*'address' => [
                    'address' => $account['address'],
                    'postcode' => $account['postcode_tn'],
                    'town' => $account['name_tn'],
                    'country' => $account['name_country'],
                    //'id_tn' => $account['id_tn'],
                    'id_country' => $account['id_country'],
                    //'lat' => $account['lat'],
                    //'lon' => $account['lon']
                ],*/
                'address' => [
					'billing' => $delivery,
					'delivery' => $billing
				],
                'active' => $account['active_ac'],
                //'missingInfos' => $account['missingInfos']
            ];

			if($account['img_ac'] !== null) {
				$this->accountData['img'] = $this->setImageData($account);
			}

            //$required = ['firstname_ac','lastname_ac','street_address','postcode_address','city_address','country_address','vat_ac','company_ac'];
            //if(key_exists('credit',$this->mods)) $this->getCreditBalance();
            //if(key_exists('subscription',$this->mods)) $this->getAccessLevel();

			foreach($this->mods as $name => $mod) {
				if(method_exists($mod,'extendAccountdata')) $this->accountData = array_merge($this->accountData,$mod->extendAccountdata($account['id_account']));
				if(property_exists($mod,'newsletter') && method_exists($mod,'isSuscribed')) {
					$this->template->addConfigFile([component_core_system::basePath().'/plugins/'.$name.'/i18n/'], ['public_local_']);
					$this->template->configLoad();

					$weekly_active = (int)$mod->isSuscribed($this->accountData['email'],(int)$this->template->getConfigVars('id_weekly_list'));
					$monthly_active = (int)$mod->isSuscribed($this->accountData['email'],(int)$this->template->getConfigVars('id_monthly_list'));

					if($this->accountData['alert']['weekly'] !== $weekly_active || $this->accountData['alert']['monthly'] !== $monthly_active) {
						$this->upd([
							'type' => 'alert',
							'data' => [
								'id' => $this->id,
								'weekly_alert' => $weekly_active,
								'monthly_alert' => $monthly_active,
								'overbid_alert' => $this->accountData['alert']['overbid'],
								'endofsale_alert' => $this->accountData['alert']['endofsale']
							]
						]);
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
    private function add(array $config) {
        switch ($config['type']) {
            case 'data':
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
            case 'alert':
                parent::update(
                    ['type' => $config['type']],
                    $config['data']
                );
                break;
        }
    }

    /**
     * Delete data
     * @param array $config
     */
    private function del(array $config) {
        switch ($config['type']) {
            case 'data':
                parent::delete(
                    ['type' => $config['type']],
                    $config['data']
                );
                break;
        }
    }
    // --------------------

    // --- Methods
    /**
     * @return array
     */
    public function getAccountData(): array {
        return $this->accountData;
    }
    // ---

	// --- Plugins
	/**
	 * @param $amount
	 */
	public function addPoints($amount) {
		$this->upd([
			'type' => 'accountSms',
			'data' => [
				'id_account' => $this->accountData['id'],
				'sms_ac' => $this->accountData['sms'] + $amount
			]
		]);
	}

	/**
	 * @param $amount
	 */
	public function removePoint($amount) {
		$this->upd([
			'type' => 'accountSms',
			'data' => [
				'id_account' => $this->accountData['id'],
				'sms_ac' => $this->accountData['sms'] - $amount
			]
		]);
	}

	/**
	 * @return mixed
	 */
	public function getPointBalance() {
		$balance = $this->getItems('pointBalance',$this->id,'one',false);
		return $balance['balance'];
	}
}