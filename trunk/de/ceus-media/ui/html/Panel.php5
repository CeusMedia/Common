<?php
/**
 *	Paging System for Lists.
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
 *	@package		ui.html
 *	@uses			UI_HTML_Tag
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@copyright		2008 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			21.11.2008
 *	@version		0.6
 */
import( 'de.ceus-media.ui.html.Tag' );
/**
 *	Paging System for Lists.
 *	@package		ui.html
 *	@uses			UI_HTML_Tag
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			21.11.2008
 *	@version		0.6
 */
class UI_HTML_Panel
{
	public static $class	= "panel";

	/**
	 *	Builds HTML Code of Panel.
	 *	@access		public
	 *	@param		string		$id				Tag ID of Panel
	 *	@param		string		$header			Content of Header
	 *	@param		string		$content		Content of Panel
	 *	@param		string		$footer			Content of Footer
	 *	@param		string		$class			CSS Class of Panel
	 *	@return		string
	 */
	public static function create( $header, $content, $footer = "", $class = "default", $attributes = array() )
	{
		$divHead	= self::wrap( self::wrap( $header, 'panelHeadInner' ), 'panelHead' );
		$divContent	= self::wrap( self::wrap( (string) $content, 'panelContentInner' ), 'panelContent' );
		$divFoot	= self::wrap( self::wrap( $footer, 'panelFootInner' ), 'panelFoot' );
		$class		= $class ? self::$class." ".$class : self::$class;
		$divPanel	= self::wrap( $divHead.$divContent.$divFoot, $class, $attributes );
		return $divPanel;
	}

	/**
	 *	Wraps Content in DIV.
	 *	@access		protected
	 *	@param		string		$content		...
	 *	@param		string		$class			CSS Class of DIV
	 *	@param		array		$attributes		Array of Attributes
	 *	@return		string
	 */
	protected static function wrap( $content, $class, $attributes = array() )
	{
		$attributes	= array_merge( $attributes, array( 'class' => $class ) );
		return UI_HTML_Tag::create( "div", $content, $attributes );
	}
}
?>