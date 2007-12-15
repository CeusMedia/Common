<?php
/**
 *	Header for HTTP Requests.
 *	@package		net.http.request
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.5
 */
/**
 *	Header for HTTP Requests.
 *	@package		net.http.request
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.5
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
		if( $this->key )
			return $this->key.": ".$this->value."\r\n";
		else
			return "\r\n";
	}
}
?>