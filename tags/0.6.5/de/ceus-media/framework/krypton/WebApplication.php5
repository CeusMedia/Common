<?php
/**
 *	Main Class for Web Applications.
 *	This Class need to be called within an existing Web Project.
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
 *	@extends		Framework_Krypton_Base
 *	@uses			Net_HTTP_Request_Response
 *	@uses			View_Interface
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@copyright		2008 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			11.04.2008
 *	@version		0.1
 */
import( 'de.ceus-media.framework.krypton.Base' );
import( 'de.ceus-media.net.http.request.Response' );
#import( 'classes.view.Interface' );
/**
 *	Main Class for Web Applications.
 *	This Class need to be called within an existing Web Project.
 *	@package		framework.krypton
 *	@extends		Framework_Krypton_Base
 *	@uses			Framework_Krypton_Core_FormDefinitionReader
 *	@uses			Framework_Krypton_Core_PageController
 *	@uses			View_Interface
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@copyright		2008 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
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
	public function __construct()
	{
		//  --  ENVIRONMENT  --  //
		$this->initRegistry();										//  must be first
		$this->initConfiguration();									//  must be one of the first
		$this->initEnvironment();									//  must be one of the first
#		$this->initCookie();
		$this->initSession();
		$this->initRequest();

		//  --  RESOURCE SUPPORT  --  //
		$this->initDatabase();										//  needs Configuration
		$this->initLanguage();										//  needs Request and Session
		$this->initFormDefinition();								//  needs Configuration
		$this->initThemeSupport();									//  needs Configuration, Request and Session
		$this->initPageController();								//  needs Configuration

		//  --  AUTHENTICATION LOGIC  --  //
		$this->initAuthentication();								//  needs Implementation of Authentication Logic in Project Classes

		ob_start();
		if( !defined( 'DEV_MODE' ) )
			define( 'DEV_MODE', 1 );
	}

	/**
	 *	Runs called Actions.
	 *	@access		public
	 *	@param		bool		$verbose		Flag: show Information
	 *	@return		void
	 */
	public function act( $verbose = FALSE )
	{
		$request	= $this->registry->get( "request" );
		$session	= $this->registry->get( "session" );
		$controller	= $this->registry->get( "controller" );
		
		//  --  VALIDATE LINK  --  //
		$link	= $this->validateLink( $request->get( 'link' ) );
		$request->set( 'link', $link );
		if( $verbose )
			remark( "<b>Requested Link: </b>".$link );

		//  --  REFERER (=last site)  --  //
		if( $referer = getEnv( 'HTTP_REFERER' ) )
			if( !preg_match( "@(=|&)(log(in|out))|register@i", $referer ) )
				$session->set( 'referer', $referer );
		if( $verbose )
			remark( "<b>Referer: </b>".$session->get( 'referer' ) );

		//  --  ACTION CALL  --  //
		if( $controller->checkPage( $link ) )
		{
			if( $controller->isDynamic( $link ) )
			{
				$classname	= "Action_".$controller->getClassname( $link );
				$filename	= $this->getFileNameOfClass( $classname );

				if( $verbose )
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
	 *	@param		bool		$verbose		Flag: show Information
	 *	@return		string
	 */
	public function respond( $verbose = FALSE )
	{
		$config		= $this->registry->get( "config" );
		$request	= $this->registry->get( "request" );
		$controller	= $this->registry->get( "controller" );
		$messenger	= $this->registry->get( "messenger" );
		$words		= $this->registry->get( "words" );

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
					if( $verbose )
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
					$source	= $controller->getSource( $link );
					if( $interface->hasContent( $source ) )
					{
						$content	= $interface->loadContent( $source );
						if( method_exists( $interface, 'setTitleByLink' ) )
							$interface->setTitleByLink( $link );
						if( method_exists( $interface, 'setKeywordsByLink' ) )
							$interface->setKeywordsByLink( $link );
						if( method_exists( $interface, 'setDescriptionByLink' ) )
							$interface->setDescriptionByLink( $link );
					}
					else
						$messenger->noteFailure( str_replace( "#URI#", $source, $words['main']['msg']['error_no_content'] ) );
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

		$zipMethod	= $config['config.http_compression'];
		$zipLogFile	= $config['config.http_compression_log'];
		Net_HTTP_Request_Response::sendContent( $content, $zipMethod, $zipLogFile );
	}

	/**
	 *	Validate requested Link (can be overwritten for another Validation).
	 *	@access		protected
	 *	@param		string		$link		Requested Link to validate
	 *	@return		string
	 */
	protected function validateLink( $link )
	{
		$auth		= $this->registry->get( "auth" );
		if( !( $link && $auth->hasAccessToPage( $link ) ) )
			return $auth->getFirstAccessiblePage();
		return $link;
	}
}
?>