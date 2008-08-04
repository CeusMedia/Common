<?php
/**
 *	Builder for Data Source Name Strings.
 *	@package		database.pdo
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			02.08.2008
 *	@version		0.1
 */
/**
 *	Builder for Data Source Name Strings.
 *	@package		database.pdo
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			02.08.2008
 *	@version		0.1
 */
class DataSourceName
{
	/**	@var		string		$type			Database Type */
	protected $type;
	/**	@var		string		$database		Database Name */
	protected $database;
	/**	@var		string		$username		Database Username */
	protected $username	;
	/**	@var		string		$password		Database Password */
	protected $password;
	/**	@var		string		$hostname		Host Name */
	protected $hostname;
	/**	@var		int			$port			Host Port */
	protected $port;

	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		string		$type			Database Type (mysql|mssql|pgsql|sqlite|sybyse|dblib)
	 *	@param		string		$hostname		Host Name
	 *	@param		int			$port			Host Port
	 *	@param		string		$database		Database Name
	 *	@param		string		$username		Username
	 *	@param		string		$password		Password
	 *	@return		void
	 */
	public function __construct( $type, $hostname = NULL, $port = NULL, $database, $username = NULL, $password = NULL )
	{
		$this->type		= strtolower( $type );
		$this->hostname	= $hostname;
		$this->port		= $port;
		$this->database	= $database;
		$this->username	= $username;
		$this->password	= $password;
	}

	/**
	 *	Returns Data Source Name String.
	 *	@access		public
	 *	@param		string		$type			Database Type (mysql|mssql|pgsql|sqlite|sybyse|dblib)
	 *	@param		string		$hostname		Host Name
	 *	@param		int			$port			Host Port
	 *	@param		string		$database		Database Name
	 *	@param		string		$username		Username
	 *	@param		string		$password		Password
	 *	@return		string
	 */
	public static function getDSN( $type, $hostname = NULL, $port = NULL, $database, $username = NULL, $password = NULL )
	{
		$dsn	= new DataSourceName( $type, $hostname, $port, $database, $username, $password );
		return $dsn->__toString();
	}

	/**
	 *	Converts DSN Object into a String.
	 *	@access		public
	 *	@return		string
	 */
	public function __toString()
	{
		$dsn	= $this->type.":";
		switch( $this->type )
		{
			case 'pgsql':														//  PORT should be 5432
				$port	= isset( $this->port ) ? $this->port : 5432;
				$dsn	.= "host=".$this->hostname.";port=".$this->port.";dbname=".$this->database.";user=".$this->username.";password=".$this->password;
				break;
			case 'sqlite':
				$dsn	.= $this->database.".sqlite3";
				break;
			case 'mysql':
			case 'mssql':
			case 'sybase':
			case 'dblib':
			default:
				$dsn	.= "host=".$this->hostname.";dbname=".$this->database;
				break; 
		}
		return $dsn;
	}
}
?>