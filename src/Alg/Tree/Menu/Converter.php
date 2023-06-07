<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

/**
 *	Converter between OPML and Tree Menu Structure.
 *
 *	Copyright (c) 2007-2023 Christian Würker (ceusmedia.de)
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
 *	@package		CeusMedia_Common_Alg_Tree_Menu
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2023 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */

namespace CeusMedia\Common\Alg\Tree\Menu;

use CeusMedia\Common\ADT\Tree\Menu\Collection as TreeMenuCollection;
use CeusMedia\Common\ADT\Tree\Menu\Item;
use CeusMedia\Common\FS\File\Reader as FileReader;
use CeusMedia\Common\XML\OPML\Parser as OpmlParser;
/**
 *	Converter between OPML and Tree Menu Structure.
 *	@category		Library
 *	@package		CeusMedia_Common_Alg_Tree_Menu
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2023 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */
class Converter
{
	/**
	 *	Adds Tree Menu Items from OPML Outlines into a given Tree Menu List recursively.
	 *	@access		public
	 *	@static
	 *	@param		array			        $lines			Outline Array from OPML Parser
	 *	@param		TreeMenuCollection		$container		Current working Menu Container, a Tree Menu List initially.
	 *	@return		void
	 */
	protected static function buildMenuListFromOutlines( array $lines, TreeMenuCollection $container ): void
	{
		foreach( $lines as $line ){
			if( isset( $line['outlines'] ) && count( $line['outlines'] ) ){
				if( isset ( $line['url'] ) )
					$item	= new Item( $line['url'], $line['text'] );
				else
					$item	= new TreeMenuCollection( $line['text'] );
				self::buildMenuListFromOutlines( $line['outlines'], $item );
			}
			else{
				$item	= new Item( $line['url'], $line['text'] );
			}
			$container->addChild( $item );
		}
	}

	/**
	 *	Converts an OPML String to a Tree Menu List.
	 *	@access		public
	 *	@static
	 *	@param		string			$opml			OPML String
	 *	@param		string			$labelRoot		Label of Top Tree Menu List
	 *	@param		string|NULL		$rootClass		CSS Class of root node
	 *	@return		TreeMenuCollection
	 */
	public static function convertFromOpml( string $opml, string $labelRoot, string $rootClass = NULL ): TreeMenuCollection
	{
		$parser		= new OpmlParser();
		$parser->parse( $opml );
		$lines		= $parser->getOutlines();
		$list		= new TreeMenuCollection( $labelRoot, ['class' => $rootClass] );

		self::buildMenuListFromOutlines( $lines, $list );
		return $list;
	}

	/**
	 *	Converts an OPML File to a Tree Menu List.
	 *	@access		public
	 *	@static
	 *	@param		string			$fileName		File Name of OPML File
	 *	@param		string			$labelRoot		Label of Top Tree Menu List
	 *	@param		string|NULL		$rootClass		CSS Class of root node
	 *	@return		TreeMenuCollection
	 */
	public static function convertFromOpmlFile( string $fileName, string $labelRoot, ?string $rootClass = NULL ): TreeMenuCollection
	{
		$opml		= FileReader::load( $fileName );
		return self::convertFromOpml( $opml, $labelRoot, $rootClass );
	}
}
