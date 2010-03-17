<?php
/**
 *	Parser for RSS 2 Feed using XPath.
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
 *	@category		cmClasses
 *	@package		xml.rss
 *	@uses			XML_DOM_XPathQuery
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2007-2009 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			30.01.2006
 *	@version		0.1
 *	@see			http://blogs.law.harvard.edu/tech/rss
 */
import( 'de.ceus-media.xml.dom.XPathQuery' );
/**
 *	Parser for RSS 2 Feed using XPath.
 *	@category		cmClasses
 *	@package		xml.rss
 *	@uses			XML_DOM_XPathQuery
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2007-2009 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			30.01.2006
 *	@version		0.1
 *	@see			http://blogs.law.harvard.edu/tech/rss
 *	@todo			Code Doc
 */
class XML_RSS_Parser
{
	public static $channelKeys	= array(
		"title",
		"language",
		"link",
		"description",
		"copyright",
		"managingEditor",
		"webMaster",
		"pubDate",
		"lastBuildDate",
		"category",
		"generator",
		"docs",
//		"cloud",
		"ttl",
		"image/title",
		"image/url",
		"image/link",
		"image/width",
		"image/height",
		"image/description",
//		"rating",
		"textInput/title",
		"textInput/description",
		"textInput/name",
		"textInput/link",
		"skipHours/hour",
		"skipDays/day",
	);
	public static $itemKeys	= array(
		"title",
		"link",
		"description",
		"author",
//		"category",
		"comments",
//		"enclosure",
		"guid",
		"pubDate",
//		"source",
	);
		
	public static function parse( $xml )
	{
		$channelData	= array();
		$itemList		= array();
		$xPath	= new XML_DOM_XPathQuery();
		$xPath->loadXml( $xml );

		$document	= $xPath->getDocument();
		$encoding	= $document->encoding;
		$version	= $document->documentElement->getAttribute( 'version' );

		foreach( self::$channelKeys as $channelKey )
		{
			$nodes	= $xPath->query( "//rss/channel/".$channelKey."/text()" );
			$parts	= explode( "/", $channelKey );
			if( isset( $parts[1] ) )
				$channelKey	= $parts[0].ucFirst( $parts[1] );
			$value	= $nodes->length ? $nodes->item( 0 )->nodeValue : NULL;
			$channelData[$channelKey]	= $value;
		}

		$nodeList	= $xPath->query( "//rss/channel/item" );
		foreach( $nodeList as $item )
		{
			$array	= array();
			foreach( self::$itemKeys as $itemKey )
			{
				$nodes	= $xPath->query( $itemKey."/text()", $item );
				$value	= $nodes->length ? $nodes->item( 0 )->nodeValue : NULL;
				$array[$itemKey]	= $value;
			}
			$itemList[]	= $array;
		}
		$data	= array(
			'encoding'		=> $encoding,
			'version'		=> $version,
			'channelData'	=> $channelData,
			'itemList'		=> $itemList
		);
		return $data;
	}
}
?>