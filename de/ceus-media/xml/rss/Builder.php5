<?php
import( 'de.ceus-media.adt.OptionObject' );
import( 'de.ceus-media.xml.dom.Node' );
import( 'de.ceus-media.xml.dom.Builder' );
/**
 *	Builder for RSS Feeds.
 *	@package		xml.rss
 *	@extends		ADT_OptionObject
 *	@uses			XML_DOM_Node
 *	@uses			XML_DOM_Builder
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			18.07.02005
 *	@version		0.4
 */
/**
 *	Builder for RSS Feeds.
 *	@package		xml.rss
 *	@extends		ADT_OptionObject
 *	@uses			XML_DOM_Node
 *	@uses			XML_DOM_Builder
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			18.07.02005
 *	@version		0.4
 */
class XML_RSS_Builder extends ADT_OptionObject
{
	/**	@var	XML_DOM_Builder	$builder			Instance of XML_DOM_Builder */
	protected $builder;
	/**	@var	array			$items				Array of items */
	protected $items	= array();
	/**	@var	array			$channelElements	Array of elements for channel */
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
	/**	@var	array			$itemElements		Array of elements for items */
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
		parent::__construct();
		$this->builder	= new XML_DOM_Builder();
		$this->setOption( 'timezone', '+0000' );
		$this->items	= array();
	}

	/**
	 *	Adds an item to RSS Feed.
	 *	@access		public
	 *	@param		array		$item			Item information to add
	 *	@return		void
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
	 *	Registers a Namespace for a Prefix.
	 *	@access		public
	 *	@param		string		$prefix			Prefix of Namespace
	 *	@param		string		$namespace		Namespace of Prefix
	 *	@return		bool
	 *	@see		http://tw.php.net/manual/de/function.dom-domxpath-registernamespace.php
	 */
	public function registerNamespace( $prefix, $namespace )
	{
		if( isset( $this->namespaces[$prefix] ) )
			throw new Exception( 'Namespace with Prefix "'.$prefix.'" is already registered for "'.$this->namespaces[$prefix].'".' );
		$this->namespaces[$prefix]	= $namespace;
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
			if( $required && !$this->getOption( $element ) )
				trigger_error( "RSS chennel element '".$element."' is required.", E_USER_WARNING );
//		if( count( $this->items ) < 1 )
//			trigger_error( "RSS items are required.", E_USER_WARNING );

		$tree = new XML_DOM_Node( 'rss' );
		$tree->setAttribute( 'version', '2.0' );
		foreach( $this->namespaces as $prefix => $namespace )
			$tree->setAttribute( "xmlns:".$prefix, $namespace );

		$channel	=& new XML_DOM_Node( 'channel' );
		
		//  --  CHANNEL  ELEMENTS  --  //
		foreach( $this->channelElements as $element => $required )
			if( $required || $this->getOption( $element ) )
				$channel->addChild( new XML_DOM_Node( $element, $this->getOption( $element ) ) );

		if( $this->getOption( 'date' ) && !$this->getOption( 'pubDate' ) )
			$channel->addChild( new XML_DOM_Node( 'pubDate', $this->getDate( $this->getOption( 'date' ) ) ) );

		if( $this->getOption( 'imageUrl' ) )
		{
			$image	=& new XML_DOM_Node( 'image' );
			$image->addChild( new XML_DOM_Node( 'url', $this->getOption( 'imageUrl' ) ) );
			if( $this->getOption( 'imageTitle' ) )
				$image->addChild( new XML_DOM_Node( 'title', $this->getOption( 'imageTitle' ) ) );
			if( $this->getOption( 'imageLink' ) )
				$image->addChild( new XML_DOM_Node( 'link', $this->getOption( 'imageLink' ) ) );
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
		return date( "c", $time ).$this->getOption( 'timezone' );
	}
}
?>