<?php
/**
 *	Setup for Resource Environment of Tool.
 *	@package		tool
 *	@uses			File_Configuration_Reader
 *	@uses			Net_HTTP_Request_Receiver
 *	@uses			Net_HTTP_Request_Response
 *	@uses			Net_HTTP_Session
 *	@uses			Logic
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			27.05.2008
 *	@version		0.1
 */
import( 'de.ceus-media.file.configuration.Reader' );
import( 'de.ceus-media.net.http.request.Receiver' );
import( 'de.ceus-media.net.http.request.Response' );
import( 'de.ceus-media.net.http.Session' );
import( 'classes.Logic' );
/**
 *	Setup for Environment of Tool.
 *	@package		tool
 *	@uses			File_Configuration_Reader
 *	@uses			Net_HTTP_Request_Receiver
 *	@uses			Net_HTTP_Request_Response
 *	@uses			Net_HTTP_Session
 *	@uses			Logic
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			27.05.2008
 *	@version		0.1
 */
class Environment
{
	/**	@var	File_Configuration_Reader	$config		Configuration Object */
	private $config;
	/**	@var	Net_HTTP_Request_Receiver	$request	HTTP Request Object */
	private $request;
	/**	@var	Net_HTTP_Request_Response	$request	HTTP Response Object */
	private $response;
	/**	@var	Net_HTTP_Session			$session	Session Object */
	private $session;
	/**	@var	Logic						$logic		Logic Object */
	private $logic;

	/**
	 *	Constructor, sets up Resource Environment.
	 *	@access		public
	 *	@param		string		$configFile		File Name of Configuration File
	 *	@return		void
	 */
	public function __construct( $configFile = "config.ini" )
	{
		$this->config	= new File_Configuration_Reader( $configFile );
		$this->logic	= new Logic( $this->config );
		$this->request	= new Net_HTTP_Request_Receiver();
		$this->response	= new Net_HTTP_Request_Response();
		$this->session	= new Net_HTTP_Session();
		ob_start();
	}
	
	/**
	 *	Returns Configuration Object.
	 *	@access		public
	 *	@return		File_Configuration_Reader
	 */
	public function getConfiguration()
	{
		return $this->config;
	}
	
	/**
	 *	Returns Logic Object.
	 *	@access		public
	 *	@return		Logic
	 */
	public function getLogic()
	{
		return $this->logic;
	}
	
	/**
	 *	Returns Request Object.
	 *	@access		public
	 *	@return		Net_HTTP_Request_Receiver
	 */
	public function getRequest()
	{
		return $this->request;
	}
	
	/**
	 *	Returns HTTP Response Object.
	 *	@access		public
	 *	@return		Net_HTTP_Request_Response
	 */
	public function getResponse()
	{
		return $this->response;
	}
	
	/**
	 *	Returns Configuration Object.
	 *	@access		public
	 *	@return		File_Configuration_Reader
	 */
	public function getSession()
	{
		return $this->session;
	}
}
?>