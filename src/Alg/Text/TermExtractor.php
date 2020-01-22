<?php
/**
 *	Extracts Terms from a Text Document.
 *
 *	Copyright (c) 2009-2020 Christian Würker (ceusmedia.de)
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
 *	@copyright		2009-2020 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 *	@since			0.6.8
 */
/**
 *	Extracts Terms from a Text Document.
 *	@category		Library
 *	@package		CeusMedia_Common_Alg_Text
 *	@uses			FS_File_Editor
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2009-2020 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 *	@since			0.6.8
 *	@todo			Code Doc
 */
class Alg_Text_TermExtractor
{
	public static $blacklist					= array();
	public static $backlistCaseSensitive		= FALSE;

	public static function getTerms( $text )
	{
		$list	= array();
		$lines	= explode( "\n", $text );
		$blacklist	= self::$blacklist;
		if( !self::$backlistCaseSensitive )
			$blacklist	= array_map( 'strtolower', $blacklist );
		foreach( $lines as $line )
		{
			$words	= explode( " ", trim( $line ) );
			foreach( $words as $word )
			{
				$word	= trim( $word );
				$word	= preg_replace( "@^\(@i", "", $word );
				$word	= preg_replace( "@\)$@i", "", $word );
				$word	= preg_replace( "@\p{Po}+$@i", "", $word );
				$word	= trim( $word );
				if( strlen( $word ) < 2 )
					continue;
				
				$search	= !self::$backlistCaseSensitive ? strtolower( $word ) : $word;
				if( in_array( $search, $blacklist ) )
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
		$string	= FS_File_Editor::load( $fileName );
		if( !Alg_Text_Unicoder::isUnicode( $string ) )
		{
			$string	= Alg_Text_Unicoder::convertToUnicode( $string );
			FS_File_Editor::save( $fileName, $string );
		}
		$list	= FS_File_Editor::loadArray( $fileName );
		self::setBlacklist( array_unique( $list ) );
	}
	
	public static function setBlacklist( $list )
	{
		self::$blacklist		= array_unique( $list );
	}
}
