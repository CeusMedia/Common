<?php
import( 'de.ceus-media.service.YamlParametricPoint' );
import( 'de.ceus-media.framework.krypton.core.http.Request' );
import( 'de.ceus-media.framework.krypton.core.http.Response' );
import( 'de.ceus-media.framework.krypton.core.Registry' );
import( 'de.ceus-media.framework.krypton.core.StopWatch' );
import( 'de.ceus-media.framework.krypton.core.Messenger' );
import( 'de.ceus-media.framework.krypton.core.PartitionSession' );
import( 'de.ceus-media.framework.krypton.core.database.pdo.Connection' );
import( 'de.ceus-media.framework.krypton.core.Language' );
import( 'de.ceus-media.framework.krypton.view.component.Template' );
import( 'de.ceus-media.framework.krypton.core.FormDefinitionReader' );
/**
 *	...
 *	@extends		Service_YamlParametricPoint
 *	@uses			Framework_Krypton_Core_HTTP_Request
 *	@uses			Framework_Krypton_Core_HTTP_Response
 *	@uses			Framework_Krypton_Core_Registry
 *	@uses			Framework_Krypton_Core_StopWatch
 *	@uses			Framework_Krypton_Core_Messenger
 *	@uses			Framework_Krypton_Core_PartitionSession
 *	@uses			Framework_Krypton_Core_Database_PDO_Connection
 *	@uses			Framework_Krypton_Core_Language
 *	@uses			Framework_Krypton_Core_FormDefinitionReader
 *	@uses			Framework_Krypton_View_Component_Template
 */
/**
 *	...
 *	@extends		Service_YamlParametricPoint
 *	@uses			Framework_Krypton_Core_HTTP_Request
 *	@uses			Framework_Krypton_Core_HTTP_Response
 *	@uses			Framework_Krypton_Core_Registry
 *	@uses			Framework_Krypton_Core_StopWatch
 *	@uses			Framework_Krypton_Core_Messenger
 *	@uses			Framework_Krypton_Core_PartitionSession
 *	@uses			Framework_Krypton_Core_Database_PDO_Connection
 *	@uses			Framework_Krypton_Core_Language
 *	@uses			Framework_Krypton_Core_FormDefinitionReader
 *	@uses			Framework_Krypton_View_Component_Template
 */
class WebServices extends Service_YamlParametricPoint
{
	public function __construct( $fileName, $cacheFile = false )
	{
		parent::__construct( $fileName, $cacheFile );
		ob_start();
		$request = new Framework_Krypton_Core_HTTP_Request;
		$this->registry	= Framework_Krypton_Core_Registry::getInstance();
		$this->registry->set( "stopwatch",	new Framework_Krypton_Core_StopWatch );		
		$this->registry->set( "messenger",	new Framework_Krypton_Core_Messenger );
		$this->registry->set( "request",	$request );

		//  --  CONFIGURATION  --  //
		$config	= parse_ini_file( "config/config.ini", true );
		error_reporting( $config['config']['error_level'] );
		$this->registry->set( "config", $config );

		//  --  DATABASE CONNECTION  --  //
		$dba	= parse_ini_file( "config/db_access.ini", true ); 

		foreach( $dba['options'] as $key => $value )
			$options[eval( "return ".$key.";" )]	= eval( "return ".$value.";" );
		$dsn	= $dba['access']['type'].":host=".$dba['access']['hostname'].";dbname=".$dba['access']['database'];
		$dbc	= new Framework_Krypton_Core_Database_PDO_Connection( $dsn, $dba['access']['username'], $dba['access']['password'], $options );

		foreach( $dba['attributes'] as $key => $value )
			$dbc->setAttribute( eval( "return ".$key.";" ), eval( "return ".$value.";" ) );
		$dbc->setLogFile( $dba['access']['logfile'] );
			
		$config['config']['table_prefix']	= $dba['access']['prefix'];
		$this->registry->set( "dbc", $dbc );
		
		//  --  SESSION HANDLING  --  //
		$session	= new Framework_Krypton_Core_PartitionSession( $config['application']['name'], $config['config']['session_name'] );
		$this->registry->set( "session", $session );
		$session->set( 'category', 'car' );

		//  --  THEME SUPPORT  --  //
		$session->set( 'theme', $config['layout']['theme'] );

		//  --  LANGUAGE SUPPORT  --  //
		$language	= new Framework_Krypton_Core_Language();
		$language->identifyLanguage();
		$language->loadLanguage( 'main' );
		$this->registry->set( "language", $language );

		//  --  FIELD DEFINITION SUPPORT  --  //
		$definition	= new Framework_Krypton_Core_FieldDefinition( $config['paths']['forms'], true, $config['paths']['cache']."forms/" );
		$definition->setChannel( "html" );
		$this->registry->set( "definition", $definition );

	}

	/**
	 *	Constructor Method.
	 *	@access		public
	 *	@param		string			Service to call
	 *	@param		string			Format to output Service Results
	 *	@return		void	
	 */
	public function callService( $service, $format )
	{
		$this->checkServiceCall( $service, $format, $_REQUEST );
		$class	= $this->services['services'][$service]['class'];
		
		$parts	= explode( "_", $class );
		$last	= array_pop( $parts );
		$file	= implode( ".", array_map( create_function( '$item', 'return strtolower( $item );' ), $parts ) ).".".$last;
		
		import( 'classes.'.$file );
		$object	= new $class;
		if( !method_exists( $object, $service ) )
			throw new Exception( "Method '".$service."' does not exist in Class '".$class."'" );
		$object->$service( $format, $_REQUEST );
	}
	
}
?>
