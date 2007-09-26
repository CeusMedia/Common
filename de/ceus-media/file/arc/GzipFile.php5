<?php
import( 'de.ceus-media.file.File' );
/**
 *	Base gzip File implementation.
 *	@package		file
 *	@subpackage		arc
 *	@extends		File
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.5
 */
/**
 *	Base gzip File implementation.
 *	@package		file
 *	@subpackage		arc
 *	@extends		File
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.5
 */
class GzipFile extends File
{
	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		string		$fileName		URI of File
	 *	@return		void
	 */
	public function __construct( $fileName )
	{
		if( !function_exists( "gzcompress" ) )
			throw new Exception( "Gzip Extension is not available." );
		parent::__construct( $fileName );
	}

	/**
	 *	Reads gzip File and returns it as String.
	 *	@access		public
	 *	@return		string
	 */
 	public function readString()
	{
		$string	= "";
		$zp		= @gzopen( $this->fileName, "r" );
		while( !feof( $zp ) )
			$string	.= @gzread( $zp, 4096 );
		gzclose( $zp );
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
		$zp	= @gzopen( $this->fileName, "w9" );
		gzwrite( $zp, $string );
		gzclose( $zp );
	}
}
?>