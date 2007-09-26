<?php
import( 'de.ceus-media.xml.wddx.WDDX_Builder' );
import( 'de.ceus-media.file.File' );
/**
 *	Writes a WDDX File.
 *	@package	xml
 *	@subpackage	wddx
 *	@extends	WDDX_Builder
 *	@uses		File
 *	@author		Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.4
 */
/**
 *	Writes a WDDX File.
 *	@package	xml
 *	@subpackage	wddx
 *	@extends	WDDX_Builder
 *	@uses		File
 *	@author		Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.4
 */
class WDDX_FileWriter extends WDDX_Builder
{
	/**	@var		string		URI of WDDX File */
	var $_filename;

	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		string		filename		URI of WDDX File
	 *	@param		string		packet_name	Packet name
	 *	@return		void
	 */
	public function __construct( $filename, $packet_name )
	{
		parent::__construct( $packet_name );
		$this->_filename	= $filename;
	}

	/**
	 *	Removing a WDDX File by its filename.
	 *	@access		public
	 *	@param		string		filename		URI of WDDX File
	 *	@return		bool
	 */
	function  delete ()
	{
		$file		= new File( $this->_filename );
		return $file->delete();
	}

	/**
	 *	Writes a string to the WDDX File.
	 *	@access		public
	 *	@param		string		string			String to write to WDDX File
	 *	@return		bool
	 */
	function write()
	{
		$string	= $this->build();
		$file		= new File( $this->_filename );
		return $file->writeString( $string );
	}
}
?>