<?php
import ("de.ceus-media.file.Reader");
import ("de.ceus-media.xml.dom.Parser");
/**
 *	Loads an parses a XML File to a Tree of XML_DOM_Nodes.
 *	@package		xml.dom
 *	@uses			XML_DOM_Parser
 *	@uses			File_Reader
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.6
 */
/**
 *	Loads an parses a XML File to a Tree of XML_DOM_Nodes.
 *	@package		xml.dom
 *	@uses			XML_DOM_Parser
 *	@uses			File_Reader
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.6
 */
class XML_DOM_FileReader
{
	/**	@var		string			$fileName		URI of XML File */
	protected $fileName;
	
	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		string			$fileName		URI of XML File
	 *	@return		void
	 */
	public function __construct( $fileName )
	{
		$this->fileName	= $fileName;
	}
	
	/**
	 *	Loads a XML File statically and returns parsed Tree.
	 *	@access		public
	 *	@param		string		$fileName		URI of XML File
	 *	@return		XML_DOM_Node
	 */
	public static function load( $fileName )
	{
		$parser	= new XML_DOM_Parser();
		$xml	= File_Reader::load( $fileName );
		$tree	= $parser->parse( $xml );
		return $tree;
	}
	
	/**
	 *	Reads XML File and returns parsed Tree.
	 *	@access		public
	 *	@return		XML_DOM_Node
	 */
	public function read()
	{
		return self::load( $this->fileName );
	}
}
?>