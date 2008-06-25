<?php
import( 'de.ceus-media.file.Writer' );
import( 'de.ceus-media.xml.rss.Builder' );
/**
 *	Writer for RSS 2.0 Feeds.
 *	@package		xml.rss
 *	@uses			File_Reader
 *	@uses			Net_Reader
 *	@uses			XML_RSS_Builder
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			20.02.2008
 *	@version		0.6
 */
/**
 *	Writer for RSS 2.0 Feeds.
 *	@package		xml.rss
 *	@uses			File_Reader
 *	@uses			XML_RSS_Builder
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			20.02.2008
 *	@version		0.6
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
	 *	Writes RSS to a File statically.
	 *	@access		public
	 *	@param		string		$fileName	File Name of XML RSS File
	 *	@param		array		$array		Array of Channel Information Pairs
	 *	@param		array		$array		List of Item
	 *	@param		string		$encoding	Encoding Type
	 *	@return		bool
	 */
	public static function save( $fileName, $channelData, $itemList, $encoding = "utf-8" )
	{
		$builder	= new XML_RSS_Builder();
		$builder->setChannelData( $channelData );
		$builder->setItemList( $itemList );
		$xml	= $builder->build( $encoding = "utf-8" );
		return (bool) File_Writer::save( $fileName, $xml );
	}

	/**
	 *	Writes RSS to a File.
	 *	@access		public
	 *	@param		string		$fileName	File Name of XML RSS File
	 *	@param		string		$encoding	Encoding Type
	 *	@return		bool
	 */
	public function write( $fileName, $encoding = "utf-8" )
	{
		return $this->save( $fileName, $this->channelData, $this->itemList, $encoding );
	}
}
?>