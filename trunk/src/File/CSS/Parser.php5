<?php
/**
 *	Parses a CSS string or file and creates a structure of ADT_CSS_* objects.
 *
 *	@author		Christian Würker <christian.wuerker@ceusmedia.de>
 *	@since		10.10.2011
 *	@version	$Id$
 */
/**
 *	Parses a CSS string or file and creates a structure of ADT_CSS_* objects.
 *
 *	@author		Christian Würker <christian.wuerker@ceusmedia.de>
 *	@since		10.10.2011
 *	@version	$Id$
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
}
?>
