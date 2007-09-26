<?php
/**
 *	Interface of Requests.
 *	@package		mv2.interface.core
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			05.03.2007
 *	@version		0.1
 */
/**
 *	Interface of Requests.
 *	@package		mv2.interface.core
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			05.03.2007
 *	@version		0.1
 */
interface Framework_Krypton_Interface_Core_Request
{
	/**
	 *	Returns a Value by its Key.
	 *	@access		public
	 *	@param		string		$key		Key of Request Value
	 *	@return		mixed
	 */
	public function get( $key );

	/**
	 *	Returns Array of Keys and Values.
	 *	@access		public
	 *	@return		array
	 */
	public function getAll();

	/**
	 *	Indicates whether a Key is registered.
	 *	@access		public
	 *	@param		string		$key		Key to be checked
	 *	@return		bool
	 */
	public function has( $key );

	/**
	 *	Sets Value by its Key.
	 *	@access		public
	 *	@param		string		$key		Key of Request Value
	 *	@param		mixed		$value 		Value to be set for Key
	 *	@return		bool
	 */
	public function set( $key, $value );

	/**
	 *	Removes a Value by its Key.
	 *	@access		public
	 *	@param		string		$key		Key of Request Value
	 *	@return		bool
	 */
	public function remove( $key );
}
?>
