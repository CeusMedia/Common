<?php
/**
 *	Parses a CSS string or file and creates a structure of ADT_CSS_* objects.
 *
 *	Copyright (c) 2011-2012 Christian Würker (ceusmedia.com)
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
 *	@package		File.CSS
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2011-2012 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			10.10.2011
 *	@version		$Id$
 */
/**
 *	Parses a CSS string or file and creates a structure of ADT_CSS_* objects.
 *
 *	@category		cmClasses
 *	@package		File.CSS
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2011-2012 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			10.10.2011
 *	@version		$Id$
 */
class File_CSS_Parser{

	/**
	 *	Parses a CSS file and returns sheet structure statically.
	 *	@access		public
	 *	@param		string			$fileName	Relative or absolute file URI
	 *	@return		ADT_CSS_Sheet
	 */
	static public function parseFile( $fileName ){
		$content	= File_Reader::load( $fileName );
		return self::parseString( $content );
	}

	/**
	 *	Parses CSS properties inside a rule and returns a list of property objects.
	 *	@access		protected
	 *	@param		string			$string		String of CSS rule properties 
	 *	@return		array			List of property objects
	 */
	static protected function parseProperties( $string ){
		$list	= array();
		foreach( explode( ';', trim( $string ) ) as $line ){
			if( !trim( $line ) )
				continue;
			$parts	= explode( ':', $line );
			$key	= array_shift( $parts );
			$value	= trim( implode( ':', $parts ) );
			$list[]	= new ADT_CSS_Property( $key, $value );
		}
		return $list;
	}

	/**
	 *	Parses a CSS string and returns sheet structure statically.
	 *	@access		public
	 *	@param		string			$string		CSS string
	 *	@return		ADT_CSS_Sheet
	 */
	static public function parseString( $string ){
		if( substr_count( $string, "{" ) !== substr_count( $string, "}" ) )							//  
			throw Exception( 'Invalid paranthesis' );
		$string	= preg_replace( '/\/\*.+\*\//sU', '', $string );
		$string	= preg_replace( '/(\t|\r|\n)/s', '', $string );
		$state	= (int) ( $buffer = $key = '' );
		$sheet	= new ADT_CSS_Sheet();
		for( $i=0; $i<strlen( $string ); $i++ ){
			$char = $string[$i];
			if( !$state && $char == '{' ){
				$state	= (boolean) ( $key = trim( $buffer ) );
				$buffer	= '';
			}
			else if( $state && $char == '}' ){
				$properties	= self::parseProperties( $buffer );
				foreach( explode( ',', $key ) as $selector )
					$sheet->addRule( new ADT_CSS_Rule( $selector, $properties ) );
				$state	= (boolean) ($buffer = '');
			}
			else
				$buffer	.= $char;
		}
		return $sheet;
	}
}
?>
