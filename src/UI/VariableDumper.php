<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

/**
 *	...
 *
 *	Copyright (c) 2007-2022 Christian Würker (ceusmedia.de)
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
 *	@package		CeusMedia_Common_UI
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2022 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */

namespace CeusMedia\Common\UI;

define( 'SERVICE_TEST_PRINT_M', 0 );
define( 'SERVICE_TEST_VAR_DUMP', 1 );

/**
 *	...
 *	@category		Library
 *	@package		CeusMedia_Common_UI
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2022 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 *	@todo			Code Doc
 */
class VariableDumper
{
	const MODE_PRINT	= 0;
	const MODE_DUMP		= 1;

	public static $modePrintIndentSign	= ". ";
	public static $modePrintIndentSize	= 2;

	/**
	 *	Creates readable Dump of a Variable, either with print_m or var_dump, depending on printMode and installed XDebug Extension
	 *
	 *	The custom method print_m creates lots of DOM Elements.
	 *	Having too much DOM Elements can be avoided by using var_dump, which now is called Print Mode.
	 *	But since XDebug extends var_dump it creates even way more DOM Elements.
	 *	So, you should use Print Mode, and it will be disabled if XDebug is detected.
	 *	However, you can force to use Print Mode.
	 *
	 *	@access		protected
	 *	@static
	 *	@param		mixed		$variable			Variable to be dumped
	 *	@param		integer		$mode				Mode: MODE_PRINT | MODE_DUMP, default: MODE_DUMP
	 *	@param		integer		$modeIfNotXDebug	Mode to use if xdebug is not installed
	 *	@return		string
	 */
	public static function dump( $variable, int $mode = self::MODE_DUMP, int $modeIfNotXDebug = self::MODE_PRINT ): string
	{
		//  open Buffer
		ob_start();
		//  check for XDebug Extension
		$hasXDebug	= extension_loaded( 'xdebug' );
		if( !$hasXDebug )
			$mode	= $modeIfNotXDebug;
		switch( $mode ){
			case self::MODE_DUMP:
				//  print  Variable Dump
				var_dump( $variable );
				if( !$hasXDebug ){
					//  get buffered Dump
					$dump	= ob_get_clean();
					//  remove Line Break on Relations
					$dump	= preg_replace( "@=>\n +@", ": ", $dump );
					//  remove Array Opener
					$dump	= str_replace( "{\n", "\n", $dump );
					//  remove Array Closer
					$dump	= str_replace( "}\n", "\n", $dump );
					//  remove Variable Key Opener
					$dump	= str_replace( ' ["', " ", $dump );
					//  remove Variable Key Closer
					$dump	= str_replace( '"]:', ":", $dump );
					//  remove Variable Type for Strings
					$dump	= preg_replace( '@string\([0-9]+\)@', "", $dump );
					//  remove Variable Type for Arrays
					$dump	= preg_replace( '@array\([0-9]+\)@', "", $dump );
					//  open Buffer
					ob_start();
					//  print Dump with XMP
					xmp( $dump );
				}
				break;
			case self::MODE_PRINT:
				//  print Dump with indent
				print_m( $variable, self::$modePrintIndentSign, self::$modePrintIndentSize );
				break;
		}
		//  return buffered Dump
		return ob_get_clean();
	}
}
function dumpVar( string $variable, int $mode = VariableDumper::MODE_DUMP, int $modeIfNotXDebug = VariableDumper::MODE_PRINT ): string
{
	return VariableDumper::dump( $variable, $mode, $modeIfNotXDebug );
}
