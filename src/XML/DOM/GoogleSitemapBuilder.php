<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

/**
 *	Builds and writes Google Sitemap.
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
 *	@package		CeusMedia_Common_XML_DOM
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2024 Christian Würker
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */

namespace CeusMedia\Common\XML\DOM;

use Exception;

/**
 *	Builds and writes Google Sitemap.
 *	@category		Library
 *	@package		CeusMedia_Common_XML_DOM
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2024 Christian Würker
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */
class GoogleSitemapBuilder
{
	/**	@var	array		$list		List of URLs */
	protected $list	= [];

	/**
	 *	Adds another Link to Sitemap.
	 *	@access		public
	 *	@param		string		$link		Link to add to Sitemap
	 *	@return		void
	 */
	public function addLink( string $link )
	{
		$this->list[]	= $link;
	}

	/**
	 *	Builds and return XML of Sitemap.
	 *	@access		public
	 *	@param		string		$baseUrl	Basic URL to add to every Link
	 *	@return		string
	 *	@throws		Exception
	 */
	public function build( string $baseUrl ): string
	{
		return self::buildSitemap( $this->list, $baseUrl );
	}

	/**
	 *	Builds and return XML of Sitemap.
	 *	@access		public
	 *	@static
	 *	@param		array		$links		List of Sitemap Link
	 *	@param		string		$baseUrl	Basic URL to add to every Link
	 *	@return		string
	 *	@throws		Exception
	 */
	public static function buildSitemap( array $links, string $baseUrl = '' ): string
	{
		$root	= new Node( "urlset" );
		$root->setAttribute( 'xmlns', "https://www.google.com/schemas/sitemap/0.84" );
		foreach( $links as $link )
		{
			$child	= new Node( "url" );
			$loc	= new Node( "loc", $baseUrl.$link );
			$child->addChild( $loc );
			$root->addChild( $child );
		}
		$builder	= new Builder();
		return $builder->build( $root );
	}
}
