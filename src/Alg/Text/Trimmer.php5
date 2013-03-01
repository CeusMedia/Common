<?php
/**
 *	Trimmer for Strings, supporting cutting to the right and central cutting for too long Strings.
 *
 *	Copyright (c) 2009-2012 Christian Würker (ceusmedia.com)
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
 *	@package		Alg.Text
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2009-2012 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			0.6.8
 *	@version		$Id$
 */
/**
 *	Trimmer for Strings, supporting cutting to the right and central cutting for too long Strings.
 *	@category		cmClasses
 *	@package		Alg.Text
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2009-2012 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			0.6.8
 *	@version		$Id$
 */
class Alg_Text_Trimmer
{
	/**
	 *	Trims String and cuts to the right if too long, also adding a mask string.
	 *	@access		public
	 *	@static
	 *	@param		string		$string		String to be trimmed
	 *	@param		int			$length		Length of String to be at most
	 *	@param		string		$mask		Mask String to append after cut.
	 *	@return		string
	 */
	public static function trim( $string, $length = 0, $mask = "..." )
	{
		$maskLength	= preg_match( '/^&.*;$/', $mask ) ? 1 : strlen( $mask );
		$string	= trim( $string );
		if( !( $length && strlen( $string ) > $length ) )
			return $string;
		$string	= substr( $string, 0, $length - $maskLength );
		$string	.= $mask;
		return $string;
	}

	/**
	 *	Trims String and cuts to the right if too long, also adding a mask string.
	 *	@access		public
	 *	@static
	 *	@param		string		$string		String to be trimmed
	 *	@param		int			$length		Length of String to be at most
	 *	@param		string		$mask		Mask String to append after cut.
	 *	@return		string
	 */
	public static function trimCentric( $string, $length = 0, $mask = "..." )
	{
		$maskLength	= preg_match( '/^&.*;$/', $mask ) ? 1 : strlen( $mask );
		if( $maskLength >= $length )
			throw new InvalidArgumentException( 'Lenght must be greater than '.$maskLength );

		if( !( $length && strlen( $string ) > $length ) )
			return $string;

		$range	= ( $length - $maskLength ) / 2;

		if( function_exists( 'mb_substr' ) )
		{
			$mbEnc	= mb_internal_encoding();
			mb_internal_encoding( 'UTF-8' );
			$left	= mb_substr( $string, 0, ceil( $range ) );
			$right	= mb_substr( $string, -floor( $range ) );
			mb_internal_encoding( $mbEnc );
		}
		else
		{
			$left	= substr( $string, 0, ceil( $range ) );
			$right	= substr( $string, -floor( $range ) );
		}
		return $left.$mask.$right;
	}
}
?>
