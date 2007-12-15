<?php
import( 'de.ceus-media.xml.wddx.Parser' );
import( 'de.ceus-media.file.Reader' );
/**
 *	Reads a WDDX File.
 *	@package		xml.wddx
 *	@uses			XML_WDDX_Parser
 *	@uses			File_Reader
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.5
 */
/**
 *	Reads a WDDX File.
 *	@package		xml.wddx
 *	@uses			XML_WDDX_Parser
 *	@uses			File_Reader
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.5
 */
class XML_WDDX_FileReader
{
	/**	@var		File_Reader		$file			WDDX File Reader */
	protected $file;

	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		string			$fileName		URI of WDDX File
	 *	@return		void
	 */
	public function __construct( $fileName )
	{
		$this->file	= new File_Reader( $fileName );
	}

	/**
	 *	Indicates whether a WDDX File contains same data like another WDDX File.
	 *	@access		public
	 *	@return		bool
	 */
	public function equals( $fileName )
	{
		return $this->file->equals( $fileName );
	}

	/**
	 *	Proving existence of a WDDX File by its filename.
	 *	@access		public
	 *	@return		bool
	 */
	public function exists()
	{
		return $this->file->exists();
	}

	/**
	 *	Reads a WDDX File and deserializes it.
	 *	@access		public
	 *	@return		mixed
	 */
 	public function read()
	{
		$packet 	= $this->file->readString();
		$parser		= new XML_WDDX_Parser();
		return $parser->parse( $packet );
	}
}
?>