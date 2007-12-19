<?php
import( 'de.ceus-media.net.http.BrowserSniffer' );
import( 'de.ceus-media.net.http.CharsetSniffer' );
import( 'de.ceus-media.net.http.EncodingSniffer' );
import( 'de.ceus-media.net.http.LanguageSniffer' );
import( 'de.ceus-media.net.http.MimeTypeSniffer' );
import( 'de.ceus-media.net.http.OperatingSystemSniffer' );
/**
 *	Combination of different Sniffers for HTTP Request to determine all information about the Client.
 *	@package		net
 *	@subpackage		http
 *	@uses			Net_HTTP_BrowserSniffer
 *	@uses			Net_HTTP_CharsetSniffer
 *	@uses			Net_HTTP_EncodingSniffer
 *	@uses			Net_HTTP_LanguageSniffer
 *	@uses			Net_HTTP_MimeTypeSniffer
 *	@uses			Net_HTTP_OperatingSystemSniffer
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			12.08.2005
 *	@version		0.6
 */
/**
 *	Combination of different Sniffers for HTTP Request to determine all information about the Client.
 *	@package		net
 *	@subpackage		http
 *	@uses			Net_HTTP_BrowserSniffer
 *	@uses			Net_HTTP_CharsetSniffer
 *	@uses			Net_HTTP_EncodingSniffer
 *	@uses			Net_HTTP_LanguageSniffer
 *	@uses			Net_HTTP_MimeTypeSniffer
 *	@uses			Net_HTTP_OperatingSystemSniffer
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			12.08.2005
 *	@version		0.6
 */
class Net_HTTP_ClientSniffer
{
	/**	@var	object	$browser_sniffer		Object of Net_HTTP_BrowserSniffer */
	protected $browser_sniffer;
	/**	@var	object	$charset_sniffer		Object of Net_HTTP_CharsetSniffer */
	protected $charset_sniffer;
	/**	@var	object	$encoding_sniffer		Object of Net_HTTP_EncodingSniffer */
	protected $encoding_sniffer;
	/**	@var	object	$language_sniffer		Object of Net_HTTP_LanguageSniffer */
	protected $language_sniffer;
	/**	@var	object	$mime_sniffer			Object of Net_HTTP_MimeTypeSniffer */
	protected $mime_sniffer;
	/**	@var	object	$os_sniffer				Object of Net_HTTP_OperatingSystemSniffer */
	protected $os_sniffer;

	/**
	 *	Constructor.
	 *	@access		public
	 *	@return		void
	 */
	public function __construct()
	{
		$this->browser	= new Net_HTTP_BrowserSniffer();
		$this->charset	= new Net_HTTP_CharsetSniffer();
		$this->encoding	= new Net_HTTP_EncodingSniffer();
		$this->language	= new Net_HTTP_LanguageSniffer();
		$this->mime		= new Net_HTTP_MimeTypeSniffer();
		$this->system	= new Net_HTTP_OperatingSystemSniffer();
	}
	
	/**
	 *	Returns IP addresse of Request.
	 *	@access		public
	 *	@return		string
	 */
	function getIp()
	{
		return getEnv( 'REMOTE_ADDR' );
	}
	
	/**
	 *	Returns prefered allowed and accepted Language of a HTTP Request.
	 *	@access		public
	 *	@param		array	$allowed			Array of Languages supported and allowed by the Application
	 *	@return		string
	 */
	function getLanguage( $allowed )
	{
		return $this->language->getLanguage( $allowed  );
	}

	/**
	 *	Returns prefered allowed and accepted Character Set of a HTTP Request.
	 *	@access		public
	 *	@param		array	$allowed			Array of Languages supported and allowed by the Application
	 *	@return		string
	 */
	function getCharset( $allowed )
	{
		return $this->charset->getCharset( $allowed  );
	}

	/**
	 *	Returns prefered allowed and accepted Mime Type of a HTTP Request.
	 *	@access		public
	 *	@param		array	$allowed			Array of Mime Types supported and allowed by the Application
	 *	@return		string
	 */
	function getMimeType( $allowed )
	{
		return $this->mime->getMimeType( $allowed  );
	}

	/**
	 *	Returns prefered allowed and accepted Encoding Methods of a HTTP Request.
	 *	@access		public
	 *	@param		array	$allowed			Array of Encoding Methods supported and allowed by the Application
	 *	@return		string
	 */
	function getEncoding( $allowed )
	{
		return $this->encoding->getEncoding( $allowed  );
	}

	/**
	 *	Returns determined Information of the Client's Operating System.
	 *	@access		public
	 *	@return		array
	 */
	function getOS()
	{
		return $this->os->getOS();
	}

	/**
	 *	Returns prefered allowed and accepted Character Set of a HTTP Request.
	 *	@access		public
	 *	@return		string
	 */
	function getBrowser()
	{
		return $this->browser->getBrowser();
	}
	
	/**
	 *	Indicates whether a HTTP Request is sent by a Search Engine Robot.
	 *	@access		public
	 *	@return		bool
	 */
	function isRobot()
	{
		return $this->browser->isRobot();
	}

	/**
	 *	Indicates whether a HTTP Request is sent by a Browser.
	 *	@access		public
	 *	@return		bool
	 */
	function isBrowser()
	{
		return $this->browser->isBrowser();
	}
}
?>