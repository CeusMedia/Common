<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

/**
 *	Builder of Exception Pages.
 *
 *	Copyright (c) 2010-2024 Christian Würker (ceusmedia.de)
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
 *	along with this program.  If not, see <https://www.gnu.org/licenses/>.
 *
 *	@category		Library
 *	@package		CeusMedia_Common_UI_HTML_Exception
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2010-2024 Christian Würker
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */

namespace CeusMedia\Common\UI\HTML\Exception;

use CeusMedia\Common\UI\HTML\JQuery;
use CeusMedia\Common\UI\HTML\PageFrame;
use CeusMedia\Common\UI\HTML\Tag;
use Exception;
use Throwable;

/**
 *	Builder of Exception Pages.
 *	@category		Library
 *	@package		CeusMedia_Common_UI_HTML_Exception
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2010-2024 Christian Würker
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */
class Page
{
	/**
	 *	Displays rendered Exception Page.
	 *	@access		public
	 *	@param		Throwable		$e					Exception to render View for
	 *	@param		?int			$andExitWithCode	Flag: if set, finish with exit code (0: ok, *: whatever)
	 *	@return		void
	 *	@static
	 */
	public static function display( Throwable $e, ?int $andExitWithCode = NULL ): void
	{
		print( self::render( $e ) );
		if( NULL !== $andExitWithCode )
			exit( $andExitWithCode );
	}

	/**
	 *	Returns rendered Exception Page.
	 *	@access		public
	 *	@param		Throwable		$e			Exception to render View for
	 *	@return		string
	 *	@static
	 */
	public static function render( Throwable $e ): string
	{
		return self::wrapExceptionViewWithHtmlPage( View::render( $e ) );
	}

	/**
	 *	Wraps an Exception View to an Exception Page.
	 *	@access		public
	 *	@param		string		$view		Exception View
	 *	@return		string
	 */
	public static function wrapExceptionViewWithHtmlPage( string $view ): string
	{
		$page	= new PageFrame();
		$page->setTitle( 'Exception' );
		$page->addJavaScript( '//cdn.ceusmedia.de/js/jquery/1.4.2.min.js' );
		$page->addJavaScript( '//cdn.ceusmedia.de/js/jquery/cmExceptionView/0.1.js' );
		$page->addStylesheet( '//cdn.ceusmedia.de/js/jquery/cmExceptionView/0.1.css' );
		$page->addStylesheet( '//cdn.ceusmedia.de/css/bootstrap.min.css' );
		$options	= ['foldTraces' => TRUE];
		$script		= JQuery::buildPluginCall( 'cmExceptionView', 'dl.exception', $options );
		$page->addHead( Tag::create( 'script', $script ) );
		$page->addBody( Tag::create( 'div', [
			Tag::create( 'h2', 'Error' ),
			$view
		], ['class' => 'container'] ) );
		return $page->build( ['style' => 'margin: 1em'] );
	}
}
