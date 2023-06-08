<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

/**
 *	Parser for RSS 2.0 Feeds using SimpleXML.
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
 *	@package		CeusMedia_Common_XML_RSS
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2023 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */

namespace CeusMedia\Common\XML\RSS;

use Exception;
use SimpleXMLElement;

/**
 *	Parser for RSS 2.0 Feeds using SimpleXML.
 *	@category		Library
 *	@package		CeusMedia_Common_XML_RSS
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2023 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */
class SimpleParser
{
	/**
	 *	Reads RSS from XML statically and returns Array containing Channel Data and Items.
	 *	@access		public
	 *	@static
	 *	@param		string		$xml		XML String to read
	 *	@return		array
	 *	@throws		Exception
	 */
	public static function parse( string $xml ): array
	{
		$channelData	= [];
		$itemList		= [];
		$xml			= new SimpleXMLElement( $xml );
		foreach( $xml->channel->children() as $nodeName => $nodeValue ){
			if( $nodeName == "image" && $nodeValue->children() ){
				$channelData[$nodeName]	= self::readSubSet( $nodeValue );
				continue;
			}
			if( $nodeName == "textInput" && $nodeValue->children() ){
				$channelData[$nodeName]	= self::readSubSet( $nodeValue );
				continue;
			}
			if( $nodeName != "item" ){
				$channelData[$nodeName]	= (string) $nodeValue;
				continue;
			}
			$item		= [];
			$itemNode	= $nodeValue;
			foreach( $itemNode->children() as $childName => $childValue )
				$item[$childName]	= (string) $childValue;
			$itemList[]	= $item;
		}
		$attributes	= $xml->attributes();
		return [
			'encoding'		=> $attributes['encoding'],
			'channelData'	=> $channelData,
			'itemList'		=> $itemList,
		];
	}

	/**
	 *	Reads Subset of Node.
	 *	@access		protected
	 *	@static
	 *	@param		SimpleXMLElement	$node		Subset Node
	 *	@return		array
	 */
	protected static function readSubSet( SimpleXMLElement $node ): array
	{
		$item	= [];
		foreach( $node->children() as $nodeName => $nodeValue )
			$item[$nodeName]	= (string) $nodeValue;
		return $item;
	}
}
