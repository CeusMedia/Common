<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

/**
 *	Combination of different Sniffers for HTTP Request to determine all information about the Client.
 *
 *	Copyright (c) 2007-2025 Christian Würker (ceusmedia.de)
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
 *	along with this program.  If not, see <https://www.gnu.org/licenses/>.
 *
 *	@category		Library
 *	@package		CeusMedia_Common_Net_HTTP_Sniffer
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2025 Christian Würker
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */

namespace CeusMedia\Common\Net\HTTP\Sniffer;

/**
 *	Combination of different Sniffers for HTTP Request to determine all information about the Client.
 *	@category		Library
 *	@package		CeusMedia_Common_Net_HTTP_Sniffer
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2025 Christian Würker
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */
class Client
{
	/**	@var		Charset			$charSet		Instance of Net_HTTP_Sniffer_Charset */
	protected Charset $charSet;

	/**	@var		Encoding		$encoding		Instance of Net_HTTP_Sniffer_Encoding */
	protected Encoding $encoding;

	/**	@var		Language		$language		Instance of Net_HTTP_Sniffer_Language */
	protected Language $language;

	/**	@var		MimeType		$mimeType		Instance of Net_HTTP_Sniffer_MimeType */
	protected MimeType $mimeType;

	/**
	 *	Constructor.
	 *	@access		public
	 *	@return		void
	 */
	public function __construct()
	{
		$this->charSet	= new Charset();
		$this->encoding	= new Encoding();
		$this->language	= new Language();
		$this->mimeType	= new MimeType();
	}

	/**
	 *	Returns IP address of Request.
	 *	@access		public
	 *	@return		string|FALSE
	 */
	public function getIp(): string|FALSE
	{
		return getEnv( 'REMOTE_ADDR' ) ;
	}

	/**
	 *	Returns preferred allowed and accepted Language of an HTTP Request.
	 *	@access		public
	 *	@param		array		$allowed			Array of Languages supported and allowed by the Application
	 *	@return		string
	 */
	public function getLanguage( array $allowed ): string
	{
		return $this->language->getLanguage( $allowed  );
	}

	/**
	 *	Returns preferred allowed and accepted Character Set of an HTTP Request.
	 *	@access		public
	 *	@param		array		$allowed			Array of Languages supported and allowed by the Application
	 *	@return		string
	 */
	public function getCharset( array $allowed ): string
	{
		return $this->charSet->getCharset( $allowed );
	}

	/**
	 *	Returns preferred allowed and accepted Mime-Type of an HTTP Request.
	 *	@access		public
	 *	@param		array		$allowed			Array of Mime-Types supported and allowed by the Application
	 *	@return		string|NULL
	 */
	public function getMimeType( array $allowed ): ?string
	{
		return $this->mimeType->getMimeType( $allowed  );
	}

	/**
	 *	Returns preferred allowed and accepted Encoding Methods of an HTTP Request.
	 *	@access		public
	 *	@param		array		$allowed			Array of Encoding Methods supported and allowed by the Application
	 *	@return		string|NULL
	 */
	public function getEncoding( array $allowed ): ?string
	{
		return $this->encoding->getEncoding( $allowed  );
	}
}
