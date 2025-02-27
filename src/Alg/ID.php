<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

/**
 *	ID generator.
 *
 *	Copyright (c) 2010-2024 Christian Würker (ceusmedia.de)
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
 *	@copyright		2014-2024 Christian Würker
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */

namespace CeusMedia\Common\Alg;

/**
 *	ID generator.
 *
 *	@category		Library
 *	@package		CeusMedia_Common_Alg
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2014-2024 Christian Würker
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 *
 *	@todo			implement versions
 *	@see			comment at linked page
 *	@link			http://php.net/manual/en/function.uniqid.php
 */
class ID
{
	public static function isUuid( string $uuid ): bool
	{
		$regexp	= '[a-f0-9]{8}\-[a-f0-9]{4}\-[a-f0-9]{4}\-[a-f0-9]{4}\-[a-f0-9]{12}';
		return preg_match( '/^'.$regexp.'$/i', $uuid );
	}

	public static function uuid(): string
	{
		if( function_exists( 'com_create_guid' ) === TRUE )
			/** @noinspection PhpUndefinedFunctionInspection */
			return trim( com_create_guid(), '{}' );
		return sprintf(
			'%04X%04X-%04X-%04X-%04X-%04X%04X%04X',
			random_int( 0, 65535 ),
			random_int( 0, 65535 ),
			random_int( 0, 65535 ),
			random_int( 16384, 20479 ),
			random_int( 32768, 49151 ),
			random_int( 0, 65535 ),
			random_int( 0, 65535 ),
			random_int( 0, 65535 )
		);
	}
}
