<?php
import( 'de.ceus-media.file.Reader' );
import( 'de.ceus-media.xml.opml.Parser' );
/**
 *	@package		xml.opml
 *	@uses			XML_OPML_Parser
 *	@uses			File_Reader
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.6
 */
/**
 *	@package		xml.opml
 *	@uses			XML_OPML_Parser
 *	@uses			File_Reader
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.6
 */
class XML_OPML_FileReader
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
	 *	Loads a OPML File statically.
	 *	@access		public
	 *	@param		string		$fileName		URI of OPML File
	 *	@return		bool
	 */
	public static function load( $fileName )
	{
		$file	= new File_Reader( $fileName );
		if( !$file->exists() )
			throw new Exception( "File '".$fileName."' is not existing." );
		$xml	= $file->readString();
		$parser	= new XML_OPML_Parser();
		return $parser->parse( $xml );
	}
	
	/**
	 *	Reads OPML File and returns Outline Array.
	 *	@access		public
	 *	@return		XML_DOM_Node
	 */
	public function read()
	{
		return self::load( $this->fileName );
	}
}
?>