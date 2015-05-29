<?php
require_once('./Services/Logging/classes/class.ilLog.php');

/**
 * Class exodLog
 *
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 * @version 1.0.0
 */
class exodLog extends ilLog {

	const OD_LOG = 'od.log';
	/**
	 * @var exodLog
	 */
	protected static $instance;


	/**
	 * @return exodLog
	 */
	public static function getInstance() {
		if (! isset(self::$instance)) {
			self::$instance = new self(ILIAS_LOG_DIR, self::OD_LOG);
		}

		return self::$instance;
	}


	/**
	 * @param      $a_msg
	 * @param null $a_log_level
	 */
	function write($a_msg, $a_log_level = NULL) {
		parent::write($a_msg, $a_log_level); // TODO: Change the autogenerated stub
	}


	/**
	 * @return mixed
	 */
	public function getLogDir() {
		return ILIAS_LOG_DIR;
	}


	/**
	 * @return string
	 */
	public function getLogFile() {
		return self::OD_LOG;
	}


	/**
	 * @return string
	 */
	public static function getFullPath() {
		$log = self::getInstance();

		return $log->getLogDir() . '/' . $log->getLogFile();
	}
}

?>
