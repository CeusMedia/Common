<?php
import( 'de.ceus-media.adt.list.Dictionary' );
/**
 *	Collects and Manages Request Data.
 *	@package	protocol
 *	@subpackage	http
 *	@extends	Dictionary
 *	@author		Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since		27.03.2006
 *	@version		0.3
 */
/**
 *	Collects and Manages Request Data.
 *	@package	protocol
 *	@subpackage	http
 *	@extends	Dictionary
 *	@author		Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since		27.03.2006
 *	@version		0.3
 */
class HTTP_RequestReceiver extends Dictionary
{
	/**	@var	string		$_ip			IP of Request */
	var $_ip;
	/**	@var	array		$_source		Array of Sources of Request Data */
	var $_sources;

	/**
	 *	Constructor, reads and stores Data from Sources to internal Dictionary.
	 *	@access		public
	 *	@param		bool		$useSession		Flag: include Session Values
	 *	@param		bool		$useCookie		Flag: include Cookie Values
	 *	@return		void
	 */
	public function __construct( $useSession = false, $useCookie = false )
	{
		$this->_sources	= array(
			"get"	=> &$_GET,
			"post"	=> &$_POST,
			"files"	=> &$_FILES,
		);
		if( $useSession )
			$this->_sources['session']	=& $_SESSION;
		if( $useCookie )
			$this->_sources['cookie']	=& $_COOKIE;

		foreach( $this->_sources as $key => $values )
			$this->pairs	= array_merge( $this->pairs, $values );
		$this->_ip	= getEnv( 'REMOTE_ADDR' );
	}
	
	/**
	 *	Reads and returns Data from Sources.
	 *	@access		public
	 *	@param		int		$source		Request Source (get,post,files[,session,cookie])
	 *	@return		void
	 */
	function getAllFromSource( $source )
	{
		if( in_array( $source, array_keys( $this->_sources ) ) )
			return $this->_sources[$source];
		trigger_error( "[HTTP_RequestReceiver::getAllFromSource] No valid Source chosen.", E_USER_ERROR );
	}
}
?>