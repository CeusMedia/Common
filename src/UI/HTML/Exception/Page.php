<?php
/**
 *	Builder of Exception Pages.
 *
 *	Copyright (c) 2010-2022 Christian Würker (ceusmedia.de)
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
 *	@category		Library
 *	@package		CeusMedia_Common_UI_HTML_Exception
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2010-2022 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 *	@since			0.7.0
 */

namespace CeusMedia\Common\UI\HTML\Exception;

use CeusMedia\Common\UI\HTML\JQuery;
use CeusMedia\Common\UI\HTML\PageFrame;
use CeusMedia\Common\UI\HTML\Tag;
use Exception;

/**
 *	Builder of Exception Pages.
 *	@category		Library
 *	@package		CeusMedia_Common_UI_HTML_Exception
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2010-2022 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 *	@since			0.7.0
 */
class Page
{
	/**
	 *	Displays rendered Exception Page.
	 *	@access		public
	 *	@param		Exception				$e			Exception to render View for
	 *	@return		string
	 *	@static
	 */
	public static function display( Exception $e )
	{
		$view	= View::render( $e );
		print( self::wrapExceptionView( $view ) );
	}
	/**
	 *	Returns rendered Exception Page.
	 *	@access		public
	 *	@param		Exception				$e			Exception to render View for
	 *	@return		string
	 *	@static
	 */
	public static function render( Exception $e )
	{
		$view	= View::render( $e );
		return self::wrapExceptionView( $view );
	}

	/**
	 *	Wraps an Exception View to an Exception Page.
	 *	@access		public
	 *	@param		View	$view		Exception View
	 *	@return		string
	 */
	public static function wrapExceptionView( $view )
	{
		$page	= new PageFrame();
		$page->setTitle( 'Exception' );
		$page->addJavaScript( '//cdn.ceusmedia.de/js/jquery/1.4.2.min.js' );
		$page->addJavaScript( '//cdn.ceusmedia.de/js/jquery/cmExceptionView/0.1.js' );
		$page->addStylesheet( '//cdn.ceusmedia.de/js/jquery/cmExceptionView/0.1.css' );
		$page->addStylesheet( '//cdn.ceusmedia.de/css/bootstrap.min.css' );
		$options	= array( 'foldTraces' => TRUE );
		$script		= JQuery::buildPluginCall( 'cmExceptionView', 'dl.exception', $options );
		$page->addHead( Tag::create( 'script', $script ) );
		$page->addBody( Tag::create( 'h2', 'Error' ).$view );
		return $page->build( array( 'style' => 'margin: 1em' ) );
	}
}
