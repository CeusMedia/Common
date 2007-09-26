<?php
import( 'de.ceus-media.file.File' );
/**
 *	Base bzip File implementation.
 *	@package		file
 *	@subpackage		arc
 *	@extends		File
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.5
 */
/**
 *	Base bzip File implementation.
 *	@package		file
 *	@subpackage		arc
 *	@extends		File
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.5
 */
class BzipFile extends File
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
			throw new Exception( "Bzip2 Extension is not available." );
		parent::__construct( $fileName );
	}

	/**
	 *	Reads gzip File and returns it as String.
	 *	@access		public
	 *	@return		string
	 */
 	public function readString()
	{
		$string = "";
		$zp = bzopen( $this->fileName, "r" );
		while( !feof( $zp ) )
			$string .= bzread( $zp, 4096 );
		bzclose( $zp );
		return $string;
	}

	/**
	 *	Writes a String to the File.
	 *	@access		public
	 *	@param		string		$string			String to write to File
	 *	@return		bool
	 */
	public function writeString( $string )
	{
		$zp = bzopen( $this->fileName, "w" );
		bzwrite( $zp, $string );
		bzclose( $zp );
	}
}
?>