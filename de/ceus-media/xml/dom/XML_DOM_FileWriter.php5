<?php
import( 'de.ceus-media.file.File' );
import( 'de.ceus-media.xml.dom.XML_DOM_Builder' );
/**
 *	Writes XML Files from Trees build with XML_Node.
 *	@package	xml
 *	@subpackage	dom
 *	@uses		XML_DOM_Builder
 *	@uses		File
 *	@author		Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.4
 */
/**
 *	Writes XML Files from Trees build with XML_Node.
 *	@package	xml
 *	@subpackage	dom
 *	@uses		XML_DOM_Builder
 *	@uses		File
 *	@author		Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.4
 */
class XML_DOM_FileWriter
{
	/**	@var	string			filename		URI (absolute) of XML File */
	var $_filename;
	
	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		string		filename		URI (absolute) of XML File
	 *	@return		void
	 */
	public function __construct ($filename)
	{
		$this->_filename	= $filename;
		$this->_builder	= new XML_DOM_Builder();
	}
	
	/**
	 *	Writes XML Tree to XML File.
	 *	@access		public
	 *	@param		XML_DOM_Node	tree		XML Tree
	 *	@param		string			encoding	Encoding Type
	 *	@return		void
	 */
	function write ($tree, $encoding = "utf-8" )
	{
		$xml	= $this->_builder->build( $tree, $encoding );
		$file	= new File( $this->_filename, 0777 );
		$file->writeString( $xml );
	}
}
?>