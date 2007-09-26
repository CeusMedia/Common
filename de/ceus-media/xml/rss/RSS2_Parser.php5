<?php
import( 'de.ceus-media.adt.OptionObject' );
import( 'de.ceus-media.xml.dom.XML_DOM_XPathQuery' );
/**
 *	Parser for RSS2 XML Files.
 *	@see			http://blogs.law.harvard.edu/tech/rss
 *	@package		xml
 *	@subpackage		rss
 *	@extends		OptionObject
 *	@uses			XML_DOM_XPathQuery
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			30.01.2006
 *	@version			0.1
 */
/**
 *	Parser for RSS2 XML Files.
 *	@see			http://blogs.law.harvard.edu/tech/rss
 *	@package		xml
 *	@subpackage		rss
 *	@extends		OptionObject
 *	@uses			XML_DOM_XPathQuery
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			30.01.2006
 *	@version			0.1
 *	@todo			Code Documentation
 */
class RSS2_Parser extends OptionObject
{
	var $_channel_keys	= array(
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
	var $_item_keys	= array(
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

	public function __construct( $xml = false )
	{
		parent::__construct();
		$this->_xpq	= new XML_DOM_XPathQuery();
		if( $xml )
			$this->loadXML( $xml );
	}
	
	function loadXML( $xml )
	{
		$this->_items	= array();
		$this->_xpq->loadXML( $xml );
	}
	
	function parse()
	{
		foreach( $this->_channel_keys as $channel_key )
		{
			$value	= $this->_xpq->query( "//rss/channel/".$channel_key."/text()", "content" );
			if( is_array( $value ) && count( $value ) )
			{
				if( count( $value ) == 1 )
					$value	= $value[0];
				$this->setOption( $channel_key, $value );
			}
		}
		$items	= $this->_xpq->query( "//rss/channel/item/title/text()" );
		for( $i=0; $i<count( $items->nodeset ); $i++ )
		{
			foreach( $this->_item_keys as $item_key )
			{
				$node	= $items->nodeset[$i];
				$query	= "//rss/channel/item/title[text()='".$node->content."']/../".$item_key."/text()";
				$value	= $this->_xpq->query( $query, "content" );
				if( count( $value ) == 1 )
					$value	= $value[0];
				$this->_items[$i][$item_key]	= $value;
			}
		}
	}

	function getItems()
	{
		return $this->_items;
	}
}
?>