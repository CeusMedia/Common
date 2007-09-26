<?php
import( 'de.ceus-media.protocol.http.Cookie' );
/**
 *	Partitioned Cookie Management.
 *	@package		protocol
 *	@subpackage		http
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			11.08.2005
 *	@version		0.1
 */
/**
 *	Partitioned Cookie Management.
 *	@package		protocol
 *	@subpackage		http
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			11.08.2005
 *	@version		0.1
 */
class PartitionCookie extends Cookie
{
	/**	@var	array	$_cake			Reference to Cookie */
	var $_cake;
	/**	@var	string	$_partition		Name of Partition in PartitionCookie */
	var $_partition;
	
	/**
	 *	Constructor.
	 *	@access		public
	 *	@return		void
	 */
	public function __construct ( $partition )
	{
		$this->_partition = $partition;
		$this->_cake =& $_COOKIE[$partition];
		$pairs = explode ("@", $this->_cake);
		foreach ($pairs as $pair)
		{
			if (trim($pair))
			{
				$parts = explode (":", $pair);
				$this->_cookie_data[$parts[0]] = $parts[1];
			}
		}
	}

	/**
	 *	Returns a Cookie by its key.
	 *	@access		public
	 *	@param		string		$key		Key of Cookie
	 *	@return		mixed
	 */
	function get( $key )
	{
		if( isset( $this->_cookie_data[$key] ) )
			return $this->_cookie_data[$key];
		return NULL;
	}
	
	/**
	 *	Returns all Cookies of this PartitionCookie.
	 *	@access		public
	 *	@return		array
	 */
	function getAll ()
	{
		return $this->_cookie_data;
	}

	/**
	 *	Sets a Cookie to this PartitionCookie.
	 *	@access		public
	 *	@param		string		$key		Key of Cookie
	 *	@param		string		$value		Value of Cookie
	 *	@return		void
	 */
	function set( $key, $value )
	{
		$this->_cookie_data[$key] = $value;
		$this->_saveCake();
	}

	/**
	 *	Saves PartitionCookie by sending to Browser.
	 *	@access		public
	 *	@return		void
	 */
	function _saveCake()
	{
		$cake	= array();
		foreach( $this->_cookie_data as $key => $value )
		$cake[]	= $key.":".$value;
		$cake	= implode( "@", $cake );
		setCookie( $this->_partition, $cake );
	}
		
	/**
	 *	Deletes a Cookie of this PartitionCookie.
	 *	@access		public
	 *	@param		string		$key		Key of Cookie
	 *	@return		void
	 */
	function remove ($key )
	{
		if( isset( $this->_cookie_data[$key] ) )
			unset( $this->_cookie_data[$key] );	
		$this->_saveCake();
	}
}
?>