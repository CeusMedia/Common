<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

/**
 *	Writer for RSS 2.0 Feeds.
 *
 *	Copyright (c) 2007-2024 Christian Würker (ceusmedia.de)
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
 *	along with this program.  If not, see <https://www.gnu.org/licenses/>.
 *
 *	@category		Library
 *	@package		CeusMedia_Common_XML_RSS
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2024 Christian Würker
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */

namespace CeusMedia\Common\XML\RSS;

use CeusMedia\Common\FS\File\Writer as FileWriter;
use Exception;

/**
 *	Writer for RSS 2.0 Feeds.
 *	@category		Library
 *	@package		CeusMedia_Common_XML_RSS
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2024 Christian Würker
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */
class Writer
{
	/**	@var	array			$channelData		Array of Channel Data */
	protected $channelData		= [];

	/**	@var	array			$itemList			Array of Items */
	protected $itemList			= [];

	/**
	 *	Adds an item to RSS Feed.
	 *	@access		public
	 *	@param		array		$item			Item information to add
	 *	@return		self
	 *	@see		http://cyber.law.harvard.edu/rss/rss.html#hrelementsOfLtitemgt
	 */
	public function addItem( array $item ): self
	{
		$this->itemList[] = $item;
		return $this;
	}

	/**
	 *	Sets Information of Channel.
	 *	@access		public
	 *	@param		array		$array		Array of Channel Information Pairs
	 *	@return		self
	 *	@see		http://cyber.law.harvard.edu/rss/rss.html#requiredChannelElements
	 */
	public function setChannelData( array $array ): self
	{
		$this->channelData	= $array;
		return $this;
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
		$this->channelData[$key]	= $value;
		return $this;
	}

	/**
	 *	Sets Item List.
	 *	@access		public
	 *	@param		array		$itemList		List of Item
	 *	@return		self
	 *	@see		http://cyber.law.harvard.edu/rss/rss.html#hrelementsOfLtitemgt
	 */
	public function setItemList( array $itemList ): self
	{
		$this->itemList	= $itemList;
		return $this;
	}

	/**
	 *	Writes RSS to a File statically and returns Number of written Bytes.
	 *	@access		public
	 *	@static
	 *	@param		string		$fileName		File Name of XML RSS File
	 *	@param		array		$channelData	Array of Channel Information Pairs
	 *	@param		array		$itemList		List of Item
	 *	@param		string		$encoding		Encoding Type
	 *	@return		int|FALSE
	 *	@throws		Exception
	 */
	public static function save( string $fileName, array $channelData, array $itemList, string $encoding = "utf-8" ): int|FALSE
	{
		$builder	= new Builder();
		$builder->setChannelData( $channelData );
		$builder->setItemList( $itemList );
		$xml	= $builder->build( $encoding );
		return FileWriter::save( $fileName, $xml );
	}

	/**
	 *	Writes RSS to a File and returns Number of written Bytes.
	 *	@access		public
	 *	@param		string		$fileName	File Name of XML RSS File
	 *	@param		string		$encoding	Encoding Type
	 *	@return		int|FALSE
	 *	@throws		Exception
	 */
	public function write( string $fileName, string $encoding = "utf-8" ): int|FALSE
	{
		return self::save( $fileName, $this->channelData, $this->itemList, $encoding );
	}
}
