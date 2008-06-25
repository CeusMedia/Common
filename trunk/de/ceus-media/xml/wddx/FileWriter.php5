<?php
import( 'de.ceus-media.xml.wddx.Builder' );
import( 'de.ceus-media.file.Writer' );
/**
 *	Writes a WDDX File.
 *	@package		xml.wddx
 *	@uses			XML_WDDX_Builder
 *	@uses			File_Writer
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.6
 */
/**
 *	Writes a WDDX File.
 *	@package		xml.wddx
 *	@uses			XML_WDDX_Builder
 *	@uses			File_Writer
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.6
 */
class XML_WDDX_FileWriter
{
	/**	@var		string		$fileName		File Name of WDDX File */
	protected $fileName;

	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		string		$fileName		File Name of WDDX File
	 *	@param		string		$packetName		Packet name
	 *	@return		void
	 */
	public function __construct( $fileName, $packetName = NULL )
	{
		$this->builder	= new XML_WDDX_Builder( $packetName );
		$this->fileName	= $fileName;
	}
	
	/**
	 *	Adds a Data Object to the packet.
	 *	@access		public
	 *	@param		string		$key			Key of Data Object
	 *	@param		string		$value			Value of Data Object
	 *	@return		bool
	 */
	public function add( $key, $value )
	{
		return $this->builder->add( $key, $value );
	}

	/**
	 *	Writes collected Data into WDDX File.
	 *	@access		public
	 *	@param		string		string			String to write to WDDX File
	 *	@return		bool
	 */
	public function write()
	{
		$wddx	= $this->builder->build();
		$writer	= new File_Writer( $this->fileName );
		return $writer->writeString( $wddx );
	}

	/**
	 *	Writes Data into a WDDX File statically.
	 *	@access		public
	 *	@param		string		$fileName		File Name of WDDX File
	 *	@param		array		$data			Array of Packet Data
	 *	@param		string		$packetName		Packet Name
	 *	@return		int
	 */
	public static function save( $fileName, $data, $packetName = NULL )
	{
		if( $packetName === NULL )
			$wddx	= wddx_serialize_value( $data );
		else
			$wddx	= wddx_serialize_value( $data, $packetName );
		return File_Writer::save( $fileName, $wddx );
	}
}
?>