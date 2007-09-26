<?php
import( 'de.ceus-media.protocol.http.Session' );
/**
 *	Session Management.
 *	@package		protocol
 *	@subpackage		http
 *	@extends		Session
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			26.07.2005
 *	@version		0.1
 */
/**
 *	Session Management.
 *	@package		protocol
 *	@subpackage		http
 *	@extends		Session
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			26.07.2005
 *	@version		0.1
 */
class PartitionSession extends Session
{
	/**	@var	string	$_partition			Name of focused Partion in Session Data */
	var $_partition;
	/**	@var	array	$_partition_data		Reference to Partion in Session Data */
	var $_partition_data;

	/**
	 *	Constructor.
	 *	@access		public
	 *	@return		void
	 */
	public function __construct( $partition = false, $session_name = false)
	{
		if( $partition )
		{
			if( $session_name )
				$this->openSession( $partition, $session_name );		
			else
				$this->openSession( $partition );		
		}
	}

	/**
	 *	Clears current Partition of Session.
	 *	@access		public
	 *	@return		void
	 */
	function clear()
	{
		foreach( $this->_partition_data as $key => $value )
			unset( $this->_partition_data[$key] );
		$ip = getEnv( 'REMOTE_ADDR' );
		$this->_session_data['ip'] = $ip;
	}

	/**
	 *	Returns a setting by its key name.
	 *	@access		public
	 *	@param		string		$key		Key name of setting
	 *	@return		mixed
	 */
	function get( $key )
	{
		if( $this->has( $key ) )
			return $this->_partition_data[$key];
		return NULL;
	}
	
	/**
	 *	Returns all settings of this session.
	 *	@access		public
	 *	@return		array
	 */
	function getAll()
	{
		return $this->_partition_data;
	}
	
	/**
	 *	Returns name of Partition.
	 *	@access		public
	 *	@return		string
	 */
	function getPartition()
	{
		return $this->_partition;
	}

	/**
	 *	Indicates whether a setting is set by its key name.
	 *	@access		public
	 *	@param		string		$key		Key name of setting
	 *	@return		string
	 */
	function has( $key )
	{
		return isset( $this->_partition_data[$key] );
	}

	/**
	 *	Opens Session.
	 *	@access		public
	 *	@param		string		$partition		Partition of Session Data
	 *	@param		string		$session_name	Name of Session ID
	 *	@return		void
	 */
	function openSession( $partition = 'default', $session_name = 'sid' )
	{
		$this->_setPartition( $partition );
		$this->_setSessionName( $session_name );
		$ip = getEnv( 'REMOTE_ADDR' );
		session_start ();
		$this->_session_data =& $_SESSION;
		if( !isset( $this->_session_data['ip'] ) )
			$this->_session_data['ip'] = $ip;
		else if( $this->_session_data['ip'] != $ip )								//  HiJack Attempt
		{
			session_regenerate_id();
			$this->_session_data =& $_SESSION;
			foreach( $this->_session_data as $key => $value )
				unset( $this->_session_data[$key] );
			$this->_session_data['ip'] = $ip;
		}
		$this->_partition_data =& $this->_session_data['partitions'][$this->getPartition()];
		$this->_open = true;
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
			unset( $this->_partition_data[$key]);	
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
		$this->_partition_data[$key] = $value;
	}
	
	/**
	 *	Sets name of Partition.
	 *	@access		private
	 *	@param		string		$partition		Partition of Session Data
	 *	@return		void
	 */
	function _setPartition( $partition )
	{
		$this->_partition = $partition;
//		if( !is_array( $_SESSION[$this->getNameSpace()] )
//			$_SESSION[$this->getNameSpace()] = array();
	}
}
?>