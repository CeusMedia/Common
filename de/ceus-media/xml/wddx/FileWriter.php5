<?php
import( 'de.ceus-media.xml.wddx.Builder' );
import( 'de.ceus-media.file.Writer' );
/**
 *	Writes a WDDX File.
 *	@package		xml.wddx
 *	@uses			XML_WDDX_Builder
 *	@uses			File_Writer
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.5
 */
/**
 *	Writes a WDDX File.
 *	@package		xml.wddx
 *	@uses			XML_WDDX_Builder
 *	@uses			File_Writer
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.5
 */
class XML_WDDX_FileWriter
{
	/**	@var		File_Writer	$file			WDDX File Writer */
	protected $file;

	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		string		$fileName		URI of WDDX File
	 *	@param		string		$packetName		Packet name
	 *	@return		void
	 */
	public function __construct( $fileName, $packetName )
	{
		$this->builder	= new XML_WDDX_Builder( $packetName );
		$this->file		= new File_Writer( $this->fileName );
	}

	/**
	 *	Removing a WDDX File by its filename.
	 *	@access		public
	 *	@return		bool
	 */
	public function  delete ()
	{
		return $this->file->delete();
	}

	/**
	 *	Writes a string to the WDDX File.
	 *	@access		public
	 *	@param		string		string			String to write to WDDX File
	 *	@return		bool
	 */
	public function write()
	{
		$string	= $this->builder->build();
		return $this->file->writeString( $string );
	}
}
?>