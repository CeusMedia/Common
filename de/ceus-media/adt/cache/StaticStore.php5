<?php
/**
 *	Abstract static Cache Store, can be used to implement a static Data Cache.
 *	@package		adt.cache
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			13.04.2009
 *	@version		0.1
 */
/**
 *	Abstract static Cache Store, can be used to implement a static Data Cache.
 *	@package		adt.cache
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			13.04.2009
 *	@version		0.1
 */
abstract class ADT_Cache_StaticStore
{
	/**
	 *	Returns a Value from Cache by its Key.
	 *	@access		public
	 *	@param		string		$key			Key of Cache File
	 *	@return		mixed
	 */
	abstract public static function get( $key );

	/**
	 *	Indicates wheter a Value is in Cache by its Key.
	 *	@access		public
	 *	@param		string		$key			Key of Cache File
	 *	@return		void
	 */
	abstract public static function has( $key );

	/**
	 *	Removes a Value from Cache by its Key.
	 *	@access		public
	 *	@param		string		$key			Key of Cache File
	 *	@return		void
	 */
	abstract public static function remove( $key );

	/**
	 *	Stores a Value in Cache by its Key.
	 *	@access		public
	 *	@param		string		$key			Key of Cache File
	 *	@param		mixed		$value			Value to store
	 *	@return		void
	 */
	abstract public static function set( $key, $value );
}
?>