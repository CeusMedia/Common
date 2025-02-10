<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

/**
 *	Base class for request to Google APIs.
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
 *	@package		CeusMedia_Common_Net_API_Google
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2008-2025 Christian Würker
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */

namespace CeusMedia\Common\Net\API\Google;

use CeusMedia\Common\Exception\IO as IoException;
use CeusMedia\Common\Net\Reader as NetReader;

/**
 *	Base class for request to Google APIs.
 *	@category		Library
 *	@package		CeusMedia_Common_Net_API_Google
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2008-2025 Christian Würker
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 *	@todo			finish implementation
 */
abstract class Request
{
	public string $apiKey		= '';

	public string $apiUrl		= '';

	public string $pathCache	= '';

	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		string		$apiKey			Google Maps API Key
	 *	@return		void
	 *	@todo		check if apiKey is still needed
	 */
	public function __construct( string $apiKey )
	{
		$this->apiKey	= $apiKey;
	}

	/**
	 *	Sets Cache Path.
	 *	@access		public
	 *	@param		string		$path		Path to Cache
	 *	@return		self
	 */
	public function setCachePath( string $path ): self
	{
		$this->pathCache	= $path;
		return $this;
	}

	/**
	 *	@param		string		$query
	 *	@return		string
	 *	@throws		IoException
	 */
	protected function sendQuery( string $query ): string
	{
		$query		.= '&key='.$this->apiKey;
		$url		= $this->apiUrl.$query;
		$response	= NetReader::readUrl( $url );
		return utf8_encode( $response );
	}
}
