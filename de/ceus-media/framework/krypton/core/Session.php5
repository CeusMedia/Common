<?php
import( 'de.ceus-media.framework.krypton.interface.core.Session' );
/**
 *	Session Management.
 *	@package		mv2.core
 *	@implements		Framework_Krypton_Interface_Core_Session
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			21.02.2007
 *	@version		0.2
 */
/**
 *	Session Management.
 *	@package		mv2.core
 *	@implements		Framework_Krypton_Interface_Core_Session
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			21.02.2007
 *	@version		0.2
 */
class Framework_Krypton_Core_Session implements Framework_Krypton_Interface_Core_Session
{
	/**	@var	array	$values			Associative Array of stored Pairs within Session */
	protected $values	= array();

	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		string		$name		Name of Session
	 *	@return		void
	 */
	public function __construct( $name = null )
	{
		if( $session_name )
			session_name( $name );
		session_start();
		$this->values =& $_SESSION;
	}

	/**
	 *	Destructor.
	 *	@access		public
	 *	@return		void
	 */
	public function __destruct()
	{
		session_write_close();
	}

	/**
	 *	Clears current Partition of Session.
	 *	@access		public
	 *	@return		void
	 */
	public function clear()
	{
		foreach( $this->values as $key => $value )
			unset( $this->values[$key] );
	}
	
	/**
	 *	Returns a setting by its key name.
	 *	@access		public
	 *	@param		string		$key			Key name of setting
	 *	@return		mixed
	 */
	public function get( $key )
	{
		if( $this->has( $key ) )
			return $this->values [$key];
		return NULL;
	}
	
	/**
	 *	Returns all settings of this session.
	 *	@access		public
	 *	@return		array
	 */
	public function getAll()
	{
		return $this->values;
	}

	/**
	 *	Indicates whether a setting is set by its key name.
	 *	@access		public
	 *	@param		string		$key			Key name of setting
	 *	@return		string
	 */
	public function has( $key )
	{
		return isset( $this->values[$key] );
	}

	/**
	 *	Deletes a setting of session.
	 *	@access		public
	 *	@param		string		$key		Key name of setting
	 *	@return		void
	 */
	function remove( $key )
	{
		if( $this->has( $key ) )
			unset( $this->values[$key] );	
	}
	
	/**
	 *	Writes a setting to session.
	 *	@access		public
	 *	@param		string		$key		Key name of setting
	 *	@param		string		$value		Value of setting
	 *	@return		void
	 */
	function set( $key, $value )
	{
		$this->values[$key] = $value;
	}
}
?>