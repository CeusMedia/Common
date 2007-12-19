<?php
import( 'de.ceus-media.file.Writer' );
import( 'de.ceus-media.xml.dom.XML_DOM_Builder' );
/**
 *	Writes XML Files from Trees build with XML_Node.
 *	@package		xml.opml
 *	@uses			XML_DOM_Builder
 *	@uses			File_Writer
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.6
 */
/**
 *	Writes XML Files from Trees build with XML_Node.
 *	@package		xml.opml
 *	@uses			XML_DOM_Builder
 *	@uses			File_Writer
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.6
 */
class XML_OPML_FileWriter
{
	/**	@var		string		$fileName		URI of OPML File */
	protected $fileName;
	
	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		string		$fileName		URI of OPML File
	 *	@return		void
	 */
	public function __construct( $fileName )
	{
		$this->fileName	= $fileName;
	}
	
	/**
	 *	Saves OPML Tree to OPML File statically.
	 *	@access		public
	 *	@param		string		$fileName		URI of OPML File
	 *	@param		XML_DOM_Node	tree		OPML Tree
	 *	@param		string			encoding	Encoding Type
	 *	@return		bool
	 */
	public static function save( $fileName, $tree, $encoding = "utf-8" )
	{
		$builder	= new XML_DOM_Builder();
		$xml		= $builder->build( $tree, $encoding );
		$file		= new File_Writer( $fileName, 0777 );
		return $file->writeString( $xml );
	}
	
	/**
	 *	Writes OPML Tree to OPML File.
	 *	@access		public
	 *	@param		XML_DOM_Node	tree		OPML Tree
	 *	@param		string			encoding	Encoding Type
	 *	@return		bool
	 */
	public function write( $tree, $encoding = "utf-8" )
	{
		return $this->save( $this->fileName, $tree, $encoding );
	}
}
?>