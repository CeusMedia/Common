<?php
import( 'de.ceus-media.xml.wddx.Parser' );
import( 'de.ceus-media.file.Reader' );
/**
 *	Reads a WDDX File.
 *	@package		xml.wddx
 *	@uses			XML_WDDX_Parser
 *	@uses			File_Reader
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.6
 */
/**
 *	Reads a WDDX File. 
 *	@package		xml.wddx
 *	@uses			XML_WDDX_Parser
 *	@uses			File_Reader
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.6
 */
class XML_WDDX_FileReader
{
	/**	@var		string		$fileName		File Name of WDDX File */
	protected $fileName;

	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		string		$fileName		File Name of WDDX File
	 *	@return		void
	 */
	public function __construct( $fileName )
	{
		$this->fileName	= $fileName;
	}

	/**
	 *	Reads a WDDX File and deserializes it.
	 *	@access		public
	 *	@return		mixed
	 */
 	public function read()
	{
		return self::load( $this->fileName );
	}
	
	/**
	 *	Reads a WDDX File statically and returns deserialized Data.
	 *	@access		public
	 *	@return		mixed
	 */
	public static function load( $fileName )
	{
		$packet	= File_Reader::load( $fileName );
		return XML_WDDX_Parser::parse( $packet );
	}
}
?>