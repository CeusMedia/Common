<?php
import ("de.ceus-media.file.File");
import ("de.ceus-media.xml.dom.Parser");
/**
 *	Loads an parses a XML File to a Tree of XML_DOM_Nodes.
 *	@package		xml.dom
 *	@uses			XML_DOM_Parser
 *	@uses			File
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.5
 */
/**
 *	Loads an parses a XML File to a Tree of XML_DOM_Nodes.
 *	@package		xml.dom
 *	@uses			XML_DOM_Parser
 *	@uses			File
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.5
 */
class XML_DOM_FileReader
{
	/**
	 *	Loads a XML File and returns parsed Tree.
	 *	@access		public
	 *	@param		string		$fileName		URI of XML File
	 *	@return		XML_DOM_Node
	 */
	public function read( $fileName )
	{
		$file	= new File( $fileName );
		$parser	= new XML_DOM_Parser();
		$xml	= $file->readString();
		$tree	= $parser->parse( $xml );
		return $tree;
	}
}
?>