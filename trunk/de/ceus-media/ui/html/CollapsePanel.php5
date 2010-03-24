<?php
/**
 *	User Interface Component to build a Panel which can be expanded and collapsed.
 *
 *	Copyright (c) 2007-2010 Christian Würker (ceus-media.de)
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
 *	@package		ui.html
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2007-2010 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@version		0.1
 */
import( 'de.ceus-media.ui.html.Panel' );
import( 'de.ceus-media.ui.html.JQuery' );
/**
 *	User Interface Component to build a Panel which can be expanded and collapsed.
 *	@category		cmClasses
 *	@package		ui.html
 *	@extends		UI_HTML_Panel
 *	@uses			UI_HTML_JQuery
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2007-2010 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@version		0.1
 */
class UI_HTML_CollapsePanel extends UI_HTML_Panel
{
	public static $classPanel	= "collapsable";

	/**
	 *	Builds HTML Code of Panel.
	 *	@access		public
	 *	@static
	 *	@param		string		$id					Tag ID of Panel
	 *	@param		string		$content			Content of Panel
	 *	@param		string		$header				Content of Header
	 *	@param		string		$abstract			Content of Abstract
	 *	@param		string		$footer				Content of Footer
	 *	@param		string		$class				CSS Class of Panel
	 *	@param		array		$attributes			Map of Attributes of Panel DIV
	 *	@return		string
	 */
	public static function create( $id, $content, $header = NULL, $abstract = NULL, $footer = NULL, $theme = "default", $attributes = array() )
	{
		$classes	= $theme ? self::$classPanel." ".$theme : self::$classPanel;
		return parent::create( $id, $content, $header, $abstract, $footer, $classes, $attributes );
	}

	/**
	 *	Builds JQuery Plugin Call for Panel.
	 *	@access		public
	 *	@static
	 *	@param		string		$selector			CSS Selector of Panel
	 *	@param		array		$options			JQuery Plugin Options
	 *	@return		string
	 *	@todo		change selector to id
	 */
	public static function createScript( $selector, $options = array() )
	{
		return UI_HTML_JQuery::buildPluginCall( "cmCollapsePanel", $selector, $options );	
	}
}
?>