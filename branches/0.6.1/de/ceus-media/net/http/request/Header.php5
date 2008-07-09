<?php
/**
 *	Header for HTTP Requests.
 *	@package		net.http.request
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.6
 */
/**
 *	Header for HTTP Requests.
 *	@package		net.http.request
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.6
 */
class Net_HTTP_Request_Header
{
	/**	@var		string		$key		Key of Header */
	protected $key;
	/**	@var		string		$value		Value of Header */
	protected $value;
	
	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		string		$key		Key of Header
	 *	@param		string		$value		Value of Header
	 *	@return		void
	 */
	public function __construct( $key, $value )
	{
		$this->key		= $key;
		$this->value	= $value;	
	}
	
	/**
	 *	Returns a representative string of Header.
	 *	@access		public
	 *	@return		string
	 */
	public function toString()
	{
		if( !trim( $this->key ) )
			return "";
		
		$list	= array();
		$parts	= explode( "-", $this->key );
		foreach( $parts as $part )
			$list[]	= ucFirst( strtolower( $part ) );
		$key	= implode( "-", $list );
		$string	= $key.": ".$this->value."\r\n";
		return $string;
	}
}
?>