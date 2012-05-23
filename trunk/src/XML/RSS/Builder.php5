<?php
/**
 *	Builder for RSS Feeds.
 *
 *	Copyright (c) 2007-2010 Christian Würker (ceusmedia.com)
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
 *	@package		XML.RSS
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2010 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			18.07.02005
 *	@version		$Id$
 */
/**
 *	Builder for RSS Feeds.
 *	@category		cmClasses
 *	@package		XML.RSS
 *	@uses			XML_DOM_Node
 *	@uses			XML_DOM_Builder
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2010 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			18.07.02005
 *	@version		$Id$
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
		"title"				=> TRUE,
		"description"		=> TRUE,
		"link"				=> TRUE,
		"pubDate"			=> FALSE,
		"lastBuildDate"		=> FALSE,
		"language"			=> FALSE,
		"copyright"			=> FALSE,
		"managingEditor"	=> FALSE,
		"webMaster"			=> FALSE,
		"category"			=> FALSE,
		"generator"			=> FALSE,
		"docs"				=> FALSE,
		"cloud"				=> FALSE,
		"ttl"				=> FALSE,
		"rating"			=> FALSE,
	);
	/**	@var	array			$itemElements		Array of Elements of Items */
	protected $itemElements	= array(
		"title"				=> true,
		"description"		=> FALSE,
		"link"				=> FALSE,
		"author"			=> FALSE,
		"category"			=> FALSE,
		"comments"			=> FALSE,
		"pubDate"			=> FALSE,
		"enclosure"			=> FALSE,
		"guid"				=> FALSE,
		"source"			=> FALSE,
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
				throw new DomainException( 'RSS Channel Element "'.$element.'" is required.' );
//		if( count( $this->items ) < 1 )
//			trigger_error( "RSS items are required.", E_USER_WARNING );

		$tree = new XML_DOM_Node( 'rss' );
		$tree->setAttribute( 'version', '2.0' );
		$channel	= new XML_DOM_Node( 'channel' );
		
		//  --  CHANNEL  ELEMENTS  --  //
		foreach( $this->channelElements as $element => $required )
			if( $required || isset( $this->channel[$element] ) )
				$channel->addChild( new XML_DOM_Node( $element, $this->channel[$element] ) );

		if( isset( $this->channel['date'] ) && !isset( $this->channel['pubDate'] ) )
			$channel->addChild( new XML_DOM_Node( 'pubDate', $this->getDate( $this->channel['date'] ) ) );

		if( isset( $this->channel['imageUrl'] ) )
		{
			$image	= new XML_DOM_Node( 'image' );
			$image->addChild( new XML_DOM_Node( 'url', $this->channel['imageUrl'] ) );
			if( isset( $this->channel['imageTitle'] ) )
				$image->addChild( new XML_DOM_Node( 'title', $this->channel['imageTitle'] ) );
			if( isset( $this->channel['imageLink'] ) )
				$image->addChild( new XML_DOM_Node( 'link', $this->channel['imageLink'] ) );

			if( isset( $this->channel['imageWith'] ) )
				$image->addChild( new XML_DOM_Node( 'width', $this->channel['imageWith'] ) );
			if( isset( $this->channel['imageHeight'] ) )
				$image->addChild( new XML_DOM_Node( 'height', $this->channel['imageHeight'] ) );
			if( isset( $this->channel['imageDescription'] ) )
				$image->addChild( new XML_DOM_Node( 'description', $this->channel['imageDescription'] ) );
			$channel->addChild( $image );
		}			
		if( isset( $this->channel['textInputTitle'] ) )
		{
			$image	= new XML_DOM_Node( 'textInput' );
			$image->addChild( new XML_DOM_Node( 'title', $this->channel['textInputTitle'] ) );
			if( isset( $this->channel['textInputDescription'] ) )
				$image->addChild( new XML_DOM_Node( 'description', $this->channel['textInputDescription'] ) );
			if( isset( $this->channel['textInputName'] ) )
				$image->addChild( new XML_DOM_Node( 'name', $this->channel['textInputName'] ) );
			if( isset( $this->channel['textInputLink'] ) )
				$image->addChild( new XML_DOM_Node( 'link', $this->channel['textInputLink'] ) );
			$channel->addChild( $image );
		}

		//  --  ITEMS  --  //
		foreach( $this->items as $item )
		{
			$node	= new XML_DOM_Node( 'item' );
			foreach( $this->itemElements as $element => $required )
			{
				$value	= isset( $item[$element] ) ? $item[$element] : NULL;
				if( $required || $value )
				{
					if( $element == "pubDate" && $value )
						$value	= $this->getDate( $value );
					$node->addChild( new XML_DOM_Node( $element, $value ) );
				}
			}
			$channel->addChild( $node );
		}
		$tree->addChild( $channel );
		$this->items	= array();
		return $this->builder->build( $tree, $encoding, $this->namespaces );	
	}

	/**
	 *	Returns formated date.
	 *	@access		protected
	 *	@param		int			$time			Timestamp
	 *	@return		string
	 */
	protected function getDate( $time )
	{
		if( preg_match( '@^[0-9]+$@', $time ) )
			$time	= date( "r", (int) $time );
		return $time;
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
	 *	@param		array		$pairs		Array of Channel Information Pairs
	 *	@return		void
	 *	@see		http://cyber.law.harvard.edu/rss/rss.html#requiredChannelElements
	 */
	public function setChannelData( $pairs )
	{
		if( !is_array( $pairs ) )
			throw new Exception( 'Channel Data List must be an Array.' );
		foreach( $pairs as $key => $value )
			$this->setChannelPair( $key, $value );
	}

	/**
	 *	Sets Item List.
	 *	@access		public
	 *	@param		array		$items		List of Item
	 *	@return		void
	 *	@see		http://cyber.law.harvard.edu/rss/rss.html#hrelementsOfLtitemgt
	 */
	public function setItemList( $items )
	{
		if( !is_array( $items ) )
			throw new Exception( 'Item List must be an Array.' );
		$this->items	= array();
		foreach( $items as $item )
			$this->addItem( $item );
	}
	
	public function registerNamespace( $prefix, $url )
	{
		$this->namespaces[$prefix]	= $url;
	}
}
?>