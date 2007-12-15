<?php
/**
 *	Base Implementation for Request to Services.
 *	@package		protocol
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.4
 */
/**
 *	Base Implementation for Request to Services.
 *	@package		protocol
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.4
 *	@deprecated		use Net_HTTP_Request_* instead
 */
class Net_HTTP_Request
{
	/**	@var	string	$host			Host IP to be connected to */
	protected $host;
	/**	@var	string	$uri			URI to request to */
	protected $uri;
	/**	@var	string	$port			Service Port of Host */
	protected $port;

 	/**
 	 *	Constructor.
 	 *	@access		public
 	 *	@param		string		$host			Host IP to be connected to
 	 *	@param		string		$uri				URI to request to
 	 *	@param		int			$port			Service Port of Host
 	 *	@return		void
 	 */
 	public function __construct( $host, $uri, $port )
	{
		$this->host = $host;
		$this->uri  = $uri;
		$this->port = $port;
	}

	/**
	 *	Sends Request to Host and return Responce.
 	 *	@access		public
 	 *	@return		string
	 */
	function send()
	{
		$result = "";
 		return $result;
	}
}
?>