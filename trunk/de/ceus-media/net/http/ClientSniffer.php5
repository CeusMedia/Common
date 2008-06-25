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
	/**	@var		object		$browserSniffer		Object of Net_HTTP_BrowserSniffer */
	protected $browserSniffer;
	/**	@var		object		$charsetSniffer		Object of Net_HTTP_CharsetSniffer */
	protected $charsetSniffer;
	/**	@var		object		$encodingSniffer	Object of Net_HTTP_EncodingSniffer */
	protected $encodingSniffer;
	/**	@var		object		$languageSniffer	Object of Net_HTTP_LanguageSniffer */
	protected $languageSniffer;
	/**	@var		object		$mimeTypeSniffer	Object of Net_HTTP_MimeTypeSniffer */
	protected $mimeTypeSniffer;
	/**	@var		object		$osSniffer			Object of Net_HTTP_OperatingSystemSniffer */
	protected $osSniffer;

	/**
	 *	Constructor.
	 *	@access		public
	 *	@return		void
	 */
	public function __construct()
	{
		$this->browserSniffer	= new Net_HTTP_BrowserSniffer();
		$this->charsetSniffer	= new Net_HTTP_CharsetSniffer();
		$this->encodingSniffer	= new Net_HTTP_EncodingSniffer();
		$this->languageSniffer	= new Net_HTTP_LanguageSniffer();
		$this->mimeSniffer		= new Net_HTTP_MimeTypeSniffer();
		$this->systemSniffer	= new Net_HTTP_OperatingSystemSniffer();
	}
	
	/**
	 *	Returns IP addresse of Request.
	 *	@access		public
	 *	@return		string
	 */
	public function getIp()
	{
		return getEnv( 'REMOTE_ADDR' );
	}
	
	/**
	 *	Returns prefered allowed and accepted Language of a HTTP Request.
	 *	@access		public
	 *	@param		array		$allowed			Array of Languages supported and allowed by the Application
	 *	@return		string
	 */
	public function getLanguage( $allowed )
	{
		return $this->languageSniffer->getLanguage( $allowed  );
	}

	/**
	 *	Returns prefered allowed and accepted Character Set of a HTTP Request.
	 *	@access		public
	 *	@param		array		$allowed			Array of Languages supported and allowed by the Application
	 *	@return		string
	 */
	public function getCharset( $allowed )
	{
		return $this->charsetSniffer->getCharset( $allowed  );
	}

	/**
	 *	Returns prefered allowed and accepted Mime Type of a HTTP Request.
	 *	@access		public
	 *	@param		array		$allowed			Array of Mime Types supported and allowed by the Application
	 *	@return		string
	 */
	public function getMimeType( $allowed )
	{
		return $this->mimeTypeSniffer->getMimeType( $allowed  );
	}

	/**
	 *	Returns prefered allowed and accepted Encoding Methods of a HTTP Request.
	 *	@access		public
	 *	@param		array		$allowed			Array of Encoding Methods supported and allowed by the Application
	 *	@return		string
	 */
	public function getEncoding( $allowed )
	{
		return $this->encodingSniffer->getEncoding( $allowed  );
	}

	/**
	 *	Returns determined Information of the Client's Operating System.
	 *	@access		public
	 *	@return		array
	 */
	public function getOS()
	{
		return $this->osSniffer->getOS();
	}

	/**
	 *	Returns prefered allowed and accepted Character Set of a HTTP Request.
	 *	@access		public
	 *	@return		string
	 */
	public function getBrowser()
	{
		return $this->browserSniffer->getBrowser();
	}
	
	/**
	 *	Indicates whether a HTTP Request is sent by a Search Engine Robot.
	 *	@access		public
	 *	@return		bool
	 */
	public function isRobot()
	{
		return $this->browserSniffer->isRobot();
	}

	/**
	 *	Indicates whether a HTTP Request is sent by a Browser.
	 *	@access		public
	 *	@return		bool
	 */
	public function isBrowser()
	{
		return $this->browserSniffer->isBrowser();
	}
}
?>