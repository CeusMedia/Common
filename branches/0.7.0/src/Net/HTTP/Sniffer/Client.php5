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
 *	@package		Net.HTTP.Sniffer
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2007-2010 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			12.08.2005
 *	@version		$Id$
 */
/**
 *	Combination of different Sniffers for HTTP Request to determine all information about the Client.
 *	@category		cmClasses
 *	@package		Net.HTTP.Sniffer
 *	@uses			Net_HTTP_Sniffer_Browser
 *	@uses			Net_HTTP_Sniffer_Charset
 *	@uses			Net_HTTP_Sniffer_Encoding
 *	@uses			Net_HTTP_Sniffer_Language
 *	@uses			Net_HTTP_Sniffer_MimeType
 *	@uses			Net_HTTP_Sniffer_OperatingSystem
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2007-2010 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			12.08.2005
 *	@version		$Id$
 */
class Net_HTTP_Sniffer_Client
{
	/**	@var		object		$browser		Instance of Net_HTTP_Sniffer_Browser */
	protected $browser;
	/**	@var		object		$charset		Instance of Net_HTTP_Sniffer_Charset */
	protected $charset;
	/**	@var		object		$encoding		Instance of Net_HTTP_Sniffer_Encoding */
	protected $encoding;
	/**	@var		object		$language		Instance of Net_HTTP_Sniffer_Language */
	protected $language;
	/**	@var		object		$mimeType		Instance of Net_HTTP_Sniffer_MimeType */
	protected $mimeType;
	/**	@var		object		$osSniffer		Instance of Net_HTTP_Sniffer_OperatingSystem */
	protected $osSniffer;

	/**
	 *	Constructor.
	 *	@access		public
	 *	@return		void
	 */
	public function __construct()
	{
		$this->browser	= new Net_HTTP_Sniffer_Browser();
		$this->charSet	= new Net_HTTP_Sniffer_Charset();
		$this->encoding	= new Net_HTTP_Sniffer_Encoding();
		$this->language	= new Net_HTTP_Sniffer_Language();
		$this->mimeType	= new Net_HTTP_Sniffer_MimeType();
		$this->system	= new Net_HTTP_Sniffer_OS();
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
		return $this->language->getLanguage( $allowed  );
	}

	/**
	 *	Returns prefered allowed and accepted Character Set of a HTTP Request.
	 *	@access		public
	 *	@param		array		$allowed			Array of Languages supported and allowed by the Application
	 *	@return		string
	 */
	public function getCharset( $allowed )
	{
		return $this->charSet->getCharset( $allowed  );
	}

	/**
	 *	Returns prefered allowed and accepted Mime Type of a HTTP Request.
	 *	@access		public
	 *	@param		array		$allowed			Array of Mime Types supported and allowed by the Application
	 *	@return		string
	 */
	public function getMimeType( $allowed )
	{
		return $this->mimeType->getMimeType( $allowed  );
	}

	/**
	 *	Returns prefered allowed and accepted Encoding Methods of a HTTP Request.
	 *	@access		public
	 *	@param		array		$allowed			Array of Encoding Methods supported and allowed by the Application
	 *	@return		string
	 */
	public function getEncoding( $allowed )
	{
		return $this->encoding->getEncoding( $allowed  );
	}

	/**
	 *	Returns determined Information of the Client's Operating System.
	 *	@access		public
	 *	@return		array
	 */
	public function getOS()
	{
		return $this->system->getOS();
	}

	/**
	 *	Returns prefered allowed and accepted Character Set of a HTTP Request.
	 *	@access		public
	 *	@return		string
	 */
	public function getBrowser()
	{
		return $this->browser->getBrowser();
	}
	
	/**
	 *	Indicates whether a HTTP Request is sent by a Search Engine Robot.
	 *	@access		public
	 *	@return		bool
	 */
	public function isRobot()
	{
		return $this->browser->isRobot();
	}

	/**
	 *	Indicates whether a HTTP Request is sent by a Browser.
	 *	@access		public
	 *	@return		bool
	 */
	public function isBrowser()
	{
		return $this->browser->isBrowser();
	}
}
?>