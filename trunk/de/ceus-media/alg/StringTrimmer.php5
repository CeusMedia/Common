<?php
/**
 *	Trimmer for Strings, supporting cutting to the right and central cutting for too long Strings.
 *
 *	Copyright (c) 2007-2009 Christian Würker (ceus-media.de)
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
 *	@package		alg
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@copyright		2007-2009 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			27.10.2008
 *	@version		0.1
 */
/**
 *	Trimmer for Strings, supporting cutting to the right and central cutting for too long Strings.
 *	@package		alg
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@copyright		2007-2009 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			27.10.2008
 *	@version		0.1
 */
class Alg_StringTrimmer
{
	/**
	 *	Trims String and cuts to the right if too long, also adding a mask string.
	 *	@access		public
	 *	@param		string		$string		String to be trimmed
	 *	@param		int			$length		Length of String to be at most
	 *	@param		string		$mask		Mask String to append after cut.
	 *	@return		string
	 */
	public static function trim( $string, $length = 0, $mask = "..." )
	{
		$string	= trim( $string );
		if( !( $length && strlen( $string ) > $length ) )
			return $string;
		$string	= substr( $string, 0, $length - strlen( $mask ) );
		$string	.= $mask;
		return $string;
	}
	
	/**
	 *	Trims String and cuts to the right if too long, also adding a mask string.
	 *	@access		public
	 *	@param		string		$string		String to be trimmed
	 *	@param		int			$length		Length of String to be at most
	 *	@param		string		$mask		Mask String to append after cut.
	 *	@return		string
	 */
	public static function trimCentric( $string, $length = 0, $mask = "..." )
	{
		if( strlen( $mask ) >= $length )
			throw new InvalidArgumentException( 'Lenght must be greater than '.strlen( $mask ) );

		if( !( $length && strlen( $string ) > $length ) )
			return $string;

		$range	= ( $length - strlen( $mask ) ) / 2;
		$left	= substr( $string, 0, ceil( $range ) );
		$right	= substr( $string, -floor( $range ) );

		return $left.$mask.$right;
	}
}
?>