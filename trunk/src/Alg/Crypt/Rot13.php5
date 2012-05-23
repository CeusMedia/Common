<?php
/**
 *	Wrapper of ROT13 Functions
 *
 *	Copyright (c) 2007-2010 Christian Würker (ceusmedia.com)
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
 *	@category		cmClasses
 *	@package		Alg.Crypt
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2010 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			17.02.2008
 *	@version		$Id$
 */
/**
 *	Wrapper of ROT13 Functions
 *	@category		cmClasses
 *	@package		Alg.Crypt
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2010 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			17.02.2008
 *	@version		$Id$
 */
class Alg_Crypt_Rot13
{
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
}
?>