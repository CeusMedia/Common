<?php
import( 'de.ceus-media.file.Editor' );
import( 'de.ceus-media.file.Writer' );
/**
 *	Base bzip File implementation.
 *	@package		file.arc
 *	@uses			File_Editor
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.6
 */
/**
 *	Base bzip File implementation.
 *	@package		file.arc
 *	@uses			File_Editor
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.6
 */
class File_Arc_Bzip extends File_Editor
{
	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		string		$fileName		URI of File
	 *	@return		void
	 */
	public function __construct( $fileName )
	{
		if( !function_exists( "bzcompress" ) )
			throw new Exception( 'Bzip2 Extension is not available.' );
		parent::__construct( $fileName );
	}

	/**
	 *	Reads gzip File and returns it as String.
	 *	@access		public
	 *	@return		string
	 */
 	public function readString()
	{
		$content	= parent::readString();
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
		return parent::writeString( $content );
	}
}
?>