<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

/**
 *	Formats Numbers intelligently and adds Units to Bytes and Seconds.
 *
 *	Copyright (c) 2015-2025 Christian Würker (ceusmedia.de)
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
 *	@package		CeusMedia_Common_Alg
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2015-2025 Christian Würker
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */

namespace CeusMedia\Common\Alg;

use DomainException;
use InvalidArgumentException;

/**
 *	Formats Numbers intelligently and adds Units to Bytes and Seconds.
 *
 *	@category		Library
 *	@package		CeusMedia_Common_Alg
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2015-2025 Christian Würker
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 *	@todo			code doc
 */
class UnitParser
{
	public static array $rules	= [
		'/^([0-9.,]+)$/'			=> 1,
		'/^([0-9.,]+)\s*B$/'		=> 1,

		'/^([0-9.,]+)\s*k$/'		=> 10 ** 3,
		'/^([0-9.,]+)\s*kB$/'		=> 10 ** 3,
		'/^([0-9.,]+)\s*kiB$/'		=> 2 ** 10,
		'/^([0-9.,]+)\s*K$/'		=> 2 ** 10,
		'/^([0-9.,]+)\s*KB$/i'		=> 2 ** 10,

		'/^([0-9.,]+)\s*M$/'		=> 10 ** 6,
		'/^([0-9.,]+)\s*MB$/i'		=> 10 ** 6,
		'/^([0-9.,]+)\s*MiB$/i'		=> 2 ** 20,

		'/^([0-9.,]+)\s*G$/'		=> 10 ** 9,
		'/^([0-9.,]+)\s*GB$/i'		=> 10 ** 9,
		'/^([0-9.,]+)\s*GiB$/i'		=> 2 ** 30,

		'/^([0-9.,]+)\s*T$/'		=> 10 ** 12,
		'/^([0-9.,]+)\s*TB$/i'		=> 10 ** 12,
		'/^([0-9.,]+)\s*TiB$/i'		=> 2 ** 40,

		'/^([0-9.,]+)\s*P$/'		=> 10 ** 15,
		'/^([0-9.,]+)\s*PB$/i'		=> 10 ** 15,
		'/^([0-9.,]+)\s*PiB$/i'		=> 2 ** 50,

		'/^([0-9.,]+)\s*E$/'		=> 10 ** 18,
		'/^([0-9.,]+)\s*EB$/i'		=> 10 ** 18,
		'/^([0-9.,]+)\s*EiB$/i'		=> 2 ** 60,
	];

	public static function parse( string $string, ?string $exceptedUnit = NULL ): float
	{
		if( !strlen( trim( $string ) ) )
			throw new InvalidArgumentException( 'String cannot be empty' );
		$int	= (int) $string;
		if( $exceptedUnit && strlen( (string) $int ) == strlen( $string ) && $int == $string )
			$string	.= $exceptedUnit;
		$string	= str_replace( ',', '.', trim( $string ) );
		$factor	= NULL;
		foreach( self::$rules as $key => $value ){
			if( preg_match( $key, $string ) ){
				$string		= (float) preg_replace( $key, '\\1', $string );
				$factor		= $value;
				break;
			}
		}
		if( $factor !== NULL )																		//
			return $factor * $string;
		throw new DomainException( 'Given string is not matching any parser rules' );
	}
}
