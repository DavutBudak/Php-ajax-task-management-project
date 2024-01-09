<?php
class Database 
{
	private static $dbName = 'davut_calender';
	private static $dbHost = 'localhost';
	private static $dbUsername = 'davut_calender';
	private static $dbUserPassword = 'Pass@!21Clicks';
	private static $cont = null;

	public function __construct() {
		exit('Init function is not allowed');
	}

	public static function connect() {
		// One connection through the whole application
		if (null == self::$cont) {
			try {
				$options = array(
    PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8mb4',
					PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
				);
				self::$cont =  new PDO("mysql:host=" . self::$dbHost . ";" . "dbname=" . self::$dbName, self::$dbUsername, self::$dbUserPassword, $options);
			} catch (PDOException $e) {
				die($e->getMessage());
			}
		}
		return self::$cont;
	}

	public static function disconnect() {
		self::$cont = null;
	}
}
?>