<?php
/**
 *	Reader for HTML Meta Tags.
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
 *	@uses			Alg_SgmlTagReader
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2007-2009 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			02.08.2008
 *	@version		0.1
 */
import( 'de.ceus-media.alg.SgmlTagReader' );
/**
 *	Reader for HTML Meta Tags.
 *	@package		alg
 *	@uses			Alg_SgmlTagReader
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2007-2009 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			02.08.2008
 *	@version		0.1
 */
class Alg_HtmlMetaTagReader
{
	const TRANSFORM_LOWERCASE	= 1;
	const TRANSFORM_UPPERCASE	= 2;

	/**
	 *	Returns Array of Meta Tags from a HTML Page String.
	 *	@access		public
	 *	@static
	 *	@param		string		$string			HTML Page String
	 *	@param		int			$transformKeys	Flag: transform Attribute Keys
	 *	@return		array
	 */
	public static function getMetaTags( $string, $transformKeys = 0 )
	{
		$metaTags	= array();
		preg_match_all( "@<meta.*/?>@", $string, $tags );
		if( !$tags )
			return array();
		foreach( $tags[0] as $tag )
		{
			$attributes	= Alg_SgmlTagReader::getAttributes( $tag, self::TRANSFORM_LOWERCASE );	//  read HTML Tag Attributes
			if( !isset( $attributes['content'] ) )
				continue;
			if( isset( $attributes['content'] ) && isset( $attributes['name'] ) )
				$key	= $attributes['name'];
			if( isset( $attributes['content'] ) && isset( $attributes['http-equiv'] ) )
				$key	= $attributes['http-equiv'];
			if( $transformKeys == self::TRANSFORM_LOWERCASE )
				$key	= strtolower( $key );
			else if( $transformKeys == self::TRANSFORM_UPPERCASE )
				$key	= strtoupper( $key );
			$metaTags[$key]	= $attributes['content'];
		}
		return $metaTags;
	}
}
?>