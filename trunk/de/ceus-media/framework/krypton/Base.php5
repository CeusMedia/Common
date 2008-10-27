<?php
/**
 *	Base for all Applications.
 *
 *	Copyright (c) 2008 Christian Würker (ceus-media.de)
 *
 *	This program is free software: you can redistribute it and/or modify
 *	it under the terms of the GNU General Public License as published by
 *	the Free Software Foundation, either version 3 of the License, or
 *	(at your option) any later version.
 *
 *	This program is distributed in the hope that it will be useful,
 *	but WITHOUT ANY WARRANTY; without even the implied warranty of
 *	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *	GNU General Public License for more details.
 *
 *	You should have received a copy of the GNU General Public License
 *	along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 *	@package		framework.krypton
 *	@uses			StopWatch
 *	@uses			Database_PDO_Connection
 *	@uses			Net_HTTP_PartitionSession
 *	@uses			Net_HTTP_Request_Receiver
 *	@uses			Net_Service_Client
 *	@uses			File_Configuration_Reader
 *	@uses			Console_RequestReceiver
 *	@uses			Framework_Krypton_Core_Registry
 *	@uses			Framework_Krypton_Core_Messenger
 *	@uses			Framework_Krypton_Core_Language
 *	@uses			Framework_Krypton_Core_FormDefinitionReader
 *	@uses			Framework_Krypton_Core_PageController
 *	@uses			Logic_Authentication
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@copyright		2008 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
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
 *	@uses			Net_Service_Client
 *	@uses			File_Configuration_Reader
 *	@uses			Console_RequestReceiver
 *	@uses			Framework_Krypton_Core_Registry
 *	@uses			Framework_Krypton_Core_Messenger
 *	@uses			Framework_Krypton_Core_Language
 *	@uses			Framework_Krypton_Core_FormDefinitionReader
 *	@uses			Framework_Krypton_Core_PageController
 *	@uses			Logic_Authentication
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@copyright		2008 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
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
	protected function initConfiguration( $errorLevelKey = "config.error.level" )
	{
/*		$config	= parse_ini_file( self::$configPath.self::$configFile, TRUE );
		if( isset( $config['config.error_level'] ) )
			error_reporting( $config['config.error_level'] );
		$this->registry->set( "config", $config, TRUE );
*/		
		import( 'de.ceus-media.file.configuration.Reader' );
		$config	= new File_Configuration_Reader( self::$configPath.self::$configFile, self::$cachePath );
		if( $config->has( $errorLevelKey ) )
			error_reporting( $config->get( $errorLevelKey ) );
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
	protected function initDatabase( $setUtf8 = TRUE )
	{
		import( 'de.ceus-media.database.pdo.Connection' );
		import( 'de.ceus-media.database.pdo.DataSourceName' );
		$config	= $this->registry->get( 'config' );

		//  --  DATABASE OPTIONS  --  //
		$options	= $config['database.options'];
		if( is_array( $options ) )
			foreach( $options as $key => $value )
				$options[constant( "PDO::".$key )]	= eval( "return ".$value.";" );

		//  --  DATA SOURCE NAME  --  //
		$dsn	= new Database_PDO_DataSourceName(
			$config['database.access.type'],
			$config['database.access.hostname'],
			$config['database.access.port'],
			$config['database.access.database'],
			$config['database.access.username'],
			$config['database.access.password']
		);

		//  --  DATABASE CONNECTION  --  //
		$dbc	= new Database_PDO_Connection(
			$dsn,
			$config['database.access.username'],
			$config['database.access.password'],
			$options
		);
#		$dbc->setErrorLogFile( $dba['access']['logfile'] );
#		$dbc->setStatementLogFile( self::$dbLogPath."queries.log" );
#		$dbc->setLogFile( $logfile );
		if( $config->has( 'database.access.errorLogFile' ) )
			$dbc->setLogFile( $errorLogFile );
		$dbc->setQueryLogFile( self::$dbLogPath."queries.log" );
		if( $config->has( 'database.access.statementLogFile' ) )
			$dbc->setLogFile( $statementLogFile );

		//  --  DATABASE ATTRIBUTES  --  //
		$attributes	= $config['database.attributes'];
		if( is_array( $attributes ) )
			foreach( $attributes as $key => $value )
				$dbc->setAttribute( constant( "PDO::".$key ), eval( "return ".$value.";" ) );

		if( $setUtf8 )
			$dbc->query( "SET NAMES utf8" );		

		$config['config.table_prefix']	= $config['database.access.prefix'];

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
	protected function initRegistry( $clear = TRUE )
	{
		import( 'de.ceus-media.framework.krypton.core.Registry' );
		$this->registry	= Framework_Krypton_Core_Registry::getInstance();
		if( $clear )
			$this->registry->clear();
	}

	/**
	 *	Sets up Request Handler.
	 *	@access		protected
	 *	@return		void
	 */
	protected function initRequest()
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
		$this->registry->set( "request", $request, TRUE );
	}

	/**
	 *	Sets up Service Client.
	 *	@access		protected
	 *	@return		void
	 */
	protected function initServiceClient( $configSection = "services", $keyUrl = "url", $keyUsername = "username", $keyPassword = "password" )
	{
		import( 'de.ceus-media.net.service.Client' );
		if( !$this->registry->has( 'config' ) )
			throw new Exception( 'Configuration has not been set up.' );
		$config		= $this->registry->get( 'config' );
		$client	= new Net_Service_Client( "", "logs/services.log" );
		$client->setHostAddress( $config["$configSection.$keyUrl"] );
		$client->setUserAgent( "Motrada Office" );
		if( $config["$configSection.$keyUsername"] )
			$client->setBasicAuth( $config["$configSection.$keyUsername"], $config["$configSection.$keyPassword"] );
		$this->registry->set( 'client', $client );
	}

	/**
	 *	Sets up Request Handler.
	 *	@access		protected
	 *	@return		void
	 */
	protected function initSession( $sessionNameKey	= 'config.session.name', $sessionPartitionKey = 'application.name' )
	{
		import( 'de.ceus-media.net.http.PartitionSession' );
		if( !$this->registry->has( 'config' ) )
			throw new Exception( 'Configuration has not been set up.' );
		$config		= $this->registry->get( 'config' );
		$session	= new Net_HTTP_PartitionSession( $config[$sessionPartitionKey], $config[$sessionNameKey ] );
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

		if( $config['layout.theme.switchable'] )
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