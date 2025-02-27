<?php

declare(strict_types=1);

/**
 *	Wrapper of ROT13 Functions
 *
 *	Copyright (c) 2007-2024 Christian Würker (ceusmedia.de)
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
 *	@package		CeusMedia_Common_Alg_Crypt
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2024 Christian Würker
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */

namespace CeusMedia\Common\Alg\Crypt;

/**
 *	Wrapper of ROT13 Functions
 *	@category		Library
 *	@package		CeusMedia_Common_Alg_Crypt
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2024 Christian Würker
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */
class Rot13
{

	/**
	 *	Decrypts a String encrypted with ROT13.
	 *	@access		public
	 *	@static
	 *	@param		string		$string		String to be decrypted.
	 *	@return		string
	 */
	public static function decrypt( $string )
	{
		return str_rot13( $string );
	}

	/**
	 *	Encrypts a String with ROT13.
	 *	@access		public
	 *	@static
	 *	@param		string		$string		String to be encrypted.
	 *	@return		string
	 */
	public static function encrypt( $string )
	{
		return str_rot13( $string );
	}
}
