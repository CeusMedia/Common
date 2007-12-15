<?php
import( 'de.ceus-media.file.Writer' );
import( 'de.ceus-media.xml.dom.XML_DOM_Builder' );
/**
 *	Writes XML Files from Trees build with XML_Node.
 *	@package		xml.opml
 *	@uses			XML_DOM_Builder
 *	@uses			File_Writer
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.5
 */
/**
 *	Writes XML Files from Trees build with XML_Node.
 *	@package		xml.opml
 *	@uses			XML_DOM_Builder
 *	@uses			File_Writer
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.5
 */
class XML_OPML_FileWriter
{
	/**
	 *	Writes XML Tree to XML File.
	 *	@access		public
	 *	@param		XML_DOM_Node	tree		XML Tree
	 *	@param		string			encoding	Encoding Type
	 *	@return		void
	 */
	public function write( $fileName, $tree, $encoding = "utf-8" )
	{
		$builder	= new XML_DOM_Builder();
		$xml		= $builder->build( $tree, $encoding );
		$file		= new File_Writer( $fileName, 0777 );
		$file->writeString( $xml );
	}
}
?>