<?php
import( 'de.ceus-media.xml.dom.XPathQuery' );
/**
 *	Parser for RSS 2 Feed using XPath.
 *	@package		xml.rss
 *	@uses			XML_DOM_XPathQuery
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			30.01.2006
 *	@version		0.1
 *	@see			http://blogs.law.harvard.edu/tech/rss
 */
/**
 *	Parser for RSS 2 Feed using XPath.
 *	@package		xml.rss
 *	@uses			XML_DOM_XPathQuery
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			30.01.2006
 *	@version		0.1
 *	@see			http://blogs.law.harvard.edu/tech/rss
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
			$channelData[$channelKey]	= $nodes->item( 0 )->nodeValue;
		}
		$nodeList	= $xPath->query( "//rss/channel/item" );
		foreach( $nodeList as $item )
		{
			$array	= array();
			foreach( self::$itemKeys as $itemKey )
			{
				$nodes	= $xPath->query( $itemKey."/text()", $item );
				$array[$itemKey]	= $nodes->item( 0 )->nodeValue;
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