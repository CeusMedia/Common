<?php
/**
 *	Visualisation of Exception.
 *
 *	Copyright (c) 2010 Christian Würker (ceus-media.de)
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
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2010 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			0.7.0
 *	@version		$Id$
 */
/**
 *	Visualisation of Exception Stack Trace.
 *	@category		cmClasses
 *	@package		UI.HTML.Exception
 *	@uses			Alg_Text_Trimmer
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2010 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			0.7.0
 *	@version		$Id$
 */
class UI_HTML_Exception_View
{
	/**
	 *	Prints exception view.
	 *	@access		public
	 *	@param		Exception	$exception		Exception
	 *	@return		void
	 */
	public static function display( Exception $e )
	{
		print self::render( $e );
	}

	public static function render( Exception $e )
	{
		$list	= array();
		$list[]	= UI_HTML_Tag::create( 'dt', 'Type' );
		$list[]	= UI_HTML_Tag::create( 'dd', get_class( $e ) );

		$list[]	= UI_HTML_Tag::create( 'dt', 'Message' );
		$list[]	= UI_HTML_Tag::create( 'dd', $e->getMessage() );

		$list[]	= UI_HTML_Tag::create( 'dt', 'code' );
		$list[]	= UI_HTML_Tag::create( 'dd', $e->getCode() );

		$list[]	= UI_HTML_Tag::create( 'dt', 'File' );
		$list[]	= UI_HTML_Tag::create( 'dd', self::trimRootPath( $e->getFile() ) );

		$list[]	= UI_HTML_Tag::create( 'dt', 'Line' );
		$list[]	= UI_HTML_Tag::create( 'dd', $e->getLine() );

		$trace	= new UI_HTML_Exception_Trace( $e );
		if( $trace )
		{
			$list[]	= UI_HTML_Tag::create( 'dt', 'Trace' );
			$list[]	= UI_HTML_Tag::create( 'dd', $trace->render() );
		}
		if( method_exists( $e, 'getPrevious' ) && $e->getPrevious() )
		{
			$trace	= new UI_HTML_Exception_Trace( $e->getPrevious() );
			$list[]	= UI_HTML_Tag::create( 'dt', 'Previous' );
			$list[]	= UI_HTML_Tag::create( 'dd', $trace->render() );
		}
		return UI_HTML_Tag::create( 'dl', join( $list ), array( 'class' => 'exception' ) );
	}


	/**
	 *	Removes Document Root in File Names.
	 *	@access		protected
	 *	@static
	 *	@param		string		$fileName		File Name to clear
	 *	@return		string
	 */
	protected static function trimRootPath( $fileName )
	{
		$rootPath	= isset( $_SERVER['DOCUMENT_ROOT'] ) ? $_SERVER['DOCUMENT_ROOT'] : "";
		if( !$rootPath || !$fileName )
			return;
		$fileName	= str_replace( '\\', "/", $fileName );
		$cut		= substr( $fileName, 0, strlen( $rootPath ) );
		if( $cut == $rootPath )
			$fileName	= substr( $fileName, strlen( $rootPath ) );
		return $fileName;
	}
}
?>