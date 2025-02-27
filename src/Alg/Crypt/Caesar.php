<?php

declare(strict_types=1);

/**
 *	Caesar Encryption.
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
 *	Caesar Encryption.
 *	@category		Library
 *	@package		CeusMedia_Common_Alg_Crypt
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2024 Christian Würker
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */
class Caesar
{
	/**
	 *	Realizes Encryption/Decryption of a text with the normal/inversed Key.
	 *	@access		public
	 *	@static
	 *	@param		string		$string		String to be encrypted
	 *	@param		int			$key		Rotation Key
	 *	@return		string
	 */
	protected static function crypt( $string, $key )
	{
		for( $i=0; $i<strlen( $string ); $i++ )
		{
			$char = ord( $string[$i] );
			if( $char > 64 && $char < 91 )
			{
				$char += $key;
				if( $char > 90 )
					$char -= 26;
				else if( $char < 65 )
					$char += 26;
			}
			else if( $char > 96 && $char < 123 )
			{
				$char += $key;
				if ($char > 122)
					$char -= 26;
				else if( $char < 97 )
					$char += 26;
			}
			$string[$i] = chr( $char );
		}
		return $string;
	}

	/**
	 *	Decrypts a String.
	 *	@access		public
	 *	@static
	 *	@param		string		$string		String to be encrypted
	 *	@param		int			$key		Rotation Key
	 *	@return		string
	 */
	public static function decrypt( $string, $key )
	{
		return self::crypt( $string, -1 * $key );
	}

	/**
	 *	Encrypts a String.
	 *	@access		public
	 *	@static
	 *	@param		string		$string		String to be encrypted
	 *	@param		int			$key		Rotation Key
	 *	@return		string
	 */
	public static function encrypt( $string, $key )
	{
		return self::crypt( $string, $key );
	}
}
