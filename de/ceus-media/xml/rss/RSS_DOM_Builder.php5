<?php
import( 'de.ceus-media.adt.OptionObject' );
import( 'de.ceus-media.xml.dom.XML_DOM_Node' );
import( 'de.ceus-media.xml.dom.XML_DOM_Builder' );
/**
 *	Builder for RSS Feeds.
 *	@package		xml
 *	@subpackage		rss
 *	@extends		OptionObject
 *	@uses			XML_DOM_Node
 *	@uses			XML_DOM_Builder
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			18.07.02005
 *	@version		0.4
 */
/**
 *	Builder for RSS Feeds.
 *	@package		xml
 *	@subpackage		rss
 *	@extends		OptionObject
 *	@uses			XML_DOM_Node
 *	@uses			XML_DOM_Builder
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			18.07.02005
 *	@version		0.4
 */
class RSS_DOM_Builder extends OptionObject
{
	/**	@var	XML_DOM_Builder	_builder			Instance of XML_DOM_Builder */
	var $_builder;
	/**	@var	array			_items			Array of items */
	var $_items	= array();
	/**	@var	array			_channel_elements	Array of elements for channel */
	var $_channel_elements	= array(
		"title"				=> true,
		"description"		=> true,
		"link"				=> true,
		"pubDate"			=> false,
		"lastBuildDate"		=> false,
		"language"			=> false,
		"copyright"			=> false,
		"managingEditor"	=> false,
		"webMaster"			=> false,
		"category"			=> false,
		"generator"			=> false,
		"docs"				=> false,
		"cloud"				=> false,
		"ttl"				=> false,
		"rating"			=> false,
		);
	/**	@var	array			_channel_elements	Array of elements for items */
	var $_item_elements	= array(
		"title"				=> true,
		"description"		=> false,
		"link"				=> false,
		"author"			=> false,
		"category"			=> false,
		"comments"			=> false,
		"pubDate"			=> false,
		"enclosure"			=> false,
		"guid"				=> false,
		"source"			=> false,
		);
	
	/**
	 *	Constructor.
	 *	@access		public
	 *	@return		void
	 */
	public function __construct()
	{
		parent::__construct();
		$this->_builder	= new XML_DOM_Builder();
		$this->setOption( 'timezone', '+0000' );
		$this->_items		= array();
	}

	/**
	 *	Adds an item to RSS Feed.
	 *	@access		public
	 *	@param		array		item			Item information to add
	 *	@return		void
	 */
	function addItem( $item )
	{
		if( !isset( $item['pubDate'] ) )
		{
			if( isset( $item['date'] ) )
			{
				$item['pubDate'] = $this->_getDate( $item['date'] );
			}
			else
				$item['pubDate'] = $this->_getDate( time() );
		
		}
		$this->_items[] = $item;
	}

	/**
	 *	Returns built RSS Feed.
	 *	@access		public
	 *	@param		string		encoding		Encoding Type
	 *	@return		string
	 */
	function build( $encoding = "utf-8" )
	{
		foreach( $this->_channel_elements as $element => $required )
			if( $required && !$this->getOption( $element ) )
				trigger_error( "RSS chennel element '".$element."' is required.", E_USER_WARNING );
//		if( count( $this->_items ) < 1 )
//			trigger_error( "RSS items are required.", E_USER_WARNING );
		

		$tree = new XML_DOM_Node( 'rss' );
		$tree->setAttribute( 'version', '2.0' );
		$channel	=& new XML_DOM_Node( 'channel' );

		//  --  CHANNEL  ELEMENTS  --  //
		foreach( $this->_channel_elements as $element => $required )
			if( $required || $this->getOption( $element ) )
				$channel->addChild( new XML_DOM_Node( $element, $this->getOption( $element ) ) );

		if( $this->getOption( 'date' ) && !$this->getOption( 'pubDate' ) )
		{
			$date	=& new XML_DOM_Node( 'pubDate', $this->_getDate( $this->getOption( 'date' ) ) );
			$channel->addChild( $date );
		}

		if( $this->getOption( 'image_url' ) )
		{
			$image	=& new XML_DOM_Node( 'image' );
			$image->addChild( new XML_DOM_Node( 'url', $this->getOption( 'image_url' ) ) );
			if( $this->getOption( 'image_title' ) )
				$image->addChild( new XML_DOM_Node( 'title', $this->getOption( 'image_title' ) ) );
			if( $this->getOption( 'image_link' ) )
				$image->addChild( new XML_DOM_Node( 'link', $this->getOption( 'image_link' ) ) );
			$channel->addChild( $image );
		}			

		//  --  ITEMS  --  //
		foreach( $this->_items as $item )
		{
			$node	=& new XML_DOM_Node( 'item' );
			foreach( $this->_item_elements as $element => $required )
			{
				if( $required || isset( $item[$element] ) )
				{
					$subnode =& new XML_DOM_Node( utf8_encode( $element ) );
					$data = $item[$element];
					if( is_array( $data ) )
					{
						$subnode->setContent( $data['content'] );
						unset( $data['content'] );
						foreach( $data as $key => $value )
							$subnode->setAttribute( $key, $value );
					}	
					else $subnode->setContent ( $data );
					$node->addChild( $subnode );
				}
			}
			$channel->addChild( $node );
		}
		$tree->addChild( $channel );
		$this->_items	= array();
		$xml	= $this->_builder->build( $tree, $encoding );
		return $xml;	
	}

	/**
	 *	Returns formated date.
	 *	@access		private
	 *	@return		string
	 */
	function _getDate( $time )
	{
		return date("r", $time );
	}
	
	function _getDcDate( $time )
	{
		return date( "c", $time ).$this->getOption( 'timezone' );
	}
}
?>