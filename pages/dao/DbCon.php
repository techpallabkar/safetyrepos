<?php
/*
	namespace pages\dao;
*/
class DbCon {
	private $host 			= 'localhost';
	private $db_user 		= 'root';
	private $db_password            = 'root';
	private $db_name 		= 'db_CESC_Safety_Management';
	protected $db 			= null;
	
	public function __construct( $config = array() ) {
		if( !empty($config) ) {
			$this->host 		= isset( $config['host'] )          ? $config['host']       : $this->host;
			$this->db_user 		= isset( $config['db_user'] )       ? $config['db_user']    : $this->db_user;
			$this->db_password 	= isset( $config['db_password'] )   ? $config['db_password']: $this->db_password;
			$this->db_name 		= isset( $config['db_name'] )       ? $config['db_name']    : $this->db_name;
		} else {
			if( defined( 'DB_HOST' ) ) $this->host              = DB_HOST;
			if( defined( 'DB_USER' ) ) $this->db_user           = DB_USER;
			if( defined( 'DB_PASSWORD' ) ) $this->db_password   = DB_PASSWORD;
			if( defined( 'DB_NAME' ) ) $this->db_name           = DB_NAME;
		}
		$this->getConnection();
	}

	public function getConnection() {
		
		try
		{
			$this->db = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name, $this->db_user, $this->db_password);
			$this->db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
		}
		catch (PDOException $pe)
		{
			die("Could not connect to the database ".$this->db_name." :" . $pe->getMessage());
		}
	}
	
	public function getConnectionCescintranet()
	{
		$con;
		$host = 'cescintranet';
		$dbname = 'cescnet';
		$username = 'hrd';
		$password = 'hrd';

		try
		{
			$con = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
			//echo "Connected to $dbname at $host successfully.";
		}
		catch (PDOException $pe)
		{
			die("Could not connect to the database $dbname :" . $pe->getMessage());
		}
		return $con;
	}

        public function disconnect()
	{
		$this->db = null;
	}
}

?>
