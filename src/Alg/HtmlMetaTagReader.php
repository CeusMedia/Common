<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

/**
 *	Reader for HTML Meta Tags.
 *
 *	Copyright (c) 2007-2022 Christian Würker (ceusmedia.de)
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
 *	@package		CeusMedia_Common_Alg
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2022 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */

namespace CeusMedia\Common\Alg;

/**
 *	Reader for HTML Meta Tags.
 *	@category		Library
 *	@package		CeusMedia_Common_Alg
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2022 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */
class HtmlMetaTagReader
{
	const TRANSFORM_NO			= 0;
	const TRANSFORM_LOWERCASE	= 1;
	const TRANSFORM_UPPERCASE	= 2;

	/**
	 *	Returns Array of Meta Tags from an HTML Page String.
	 *	@access		public
	 *	@static
	 *	@param		string		$string			HTML Page String
	 *	@param		int			$transformKeys	Flag: transform Attribute Keys
	 *	@return		array
	 */
	public static function getMetaTags( string $string, int $transformKeys = self::TRANSFORM_NO ): array
	{
		$metaTags	= [];
		preg_match_all( "@<meta.*/?>@", $string, $tags );
		if( isset( $tags[0] ) ){
			foreach( $tags[0] as $tag ){
				//  read HTML Tag Attributes
				$attributes	= SgmlTagReader::getAttributes( $tag, self::TRANSFORM_LOWERCASE );
				if( !isset( $attributes['content'] ) )
					continue;
				if( isset( $attributes['http-equiv'] ) )
					$key	= $attributes['http-equiv'];
				else if( isset( $attributes['name'] ) )
					$key	= $attributes['name'];
				else
					continue;
				if( $transformKeys == self::TRANSFORM_LOWERCASE )
					$key	= strtolower( $key );
				else if( $transformKeys == self::TRANSFORM_UPPERCASE )
					$key	= strtoupper( $key );
				$metaTags[$key]	= $attributes['content'];
			}
		}
		return $metaTags;
	}
}
