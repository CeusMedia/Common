<?php
/**
 *	JSON Implementation for building JSON Code.
 *	@package		adt.json
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			14.05.2006
 *	@version		0.2
 */
/**
 *	JSON Implementation for building JSON Code.
 *	@package		adt.json
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			14.05.2006
 *	@version		0.2
 */
class ADT_JSON_Builder
{
	/**
	 *	Encodes Data into a representative String.
	 *	@access		public
	 *	@param		mixed		$data			Data to be encoded
	 *	@return		string
	 */
	public static function encode( $data )
	{
		return self::get( NULL, $data );
	}
	
	/**
	 *	Returns a representative String for a Data Pair.
	 *	@access		public
	 *	@param		string		$key			Key of Pair
	 *	@param		mixed		$value			Value of Pair
	 *	@param		string		$parent			Parent of Pair
	 *	@return		string
	 */
	public function get( $key, $value, $parent = NULL )
	{
		$type	= self::getType( $key, $value );
		switch( $type )
		{
			case 'object':
				$value	= '{'.self::loop( $value, $type ).'}';
				break;
			case 'array':
				$value	= '['.self::loop( $value, $type ).']';
				break;
			case 'number':
				$value	= $value;
				break;
			case 'string':
				$value	= '"'.self::escape( $value ).'"';
				break;
			case 'boolean':
				$value	= $value ? 'true' : 'false';
				break;
			case 'null':
				$value	= 'null';
				break;
		}
		if( !is_null( $key ) && $parent != 'array' )
			$value	= '"'.$key.'":'.$value;
		return $value;
	}

	//  --  PRIVATE METHODS  --  //
	/**
	 *	Returns Data Type of Pair Value.
	 *	@access		private
	 *	@param		string		$key			Key of Pair
	 *	@param		mixed		$value			Value of Pair
	 *	@return		string
	 */
	private static function getType( $key, $value )
	{
		if( is_object( $value ))
			$type	= 'object';
		elseif( is_array( $value ) )
			$type	= self::isAssoc( $value ) ? 'object' : 'array';
		elseif( is_int( $value ) || is_float( $value ) || is_double( $value ) )
			$type	= 'number';
		elseif( is_string( $value ) )
			$type	= 'string';
		elseif( is_bool( $value ) )
			$type	= 'boolean';
		elseif( is_null( $value ) )
			$type	= 'null';
		else
			throw new InvalidArgumentException( 'Variable "'.$key.'" is not a supported Type.' );
		return $type;
	}

	/**
	 *	Loops through Data Array and returns a representative String.
	 *	@access		private
	 *	@param		array		$array			Array to be looped
	 *	@param		string		$type			Data Type
	 *	@return		string
	 */
	private static function loop( $array, $type )
	{
		$output	= NULL;
		foreach( $array as $key => $value )
			$output	.= self::get( $key, $value, $type ).',';
		$output	= trim( $output, ',' );
		return $output;
	}

	/**
	 *	Escpapes Control Sings in String.
	 *	@access		private
	 *	@param		string		$string			String to be escaped
	 *	@return		string
	 */
	private static function escape( $string )
	{
		$replace	= array(
			'\\'	=> '\\\\',
			'"'	=> '\"',
			'/'	=> '\/',
			"\b"	=> '\b',
			"\f"	=> '\f',
			"\n"	=> '\n',
			"\r"	=> '\r',
			"\t"	=> '\t',
			"\u"	=> '\u'
			);
		$string	= str_replace( array_keys( $replace ), array_values( $replace ), $string );
		return $string;
	}

	/**
	 *	Indicates whether a array is associative or not.
	 *	@access		private
	 *	@param		array		$array			Array to be checked
	 *	@return		bool
	 */
	private static function isAssoc( $array )
	{
		krsort( $array, SORT_STRING );
		return !is_numeric( key( $array ) );
	}
}
?>