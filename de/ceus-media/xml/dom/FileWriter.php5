<?php
import( 'de.ceus-media.file.Writer' );
import( 'de.ceus-media.xml.dom.Builder' );
/**
 *	Writes XML Files from Trees build with XML_DOM_Nodes.
 *	@package		xml.dom
 *	@uses			XML_DOM_Builder
 *	@uses			File_Writer
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.5
 */
/**
 *	Writes XML Files from Trees build with XML_DOM_Nodes.
 *	@package		xml.dom
 *	@uses			XML_DOM_Builder
 *	@uses			File_Writer
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.5
 */
class XML_DOM_FileWriter
{
	/**
	 *	Writes XML Tree to XML File.
	 *	@access		public
	 *	@param		string			$fileName		URI of XML File
	 *	@param		XML_DOM_Node	$tree			XML Tree
	 *	@param		string			$encoding		Encoding Type
	 *	@return		bool
	 */
	public function write( $fileName, $tree, $encoding = "utf-8" )
	{
		$builder	= new XML_DOM_Builder();
		$xml		= $builder->build( $tree, $encoding );
		$file		= new File( $fileName, 0777 );
		return $file->writeString( $xml );
	}
}
?>