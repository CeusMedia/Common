<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

/**
 *	Builder for RSS Feeds.
 *
 *	Copyright (c) 2007-2023 Christian Würker (ceusmedia.de)
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
 *	@category		Library
 *	@package		CeusMedia_Common_XML_RSS
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2023 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */

namespace CeusMedia\Common\XML\RSS;

use CeusMedia\Common\XML\DOM\Builder as DomBuilder;
use CeusMedia\Common\XML\DOM\Node;
use DomainException;
use DOMException;
use Exception;

/**
 *	Builder for RSS Feeds.
 *	@category		Library
 *	@package		CeusMedia_Common_XML_RSS
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2023 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */
class Builder
{
	/**	@var	DomBuilder		$builder			Instance of XML_DOM_Builder */
	protected $builder;

	/**	@var	array			$channel			Array of Channel Data */
	protected $channel			= [];

	/**	@var	array			$items				Array of Items */
	protected $items			= [];

	/**	@var	array			$channelElements	Array of Elements of Channel */
	protected $channelElements	= [
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
	];

	/**	@var	array			$itemElements		Array of Elements of Items */
	protected $itemElements	= [
		"title"				=> TRUE,
		"description"		=> FALSE,
		"link"				=> FALSE,
		"author"			=> FALSE,
		"category"			=> FALSE,
		"comments"			=> FALSE,
		"pubDate"			=> FALSE,
		"enclosure"			=> FALSE,
		"guid"				=> FALSE,
		"source"			=> FALSE,
	];

	/**	@var	array			$namespaces		Array or RSS Namespaces */
	protected $namespaces	= [];

	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		array		$data			Array of Channel Information Pairs
	 *	@return		void
	 *	@throws		Exception
	 */
	public function __construct( array $data = [] )
	{
		$this->builder	= new DomBuilder();
		$this->channel['timezone']	= '+0000';
		$this->items	= [];

		if( !is_array( $data ) )
			throw new Exception( 'Channel Data List must be an Array.' );
		foreach( $data as $key => $value )
			$this->setChannelPair( $key, $value );
	}

	/**
	 *	Adds an item to RSS Feed.
	 *	@access		public
	 *	@param		array		$item			Item information to add
	 *	@return		void
	 *	@see		http://cyber.law.harvard.edu/rss/rss.html#hrelementsOfLtitemgt
	 */
	public function addItem( array $item )
	{
		$this->items[] = $item;
	}

