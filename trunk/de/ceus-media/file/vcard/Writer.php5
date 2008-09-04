<?php
/**
 *	Writes vCard String from vCard Data Object to a File.
 *	@package		file.vcard
 *	@uses			File_Writer
 *	@uses			File_VCard_Builder
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			03.09.2008
 *	@version		0.1
 */
import( 'de.ceus-media.file.Writer' );
import( 'de.ceus-media.file.vcard.Builder' );
/**
 *	Writes vCard String from vCard Data Object to a File.
 *	@package		file.vcard
 *	@uses			File_Writer
 *	@uses			File_VCard_Builder
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			03.09.2008
 *	@version		0.1
 */
class File_VCard_Writer
{
	/**	@var		string		$fileName		File Name of VCard File */
	protected $fileName;

	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		string		$fileName		File Name of VCard File.
	 *	@return		void
	 */
	public function __construct( $fileName )
	{
		$this->fileName	= $fileName;
	}

	/**
	 *	Writes a vCard Object to the set up File and returns Number of written Bytes.
	 *	@access		public
	 *	@param		ADT_VCard	$card			vCard Object
	 *	@param		string		$charsetIn		Charset to convert from
	 *	@param		string		$charsetOut		Charset to convert to
	 *	@return		int
	 */
	public function write( $card, $charsetIn = NULL, $charsetOut = NULL )
	{
		return $this->save( $this->fileName, $card, $charsetIn, $charsetOut );
	}
	
	/**
	 *	Saves a vCard Object to a File statically and returns Number of written Bytes.
	 *	@access		public
	 *	@param		ADT_VCard	$card			vCard Object
	 *	@param		string		$charsetIn		Charset to convert from
	 *	@param		string		$charsetOut		Charset to convert to
	 *	@return		int
	 */
	public static function save( $fileName, $card, $charsetIn = NULL, $charsetOut = NULL )
	{
		$string	= File_VCard_Builder::build( $card, $charsetIn, $charsetOut );
		return File_Writer::save( $fileName, $string );
	}
}
?>