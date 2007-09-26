<?php
/**
 *	Session Management.
 *	@package		mv2.interface.core
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			05.03.2007
 *	@version		0.1
 */
/**
 *	Session Management.
 *	@package		mv2.interface.core
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			05.03.2007
 *	@version		0.1
 */
interface Framework_Krypton_Interface_Core_Session
{
	/**
	 *	Clears current Partition of Session.
	 *	@access		public
	 *	@return		void
	 */
	public function clear();
	
	/**
	 *	Returns a setting by its key name.
	 *	@access		public
	 *	@param		string		$key			Key name of setting
	 *	@return		mixed
	 */
	public function get( $key );
	
	/**
	 *	Returns all settings of this session.
	 *	@access		public
	 *	@return		array
	 */
	public function getAll();

	/**
	 *	Indicates whether a setting is set by its key name.
	 *	@access		public
	 *	@param		string		$key			Key name of setting
	 *	@return		string
	 */
	public function has( $key );

	/**
	 *	Deletes a setting of session.
	 *	@access		public
	 *	@param		string		$key		Key name of setting
	 *	@return		void
	 */
	public function remove( $key );
	
	/**
	 *	Writes a setting to session.
	 *	@access		public
	 *	@param		string		$key		Key name of setting
	 *	@param		string		$value		Value of setting
	 *	@return		void
	 */
	public function set( $key, $value );
}
?>