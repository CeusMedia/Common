<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

/**
 *	Access to DDNSS (ddnss.de) API.
 *
 *	Copyright (c) 2015-2024 Christian Würker (ceusmedia.de)
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
 *	@package		CeusMedia_Common_Net_API
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2015-2024 Christian Würker
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */

namespace CeusMedia\Common\Net\API;

use CeusMedia\Common\Net\Reader as NetReader;
use Exception;

/**
 *	Access to DDNSS (ddnss.de) API.
 *
 *	@category		Library
 *	@package		CeusMedia_Common_Net_API
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2015-2024 Christian Würker
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */
class DDNSS
{
	/**	@var		string		Base URL for update */
	public static $urlUpdate	= "https://ddnss.de/upd.php?key=%s&host=%s";

	/**
	 *	Updated host or hosts.
	 *	@static
	 *	@param		string			$key		Auth key from DDNSS
	 *	@param		string|array	$hosts		Host or list of hosts
	 *	@return		integer			Number of updated hosts
	 *	@todo		parse response header DDNSS-Response
	 *	@todo		and handle update errors
	 */
	public static function update( string $key, $hosts ): int
	{
		if( is_array( $hosts ) )
			$hosts	= implode( ",", $hosts );
		$url	= sprintf( self::$urlUpdate, $key, $hosts );
		try{
			$reader		= new NetReader( $url );
			$reader->setUserAgent( "cURL" );
			$response	= strip_tags( $reader->read() );
			if( !preg_match( "/Updated [0-9]+ /", $response ) )
				return 0;
			$number	= preg_replace( "/^.+Updated ([0-9]+) host.+$/s", "\\1", $response );
			return (int) $number;
		}
		catch( Exception $e ){
			die( $e->getMessage() );
		}
	}
}
