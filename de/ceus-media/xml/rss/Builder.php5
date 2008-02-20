<?php
import( 'de.ceus-media.xml.dom.Node' );
import( 'de.ceus-media.xml.dom.Builder' );
/**
 *	Builder for RSS Feeds.
 *	@package		xml.rss
 *	@uses			XML_DOM_Node
 *	@uses			XML_DOM_Builder
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			18.07.02005
 *	@version		0.4
 */
/**
 *	Builder for RSS Feeds.
 *	@package		xml.rss
 *	@uses			XML_DOM_Node
 *	@uses			XML_DOM_Builder
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			18.07.02005
 *	@version		0.4
 */
class XML_RSS_Builder
{
	/**	@var	XML_DOM_Builder	$builder			Instance of XML_DOM_Builder */
	protected $builder;
	/**	@var	array			$channel			Array of Channel Data */
	protected $channel			= array();
	/**	@var	array			$items				Array of Items */
	protected $items			= array();
	/**	@var	array			$channelElements	Array of Elements of Channel */
	protected $channelElements	= array(
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
	/**	@var	array			$itemElements		Array of Elements of Items */
	protected $itemElements	= array(
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
	/**	@var	array			$namespaces			Array or RSS Namespaces */
	protected $namespaces	= array();
	
	/**
	 *	Constructor.
	 *	@access		public
	 *	@return		void
	 */
	public function __construct()
	{
		$this->builder	= new XML_DOM_Builder();
		$this->channel['timezone']	= '+0000';
		$this->items	= array();
	}

	/**
	 *	Adds an item to RSS Feed.
	 *	@access		public
	 *	@param		array		$item			Item information to add
	 *	@return		void
	 *	@see		http://cyber.law.harvard.edu/rss/rss.html#hrelementsOfLtitemgt
	 */
	public function addItem( $item )
	{
		if( !isset( $item['pubDate'] ) )
		{
			if( isset( $item['date'] ) )
				$item['pubDate'] = $this->getDate( $item['date'] );
			else
				$item['pubDate'] = $this->getDate( time() );
		}
		$this->items[] = $item;
	}

	/**
	 *	Returns built RSS Feed.
	 *	@access		public
	 *	@param		string		$encoding		Encoding Type
	 *	@return		string
	 */
	public function build( $encoding = "utf-8" )
	{
		foreach( $this->channelElements as $element => $required )
			if( $required && !isset( $this->channel[$element] ) )
				throw new Exception( 'RSS Channel Element "'.$element.'" is required.' );
//		if( count( $this->items ) < 1 )
//			trigger_error( "RSS items are required.", E_USER_WARNING );

		$tree = new XML_DOM_Node( 'rss' );
		$tree->setAttribute( 'version', '2.0' );
		foreach( $this->namespaces as $prefix => $namespace )
			$tree->setAttribute( "xmlns:".$prefix, $namespace );

		$channel	=& new XML_DOM_Node( 'channel' );
		
		//  --  CHANNEL  ELEMENTS  --  //
		foreach( $this->channelElements as $element => $required )
			if( $required || isset( $this->channel[$element] ) )
				$channel->addChild( new XML_DOM_Node( $element, $this->channel[$element] ) );

		if( isset( $this->channel['date'] ) && !isset( $this->channel['pubDate'] ) )
			$channel->addChild( new XML_DOM_Node( 'pubDate', $this->getDate( $this->channel['date'] ) ) );

		if( isset( $this->channel['imageUrl'] ) )
		{
			$image	=& new XML_DOM_Node( 'image' );
			$image->addChild( new XML_DOM_Node( 'url', $this->channel['imageUrl'] ) );
			if( isset( $this->channel['imageTitle'] ) )
				$image->addChild( new XML_DOM_Node( 'title', $this->channel['imageTitle'] ) );
			if( isset( $this->channel['imageLink'] ) )
				$image->addChild( new XML_DOM_Node( 'link', $this->channel['imageLink'] ) );
			$channel->addChild( $image );
		}			

		//  --  ITEMS  --  //
		foreach( $this->items as $item )
		{
			$node	=& new XML_DOM_Node( 'item' );
			foreach( $this->itemElements as $element => $required )
				if( $required || isset( $item[$element] ) )
				{
					$item[$element]	= isset( $item[$element] ) ? $item[$element] : "";
					if( $element == "description" && $item[$element] )
						$item[$element]	= $item[$element];
					$node->addChild( new XML_DOM_Node( $element, $item[$element] ) );
				}
			$channel->addChild( $node );
		}
		$tree->addChild( $channel );
		$this->items	= array();
		return $this->builder->build( $tree, $encoding );	
	}

	/**
	 *	Returns formated date.
	 *	@access		protected
	 *	@param		int			$time			Timestamp
	 *	@return		string
	 */
	protected function getDate( $time )
	{
		return date( "r", $time );
	}
	
	/**
	 *	Returns formated date of Dublin Core.
	 *	@access		protected
	 *	@param		int			$time			Timestamp
	 *	@return		string
	 */
	protected function getDcDate( $time )
	{
		return date( "c", $time ).$this->channel['timezone'];
	}
	
	/** 
	 *	Registers a Namespace for a Prefix.
	 *	@access		public
	 *	@param		string		$prefix			Prefix of Namespace
	 *	@param		string		$namespace		Namespace of Prefix
	 *	@return		bool
	 *	@see		http://php.net/manual/en/function.dom-domxpath-registernamespace.php
	 */
	public function registerNamespace( $prefix, $namespace )
	{
		if( isset( $this->namespaces[$prefix] ) )
			throw new Exception( 'Namespace with Prefix "'.$prefix.'" is already registered for "'.$this->namespaces[$prefix].'".' );
		$this->namespaces[$prefix]	= $namespace;
	}
	
	/**
	 *	Sets an Information Pair of Channel.
	 *	@access		public
	 *	@param		string		$key		Key of Channel Information Pair
	 *	@param		string		$value		Value of Channel Information Pair
	 *	@return		void
	 *	@see		http://cyber.law.harvard.edu/rss/rss.html#requiredChannelElements
	 */
	public function setChannelPair( $key, $value )
	{
		$this->channel[$key]	= $value;
	}
	
	/**
	 *	Sets Information of Channel.
	 *	@access		public
	 *	@param		array		$array		Array of Channel Information Pairs
	 *	@return		void
	 *	@see		http://cyber.law.harvard.edu/rss/rss.html#requiredChannelElements
	 */
	public function setChannelData( $array )
	{
		$this->channel	= $array;
	}

	/**
	 *	Sets Item List.
	 *	@access		public
	 *	@param		array		$array		List of Item
	 *	@return		void
	 *	@see		http://cyber.law.harvard.edu/rss/rss.html#hrelementsOfLtitemgt
	 */
	public function setItemList( $itemList )
	{
		$this->items	= array();
		foreach( $itemList as $item )
			$this->addItem( $item );
	}
}
?>