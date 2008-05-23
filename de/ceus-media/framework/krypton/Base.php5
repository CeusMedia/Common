<?php
/**
 *	Base for all Applications.
 *	@package		framework.krypton
 *	@uses			StopWatch
 *	@uses			Database_PDO_Connection
 *	@uses			Net_HTTP_PartitionSession
 *	@uses			Net_HTTP_Request_Receiver
 *	@uses			File_Configuration_Reader
 *	@uses			Console_RequestReceiver
 *	@uses			Framework_Krypton_Core_Registry
 *	@uses			Framework_Krypton_Core_Messenger
 *	@uses			Framework_Krypton_Core_Language
 *	@uses			Framework_Krypton_Core_FormDefinitionReader
 *	@uses			Framework_Krypton_Core_PageController
 *	@uses			Logic_Authentication
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			01.02.2007
 *	@version		0.1
 */
/**
 *	Base for all Applications.
 *	@package		framework.krypton
 *	@uses			StopWatch
 *	@uses			Database_PDO_Connection
 *	@uses			Net_HTTP_PartitionSession
 *	@uses			Net_HTTP_Request_Response
 *	@uses			File_Configuration_Reader
 *	@uses			Console_RequestReceiver
 *	@uses			Framework_Krypton_Core_Registry
 *	@uses			Framework_Krypton_Core_Messenger
 *	@uses			Framework_Krypton_Core_Language
 *	@uses			Framework_Krypton_Core_FormDefinitionReader
 *	@uses			Framework_Krypton_Core_PageController
 *	@uses			Logic_Authentication
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			01.02.2007
 *	@version		0.1
 */
abstract class Framework_Krypton_Base
{
	/**	@var		string		$configFile		File Name of Base Configuration File */	
	public static $configFile	= "config.ini";
	/**	@var		string		$configPath		Path to Configuration Files */	
	public static $configPath	= "config/";
	/**	@var		string		$configFile		File Name of Base Configuration File */	
	public static $cachePath	= "contents/cache/";
	/**	@var		string		$configPath		Path to Configuration Files */	
	public static $dbLogPath	= "logs/database/";
	/**	@var		object		$registry		Instance of Framework_Krypton_Core_Registry */
	protected $registry		= null;

	/**
	 *	Constructor, sets up Environment.
	 *	@access		public
	 *	@param		string		$cachePath			Cache Path for basic Configuration Files
	 *	@return		void
	 */
	abstract function __construct();

	/**
	 *	Returns File Name for a Class Name.
	 *	@access		protected
	 *	@param		string		$className			Class Name to get File Name for
	 *	@param		string		$caseSensitive		Flag: sense Case (important on *nix Servers)
	 *	@param		string		$extension			Class File Extension, by default 'php5'
	 *	@return		string
	 */
	protected static function getPathNameOfClass( $className, $caseSensitive = TRUE )
	{
		if( !$caseSensitive )
			return str_replace( "_", ".", $className );
		$parts		= explode( "_", $className );
		$class		= array_pop( $parts );
		$parts		= array_map( 'strtolower', array_values( $parts ) );
		array_push( $parts, $class );
		$pathName	= implode( ".", $parts );
		return $pathName;
	}

	/**
	 *	Returns File Name for a Class Name.
	 *	@access		protected
	 *	@param		string		$className			Class Name to get File Name for
	 *	@param		string		$caseSensitive		Flag: sense Case (important on *nix Servers)
	 *	@param		string		$extension			Class File Extension, by default 'php5'
	 *	@return		string
	 */
	protected static function getFileNameOfClass( $className, $caseSensitive = TRUE, $extension = "php5" )
	{
		if( !$caseSensitive )
			return "classes/".str_replace( "_", "/", $className ).".".$extension;
		$parts		= explode( "_", $className );
		$class		= array_pop( $parts );
		$parts		= array_map( 'strtolower', array_values( $parts ) );
		array_push( $parts, $class );
		$path		= implode( "/", $parts );
		$fileName	= "classes/".$path.".".$extension;
		return $fileName;
	}

	/**
	 *	Sets up Authentication.
	 *	@access		protected
	 *	@return		void
	 */
	protected function initAuthentication()
	{
		import( 'classes.logic.Authentication' );
		$auth	= new Logic_Authentication();
		$this->registry->set( 'auth', $auth );
	}
	
