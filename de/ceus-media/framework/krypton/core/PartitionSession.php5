<?php
import( 'de.ceus-media.framework.krypton.core.Session' );
/**
 *	Session Management for Partitions.
 *	@package		mv2.core
 *	@extends		Framework_Krypton_Core_Session
 *	@extends		Session
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			05.03.2007
 *	@version		0.2
 */
/**
 *	Session Management for Partitions.
 *	@package		mv2.core
 *	@extends		Framework_Krypton_Core_Session
 *	@extends		Session
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			05.03.2007
 *	@version		0.2
 */
class Framework_Krypton_Core_PartitionSession extends Framework_Krypton_Core_Session
{
	/**	@var	string	$partition			Name of focused Partion in Session */
	protected $partition;

	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		string		$partition	Name of Partition within Session
	 *	@param		string		$name		Name of Session
	 *	@return		void
	 */
	function __construct( $partition, $name = null )
	{
		if( $name )
			session_name( $name );
		session_start ();
		$ip = getEnv( 'REMOTE_ADDR' );
		if( !isset( $_SESSION['ip'] ) )
			$_SESSION['ip'] = $ip;
		else if( $_SESSION['ip'] != $ip )								//  HiJack Attempt
		{
			session_regenerate_id();
			foreach( $_SESSION as $key => $value )
				unset( $_SESSION[$key] );
			$_SESSION['ip'] = $ip;
		}
		$this->values =& $_SESSION['partitions'][$partition];
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
	function clear()
	{
		foreach( $this->values as $key => $value )
			unset( $this->values[$key] );
		$_SESSION['ip'] = getEnv( 'REMOTE_ADDR' );
	}

	/**
	 *	Returns a setting by its key name.
	 *	@access		public
	 *	@param		string		$key		Key of set Value
	 *	@return		mixed
	 */
	function get( $key )
	{
		if( $this->has( $key ) )
			return $this->values[$key];
		return NULL;
	}
	
	/**
	 *	Returns all settings of this session.
	 *	@access		public
	 *	@return		array
	 */
/*	function getAll()
	{
		return $this->values;
	}*/

	/**
	 *	Indicates whether a setting is set by its key name.
	 *	@access		public
	 *	@param		string		$key		Key to be checked
	 *	@return		bool
	 */
	function has( $key )
	{
		return isset( $this->values[$key] );
	}

	/**
	 *	Deletes a setting of session.
	 *	@access		public
	 *	@param		string		$key		Key of set Value to be removed
	 *	@return		void
	 */
	function remove( $key )
	{
		if( $this->has( $key ) )
		{
			unset( $this->values[$key]);	
			return true;
		}
		return false;
	}
	
	/**
	 *	Writes a setting to session.
	 *	@access		public
	 *	@param		string		$key		Key of set Value
	 *	@param		string		$value		Value to be stored
	 *	@return		bool
	 */
	function set( $key, $value )
	{
		$this->values[$key] = $value;
		return true;
	}
}
?>