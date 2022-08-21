<?php
/**
 *	Combination of different Sniffers for HTTP Request to determine all information about the Client.
 *
 *	Copyright (c) 2007-2022 Christian Würker (ceusmedia.de)
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
 *	@category		Library
 *	@package		CeusMedia_Common_Net_HTTP_Sniffer
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2022 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 *	@since			12.08.2005
 */

namespace CeusMedia\Common\Net\HTTP\Sniffer;

/**
 *	Combination of different Sniffers for HTTP Request to determine all information about the Client.
 *	@category		Library
 *	@package		CeusMedia_Common_Net_HTTP_Sniffer
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2022 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 *	@since			12.08.2005
 */
class Client
{
	/**	@var		object		$browser		Instance of Net_HTTP_Sniffer_Browser */
	protected $browser;

	/**	@var		object		$charSet		Instance of Net_HTTP_Sniffer_Charset */
	protected $charSet;

	/**	@var		object		$encoding		Instance of Net_HTTP_Sniffer_Encoding */
	protected $encoding;

	/**	@var		object		$language		Instance of Net_HTTP_Sniffer_Language */
	protected $language;

	/**	@var		object		$mimeType		Instance of Net_HTTP_Sniffer_MimeType */
	protected $mimeType;

	/**	@var		object		$system			Instance of Net_HTTP_Sniffer_OS */
	protected $system;

	/**
	 *	Constructor.
	 *	@access		public
	 *	@return		void
	 */
	public function __construct()
	{
		$this->browser	= new Browser();
		$this->charSet	= new Charset();
		$this->encoding	= new Encoding();
		$this->language	= new Language();
		$this->mimeType	= new MimeType();
		$this->system	= new OS();
	}

	/**
	 *	Returns IP address of Request.
	 *	@access		public
	 *	@return		string
	 */
	public function getIp()
	{
		return getEnv( 'REMOTE_ADDR' );
	}

	/**
	 *	Returns preferred allowed and accepted Language of an HTTP Request.
	 *	@access		public
	 *	@param		array		$allowed			Array of Languages supported and allowed by the Application
	 *	@return		string
	 */
	public function getLanguage( $allowed )
	{
		return $this->language->getLanguage( $allowed  );
	}

	/**
	 *	Returns preferred allowed and accepted Character Set of an HTTP Request.
	 *	@access		public
	 *	@param		array		$allowed			Array of Languages supported and allowed by the Application
	 *	@return		string
	 */
	public function getCharset( $allowed )
	{
		return $this->charSet->getCharset( $allowed );
	}

	/**
	 *	Returns preferred allowed and accepted Mime Type of an HTTP Request.
	 *	@access		public
	 *	@param		array		$allowed			Array of Mime Types supported and allowed by the Application
	 *	@return		string
	 */
	public function getMimeType( $allowed )
	{
		return $this->mimeType->getMimeType( $allowed  );
	}

	/**
	 *	Returns preferred allowed and accepted Encoding Methods of an HTTP Request.
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
	 *	Returns preferred allowed and accepted Character Set of an HTTP Request.
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
	public function isRobot(): bool
	{
		return $this->browser->isRobot();
	}

	/**
	 *	Indicates whether a HTTP Request is sent by a Browser.
	 *	@access		public
	 *	@return		bool
	 */
	public function isBrowser(): bool
	{
		return $this->browser->isBrowser();
	}
}
