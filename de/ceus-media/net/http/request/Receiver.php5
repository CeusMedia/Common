<?php
import( 'de.ceus-media.adt.list.Dictionary' );
/**
 *	Collects and Manages Request Data.
 *	@package		net.http.request
 *	@extends		ADT_List_Dictionary
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			27.03.2006
 *	@version		0.6
 */
/**
 *	Collects and Manages Request Data.
 *	@package		net.http.request
 *	@extends		ADT_List_Dictionary
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			27.03.2006
 *	@version		0.6
 */
class Net_HTTP_Request_Receiver extends ADT_List_Dictionary
{
	/**	@var		string		$ip				IP of Request */
	protected $ip;
	/**	@var		array		$sources		Array of Sources of Request Data */
	protected $sources;

	/**
	 *	Constructor, reads and stores Data from Sources to internal Dictionary.
	 *	@access		public
	 *	@param		bool		$useSession		Flag: include Session Values
	 *	@param		bool		$useCookie		Flag: include Cookie Values
	 *	@return		void
	 */
	public function __construct( $useSession = false, $useCookie = false )
	{
		$this->sources	= array(
			"get"	=> &$_GET,
			"post"	=> &$_POST,
			"files"	=> &$_FILES,
		);
		if( $useSession )
			$this->sources['session']	=& $_SESSION;
		if( $useCookie )
			$this->sources['cookie']	=& $_COOKIE;

		$this->ip	= getEnv( 'REMOTE_ADDR' );
		foreach( $this->sources as $key => $values )
			$this->pairs	= array_merge( $this->pairs, $values );
	}
	
	/**
	 *	Reads and returns Data from Sources.
	 *	@access		public
	 *	@param		int			$source			Request Source (get,post,files[,session,cookie])
	 *	@return		array
	 */
	public function getAllFromSource( $source )
	{
		if( !in_array( $source, array_keys( $this->sources ) ) )
			throw new InvalidArgumentException( "No valid source chosen." );
		return $this->sources[$source];
	}
}
?>