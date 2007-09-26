<?php
import( 'de.ceus-media.protocol.http.HTTP_BrowserSniffer' );
import( 'de.ceus-media.protocol.http.HTTP_CharsetSniffer' );
import( 'de.ceus-media.protocol.http.HTTP_EncodingSniffer' );
import( 'de.ceus-media.protocol.http.HTTP_LanguageSniffer' );
import( 'de.ceus-media.protocol.http.HTTP_MimeTypeSniffer' );
import( 'de.ceus-media.protocol.http.HTTP_OperatingSystemSniffer' );
/**
 *	Combination of different Sniffers for HTTP Request to determine all information about the Client.
 *	@package		protocol
 *	@subpackage		http
 *	@uses			HTTP_BrowserSniffer
 *	@uses			HTTP_CharsetSniffer
 *	@uses			HTTP_EncodingSniffer
 *	@uses			HTTP_LanguageSniffer
 *	@uses			HTTP_MimeTypeSniffer
 *	@uses			HTTP_OperatingSystemSniffer
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			12.08.2005
 *	@version		0.1
 */
/**
 *	Combination of different Sniffers for HTTP Request to determine all information about the Client.
 *	@package		protocol
 *	@subpackage		http
 *	@uses			HTTP_BrowserSniffer
 *	@uses			HTTP_CharsetSniffer
 *	@uses			HTTP_EncodingSniffer
 *	@uses			HTTP_LanguageSniffer
 *	@uses			HTTP_MimeTypeSniffer
 *	@uses			HTTP_OperatingSystemSniffer
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			12.08.2005
 *	@version		0.1
 */
class HTTP_ClientSniffer
{
	/**	@var	object	$_browser_sniffer		Object of HTTP_BrowserSniffer */
	var $_browser_sniffer;
	/**	@var	object	$_charset_sniffer		Object of HTTP_CharsetSniffer */
	var $_charset_sniffer;
	/**	@var	object	$_encoding_sniffer		Object of HTTP_EncodingSniffer */
	var $_encoding_sniffer;
	/**	@var	object	$_language_sniffer		Object of HTTP_LanguageSniffer */
	var $_language_sniffer;
	/**	@var	object	$_mime_sniffer		Object of HTTP_MimeTypeSniffer */
	var $_mime_sniffer;
	/**	@var	object	$_os_sniffer			Object of HTTP_OperatingSystemSniffer */
	var $_os_sniffer;

	/**
	 *	Constructor.
	 *	@access		public
	 *	@return		void
	 */
	public function __construct()
	{
		$this->_browser_sniffer		= new HTTP_BrowserSniffer ();
		$this->_charset_sniffer		= new HTTP_CharsetSniffer ();
		$this->_encoding_sniffer	= new HTTP_EncodingSniffer ();
		$this->_language_sniffer	= new HTTP_LanguageSniffer ();
		$this->_mime_sniffer		= new HTTP_MimeTypeSniffer ();
		$this->_os_sniffer			= new HTTP_OperatingSystemSniffer ();
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
		return $this->_language_sniffer->getLanguage( $allowed  );
	}

	/**
	 *	Returns prefered allowed and accepted Character Set of a HTTP Request.
	 *	@access		public
	 *	@param		array	$allowed			Array of Languages supported and allowed by the Application
	 *	@return		string
	 */
	function getCharset( $allowed )
	{
		return $this->_charset_sniffer->getCharset( $allowed  );
	}

	/**
	 *	Returns prefered allowed and accepted Mime Type of a HTTP Request.
	 *	@access		public
	 *	@param		array	$allowed			Array of Mime Types supported and allowed by the Application
	 *	@return		string
	 */
	function getMimeType( $allowed )
	{
		return $this->_mime_sniffer->getMimeType( $allowed  );
	}

	/**
	 *	Returns prefered allowed and accepted Encoding Methods of a HTTP Request.
	 *	@access		public
	 *	@param		array	$allowed			Array of Encoding Methods supported and allowed by the Application
	 *	@return		string
	 */
	function getEncoding( $allowed )
	{
		return $this->_encoding_sniffer->getEncoding( $allowed  );
	}

	/**
	 *	Returns determined Information of the Client's Operating System.
	 *	@access		public
	 *	@return		array
	 */
	function getOS()
	{
		return $this->_os_sniffer->getOS();
	}

	/**
	 *	Returns prefered allowed and accepted Character Set of a HTTP Request.
	 *	@access		public
	 *	@return		string
	 */
	function getBrowser()
	{
		return $this->_browser_sniffer->getBrowser();
	}
	
	/**
	 *	Indicates whether a HTTP Request is sent by a Search Engine Robot.
	 *	@access		public
	 *	@return		bool
	 */
	function isRobot()
	{
		return $this->_browser_sniffer->isRobot();
	}

	/**
	 *	Indicates whether a HTTP Request is sent by a Browser.
	 *	@access		public
	 *	@return		bool
	 */
	function isBrowser()
	{
		return $this->_browser_sniffer->isBrowser();
	}
}
?>