<?php
/**
 *	Combination of different Sniffers for HTTP Request to determine all information about the Client.
 *
 *	Copyright (c) 2007-2010 Christian Würker (ceus-media.de)
 *
 *	This program is free software: you can redistribute it and/or modify
 *	it under the terms of the GNU General Public License as published by
 *	the Free Software Foundation, either version 3 of the License, or
 *	(at your option) any later version.
 *
 *	This program is distributed in the hope that it will be useful,
 *	but WITHOUT ANY WARRANTY; without even the implied warranty of
 *	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *	GNU General Public License for more details.
 *
 *	You should have received a copy of the GNU General Public License
 *	along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 *	@category		cmClasses
 *	@package		net.http
 *	@uses			Net_HTTP_BrowserSniffer
 *	@uses			Net_HTTP_CharsetSniffer
 *	@uses			Net_HTTP_EncodingSniffer
 *	@uses			Net_HTTP_LanguageSniffer
 *	@uses			Net_HTTP_MimeTypeSniffer
 *	@uses			Net_HTTP_OperatingSystemSniffer
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2007-2010 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			12.08.2005
 *	@version		$Id$
 */
import( 'de.ceus-media.net.http.BrowserSniffer' );
import( 'de.ceus-media.net.http.CharsetSniffer' );
import( 'de.ceus-media.net.http.EncodingSniffer' );
import( 'de.ceus-media.net.http.LanguageSniffer' );
import( 'de.ceus-media.net.http.MimeTypeSniffer' );
import( 'de.ceus-media.net.http.OperatingSystemSniffer' );
/**
 *	Combination of different Sniffers for HTTP Request to determine all information about the Client.
 *	@category		cmClasses
 *	@package		net.http
 *	@uses			Net_HTTP_BrowserSniffer
 *	@uses			Net_HTTP_CharsetSniffer
 *	@uses			Net_HTTP_EncodingSniffer
 *	@uses			Net_HTTP_LanguageSniffer
 *	@uses			Net_HTTP_MimeTypeSniffer
 *	@uses			Net_HTTP_OperatingSystemSniffer
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2007-2010 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			12.08.2005
 *	@version		$Id$
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