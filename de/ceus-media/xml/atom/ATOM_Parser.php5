<?php
import( 'de.ceus-media.adt.OptionObject' );
import( 'de.ceus-media.xml.dom.XML_DOM_XPathQuery' );
/**
 *	Parser for ATOM XML Files.
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
 *	Parser for ATOM XML Files.
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
class ATOM_Parser extends OptionObject
{
	var $_channel_keys	= array(
		"id/text()"					=> "content",
		"title/text()"					=> "content",	
		"updated/text()"				=> "content",

		"author/atom:name/text()"		=> "content",
		"author/atom:email/text()"		=> "content",
		"author/atom:uri/text()"			=> "content",

		"subtitle/text()"				=> "content",
		"link/@href"					=> "value",
		"link/@rel"					=> "value",
		"link/@title"					=> "value",

		"category/@term"				=> "value",
		"contributor/atom:name/text()"	=> "content",

		"generator/@uri"				=> "value",
		"generator/@version"			=> "value",
		"generator/text()"				=> "content",

		"icon/text()"					=> "content",
		"logo/text()"					=> "content",
		"rights/text()"					=> "content",

		);
	var $_entry_keys	= array(
		"id/text()"					=> "content",
		"title/text()"					=> "content",	
		"updated/text()"				=> "content",
		"published/text()"				=> "content",

//		"created/text()"				=> "content",
//		"issued/text()"					=> "content",
//		"modified/text()"				=> "content",

		"content/text()"				=> "content",
		"summary/text()"				=> "content",
		"category/@term"				=> "value",
		"contributor/atom:name/text()"	=> "content",

		"author/atom:name/text()"		=> "content",
		"author/atom:email/text()"		=> "content",
		"author/atom:uri/text()"			=> "content",

		"link/@rel"					=> "value",
		"link/@href"					=> "value",

		"source/atom:id/text()"			=> "content",
		"source/atom:title/text()"		=> "content",
		"source/atom:updated/text()"	=> "content",
		"source/atom:rights/text()"		=> "content",

		"rights/text()"					=> "content",

/*		"description",
		"author",
//		"category",
		"comments",
//		"enclosure",
		"guid",
		"pubDate",
//		"source",
*/		);

	public function __construct( $xml = false )
	{
		$this->_xpq	= new XML_DOM_XPathQuery();
		if( $xml )
			$this->loadXML( $xml );
	}
	
	function loadXML( $xml )
	{
		$this->_entries	= array();
		$this->_xpq->loadXML( $xml );
	}
	
	function parse()
	{
		$namespace	= $this->_xpq->query( 'namespace-uri(//*)' );
		$this->_xpq->registerNameSpace( "atom", $namespace->value );
		
		foreach( $this->_channel_keys as $channel_key => $area)
		{
			$value	= $this->_xpq->query( "//atom:feed/atom:".$channel_key, $area );
			if( is_array( $value ) && count( $value ) )
			{
				if( count( $value ) == 1 )
					$value	= $value[0];
				$this->setOption( $channel_key, $value );
			}
		}
		$entries	= $this->_xpq->query( "//atom:feed/atom:entry/atom:id/text()" );
		for( $i=0; $i<count( $entries->nodeset ); $i++ )
		{
			foreach( $this->_entry_keys as $entry_key => $area)
			{
				$node	= $entries->nodeset[$i];
				echo $node->content;
				$query	= "//atom:feed/atom:entry/atom:id[text()='".$node->content."']/../atom:".$entry_key;
				$value	= $this->_xpq->query( $query, $area );
				if( count( $value ) == 1 )
					$value	= $value[0];
				$this->_entries[$i][$entry_key]	= $value;
			}
		}
	}

	function getEntries()
	{
		return $this->_entries;
	}
}
?>