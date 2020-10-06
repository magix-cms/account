<?php
class model_session extends plugins_account_db {
	private $httpSession,$setting,$settings;

	/**
	 * backend_model_session constructor.
	 */
	public function __construct($t = null)
	{
		$this->setting = new frontend_model_setting($t);
		$ssl = $this->setting->getSetting('ssl');
		$this->httpSession = new http_session($ssl['value']);
	}

	/**
	 * clean old register 2 days
	 * @access private
	 * @return void
	 * @param $data
	 */
	private function cleanOldSession($data) {
		//On supprime les enregistrements de plus de deux jours
		$date = new DateTime('NOW');
		$date->modify('-1 day');
		$limit = $date->format('Y-m-d H:i:s');
		parent::delete(array('type'=>'lastSession'),array('limit'=>$limit,'id_account'=>$data['id_account']));
	}

	/**
	 * Open session
	 * @param $userid
	 * @return true
	 */
	public function openSession($data){
		parent::delete(
			array(
				'type' => 'currentSession'
			),
			array(
				'id_account' => $data['id_account']
			)
		);
		$this->cleanOldSession(array('id_account'=>$data['id_account']));
		//On ajoute un nouvel identifiant de session dans la table
		parent::insert(
			array(
				'type'=>'session'
			),
			array(
				'id_session'  	  => $data['id_session'],
				'id_account'      => $data['id_account'],
				'expires' 		  => $data['expires'] ? $data['expires'] : null,
				'ip_session'      => $this->httpSession->getIp(),
				'browser_session' => $this->httpSession->getBrowser(),
				'keyuniqid_ac' 	  => $data['keyuniqid_ac']
			)
		);
		return true;
	}

	/**
	 * @param bool $connexion
	 */
	public function redirect($connexion=false){
		if($connexion){
			if (!headers_sent()) {
				header('location: '.http_url::getUrl().'/admin/index.php?controller=dashboard');
				exit;
			}
		}else{
			if (!headers_sent()) {
				header('location: '.http_url::getUrl().'/admin/index.php?controller=login');
				exit;
			}
		}
	}

	/**
	 * close session
	 * @return void
	 */
	public function closeSession() {
		parent::delete(array('type'=>'session'),array('id_session'=>session_id()));
	}

	/**
	 * Compare la session avec une entrée session mysql
	 * @return void
	 */
	public function compareSessionId(){
		return parent::fetchData(
			array(
				'context' => 'one',
				'type' => 'session'
			)
			,array(
				'id_session' => session_id()
			)
		);
	}
}