<?php
/**
 *	Base Implementation for Request to Services.
 *	@package	protocol
 *	@author		Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.4
 */
/**
 *	Base Implementation for Request to Services.
 *	@package	protocol
 *	@author		Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.4
 */
class Request
{
	/**	@var	string	$_host			Host IP to be connected to */
	var $_host;
	/**	@var	string	$_uri			URI to request to */
	var $_uri;
	/**	@var	string	$_port			Service Port of Host */
	var $_port;

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
		$this->_host = $host;
		$this->_uri  = $uri;
		$this->_port = $port;
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