	/**
	 *	Sets up Basic Configuration.
	 *	@access		protected
	 *	@return		void
	 */
	protected function initConfiguration()
	{
/*		$config	= parse_ini_file( self::$configPath.self::$configFile, TRUE );
		if( isset( $config['config.error_level'] ) )
			error_reporting( $config['config.error_level'] );
		$this->registry->set( "config", $config, TRUE );
*/		
		import( 'de.ceus-media.file.configuration.Reader' );
		$config	= new File_Configuration_Reader( self::$configPath.self::$configFile, self::$cachePath );
		if( $config->has( 'config.error_level' ) )
			error_reporting( $config->get( 'config.error_level' ) );
		$this->registry->set( "config", $config, TRUE );
	}

	/**
	 *	Sets up Cookie Support.
	 *	@access		protected
	 *	@return		void
	 */
	protected function initCookie()
	{
		import( 'de.ceus-media.net.http.PartitionCookie' );
		$config	=& $this->registry->get( 'config' );
		$cookie	= new Net_HTTP_PartitionCookie( $config['application.name'] );
		$this->registry->set( 'cookie', $cookie );
	}
	
	/**
	 *	Sets up Database Connection.
	 *	@access		protected
	 *	@return		void
	 */
	protected function initDatabase()
	{
		import( 'de.ceus-media.database.pdo.Connection' );
		$config	= $this->registry->get( 'config' );

		//  --  DATABASE OPTIONS  --  //
		foreach( $config['database.options'] as $key => $value )
			$options[constant( "PDO::".$key )]	= eval( "return ".$value.";" );

		//  --  DATA SOURCE NAME  --  //
		extract( $config['database.access'] );
		$dsn	= $type.":";
		switch( $type )
		{
			case 'pgsql':														//  PORT should be 5432
				$port	= isset( $port ) ? $port : 5432;
				$dsn	.= "host=".$hostname.";port=".$port.";dbname=".$database.";user=".$username.";password=".$password;
				break;
			case 'sqlite':
				$dsn	.= $database.".sqlite3";
				break;
			case 'mysql':
			case 'mssql':
			case 'sybase':
			case 'dblib':
			default:
				$dsn	.= "host=".$hostname.";dbname=".$database;
				break;
		}

		//  --  DATABASE CONNECTION  --  //
		$dbc	= new Database_PDO_Connection( $dsn, $username, $password, $options );
#		$dbc->setErrorLogFile( $dba['access']['logfile'] );
#		$dbc->setStatementLogFile( self::$dbLogPath."queries.log" );
		$dbc->setLogFile( $logfile );
		if( $config->has( 'database.access.errorLogFile' ) )
			$dbc->setLogFile( $errorLogFile );
		$dbc->setQueryLogFile( self::$dbLogPath."queries.log" );
		if( $config->has( 'database.access.statementLogFile' ) )
			$dbc->setLogFile( $statementLogFile );

		//  --  DATABASE ATTRIBUTES  --  //
		foreach( $config['database.attributes'] as $key => $value )
			$dbc->setAttribute( constant( "PDO::".$key ), eval( "return ".$value.";" ) );
		
		$config['config.table_prefix']	= $prefix;
		$this->registry->set( "dbc", $dbc, TRUE );
	}

	/**
	 *	Sets up basic Environment.
	 *	@access		protected
	 *	@return		void
	 */
	protected function initEnvironment()
	{
		import( 'de.ceus-media.StopWatch' );
		import( 'de.ceus-media.framework.krypton.core.Messenger' );
		$this->registry->set( "stopwatch", new StopWatch, TRUE );
		$this->registry->set( "messenger", new Framework_Krypton_Core_Messenger, TRUE );
	}

	/**
	 *	Sets up Form Definition Support.
	 *	@access		protected
	 *	@return		void
	 */
	protected function initFormDefinition()
	{
		import( 'de.ceus-media.framework.krypton.core.FormDefinitionReader' );
		$config		= $this->registry->get( "config" );
		$formPath	= $config['paths.forms'];
		$cachePath	= $config['paths.cache'].basename( $config['paths.forms'] )."/";
		$definition	= new Framework_Krypton_Core_FormDefinitionReader( $formPath, TRUE, $cachePath );
		$definition->setChannel( "html" );
		$this->registry->set( 'definition', $definition );
	}

	/**
	 *	Sets up Language Support.
	 *	@access		protected
	 *	@return		void
	 */
	protected function initLanguage( $identify = TRUE )
	{
		import( 'de.ceus-media.framework.krypton.core.Language' );
		$language	= new Framework_Krypton_Core_Language( $identify );
		$language->loadLanguage( 'main' );
		$this->registry->set( 'language', $language, TRUE );		
		
		import( 'de.ceus-media.framework.krypton.exception.Template' );
		import( 'de.ceus-media.framework.krypton.exception.SQL' );
		Framework_Krypton_Exception_Template::$exceptionMessage	 = $language->getWord( 'main', 'exceptions', 'template' );
		Framework_Krypton_Exception_Sql::$exceptionMessage	 = $language->getWord( 'main', 'exceptions', 'sql' );
	}

