<?php
require_once('./Customizing/global/plugins/Modules/Cloud/CloudHook/OneDrive/classes/Client/class.exodClientFactory.php');

/**
 * Class exodApp
 *
 * @author Fabian Schmid <fs@studer-raimann.ch>
 */
abstract class exodApp {

	const TYPE_BUSINESS = 1;
	const TYPE_PUBLIC = 2;
	const RESP_TYPE_CODE = 'code';
	/**
	 * @var string
	 */

	protected $base_url = '';
	/**
	 * @var string
	 */
	protected $auth_url = '';
	/**
	 * @var string
	 */
	protected $token_url = '';
	/**
	 * @var string
	 */
	protected $client_id = '***REMOVED***';
	/**
	 * @var string
	 */
	protected $response_type = self::RESP_TYPE_CODE;
	/**
	 * @var string
	 */
	protected $redirect_uri = 'https://rel44.local/od_oauth.php';
	//	protected $redirect_uri = '***REMOVED***';
	/**
	 * @var string
	 */
	protected $client_secret = '***REMOVED***';
	/**
	 * @var string
	 */
	protected $ressource_uri = '';
	/**
	 * @var string
	 */
	protected $ressource = '';
	/**
	 * @var int
	 */
	protected $type = self::TYPE_BUSINESS;
	/**
	 * @var exodAuth
	 */
	protected $exod_auth;
	/**
	 * @var exodClientBusiness
	 */
	protected $exod_client;
	/**
	 * @var exodBearerToken
	 */
	protected $exod_bearer_token;
	/**
	 * @var int
	 */
	protected $il_one_drive;
	protected static $instance;
	/**
	 * @return exodApp
	 */
	//	abstract public static function getInstance();

	/**
	 * @param exodBearerToken $exod_bearer_token
	 * @param                 $client_id
	 * @param                 $client_secret
	 */
	protected function __construct(exodBearerToken $exod_bearer_token, $client_id, $client_secret) {
		$this->client_id = $client_id;
		$this->client_secret = $client_secret;
		$this->exod_bearer_token = $exod_bearer_token;
		$this->buildURLs();
		$this->init();
	}


	abstract public function buildURLs();


	protected function init() {
		switch ($this->getType()) {
			case self::TYPE_BUSINESS:
				$exodAuth = new exodAuth($this);
				$exodClient = new exodClientBusiness($this);
				break;
			case self::TYPE_PUBLIC:
				// TODO
				break;
		}
		$this->setExodAuth($exodAuth);
		$this->setExodClient($exodClient);
		// $this->checkAndRefreshToken();
	}


	/**
	 * @throws Exception
	 */
	public function checkAndRefreshToken() {
		if ($this->exod_bearer_token->refresh($this->getExodAuth())) {
			return true;
		}

		return false;
	}


	/**
	 * @return ilOneDrive
	 */
	public function getIlonedrive() {
		return $this->il_one_drive;
	}


	/**
	 * @param ilOneDrive $il_one_drive
	 */
	public function setIlonedrive(ilOneDrive $il_one_drive) {
		$this->il_one_drive = $il_one_drive;
	}


	/**
	 * @return exodAuth
	 */
	public function getExodAuth() {
		return $this->exod_auth;
	}


	/**
	 * @param exodAuth $exod_auth
	 */
	public function setExodAuth($exod_auth) {
		$this->exod_auth = $exod_auth;
	}


	/**
	 * @return exodClientBusiness
	 */
	public function getExodClient() {
		return $this->exod_client;
	}


	/**
	 * @param exodClientBusiness $exod_client
	 */
	public function setExodClient($exod_client) {
		$this->exod_client = $exod_client;
	}


	/**
	 * @return string
	 */
	public function getRedirectUri() {
		return $this->redirect_uri;
	}


	/**
	 * @param string $redirect_uri
	 */
	public function setRedirectUri($redirect_uri) {
		$this->redirect_uri = $redirect_uri;
	}


	/**
	 * @return string
	 */
	public function getBaseUrl() {
		return $this->base_url;
	}


	/**
	 * @param string $base_url
	 */
	public function setBaseUrl($base_url) {
		$this->base_url = $base_url;
	}


	/**
	 * @return string
	 */
	public function getAuthUrl() {
		return $this->auth_url;
	}


	/**
	 * @param string $auth_url
	 */
	public function setAuthUrl($auth_url) {
		$this->auth_url = $auth_url;
	}


	/**
	 * @return string
	 */
	public function getTokenUrl() {
		return $this->token_url;
	}


	/**
	 * @param string $token_url
	 */
	public function setTokenUrl($token_url) {
		$this->token_url = $token_url;
	}


	/**
	 * @return string
	 */
	public function getClientId() {
		return $this->client_id;
	}


	/**
	 * @param string $client_id
	 */
	public function setClientId($client_id) {
		$this->client_id = $client_id;
	}


	/**
	 * @return string
	 */
	public function getResponseType() {
		return $this->response_type;
	}


	/**
	 * @param string $response_type
	 */
	public function setResponseType($response_type) {
		$this->response_type = $response_type;
	}


	/**
	 * @return string
	 */
	public function getClientSecret() {
		return $this->client_secret;
	}


	/**
	 * @param string $client_secret
	 */
	public function setClientSecret($client_secret) {
		$this->client_secret = $client_secret;
	}


	/**
	 * @return string
	 */
	public function getRessourceUri() {
		return $this->ressource_uri;
	}


	/**
	 * @param string $ressource_uri
	 */
	public function setRessourceUri($ressource_uri) {
		$this->ressource_uri = $ressource_uri;
	}


	/**
	 * @return string
	 */
	public function getRessource() {
		return $this->ressource;
	}


	/**
	 * @param string $ressource
	 */
	public function setRessource($ressource) {
		$this->ressource = $ressource;
	}


	/**
	 * @return int
	 */
	public function getType() {
		return $this->type;
	}


	/**
	 * @param int $type
	 */
	public function setType($type) {
		$this->type = $type;
	}


	/**
	 * @return exodBearerToken
	 */
	public function getExodBearerToken() {
		return $this->exod_bearer_token;
	}


	/**
	 * @param exodBearerToken $exod_bearer_token
	 */
	public function setExodBearerToken($exod_bearer_token) {
		$this->exod_bearer_token = $exod_bearer_token;
	}
}


?>