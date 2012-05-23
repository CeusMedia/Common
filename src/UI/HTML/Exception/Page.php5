<?php
/**
 *	Builder of Exception Pages.
 *
 *	Copyright (c) 2010-2012 Christian Würker (ceusmedia.com)
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
 *	@category		cmClasses
 *	@package		UI.HTML.Exception
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2010-2012 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			0.7.0
 *	@version		$Id$
 */
/**
 *	Builder of Exception Pages.
 *	@category		cmClasses
 *	@package		UI.HTML.Exception
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2010-2012 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			0.7.0
 *	@version		$Id$
 */
class UI_HTML_Exception_Page
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
		$view	= UI_HTML_Exception_View::render( $e );
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
		$view	= UI_HTML_Exception_View::render( $e );
		return self::wrapExceptionView( $view );
	}

	/**
	 *	Wraps an Exception View to an Exception Page.
	 *	@access		public
	 *	@param		UI_HTML_Exception_View	$view		Exception View
	 *	@return		string
	 */
	public static function wrapExceptionView( $view )
	{
		$page	= new UI_HTML_PageFrame();
		$page->setTitle( 'Exception' );
		$page->addJavaScript( '//js.ceusmedia.de/jquery/1.4.2.min.js' );
		$page->addJavaScript( '//js.ceusmedia.de/jquery/cmExceptionView/0.1.js' );
		$page->addStylesheet( '//js.ceusmedia.de/jquery/cmExceptionView/0.1.css' );
		$options	= array( 'foldTraces' => TRUE );
		$script		= UI_HTML_JQuery::buildPluginCall( 'cmExceptionView', 'dl.exception', $options );
		$page->addHead( UI_HTML_Tag::create( 'script', $script ) );
		$page->addBody( $view );
		return $page->build( array( 'style' => 'margin: 1em' ) );
	}
}
?>