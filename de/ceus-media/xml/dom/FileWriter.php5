<?php
import( 'de.ceus-media.file.Writer' );
import( 'de.ceus-media.xml.dom.Builder' );
/**
 *	Writes XML Files from Trees build with XML_DOM_Nodes.
 *	@package		xml.dom
 *	@uses			XML_DOM_Builder
 *	@uses			File_Writer
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.6
 */
/**
 *	Writes XML Files from Trees build with XML_DOM_Nodes.
 *	@package		xml.dom
 *	@uses			XML_DOM_Builder
 *	@uses			File_Writer
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.6
 */
class XML_DOM_FileWriter
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
	 *	Writes XML Tree into XML File.
	 *	@access		public
	 *	@param		string			$fileName		URI of XML File
	 *	@param		XML_DOM_Node	$tree			XML Tree
	 *	@param		string			$encoding		Encoding Type
	 *	@return		bool
	 */
	public function write( $tree, $encoding = "utf-8" )
	{
		return self::save( $this->fileName, $tree, $encoding );
	}

	/**
	 *	Writes XML Tree into XML File.
	 *	@access		public
	 *	@param		string			$fileName		URI of XML File
	 *	@param		XML_DOM_Node	$tree			XML Tree
	 *	@param		string			$encoding		Encoding Type
	 *	@return		bool
	 */
	public static function save( $fileName, $tree, $encoding = "utf-8" )
	{
		$builder	= new XML_DOM_Builder();
		$xml		= $builder->build( $tree, $encoding );
		return File_Writer::save( $fileName, $xml );
	}
}
?>