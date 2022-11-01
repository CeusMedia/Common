<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

/**
 *	Google Sitemap XML Builder.
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
 *	@package		CeusMedia_Common_Net_Site
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2022 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */

namespace CeusMedia\Common\Net\Site;

use CeusMedia\Common\XML\DOM\Builder as XmlBuilder;
use CeusMedia\Common\XML\DOM\Node;

/**
 *	Builds Sitemap XML File for Google.
 *	@category		Library
 *	@package		CeusMedia_Common_Net_Site
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2022 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */
class MapBuilder
{
	/**
	 *	Builds Sitemap XML for List of URLs.
	 *	@access		public
	 *	@static
	 *	@param		array		$urls			List of URLs
	 *	@return		string
	 */
	public static function build( array $urls ): string
	{
		$set	= new Node( "urlset" );
		$set->setAttribute( 'xmlns', "https://www.google.com/schemas/sitemap/0.84" );
		foreach( $urls as $url ){
			$node	= new Node( "url" );
			$child	= new Node( "loc", $url );
			$node->addChild( $child );
			$set->addChild( $node );
		}
		$xb	= new XmlBuilder();
		return $xb->build( $set );
	}
}
