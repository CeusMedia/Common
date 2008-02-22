<?php
/**
 *	Reader for XML Nodes.
 *	@package		xml
 *	@uses			File_Reader
 *	@uses			Net_Reader
 *	@uses			XML_Node
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			20.02.2008
 *	@version		0.6
 */
/**
 *	Reader for XML Nodes.
 *	@package		xml
 *	@uses			File_Reader
 *	@uses			Net_Reader
 *	@uses			XML_Node
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			20.02.2008
 *	@version		0.6
 */
class XML_NodeReader
{
	/**
	 *	Reads XML from File.
	 *	@access		public
	 *	@param		string		$fileName	File Name to XML File
	 *	@return		array
	 */
	public static function readFile( $fileName )
	{
		import( 'de.ceus-media.file.Reader' );
		import( 'de.ceus-media.xml.Node' );
		$xml	= File_Reader::load( $fileName );
		return new XML_Node( $xml );
	}
	
	/**
	 *	Reads XML from URL.
	 *	@access		public
	 *	@param		string		$url		URL to read XML from
	 *	@return		array
	 */
	public static function readUrl( $url )
	{
		import( 'de.ceus-media.net.Reader' );
		import( 'de.ceus-media.xml.Node' );
		$xml	= Net_Reader::readUrl( $url );
		return new XML_Node( $xml );
	}
}
?>