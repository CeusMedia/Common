<?php
import( 'de.ceus-media.file.Reader' );
import( 'de.ceus-media.file.Writer' );
/**
 *	Base bzip File implementation.
 *	@package		file
 *	@subpackage		arc
 *	@uses			File_Reader
 *	@uses			File_Writer
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.5
 */
/**
 *	Base bzip File implementation.
 *	@package		file
 *	@subpackage		arc
 *	@uses			File_Reader
 *	@uses			File_Writer
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.5
 *	@todo			use File_Editor
 */
class BzipFile
{
	protected $fileName;
	
	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		string		$fileName		URI of File
	 *	@return		void
	 */
	public function __construct( $fileName )
	{
		if( !function_exists( "bzcompress" ) )
			throw new Exception( "Bzip2 Extension is not available." );
		$this->fileName	= $fileName;
#		parent::__construct( $fileName );
	}

	/**
	 *	Reads gzip File and returns it as String.
	 *	@access		public
	 *	@return		string
	 */
 	public function readString()
	{
		$content	= File_Reader::load( $this->fileName );
		return bzdecompress( $content );
	}

	/**
	 *	Writes a String to the File.
	 *	@access		public
	 *	@param		string		$string			String to write to File
	 *	@return		int
	 */
	public function writeString( $string )
	{
		$content	= bzcompress( $string );
		return File_Writer::save( $this->fileName, $content );
	}
}
?>