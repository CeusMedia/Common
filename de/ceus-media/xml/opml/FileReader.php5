<?php
import( 'de.ceus-media.file.Reader' );
import( 'de.ceus-media.xml.opml.Parser' );
/**
 *	@package		xml.opml
 *	@extends		XML_OPML_Parser
 *	@uses			File_Reader
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.5
 */
/**
 *	@package		xml.opml
 *	@extends		XML_OPML_Parser
 *	@uses			File_Reader
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.5
 */
class XML_OPML_FileReader extends XML_OPML_Parser
{
	/**
	 *	Loads a XML File.
	 *	@access		public
	 *	@param		string		$fileName		URI of XML File
	 *	@return		bool
	 */
	function read( $fileName )
	{
		$file	= new File_Reader( $fileName );
		if( !$file->exists() )
			throw new Exception( "File '".$fileName."' is not existing." );
		$xml	= $file->readString();
		$this->parse( $xml );
	}
}
?>