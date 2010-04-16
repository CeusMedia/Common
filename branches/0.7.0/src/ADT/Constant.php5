<?php
/**
 *	Sets and gets constant values.
 *	List all constants with a given prefix.
 *	@category		cmClasses
 *	@package		adt
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2010 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			0.7.0
 *	@version		$Id$
 */
/**
 *	Sets and gets constant values.
 *	List all constants with a given prefix.
 *	@category		cmClasses
 *	@package		adt
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2010 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			0.7.0
 *	@version		$Id$
 */
class ADT_Constant
{
	/**
	 *	Returns the Value of a set Constant, throws Exception otherwise.
	 *	@access		public
	 *	@param		string		$key		Name of Constant to return
	 *	@return		mixed
	 *	@todo		finish impl
	 */
	public static function get( $key )
	{
		$key	= strtoupper( $key );
		if( self::has( $key ) )
			return constant( $key );
		throw new InvalidArgumentException( 'Constant "'.$key.'" is not set' );
	}

	/**
	 *	Returns a Map of defined Constants.
	 *	@access		public
	 *	@return		array
	 */
	public function getAll( $prefix = NULL )
	{
		if( !$prefix )
			return get_defined_constants();
		$prefix	= strtoupper( $prefix );
		$length	= strlen( $prefix );
		if( $length	< 2 )
			throw new InvalidArgumentException( 'Prefix "'.$prefix.'" is to short.' );
		$map	= get_defined_constants();
		foreach( $map as $key => $value )
		{
			if( $key[0] !== $prefix[0] )
				unset( $map[$key] );
			else if( $key[1] !== $prefix[1] )
				unset( $map[$key] );
			else if( substr( $key, 0, $length ) !== $prefix )
				unset( $map[$key] );
#			remark( $prefix." - ".$key." - ".(int)isset( $map[$key] ) );
		}
		return $map;
	}

	/**
	 *	Indicates whether a Constant has been set by its Name.
	 *	@access		public
	 *	@param		string		$key		Name of Constant to check
	 *	@return		bool
	 */
	public static function has( $key )
	{
		$key	= strtoupper( $key );
		return defined( $key );
	}

	/**
	 *	Sets a Constant.
	 *	@access		public
	 *	@static
	 *	@param		string		$key		Name of Constant to set
	 *	@param		mixed		$value		Value of Constant to set
	 *	@param		bool		$strict		Flag: set only if unset
	 *	@return		bool
	 *	@throws		RuntimeException		if Constant has already been set
	 */
	public static function set( $key, $value, $strict = TRUE )
	{
		$key	= strtoupper( $key );
		if( defined( $key ) && $strict )
			throw new RuntimeException( 'Constant "'.$key.'" is already defined.' );
		return define( $key, $value );
	}
}
?>