	/**
	 *	Returns built RSS Feed.
	 *	@access		public
	 *	@param		string		$encoding		Encoding Type
	 *	@param		string		$version		RSS version, default: 2.0
	 *	@return		string
	 *	@throws		DOMException
	 *	@todo		recheck RSS versions and perhaps set default to 0.92
	 */
	public function build( string $encoding = "utf-8", string $version = "2.0" ): string
	{
		foreach( $this->channelElements as $element => $required )
			if( $required && !isset( $this->channel[$element] ) )
				throw new DomainException( 'RSS Channel Element "'.$element.'" is required.' );
//		if( count( $this->items ) < 1 )
//			trigger_error( "RSS items are required.", E_USER_WARNING );

		$tree = new Node( 'rss' );
		$tree->setAttribute( 'version', $version );
		$channel	= new Node( 'channel' );

		//  --  CHANNEL  ELEMENTS  --  //
		foreach( $this->channelElements as $element => $required )
			if( $required || isset( $this->channel[$element] ) )
				$channel->addChild( new Node( $element, $this->channel[$element] ) );

		if( isset( $this->channel['date'] ) && !isset( $this->channel['pubDate'] ) )
			$channel->addChild( new Node( 'pubDate', $this->getDate( $this->channel['date'] ) ) );

		if( isset( $this->channel['imageUrl'] ) ){
			$image	= new Node( 'image' );
			$image->addChild( new Node( 'url', $this->channel['imageUrl'] ) );
			if( isset( $this->channel['imageTitle'] ) )
				$image->addChild( new Node( 'title', $this->channel['imageTitle'] ) );
			if( isset( $this->channel['imageLink'] ) )
				$image->addChild( new Node( 'link', $this->channel['imageLink'] ) );

			if( isset( $this->channel['imageWidth'] ) )
				$image->addChild( new Node( 'width', $this->channel['imageWidth'] ) );
			if( isset( $this->channel['imageHeight'] ) )
				$image->addChild( new Node( 'height', $this->channel['imageHeight'] ) );
			if( isset( $this->channel['imageDescription'] ) )
				$image->addChild( new Node( 'description', $this->channel['imageDescription'] ) );
			$channel->addChild( $image );
		}
		if( isset( $this->channel['textInputTitle'] ) ){
			$image	= new Node( 'textInput' );
			$image->addChild( new Node( 'title', $this->channel['textInputTitle'] ) );
			if( isset( $this->channel['textInputDescription'] ) )
				$image->addChild( new Node( 'description', $this->channel['textInputDescription'] ) );
			if( isset( $this->channel['textInputName'] ) )
				$image->addChild( new Node( 'name', $this->channel['textInputName'] ) );
			if( isset( $this->channel['textInputLink'] ) )
				$image->addChild( new Node( 'link', $this->channel['textInputLink'] ) );
			$channel->addChild( $image );
		}

		//  --  ITEMS  --  //
		foreach( $this->items as $item ){
			$node	= new Node( 'item' );
			foreach( $this->itemElements as $element => $required ){
				$value	= $item[$element] ?? NULL;
				if( $required || $value ){
					if( $element == "source" && $value ){
						$node->addChild( new Node( $element, $this->channel['title'], ['url' => $value] ) );
						continue;
					}
					if( $element == "guid" && $value ){
						$node->addChild( new Node( $element, $value, ['isPermaLink' => 'true'] ) );
						continue;
					}
					if( $element == "pubDate" && $value )
						$value	= $this->getDate( $value );
					$node->addChild( new Node( $element, $value ) );
				}
			}
			$channel->addChild( $node );
		}
		$tree->addChild( $channel );
		$this->items	= [];
		return $this->builder->build( $tree, $encoding, $this->namespaces );
	}

	/**
	 *	Returns formatted date.
	 *	@access		protected
	 *	@param		string|int		$timestamp		Timestamp or formatted date
	 *	@return		string
	 */
	protected function getDate( $timestamp ): string
	{
		if( preg_match( '@^[0-9]+$@', $timestamp ) )
			$timestamp	= date( "r", $timestamp );
		return $timestamp;
	}

	/**
	 *	Sets an Information Pair of Channel.
	 *	@access		public
	 *	@param		string			$key		Key of Channel Information Pair
	 *	@param		string|NULL		$value		Value of Channel Information Pair
	 *	@return		self
	 *	@see		http://cyber.law.harvard.edu/rss/rss.html#requiredChannelElements
	 */
	public function setChannelPair( string $key, ?string $value ): self
	{
		$this->channel[$key]	= $value;
		return $this;
	}

	/**
	 *	Sets Information of Channel.
	 *	@access		public
	 *	@param		array		$pairs		Array of Channel Information Pairs
	 *	@return		self
	 *	@throws		Exception
	 *	@see		http://cyber.law.harvard.edu/rss/rss.html#requiredChannelElements
	 */
	public function setChannelData( array $pairs ): self
	{
		foreach( $pairs as $key => $value )
			$this->setChannelPair( $key, $value );
		return $this;
	}

	/**
	 *	Sets Item List.
	 *	@access		public
	 *	@param		array		$items		List of Item
	 *	@return		self
	 *	@see		http://cyber.law.harvard.edu/rss/rss.html#hrelementsOfLtitemgt
	 */
	public function setItemList( array $items ): self
	{
		$this->items	= [];
		foreach( $items as $item )
			$this->addItem( $item );
		return $this;
	}

	public function registerNamespace( string $prefix, string $url ): self
	{
		$this->namespaces[$prefix]	= $url;
		return $this;
	}
}
