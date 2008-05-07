<?php
/**
 *	Base for all Applications.
 *	@package		framework.krypton
 *	@uses			StopWatch
 *	@uses			Database_PDO_Connection
 *	@uses			Net_HTTP_PartitionSession
 *	@uses			Net_HTTP_Request_Receiver
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
	/**	@var	Core_Registry	$registry		Instance of Framework_Krypton_Core_Registry */
	protected $registry		= null;

	public static $databaseLogPath	= "logs/database/";

	public static $configPath		= "config/";

	/**
	 *	Constructor, sets up Environment.
	 *	@access		public
	 *	@param		string		$configPath			Path to basic Configuration Files
	 *	@return		void
	 */
	abstract function __construct( $configPath = "config/" );

	/**
	 *	Returns File Name for a Class Name.
	 *	@access		protected
	 *	@param		string		$className			Class Name to get File Name for
	 *	@param		string		$caseSensitive		Flag: sense Case (important on *nix Servers)
	 *	@param		string		$extension			Class File Extension, by default 'php5'
	 *	@return		string
	 */
	protected static function getFileNameOfClass( $className, $caseSensitive = true, $extension = "php5" )
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
	 *	Sets up Cookie Support.
	 *	@access		protected
	 *	@return		void
	 */
	protected function initCookie()
	{
		import( 'de.ceus-media.net.http.PartitionCookie' );
		$config	=& $this->registry->get( 'config' );
		$cookie	= new Net_HTTP_PartitionCookie( $config['application']['name'] );
		$this->registry->set( 'cookie', $cookie );
	}
	
	/**
	 *	Sets up Basic Configuration.
	 *	@access		protected
	 *	@param		string		$configPath		Path to basic Configuration Files
	 *	@return		void
	 */
	protected function initConfiguration( $configPath = "config/" )
	{
		$config	= parse_ini_file( $configPath."config.ini", true );
		if( isset( $config['config']['error_level'] ) )
			error_reporting( $config['config']['error_level'] );
		$this->registry->set( "config", $config, true );
	}

	/**
	 *	Sets up Database Connection.
	 *	@access		protected
	 *	@param		string		$configPath		Path to basic Configuration Files
	 *	@return		void
	 */
	protected function initDatabase( $configPath = "config/", $logPath = "logs/database/" )
	{
		import( 'de.ceus-media.database.pdo.Connection' );
		$dba	= parse_ini_file( $configPath."db_access.ini", true );
		foreach( $dba['options'] as $key => $value )
			$options[constant( "PDO::".$key )]	= eval( "return ".$value.";" );
			
		$dsn	= $dba['access']['type'].":host=".$dba['access']['hostname'].";dbname=".$dba['access']['database'];
		$dbc	= new Database_PDO_Connection( $dsn, $dba['access']['username'], $dba['access']['password'], $options );
#		$dbc->setErrorLogFile( $dba['access']['logfile'] );
#		$dbc->setStatementLogFile( self::$databaseLogPath."queries.log" );
		$dbc->setLogFile( $dba['access']['logfile'] );
		$dbc->setQueryLogFile( self::$databaseLogPath."queries.log" );

		foreach( $dba['attributes'] as $key => $value )
			$dbc->setAttribute( constant( "PDO::".$key ), eval( "return ".$value.";" ) );
		$config	=& $this->registry->get( 'config' );
		$config['config']['table_prefix']	= $dba['access']['prefix'];
		$this->registry->set( "dbc", $dbc, true );
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
		$formPath	= $config['paths']['forms'];
		$cachePath	= $config['paths']['cache'].basename( $config['paths']['forms'] )."/";
		$definition	= new Framework_Krypton_Core_FormDefinitionReader( $formPath, true, $cachePath );
		$definition->setChannel( "html" );
		$this->registry->set( 'definition', $definition );
	}

	/**
	 *	Sets up Language Support.
	 *	@access		protected
	 *	@return		void
	 */
	protected function initLanguage( $identify = true )
	{
		import( 'de.ceus-media.framework.krypton.core.Language' );
		$language	= new Framework_Krypton_Core_Language();
		if( $identify )
			$language->identifyLanguage();
		$language->loadLanguage( 'main' );
		$this->registry->set( 'language', $language, true );		
		
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
	protected function initPageController( $configPath = "config/" )
	{
		import( 'de.ceus-media.framework.krypton.core.PageController' );
		$controller	= new Framework_Krypton_Core_PageController( $configPath."pages.xml" );
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
	protected function initRequest( $console = false )
	{
		if( $console )
		{
			import( 'de.ceus-media.console.RequestReceiver' );
			$request	= new Console_RequestReceiver;
		}
		else
		{
			import( 'de.ceus-media.net.http.request.Receiver' );
			$request	= new Net_HTTP_Request_Receiver;
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
		$client->setHostAddress( $config['services']['url'] );
		$client->setUserAgent( "Motrada Office" );
		if( $config['services']['username'] )
			$client->setBasicAuth( $config['services']['username'], $config['services']['password'] );
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
		$session	= new Net_HTTP_PartitionSession( $config['application']['name'], $config['config']['session_name'] );
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

		if( $config['layout']['switchable_themes'] )
		{
			if( $request->has( 'switchThemeTo' ) )
				$session->set( 'theme', $request->get( 'switchThemeTo' ) );
			if( $session->get( 'theme' ) )
				$config['layout']['theme'] =  $session->get( 'theme' );
			else
				$session->set( 'theme', $config['layout']['theme'] );
		}
		else
			$session->set( 'theme', $config['layout']['theme'] );
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
		$log->note( $data, false );
	}
}
?>