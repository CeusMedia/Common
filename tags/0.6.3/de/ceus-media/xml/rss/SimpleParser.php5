<?php
/**
 *	Parser for RSS 2.0 Feeds usind SimpleXML.
 *
 *	Copyright (c) 2008 Christian Würker (ceus-media.de)
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
 *	@package		xml.rss
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@copyright		2008 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			20.02.2008
 *	@version		0.6
 */
/**
 *	Parser for RSS 2.0 Feeds usind SimpleXML.
 *	@package		xml.rss
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@copyright		2008 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			20.02.2008
 *	@version		0.6
 */
class XML_RSS_SimpleParser
{
	/**
	 *	Reads RSS from XML statically and returns Array containing Channel Data and Items.
	 *	@access		public
	 *	@param		string		$xml		XML String to read
	 *	@return		array
	 */
	public static function parse( $xml )
	{
		$channelData	= array();
		$itemList		= array();
		$xml	= new SimpleXMLElement( $xml );
		foreach( $xml->channel->children() as $nodeName => $nodeValue )
		{
			if( $nodeName == "image" && $nodeValue->children() )
			{
				$channelData[$nodeName]	= self::readSubSet( $nodeValue );
				continue;
			}
			if( $nodeName == "textInput" && $nodeValue->children() )
			{
				$channelData[$nodeName]	= self::readSubSet( $nodeValue );
				continue;
			}
			if( $nodeName != "item" )
			{
				$channelData[$nodeName]	= (string) $nodeValue;
				continue;
			}
			$item		= array();
			$itemNode	= $nodeValue;
			foreach( $itemNode->children() as $nodeName => $nodeValue )
				$item[$nodeName]	= (string) $nodeValue;
			$itemList[]	= $item;
		}
		$attributes	= $xml->attributes();
		$data	= array(
			'encoding'		=> $attributes['encoding'],
			'channelData'	=> $channelData,
			'itemList'		=> $itemList,
		);
		return $data;
	}

	/**
	 *	Reads Subset of Node.
	 *	@access		protected
	 *	@param		SimpleXMLElement	$node		Subset Node
	 *	@return		array
	 */
	protected static function readSubSet( $node )
	{
		$item	= array();
		foreach( $node->children() as $nodeName => $nodeValue )
			$item[$nodeName]	= (string) $nodeValue;
		return $item;
	}
}
?>