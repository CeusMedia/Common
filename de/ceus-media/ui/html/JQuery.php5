<?php
/**
 *	Builder for jQuery Plugin Calls for HTML Documents.
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
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@copyright		2008 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			25.06.2008
 *	@version		0.1
 */
/**
 *	Builder for jQuery Plugin Calls for HTML Documents.
 *	@package		ui.html
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@copyright		2008 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			25.06.2008
 *	@version		0.1
 */
class UI_HTML_JQuery
{
	/**	@var		string		$jQueryFunctionName		Name of jQuery Function to call, default: $ */
	public static $jQueryFunctionName	= 'jQuery';
	
	/**
	 *	Builds and returns Plugin Constructor Options.
	 *	@access		protected
	 *	@param		array		$options		Array of Plugin Constructor Options
	 *	@param		int			$spaces			Number of indenting Whitespaces
	 *	@return		string
	 */
	protected static function buildOptions( $options, $spaces = 2 )
	{
		$innerIndent	= "";
		$outerIndent	= "";
		if( $spaces > 1 )
		{
			$innerIndent	= str_repeat( " ", $spaces + 2 );
			$outerIndent	= str_repeat( " ", $spaces );
		}

		if( $options )
		{
			$list	= array();
			foreach( $options as $key => $value )
			{
				if( is_array( $value ) )
					$value	= self::buildOptions( $value, $spaces + 2 );
				else if( is_bool( $value ) )
					$value	= (int) $value;
				else if( is_string( $value ) )
					$value	= '"'.$value.'"';
				if( is_int( $key ) )
					$list[]	= $value;
				else
					$list[]	= $key.": ".$value;
			}
			$options	= implode( ",\n    ", $list );
			$options	= "{\n".$innerIndent.$options."\n".$outerIndent."}";
		}
		else
			$options	= "";
		return $options;
	}

	/**
	 *	Builds and returns JavaScript Code of jQuery Plugin Call.
	 *	@access		public
	 *	@param		string		$plugin			Name of Plugin Constructor Methode
	 *	@param		string		$selector		XPath Selector of HTML Tag(s) to call Plugin on
	 *	@param		array		$option			Array of Plugin Constructor Options
	 *	@param		int			$spaces			Number of indenting Whitespaces
	 *	@return		string
	 */
	public static function buildPluginCall( $plugin, $selector, $options, $spaces = 0 )
	{
		$innerIndent	= str_repeat( " ", $spaces + 2 );
		$outerIndent	= str_repeat( " ", $spaces );
		$options		= self::buildOptions( $options, $spaces + 2 );
		$show			= $selector ? '.show()' : "";
		$selector		= $selector ? '("'.$selector.'")' : "";
		return $outerIndent.self::$jQueryFunctionName.'(document).ready(function(){
'.$innerIndent.self::$jQueryFunctionName.$selector.'.'.$plugin.'('.$options.')'.$show.';
'.$outerIndent.'});';
	}
}
?>