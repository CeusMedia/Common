<?php
import( 'de.ceus-media.xml.wddx.Builder' );
import( 'de.ceus-media.file.Writer' );
/**
 *	Writes a WDDX File.
 *
 *	Copyright (c) 2007-2009 Christian W�rker (ceus-media.de)
 *
 *	This program is free software: you can redistribute it and/or modify
 *	it under the terms of the GNU General Public License as published by
 *	the Free Software Foundation, either version 3 of the License, or
 *	(at your option) any later version.
 *
 *	This program is distributed in the hope that it will be useful,
 *	but WITHOUT ANY WARRANTY; without even the implied warranty of
 *	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *	GNU General Public License for more details.
 *
 *	You should have received a copy of the GNU General Public License
 *	along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 *	@package		xml.wddx
 *	@uses			XML_WDDX_Builder
 *	@uses			File_Writer
 *	@author			Christian W�rker <christian.wuerker@ceus-media.de>
 *	@copyright		2007-2009 Christian W�rker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@version		0.6
 */
/**
 *	Writes a WDDX File.
 *	@package		xml.wddx
 *	@uses			XML_WDDX_Builder
 *	@uses			File_Writer
 *	@author			Christian W�rker <christian.wuerker@ceus-media.de>
 *	@copyright		2007-2009 Christian W�rker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
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
	 *	@static
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