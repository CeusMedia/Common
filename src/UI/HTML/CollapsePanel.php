<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

/**
 *	User Interface Component to build a Panel which can be expanded and collapsed.
 *
 *	Copyright (c) 2007-2023 Christian Würker (ceusmedia.de)
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
 *	@package		CeusMedia_Common_UI_HTML
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2023 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */

namespace CeusMedia\Common\UI\HTML;

/**
 *	User Interface Component to build a Panel which can be expanded and collapsed.
 *	@category		Library
 *	@package		CeusMedia_Common_UI_HTML
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2023 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */
class CollapsePanel extends Panel
{
	public static string $classPanel	= "collapsable";

	/**
	 *	Builds HTML Code of Panel.
	 *	@access		public
	 *	@static
	 *	@param		string			$id					Tag ID of Panel
	 *	@param		mixed			$content			Content of Panel
	 *	@param		mixed|NULL		$header				Content of Header
	 *	@param		mixed|NULL		$abstract			Content of Abstract
	 *	@param		mixed|NULL		$footer				Content of Footer
	 *	@param		string			$theme				Theme to apply, default: default
	 *	@param		array			$attributes			Map of Attributes of Panel DIV
	 *	@return		string
	 */
	public static function create( string $id, $content, $header = NULL, $abstract = NULL, $footer = NULL, string $theme = "default", array $attributes = [] ): string
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
	public static function createScript( string $selector, array $options = [] ): string
	{
		return JQuery::buildPluginCall( "cmCollapsePanel", $selector, $options );
	}
}
