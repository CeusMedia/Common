<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

/**
 *	Extracts Terms from a Text Document.
 *
 *	Copyright (c) 2009-2022 Christian Würker (ceusmedia.de)
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
 *	@copyright		2009-2022 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */

namespace CeusMedia\Common\Alg\Text;

use CeusMedia\Common\FS\File\Editor as FileEditor;

/**
 *	Extracts Terms from a Text Document.
 *	@category		Library
 *	@package		CeusMedia_Common_Alg_Text
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2009-2022 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 *	@todo			Code Doc
 */
class TermExtractor
{
	public static array $blacklist					= [];
	public static bool $backlistCaseSensitive		= FALSE;

	public static function getTerms( string $text ): array
	{
		$list	= [];
		$lines	= explode( "\n", $text );
		$blacklist	= self::$blacklist;
		if( !self::$backlistCaseSensitive )
			$blacklist	= array_map( 'strtolower', $blacklist );
		foreach( $lines as $line ){
			$words	= explode( " ", trim( $line ) );
			foreach( $words as $word ){
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

				if( !isset( $list[$word] ) )
					$list[$word]	= 0;
				$list[$word]++;
			}
		}
//		ksort( $list );
		arsort( $list );
		return $list;
	}

	public static function loadBlacklist( string $fileName ): void
	{
		$string	= FileEditor::load( $fileName );
		if( !Unicoder::isUnicode( $string ) ){
			$string	= Unicoder::convertToUnicode( $string );
			FileEditor::save( $fileName, $string );
		}
		$list	= FileEditor::loadArray( $fileName );
		self::setBlacklist( array_unique( $list ) );
	}

	public static function setBlacklist( array $list ): void
	{
		self::$blacklist	= array_unique( $list );
	}
}
