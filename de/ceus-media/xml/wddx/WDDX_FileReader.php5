<?php
import( 'de.ceus-media.xml.wddx.WDDX_Parser' );
import( 'de.ceus-media.file.File' );
/**
 *	Reads a WDDX File.
 *	@package	xml
 *	@subpackage	wddx
 *	@extends	WDDX_Parser
 *	@uses		File
 *	@author		Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.4
 */
/**
 *	Reads a WDDX File.
 *	@package	xml
 *	@subpackage	wddx
 *	@extends	WDDX_Parser
 *	@uses		File
 *	@author		Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.4
 */
class WDDX_FileReader extends WDDX_Parser
{
	/**	@var		string		Name of WDDX File */
	var $_filename;

	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		string	filename		URI of WDDX File
	 *	@return		void
	 */
	function WDDX_FileReader( $filename )
	{
		parent::__construct();
		$this->_filename	= $filename;
	}

	/**
	 *	Indicates whether a WDDX File contains same data like another WDDX File.
	 *	@access		public
	 *	@return		bool
	 */
	function equals( $filename )
	{
		$file		= new File( $this->_filename );
		return $file->equals( $filename );
	}

	/**
	 *	Proving existence of a WDDX File by its filename.
	 *	@access		public
	 *	@return		bool
	 */
	function exists()
	{
		$file		= new File( $this->_filename );
		return $file->exists();
	}

	/**
	 *	Reads a WDDX File and deserializes it.
	 *	@access		public
	 *	@return		mixed
	 */
 	function read()
	{
		$file		= new File( $this->_filename );
		$packet 	= $file->readString();
		$this->WDDX_Parser( $packet );
		return $this->parse();
	}
}
?>