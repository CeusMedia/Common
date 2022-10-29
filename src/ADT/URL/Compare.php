<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

/**
 *	...
 *
 *	Copyright (c) 2018-2022 Christian Würker (ceusmedia.de)
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
 *	@package		CeusMedia_Common_ADT_URL
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2018-2022 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 *	@see			http://www.w3.org/Addressing/URL/url-spec.html
 */

namespace CeusMedia\Common\ADT\URL;

use CeusMedia\Common\ADT\URL;
use InvalidArgumentException;
use RuntimeException;

/**
 *	...
 *	@category		Library
 *	@package		CeusMedia_Common_ADT_URL
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2018-2022 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 *	@see			http://www.w3.org/Addressing/URL/url-spec.html
 */
class Compare
{
	protected ?URL $url1;

	protected ?URL $url2;

	/**
	 *	...
	 *	@access		public
	 *	@param		URL|string		$url1
	 *	@param		URL|string		$url2
	 */
	public function __construct( $url1 = NULL, $url2 = NULL )
	{
		if( $url1 )
			$this->setUrl1( $url1 );
		if( $url2 )
			$this->setUrl2( $url2 );
	}

	/**
	 *	...
	 *	@access		public
	 *	@param		URL|string		$url
	 *	@return		self
	 */
	public function setUrl1( $url ): self
	{
		if( is_string( $url ) )
			$url	= new URL( $url );
		if( !( $url instanceof URL ) )
			throw new InvalidArgumentException( 'Given first URL is neither URL object nor string' );
		$this->url1		= $url;
		return $this;
	}

	/**
	 *	...
	 *	@access		public
	 *	@param		URL|string		$url
	 *	@return		self
	 */
	public function setUrl2( $url ): self
	{
		if( is_string( $url ) )
			$url	= new URL( $url );
		if( !( $url instanceof URL ) )
			throw new InvalidArgumentException( 'Given second URL is neither URL object nor string' );
		$this->url2		= $url;
		return $this;
	}

	public function sameBase(): bool
	{
		if( !$this->url1 || !$this->url2 )
			throw new RuntimeException( 'Not both URLs are set' );
		if( $this->url1 === $this->url2 )
			return TRUE;
		$sameScheme		= $this->url1->getScheme() === $this->url2->getScheme();
		$sameHost		= $this->url1->getHost() === $this->url2->getHost();
		$samePort		= $this->url1->getPort() === $this->url2->getPort();
		$sameUsername	= $this->url1->getUsername() === $this->url2->getUsername();
		$samePassword	= $this->url1->getPassword() === $this->url2->getPassword();
		return $sameScheme && $sameHost && $samePort && $sameUsername && $samePassword;
	}

	/**
	 *	...
	 *	@access		public
	 *	@static
	 *	@param		URL|string		$url1
	 *	@param		URL|string		$url2
	 *	@return		bool
	 */
	public static function sameBaseStatic( $url1, $url2 ): bool
	{
		$that	= new self( $url1, $url2 );
		return $that->sameBase();
	}
}
