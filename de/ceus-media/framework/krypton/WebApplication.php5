<?php
import( 'classes.core.http.Request' );
import( 'classes.core.http.Response' );
import( 'classes.core.Registry' );
import( 'classes.core.StopWatch' );
import( 'classes.core.Messenger' );
import( 'classes.core.PartitionSession' );
import( 'classes.core.database.pdo.Connection' );
import( 'classes.core.Language' );
import( 'classes.core.FordDefinitionReader' );
import( 'classes.view.Interface' );
import( 'classes.view.component.Template' );
import( 'classes.view.Content' );
/**
 *	Main Class of Motrada V2 to realize Actions and build Views.
 *	@package		mv2
 *	@uses			Framework_Krypton_Core_HTTP_Request
 *	@uses			Framework_Krypton_Core_HTTP_Response
 *	@uses			Framework_Krypton_Core_Registry
 *	@uses			Framework_Krypton_Core_StopWatch
 *	@uses			Framework_Krypton_Core_Messenger
 *	@uses			Framework_Krypton_Core_PartitionSession
 *	@uses			Framework_Krypton_Core_Database_PDO_Connection
 *	@uses			Framework_Krypton_Core_Language
 *	@uses			Framework_Krypton_Core_FieldDefinition
 *	@uses			Framework_Krypton_View_Interface
 *	@uses			Framework_Krypton_View_Content
 *	@uses			Framework_Krypton_View_Component_Template
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			11.12.2006
 *	@version		0.3
 */
/**
 *	Main Class of Motrada V2 to realize Actions and build Views.
 *	@package		mv2
 *	@uses			Framework_Krypton_Core_HTTP_Request
 *	@uses			Framework_Krypton_Core_HTTP_Response
 *	@uses			Framework_Krypton_Core_Registry
 *	@uses			Framework_Krypton_Core_StopWatch
 *	@uses			Framework_Krypton_Core_Messenger
 *	@uses			Framework_Krypton_Core_PartitionSession
 *	@uses			Framework_Krypton_Core_Database_PDO_Connection
 *	@uses			Framework_Krypton_Core_Language
 *	@uses			Framework_Krypton_Core_FormDefinitionReader
 *	@uses			Framework_Krypton_View_Interface
 *	@uses			Framework_Krypton_View_Content
 *	@uses			Framework_Krypton_View_Component_Template
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			11.12.2006
 *	@version		0.3
 */
class Prototype
{
	/**	@var	Framework_Krypton_Core_Registry	$registry		Instance of Framework_Krypton_Core_Registry */
	protected $registry		= null;

	/**
	 *	Creates registered Objects and loads Configuration.
	 *	@access		public
	 *	@param		string		$configPath		Path to Configuration Files
	 *	@return		void
	 */
	public function __construct( $configPath = "config/" )
	{
		ob_start();

		$request = new Framework_Krypton_Core_HTTP_Request;
		$this->registry	= Framework_Krypton_Core_Registry::getInstance();
		$this->registry->set( "stopwatch",	new Framework_Krypton_Core_StopWatch );		
		$this->registry->set( "messenger",	new Framework_Krypton_Core_Messenger );
		$this->registry->set( "request",	$request );

		//  --  CONFIGURATION  --  //
		$config	= parse_ini_file( $configPath."config.ini", true );
		$config['subfooter']	= parse_ini_file( $configPath."subfooter.ini", true );
		error_reporting( $config['config']['error_level'] );
		$this->registry->set( "config", $config );

		//  --  DATABASE CONNECTION  --  //
		$dba	= parse_ini_file( $configPath."db_access.ini", true ); 

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

		//  --  LANGUAGE SUPPORT  --  //
		$language	= new Framework_Krypton_Core_Language();
		$language->identifyLanguage();
		$language->loadLanguage( 'main' );
		$this->registry->set( "language", $language );

		//  --  REWRITE PARAMETERS  --  //
//		$this->_rewriteParameters();

		//  --  THEME SUPPORT  --  //
		$theme = $request->get( 'theme' );
		if( $theme !== NULL ) 
			$session->set( 'theme', $theme );
		if( $session->get( 'theme' ) )
			$config['layout']['theme'] =  $session->get( 'theme' );
		else
			$session->set( 'theme', $config['layout']['theme'] );

		//  --  FIELD DEFINITION SUPPORT  --  //
		$definition	= new Framework_Krypton_Core_FieldDefinition( $platform->getPlatformName() );
		$definition->setChannel( "html" );
		$this->registry->set( "definition", $definition );
	}

	/**
	 *	Runs called Actions.
	 *	@access		public
	 *	@return		void
	 */
	public function runActions()
	{
		$config		= $this->registry->get( "config" );
		$request	= $this->registry->get( "request" );
		$session	= $this->registry->get( "session" );
		$language	= $this->registry->get( "language" );
		$controler	= $this->registry->get( "controler" );

		$link		= $this->evaluateLink();
		$request->set( 'link', $link );
		remark( "<b>Requested Link: </b>".$link );

		//  --  REFERER (=last site)  --  //
		if( $referer = getEnv( 'HTTP_REFERER' ) )
			if( !preg_match( "@(=|&)(log(in|out))|register@i", $referer ) )
				$session->set( 'referer', $referer );
		remark( "<b>Referer: </b>".$session->get( 'referer' ) );

		//  --  ACTION CALL  --  //
		if( $controler->checkPage( $link ) )
		{
			if( $controler->isDynamic( $link ) )
			{
				$classname	= "Action_".$controler->getClassname( $link );
				$filename	= $this->buildFilenameFromClass( $classname );
				
				remark( "<b>Action Class: </b>".$classname." (from File ".$filename.")" );
				if( file_exists( $filename ) )
				{
					require_once( $filename );
					$actions	= new $classname();
					$actions->performActions();
					$actions->act();
				}
			}
		}
	}

