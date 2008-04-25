<?php
import( 'de.ceus-media.framework.krypton.Base' );
import( 'de.ceus-media.net.http.request.Response' );
import( 'classes.view.Interface' );
/**
 *	Designer Collective Web Applications.
 *	@package		collective
 *	@extends		Framework_Krypton_Base
 *	@uses			Net_HTTP_Request_Response
 *	@uses			View_Interface
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			11.04.2008
 *	@version		0.1
 */
/**
 *	Designer Collective Web Applications.
 *	@package		collective
 *	@extends		Framework_Krypton_Base
 *	@uses			Framework_Krypton_Core_FormDefinitionReader
 *	@uses			Framework_Krypton_Core_PageController
 *	@uses			View_Interface
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			11.04.2008
 *	@version		0.1
 */
class Framework_Krypton_WebApplication extends Framework_Krypton_Base
{
	/**
	 *	Constructor.
	 *	@access		public
	 *	@return		void
	 */
	public function __construct( $configPath = "config/" )
	{
		//  --  ENVIRONMENT  --  //
		$this->initRegistry();							//  must be first
		$this->initConfiguration( $configPath );		//  must be one of the first
		$this->initEnvironment();						//  must be one of the first
#		$this->initCookie();
		$this->initSession();
		$this->initRequest();

		//  --  RESOURCE SUPPORT  --  //
		$this->initDatabase( $configPath );				//  needs Configuration
		$this->initLanguage();							//  needs Request and Session
		$this->initFormDefinition();					//  needs Configuration
		$this->initThemeSupport();						//  needs Configuration, Request and Session
		$this->initPageController( $configPath );		//  needs Configuration

		//  --  AUTHENTICATION LOGIC  --  //
		$this->initAuthentication();					//  needs Implementation of Authentication Logic in Project Classes

		ob_start();
		
		if( !defined( 'DEV_MODE' ) )
			define( 'DEV_MODE', 1 );
	}
	
	/**
	 *	Runs called Actions.
	 *	@access		public
	 *	@return		void
	 */
	public function act()
	{
		$request	= $this->registry->get( "request" );
		$session	= $this->registry->get( "session" );
		$controller	= $this->registry->get( "controller" );
		$auth		= $this->registry->get( "auth" );
		
		//  --  EVALUATE LINK  --  //
		$link		= $request->get( 'link' );
		if( !$auth->hasAccessToPage( $link ) )
			$link	= $auth->getFirstAccessiblePage();
		$request->set( 'link', $link );
//		remark( "<b>Requested Link: </b>".$link );

		//  --  REFERER (=last site)  --  //
		if( $referer = getEnv( 'HTTP_REFERER' ) )
			if( !preg_match( "@(=|&)(log(in|out))|register@i", $referer ) )
				$session->set( 'referer', $referer );
		remark( "<b>Referer: </b>".$session->get( 'referer' ) );

		//  --  ACTION CALL  --  //
		if( $controller->checkPage( $link ) )
		{
			if( $controller->isDynamic( $link ) )
			{
				$classname	= "Action_".$controller->getClassname( $link );
				$filename	= $this->getFileNameOfClass( $classname );

				remark( "<b>Action Class: </b>".$classname." (from File ".$filename.")" );
				if( file_exists( $filename ) )
				{
					require_once( $filename );
					$action	= new $classname();
					if( !DEV_MODE && method_exists( $action, 'handleException' ) )
					{
						try
						{
							$action->performActions();
							$action->act();
						}
						catch( Exception $e )
						{
							$action->handleException( $e );
						}
					}
					else
					{
						$action->performActions();
						$action->act();
					}
				}
			}
		}
	}

	/**
	 *	Creates Views by called Link and Rights of current User and returns HTML.
	 *	@access		public
	 *	@return		string
	 */
	public function respond()
	{
		$session	= $this->registry->get( "session" );
		$request	= $this->registry->get( "request" );
		$controller	= $this->registry->get( "controller" );
		$messenger	= $this->registry->get( "messenger" );

		$extra		= "";
		$field		= "";
		$control	= "";
		$content	= "";
		$link		= $request->get( 'link' );
		$interface	= new View_Interface();
//		$view		= new View_Auth;
//		$control	= $view->buildControl();
//		$content	= $view->buildContent();

		if( !$content )
		{
			if( $controller->checkPage( $link ) )
			{
				if( $controller->isDynamic( $link ) )
				{
					$classname	= "View_".$controller->getClassname( $link );
					$filename	= $this->getFileNameOfClass( $classname );
					remark( "<b>View Class: </b>".$classname." (from File ".$filename.")" );
					if( file_exists( $filename ) )
					{
						require_once( $filename );
						$view		= new $classname;
						if( !DEV_MODE && method_exists( $view, 'handleException' ) )
						{
							try
							{
								$content	= $view->buildContent();
								$control	.= $view->buildControl();
								$extra		.= $view->buildExtra();
							}
							catch( Exception $e )
							{
								$view->handleException( $e, 'main', 'exceptions' );
							}
						}
						else
						{
							$content	= $view->buildContent();
							$control	.= $view->buildControl();
							$extra		.= $view->buildExtra();
						}
					}
					else
						$messenger->noteFailure( "Class '".$classname."' is not existing." );
				}
				else
				{
					$source		= $controller->getSource( $link );
					remark( "<b>HTML File: </b>".$source );
					$content	= $interface->loadContent( $source );
				//	$extra		.= $view->buildExtra();
				}
			}
		}

	if( isset( $GLOBALS['length'] ) )
	{
		remark( "<b>Class Length: </b>".$GLOBALS['length']['total'] );
		arsort( $GLOBALS['length']['classes'] );
		print_m( array_flip( $GLOBALS['length']['classes'] ) );
	}

		$content	= $interface->buildInterface( $content, $control, $extra );

		$response	= new Net_HTTP_Request_Response();
		$response->write( $content );
		$response->send();
	}
}
?>