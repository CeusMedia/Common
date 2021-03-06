<?php
/**
 *	Writer for RSS 2.0 Feeds.
 *
 *	Copyright (c) 2007-2020 Christian Würker (ceusmedia.de)
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
 *	@copyright		2007-2020 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 *	@since			20.02.2008
 */
/**
 *	Writer for RSS 2.0 Feeds.
 *	@category		Library
 *	@package		CeusMedia_Common_XML_RSS
 *	@uses			FS_File_Reader
 *	@uses			XML_RSS_Builder
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2020 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 *	@since			20.02.2008
 */
class XML_RSS_Writer
{
	/**	@var	array			$channelData		Array of Channel Data */
	protected $channelData		= array();
	/**	@var	array			$itemList			Array of Items */
	protected $itemList			= array();

	/**
	 *	Adds an item to RSS Feed.
	 *	@access		public
	 *	@param		array		$item			Item information to add
	 *	@return		void
	 *	@see		http://cyber.law.harvard.edu/rss/rss.html#hrelementsOfLtitemgt
	 */
	public function addItem( $item )
	{
		$this->itemList[] = $item;
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
		$this->channelData	= $array;
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
		$this->channelData[$key]	= $value;
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
		$this->itemList	= $itemList;
	}

	/**
	 *	Writes RSS to a File statically and returns Number of written Bytes.
	 *	@access		public
	 *	@static
	 *	@param		string		$fileName	File Name of XML RSS File
	 *	@param		array		$array		Array of Channel Information Pairs
	 *	@param		array		$array		List of Item
	 *	@param		string		$encoding	Encoding Type
	 *	@return		int
	 */
	public static function save( $fileName, $channelData, $itemList, $encoding = "utf-8" )
	{
		$builder	= new XML_RSS_Builder();
		$builder->setChannelData( $channelData );
		$builder->setItemList( $itemList );
		$xml	= $builder->build( $encoding = "utf-8" );
		return FS_File_Writer::save( $fileName, $xml );
	}

	/**
	 *	Writes RSS to a File and returns Number of written Bytes.
	 *	@access		public
	 *	@param		string		$fileName	File Name of XML RSS File
	 *	@param		string		$encoding	Encoding Type
	 *	@return		int
	 */
	public function write( $fileName, $encoding = "utf-8" )
	{
		return self::save( $fileName, $this->channelData, $this->itemList, $encoding );
	}
}
