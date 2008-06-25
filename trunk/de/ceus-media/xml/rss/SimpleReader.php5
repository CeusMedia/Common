<?php
import( 'de.ceus-media.xml.rss.SimpleParser' );
/**
 *	Reader for RSS 2.0 Feeds.
 *	@package		xml.rss
 *	@uses			File_Reader
 *	@uses			Net_Reader
 *	@uses			XML_RSS_SimpleParser
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			20.02.2008
 *	@version		0.6
 */
/**
 *	Reader for RSS 2.0 Feeds.
 *	@package		xml.rss
 *	@uses			File_Reader
 *	@uses			XML_RSS_SimpleParser
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			20.02.2008
 *	@version		0.6
 */
class XML_RSS_SimpleReader
{
	/**
	 *	Reads RSS from File.
	 *	@access		public
	 *	@param		string		$fileName	File Name to XML RSS File
	 *	@return		array
	 */
	public static function readFile( $fileName )
	{
		import( 'de.ceus-media.file.Reader' );
		$xml	= File_Reader::load( $fileName );
		return XML_RSS_SimpleParser::parse( $xml );
	}
	
	/**
	 *	Reads RSS from URL.
	 *	@access		public
	 *	@param		string		$url		URL to read RSS from
	 *	@return		array
	 */
	public static function readUrl( $url )
	{
		import( 'de.ceus-media.net.Reader' );
		$xml	= Net_Reader::readUrl( $url );
		return XML_RSS_SimpleParser::parse( $xml );
	}

	/**
	 *	Reads RSS from XML.
	 *	@access		public
	 *	@param		string		$xml		XML String to read
	 *	@return		array
	 */
	public static function readXml( $xml )
	{
		return XML_RSS_SimpleParser::parse( $xml );
	}
}
?>