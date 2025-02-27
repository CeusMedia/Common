<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

/**
 *	Builder for jQuery Plugin Calls for HTML Documents.
 *
 *	Copyright (c) 2007-2024 Christian Würker (ceusmedia.de)
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
 *	@package		CeusMedia_Common_UI_HTML
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2024 Christian Würker
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */

namespace CeusMedia\Common\UI\HTML;

use CeusMedia\Common\ADT\JSON\Encoder as JsonEncoder;

/**
 *	Builder for jQuery Plugin Calls for HTML Documents.
 *	@category		Library
 *	@package		CeusMedia_Common_UI_HTML
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2024 Christian Würker
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */
class JQuery
{
	/**	@var		string		$jQueryFunctionName		Name of jQuery Function to call, default: $ */
	public static $jQueryFunctionName	= 'jQuery';

	/**
	 *	Builds and returns JavaScript Code of jQuery Plugin Call.
	 *	@access		public
	 *	@static
	 *	@param		string		$plugin			Name of Plugin Constructor Methode
	 *	@param		string		$selector		XPath Selector of HTML Tag(s) to call Plugin on
	 *	@param		array		$options			Array of Plugin Constructor Options
	 *	@param		int			$spaces			Number of indenting Whitespaces
	 *	@return		string
	 */
	public static function buildPluginCall( string $plugin, string $selector, array $options = [], int $spaces = 0 ): string
	{
		$innerIndent	= str_repeat( " ", $spaces + 2 );
		$outerIndent	= str_repeat( " ", $spaces );
		$options		= JsonEncoder::create()->encode( $options );
		$show			= $selector ? '.show()' : "";
		$selector		= $selector ? '("'.$selector.'")' : "";
		return $outerIndent.self::$jQueryFunctionName.'(document).ready(function(){
'.$innerIndent.self::$jQueryFunctionName.$selector.'.'.$plugin.'('.$options.')'.$show.';
'.$outerIndent.'});';
	}
}
