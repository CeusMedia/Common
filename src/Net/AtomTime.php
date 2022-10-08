<?php /** @noinspection PhpUnused */
/** @noinspection PhpMultipleClassDeclarationsInspection */

/**
 *	Connects Server to request Atom Time.
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
 *	@package		CeusMedia_Common_Net
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2022 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */

namespace CeusMedia\Common\Net;

use CeusMedia\Common\Exception\IO as IoException;
use DateTime;
use DateTimeZone;

/**
 *	Connects Server to request Atom Time.
 *	@category		Library
 *	@package		CeusMedia_Common_Net
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2022 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 *	@see			https://www.ntp-server.de/ntp-server-deutschland/
 *	@see			https://timetoolsltd.com/information/public-ntp-server/
 *	@see			https://github.com/bt51/ntp
 */
class AtomTime
{
	/**	@var		string				$url			URL for Server Request */
	protected static string $hostName	= 'ptbtime1.ptb.de';

	/**	@var		integer				$url			Port for Server Request */
	protected static int $hostPort		= 123;

	/**
	 *	@param		DateTimeZone|NULL		$dateTimeZone
	 *	@return		DateTime
	 *	@throws		IoException
	 */
	public static function get( ?DateTimeZone $dateTimeZone = NULL ): DateTime
	{
		$hostIp		= 'udp://' . gethostbyname( self::$hostName );
		$resource	= fsockopen( $hostIp, self::$hostPort, $errNumber, $errMessage, 5 );
		if( !$resource )
			throw new IoException( $errMessage );

		fwrite( $resource, chr( 0x1B ) . str_repeat( chr( 0x00 ), 47 ) );
		$response = fread( $resource, 48 );
		fclose( $resource );
		$data = unpack( 'N12', $response );
		$time = sprintf( '%u', $data[9] ) - 2208988800;
		$dateTime = DateTime::createFromFormat('U', $time, new DateTimeZone('UTC'));
		if( $dateTimeZone !== NULL )
			$dateTime->setTimezone( $dateTimeZone );
		return $dateTime;
	}

	/**
	 *	Returns timestamp.
	 *	@access		public
	 *	@static
	 *	@param		DateTimeZone|NULL		$dateTimeZone
	 *	@return		int
	 *	@throws		IoException
	 */
	public static function getTimestamp( ?DateTimeZone $dateTimeZone = NULL ): int
	{
		$dateTime	= self::get( $dateTimeZone );
		return (int) $dateTime->format( 'U' );
	}

	/**
	 *	Returns date as formatted string.
	 *	@access		public
	 *	@static
	 *	@param		string				$format			Date format
	 *	@param		DateTimeZone|NULL	$dateTimeZone
	 *	@return		string
	 *	@throws		IoException
	 */
	public static function getDate( string $format = "d.m.Y - H:i:s", ?DateTimeZone $dateTimeZone = NULL ): string
	{
		return date( $format, self::getTimestamp( $dateTimeZone )  );
	}
}
