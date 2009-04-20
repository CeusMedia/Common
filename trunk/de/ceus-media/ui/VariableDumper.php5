<?php
/**
 *	...
 *
 *	Copyright (c) 2007-2009 Christian Würker (ceus-media.de)
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
 *	@package		ui
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2007-2009 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@todo			Code Docu
 */
define( 'SERVICE_TEST_PRINT_M', 0 );
define( 'SERVICE_TEST_VAR_DUMP', 1 );
/**
 *	...
 *	@package		ui
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2007-2009 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@todo			Code Docu
 */
class UI_VariableDumper
{
	const MODE_PRINT	= 0;
	const MODE_DUMP		= 1;

	/**
	 *	Creates readable Dump of a Variable, either with print_m or var_dump, depending on printMode and installed XDebug Extension
	 *
	 *	The custom method print_m creates lots of DOM Elements.
	 *	Having to much DOM Elements can be avoided by using var_dump, which now is called Print Mode.
	 *	But since XDebug extends var_dump it creates even way more DOM Elements.
	 *	So, you should use Print Mode and it will be disabled if XDebug is detected.
	 *	However, you can force to use Print Mode.
	 *
	 *	@access		protected
	 *	@param		mixed		$element		Variable to be dumped
	 *	@param		bool		$forcePrintMode	Flag: force to use var_dump even if XDebug is enabled (not recommended)
	 *	@return		string
	 */
	public static function dump( $variable, $mode = self::MODE_DUMP, $modeIfNotXDebug = self::MODE_PRINT )
	{
		ob_start();																	//  open Buffer
		$hasXDebug	= extension_loaded( 'xdebug' );									//  check for XDebug Extension
		if( !$hasXDebug )
			$mode	= $modeIfNotXDebug;
		switch( $mode )
		{
			case self::MODE_DUMP:
				var_dump( $variable );												//  print  Variable Dump
				if( !$hasXDebug )
				{
					$dump	= ob_get_clean();										//  get buffered Dump
					$dump	= preg_replace( "@=>\n +@", ": ", $dump );				//  remove Line Break on Relations
					$dump	= str_replace( "{\n", "\n", $dump );					//  remove Array Opener
					$dump	= str_replace( "}\n", "\n", $dump );					//  remove Array Closer
					$dump	= str_replace( ' ["', " ", $dump );						//  remove Variable Key Opener
					$dump	= str_replace( '"]:', ":", $dump );						//  remove Variable Key Closer
					$dump	= preg_replace( '@string\([0-9]+\)@', "", $dump );		//  remove Variable Type for Strings
					$dump	= preg_replace( '@array\([0-9]+\)@', "", $dump );		//  remove Variable Type for Arrays
					ob_start();														//  open Buffer
					xmp( $dump );													//  print Dump with XMP
				}
				break;
			case self::MODE_PRINT:
				print_m( $variable, ". ", 2 );										//  print Dump with 2 Dots as Indent Space
				break;
		}
		return ob_get_clean();														//  return buffered Dump
	}
}
function dumpVar( $variable, $mode = UI_VariableDumper::MODE_DUMP, $modeIfNotXDebug = UI_VariableDumper::MODE_PRINT )
{
	return UI_VariableDumper::dump( $variable, $mode, $modeIfNotXDebug );
}
?>