<?php
/**
 *	Extracts Terms from a Text Document.
 *
 *	Copyright (c) 2009-2010 Christian Würker (ceus-media.de)
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
 *	@package		alg
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2009-2010 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			0.6.8
 *	@version		$Id$
 *	
 */
import( 'de.ceus-media.file.Editor' );
/**
 *	Extracts Terms from a Text Document.
 *	@category		cmClasses
 *	@package		alg
 *	@uses			File_Editor
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2009-2010 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			0.6.8
 *	@version		$Id$
 *	@todo			Code Doc
 */
class Alg_Text_TermExtractor
{
	public static $blacklist	= array();

	public static function getTerms( $text )
	{
		$list	= array();
		$lines	= explode( "\n", $text );
		foreach( $lines as $line )
		{
			$words	= explode( " ", trim( $line ) );
			foreach( $words as $word )
			{
				$word	= trim( $word );
				$word	= preg_replace( "@^\(@i", "", $word );
				$word	= preg_replace( "@\)$@i", "", $word );
				$word	= preg_replace( "@[,;._-]$@i", "", $word );
				$word	= trim( $word );
				if( strlen( $word ) < 2 )
					continue;
				
				if( in_array( $word, self::$blacklist ) )
					continue;
				
				if( $word )
				{
					if( !isset( $list[$word] ) )
						$list[$word]	= 0;
					$list[$word]++;
				}
			}
		}
#		ksort( $list );
		arsort( $list );
		return $list;
	}
	
	public static function loadBlacklist( $fileName )
	{
		$string	= File_Editor::load( $fileName );
		if( !Alg_StringUnicoder::isUnicode( $string ) )
		{
			$string	= Alg_StringUnicoder::convertToUnicode( $string );
			File_Editor::save( $fileName, $string );
		}
		$list	= File_Editor::loadArray( $fileName );
		self::setBlacklist( array_unique( $list ) );
	}
	
	public static function setBlacklist( $list )
	{
		self::$blacklist	= array_unique( $list );
	}
}
?>