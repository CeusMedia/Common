<?php
import( 'de.ceus-media.xml.Element' );
/**
 *	Reader for XML Elements from File or URL.
 *	@package		xml
 *	@uses			File_Reader
 *	@uses			Net_Reader
 *	@uses			XML_Element
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			20.02.2008
 *	@version		0.6
 */
/**
 *	Reader for XML Elements from File or URL.
 *	@package		xml
 *	@uses			File_Reader
 *	@uses			Net_Reader
 *	@uses			XML_Element
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			20.02.2008
 *	@version		0.6
 */
class XML_ElementReader
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
		$xml	= File_Reader::load( $fileName );
		return new XML_Element( $xml );
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
		$xml	= Net_Reader::readUrl( $url );
		return new XML_Element( $xml );
	}
}
?>