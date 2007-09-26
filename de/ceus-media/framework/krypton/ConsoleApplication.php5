<?php
require_once( "classes/core/interface/Request.php5" );
require_once( "classes/core/ConsoleRequest.php5" );
require_once( "classes/core/Registry.php5" );
require_once( "classes/core/StopWatch.php5" );
//require_once( "classes/core/Messenger.php5" );
require_once( "classes/core/PartitionSession.php5" );
require_once( "classes/core/database/pdo/Connection.php5" );
require_once( "classes/core/Language.php5" );
require_once( "classes/core/Platform.php5" );
require_once( "classes/core/FieldDefinition.php5" );
require_once( "classes/core/CategoryFactory.php5" );
//require_once( "classes/logic/Authentification.php5" );
//require_once( "classes/action/Auth.php5" );
//require_once( "classes/view/Auth.php5" );
//require_once( "classes/view/Interface.php5" );
//require_once( "classes/view/component/Template.php5" );
/**
 *	Main Class of Motrada V2 to realize Actions and build Views.
 *	@package		mv2
 *	@uses			Core_HTTP_Request
 *	@uses			Core_Registry
 *	@uses			Core_StopWatch
 *	@uses			Core_Messenger
 *	@uses			Core_PartitionSession
 *	@uses			Core_Database_PDO_Connection
 *	@uses			Core_Language
 *	@uses			Core_Platform
 *	@uses			Core_FieldDefinition
 *	@uses			Core_CategoryFactory
 *	@uses			Logic_Authentification
 *	@uses			Action_Auth
 *	@uses			View_Auth
 *	@uses			View_Interface
 *	@uses			View_Component_Template
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			11.12.2006
 *	@version		0.3
 */
/**
 *	Main Class of Motrada V2 to realize Actions and build Views.
 *	@package		mv2
 *	@uses			Core_HTTP_Request
 *	@uses			Core_Registry
 *	@uses			Core_StopWatch
 *	@uses			Core_Messenger
 *	@uses			Core_PartitionSession
 *	@uses			Core_Database_PDO_Connection
 *	@uses			Core_Language
 *	@uses			Core_Platform
 *	@uses			Core_FieldDefinition
 *	@uses			Core_CategoryFactory
 *	@uses			Logic_Authentification
 *	@uses			Action_Auth
 *	@uses			View_Auth
 *	@uses			View_Interface
 *	@uses			View_Component_Template
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			11.12.2006
 *	@version		0.3
 */
class ConsoleApplication
{
	/**	@var	Core_Registry	$registry		Instance of Core_Registry */
	protected $registry		= null;

	/**
	 *	Creates registered Objects and loads Configuration.
	 *	@access		public
	 *	@return		void
	 */
	public function __construct()
	{
		try
		{
			$this->registry	= Core_Registry::getInstance();
			$this->registry->set( "stopwatch",	new Core_StopWatch );		
//			$this->registry->set( "messenger",	new Core_Messenger );
			$this->registry->set( "request",new Core_ConsoleRequest );

			//  --  CONFIGURATION  --  //
			$config	= parse_ini_file( "config/config.ini", true );
			error_reporting( $config['config']['error_level'] );
			$this->registry->set( "config", $config );

			//  --  SESSION HANDLING  --  //
			$session	= new Core_PartitionSession( $config['application']['name'], $config['config']['session_name'] );
			$this->registry->set( "session", $session );

			//  --  DATABASE CONNECTION  --  //
			$dba	= parse_ini_file("config/db_access.ini", true ); 

			foreach( $dba['options'] as $key => $value )
				$options[eval( "return ".$key.";" )]	= eval( "return ".$value.";" );
			$dsn	= $dba['access']['type'].":host=".$dba['access']['hostname'].";dbname=".$dba['access']['database'];
			$dbc	= new Core_Database_PDO_Connection( $dsn, $dba['access']['username'], $dba['access']['password'], $options );

			foreach( $dba['attributes'] as $key => $value )
				$dbc->setAttribute( eval( "return ".$key.";" ), eval( "return ".$value.";" ) );
			$dbc->setLogFile( $dba['access']['logfile'] );
			
//			$dbc		= new Core_Database_MySQLt_Connection( $dba['logfile'] );
//			$dbc->connect( $dba['hostname'], $dba['username'], $dba['password'], $dba['database'] );
			$config['config']['table_prefix']	= $dba['access']['prefix'];
			$this->registry->set( "dbc", $dbc );
		
			//  --  PLATFORM IDENTIFACTION  --  //
			$platform	= new Core_Platform( $dbc );
			$platform->setBaseConfigurationPath( "settings.test." );
			$platform->setHtmlFile( "contents/html/no_platform.html" );
			$platform->setCachePath( "contents/cache/configuration/" );
			$platform->setUp();
			$this->registry->set( "platform", $platform );		

			//  --  LANGUAGE SUPPORT  --  //
			$language	= new Core_Language();
			$language->identifyLanguage();
//			$language->loadLanguage( 'main' );
			$this->registry->set( "language", $language );

			//  --  THEME SUPPORT  --  //
/*			$theme = $request->get( 'theme' );
			if( $theme !== NULL ) 
				$session->set( 'theme', $theme );
			if( $session->get( 'theme' ) )
				$config['layout']['theme'] =  $session->get( 'theme' );
			else
				$session->set( 'theme', $config['layout']['theme'] );
*/
			//  --  FIELD DEFINITION SUPPORT  --  //
			$definition	= new Core_FieldDefinition( $config['paths']['accounts'].$platform->getPlatformName()."/config/forms/", true, $config['paths']['accounts'].$platform->getPlatformName()."/".$config['paths']['cache']."forms/" );
			$definition->setChannel( "html" );
			$this->registry->set( "definition", $definition );

			//  --  USER AUTHENTIFICATION  --  //
//			$auth	= new Logic_Authentification( $config['config']['use_authentification']);
//			$this->registry->set( "auth", $auth );

			//  --  FACTORY  --  //
			$factory	= new Core_CategoryFactory();
			$factory->setTypes( array( 'car', 'boat' ) );
			$factory->setDefault( 'car' );
/*			$factory->setType( $session->get( 'category' ) );*/
			$this->registry->set( 'factory', $factory );
		}
		catch( Exception $e )
		{
			die( $e->getMessage() );
		}
	}

	function run()
	{
		$request	= Core_Registry::getStatic( 'request' );
		if( $request->has( 'cmd' ) )
			$command	= $request->get( 'cmd' );

		//  --  SHOW SYNTAX  --  //
		if( count( $request->getAll() ) < 3 )
			die( "Syntax: php -f runJob.php5 <Class>" );

		//  --  RUN JOB  --  //
		$className	= $request->get( 1 );
		try
		{
			$command	= $request->get( 'cmd' );
			$job		= new $className();
			$result		= $job->run( $command );
			error_log( time()." ".$className."[".$command."]: ".(string)$result."\n", 3, LOG_JOBS );
		}
		catch( Exception $e )
		{
			error_log( time()."|".$className."(".$e->getFile()."[".$e->getLine()."]):".$e->getMessage()."|trace:".serialize( $e->getTrace() )."\n", 3, LOG_JOBERROR );
		}
	
	
	}
}
?>