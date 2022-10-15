<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

/**
 *	Formats Numbers intelligently and adds Units to Bytes and Seconds.
 *
 *	Copyright (c) 2015-2022 Christian Würker (ceusmedia.de)
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
 *	@package		CeusMedia_Common_Alg
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2015-2022 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
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
 *	@copyright		2015-2022 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 *	@todo			code doc
 */
class UnitParser
{
	public static array $rules	= [
		'/^([0-9.,]+)$/'		=> 1,
		'/^([0-9.,]+)B$/'		=> 1,
		'/^([0-9.,]+)k$/'		=> 1000,
		'/^([0-9.,]+)kB$/'		=> 1000,
		'/^([0-9.,]+)kiB$/'		=> 1000,
		'/^([0-9.,]+)K$/'		=> 1024,
		'/^([0-9.,]+)KB$/i'		=> 1024,
		'/^([0-9.,]+)m$/'		=> 1000000,
		'/^([0-9.,]+)M$/'		=> 1048576,
		'/^([0-9.,]+)MB$/i'		=> 1048576,
		'/^([0-9.,]+)MiB$/i'	=> 1000000,
		'/^([0-9.,]+)g$/'		=> 1000000000,
		'/^([0-9.,]+)G$/'		=> 1073741824,
		'/^([0-9.,]+)GB$/i'		=> 1073741824,
		'/^([0-9.,]+)GiB$/i'	=> 1000000000,
	];

	public static function parse( string $string, ?string $exceptedUnit = NULL ): float
	{
		if( !strlen( trim( $string ) ) )
			throw new InvalidArgumentException( 'String cannot be empty' );
		$int	= (int) $string;
		if( $exceptedUnit && strlen( $int ) == strlen( $string ) && $int == $string )
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