	/**
	 *	Sets up Page Controller.
	 *	@access		protected
	 *	@return		void
	 */
	protected function initPageController()
	{
		import( 'de.ceus-media.framework.krypton.core.PageController' );
		$controller	= new Framework_Krypton_Core_PageController( self::$configPath."pages.xml" );
		$this->registry->set( 'controller', $controller );
	}

	/**
	 *	Sets up Registry.
	 *	@access		protected
	 *	@return		void
	 */
	protected function initRegistry()
	{
		import( 'de.ceus-media.framework.krypton.core.Registry' );
		$this->registry	= Framework_Krypton_Core_Registry::getInstance();
	}

	/**
	 *	Sets up Request Handler.
	 *	@access		protected
	 *	@return		void
	 */
	protected function initRequest( $console = FALSE )
	{
		if( getEnv( 'HTTP_HOST' ) )
		{
			import( 'de.ceus-media.net.http.request.Receiver' );
			$request	= new Net_HTTP_Request_Receiver;
		}
		else
		{
			import( 'de.ceus-media.console.RequestReceiver' );
			$request	= new Console_RequestReceiver;
		}
		$this->registry->set( "request", $request );
	}

	/**
	 *	Sets up Service Client.
	 *	@access		protected
	 *	@return		void
	 */
	protected function initServiceClient()
	{
		if( !$this->registry->has( 'config' ) )
			throw new Exception( 'Configuration has not been set up.' );
		$config		= $this->registry->get( 'config' );
		$client	= new Service_Client( "", "logs/services.log" );
		$client->setHostAddress( $config['services.url'] );
		$client->setUserAgent( "Motrada Office" );
		if( $config['services.username'] )
			$client->setBasicAuth( $config['services.username'], $config['services.password'] );
		$this->registry->set( 'client', $client );
	}

	/**
	 *	Sets up Request Handler.
	 *	@access		protected
	 *	@return		void
	 */
	protected function initSession()
	{
		import( 'de.ceus-media.net.http.PartitionSession' );
		if( !$this->registry->has( 'config' ) )
			throw new Exception( 'Configuration has not been set up.' );
		$config		= $this->registry->get( 'config' );
		$session	= new Net_HTTP_PartitionSession( $config['application.name'], $config['config.session_name'] );
		$this->registry->set( "session", $session );
	}

	/**
	 *	Sets up Theme Support.
	 *	@access		protected
	 *	@return		void
	 */
	protected function initThemeSupport()
	{
		if( !$this->registry->has( 'config' ) )
			throw new Exception( 'Configuration has not been set up.' );
		if( !$this->registry->has( 'request' ) )
			throw new Exception( 'Request Handler has not been set up.' );
		if( !$this->registry->has( 'session' ) )
			throw new Exception( 'Session Support has not been set up.' );

		$config		= $this->registry->get( "config" );
		$request	= $this->registry->get( "request" );
		$session	= $this->registry->get( "session" );

		if( $config['layout.switchable_themes'] )
		{
			if( $request->has( 'switchThemeTo' ) )
				$session->set( 'theme', $request->get( 'switchThemeTo' ) );
			if( $session->get( 'theme' ) )
				$config['layout.theme'] =  $session->get( 'theme' );
			else
				$session->set( 'theme', $config['layout.theme'] );
		}
		else
			$session->set( 'theme', $config['layout.theme'] );
	}

	protected function logRemarks( $output )
	{
		import( 'de.ceus-media.file.log.Writer' );
		$request		= $this->registry->get( "request" );
		if( !$output )
			return 0;
		$ip		= getEnv( 'REMOTE_ADDR' );
		$data	= array(
			'ip'		=> $ip,
			'agent'		=> getEnv( 'HTTP_USER_AGENT' ),
			'uri'		=> getEnv( 'REQUEST_URI' ),
			'referrer'	=> getEnv( 'HTTP_REFERER' ),
			'remarks'	=> $output,
			'request'	=> $request->getAll(),
		);
		$data	= base64_encode( serialize( $data ) );
		$log	= new File_Log_Writer( "logs/dev/".$ip.".log" );
		$log->note( $data, FALSE );
	}
}
?>