	/**
	 *	Creates Views by called Link and Rights of current User and returns HTML.
	 *	@access		public
	 *	@return		string
	 */
	public function buildViews()
	{
		$config		= $this->registry->get( "config" );
		$session	= $this->registry->get( "session" );
		$request	= $this->registry->get( "request" );
		$messenger	= $this->registry->get( "messenger" );
		$language	= $this->registry->get( "language" );
		$words		= $this->registry->get( "words" );
		$controler	= $this->registry->get( "controler" );
		$platform	= $this->registry->get( 'platform' );
		$st			= $this->registry->get( "stopwatch" );
		$auth		= $this->registry->get( "auth" );
		$extra		= "";
		$field		= "";
		$control	= "";
		$content	= "";
		$link		= $request->get( 'link' );

		if( !$content )
		{
			if( $controler->checkPage( $link ) )
			{
				if( $controler->isDynamic( $link ) )
				{
					$classname	= "View_".$controler->getClassname( $link );
					$filename	= $this->buildFilenameFromClass( $classname );
					remark( "<b>View Class: </b>".$classname." (from File ".$filename.")" );
					if( file_exists( $filename ) )
					{
						require_once( $filename );
						$view		= new $classname;
						$content	= $view->buildContent();
						$control	.= $view->buildControl();
					//	$extra		.= $view->buildExtra();
					}
					else
						$messenger->noteFailure( "Class '".$classname."' is not existing." );
				}
				else
				{
					$source		= $controler->getSource( $link );
					remark( "<b>HTML File: </b>".$source );
					$view		= new View_Content;
					$content	= $view->loadContent( $source );
				//	$content	= $view->buildContent();
				//	$extra		.= $view->buildExtra();
				}
			}
		}

		$ui['title']	= $words['main']['main']['title'];
		$ui['path_js']	= $config['paths']['javascripts'];
		$ui['path_css']	= $config['paths']['accounts'].$platform->getPlatformName()."/themes/".$config['layout']['theme']."/css/";
		$ui['layout']	= $config['layout']['style'];

		$view	= new Framework_Krypton_View_Interface();

		$ui['control']			= $control;
		$ui['content']			= $content;
		$ui['extra']			= $extra;
		$ui['mainheader']		= $view->buildMainHeader();
		$ui['subheader']		= $view->buildSubHeader();
		$ui['login']			= $view->buildControlLogin();
		$ui['mainnavigation']	= $view->buildMainNavigation();
		$ui['subnavigation']	= $view->buildSubNavigation();
		$ui['mainfooter']		= $view->buildMainFooter();
		$ui['subfooter']		= $view->buildSubFooter();
		$ui['messages']			= $messenger->buildMessages();
		$ui['dev']				= $view->buildDevCenter( ob_get_clean() );
		$ui['language']	= $language->getLanguage();
		foreach( $config as $section => $data )
			foreach( $data as $key => $value )
				$ui["config.".$section.".".$key]	= $value;
		try{
			$template	= new View_Component_Template( 'master', $ui );
			$content	= $template->create();
		}
		catch( Exception $e )
		{
			$labels	= implode( ", ", $e->getNotUsedLabels() );
			$labels	= htmlentities( $labels );
			die( $e->getMessage()."<br/><small>".$labels."</small>" );
		}
		$response	= new Framework_Krypton_Core_HTTP_Response();
		$response->write( $content );
		$response->flush();
	}

	//  --  PRIVATE METHODS  --  //
	/**
	 *	Evaluate called Link (to be overwritten or modified).
	 *	@access		protected
	 *	@return		string
	 */
	protected function evaluateLink()
	{
		$request	= $this->registry->get( "request" );
		$link		= $request->get( 'link' );
		$auth		= $this->registry->get( "auth" );

		if( !$auth->hasAccessToPage( $link ) )
			return $auth->getFirstAccessiblePage();
		return $link;
	}

	/**
	 *	Parses rewritten Request Parameters.
	 *	@access		protected
	 *	@return		void
	 */
	protected function _rewriteParameters()
	{
		$request	= $this->registry->get( "request" );
		$_params	= array();
		if( isset( $_GET['param'] ) )
		{
			$params	= explode( ";", $_GET['param'] );
			foreach( $params as $param )
			{
				if( $param = trim( $param ) )
				{
					$parts	= explode( ",", $param );
					$parts[1]	= isset( $parts[1] ) ? $parts[1] : "";
					$request->set( trim( $parts[0] ), trim( $parts[1] ) );
				}
			}
			unset( $_GET['param'] );
		}
	}
	
	protected static function buildFilenameFromClass( $className, $caseSensitive = true )
	{
		if( !$caseSensitive )
			return "classes/".str_replace( "_", "/", $className ).".php5";
		$parts		= explode( "_", $className );
		$class		= array_pop( $parts );
		$parts		= array_map( 'strtolower', array_values( $parts ) );
		array_push( $parts, $class );
		$path		= implode( "/", $parts );
		$fileName	= "classes/".$path.".php5";
		return $fileName;
	}
}
//Test 9

?>
