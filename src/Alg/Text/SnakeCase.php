<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

/**
 *	Support for strings in snake case.
 *	Convert strings into and from snake case.
 *	Snake case is a string format where all spaces are replaced by underscores.
 *	Example for encoding: Hello World! ---> Hello_World!
 *	Example for decoding: snake_cased_string ---> snake cased string
 *
 *	Copyright (c) 2017-2023 Christian Würker (ceusmedia.de)
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
 *	@package		CeusMedia_Common_Alg_Text
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2017-2023 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 *	@see			https://en.wikipedia.org/wiki/Snake_case
 */

namespace CeusMedia\Common\Alg\Text;

/**
 *	Support for strings in snake case.
 *	@category		Library
 *	@package		CeusMedia_Common_Alg_Text
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2017-2023 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */
class SnakeCase
{
	public static function apply( string $string ): string
	{
		return self::encode( $string );
	}

	public static function decode( string $string ): string
	{
		return str_replace( "_", " ", $string );
	}

	public static function encode( string $string ): string
	{
		return str_replace( " ", "_", $string );
	}

	public static function toCamelCase( string $string ): string
	{
		return CamelCase::encode( static::decode( $string ) );
	}

	public static function toPascalCase( string $string ): string
	{
		return PascalCase::encode( static::decode( $string ) );
	}

	public static function validate( string $string ): bool
	{
		return self::apply( $string ) === $string;
	}
}
