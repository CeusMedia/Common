<?php
import( 'de.ceus-media.xml.dom.XPathQuery' );
/**
 *	Parser for RSS2 XML Files.
 *	@package		xml.rss
 *	@uses			XML_DOM_XPathQuery
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			30.01.2006
 *	@version		0.1
 *	@see			http://blogs.law.harvard.edu/tech/rss
 */
/**
 *	Parser for RSS2 XML Files.
 *	@package		xml.rss
 *	@uses			XML_DOM_XPathQuery
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			30.01.2006
 *	@version		0.1
 *	@see			http://blogs.law.harvard.edu/tech/rss
 */
class XML_RSS_Parser
{
	public $channelKeys	= array(
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
	public $itemKeys	= array(
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
		
	protected $channelData	= array();
	protected $itemList		= array();

	public function __construct( $xml = false )
	{
		$this->xPath	= new XML_DOM_XPathQuery();
		if( $xml )
			$this->loadXml( $xml );
	}
	
	public function loadXml( $xml )
	{
		$this->items	= array();
		$this->xPath->loadXml( $xml );
	}
	
	public function parse()
	{
		foreach( $this->channelKeys as $channelKey )
		{
			$nodeList	= $this->xPath->query( "//rss/channel/".$channelKey."/text()" );
			$this->channelData[$channelKey]	= $nodeList->item( 0 )->nodeValue;
		}
		$itemList	= $this->xPath->query( "//rss/channel/item" );
		foreach( $itemList as $item )
		{
			$array	= array();
			foreach( $this->itemKeys as $itemKey )
			{
				$nodeList	= $this->xPath->query( $itemKey."/text()", $item );
				$array[$itemKey]	= $nodeList->item( 0 )->nodeValue;
			}
			$this->itemList[]	= $array;
		}
	}

	public function getItemList()
	{
		return $this->itemList;
	}

	public function getChannelData()
	{
		return $this->channelData;
	}
}
?>