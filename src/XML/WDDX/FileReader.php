<?php
/**
 *	Reads a WDDX File.
 *
 *	Copyright (c) 2007-2012 Christian Würker (ceusmedia.de)
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
 *	@category		Library
 *	@package		CeusMedia_Common_XML_WDDX
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2012 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 *	@version		$Id$
 */
/**
 *	Reads a WDDX File. 
 *	@category		Library
 *	@package		CeusMedia_Common_XML_WDDX
 *	@uses			XML_WDDX_Parser
 *	@uses			FS_File_Reader
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2012 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 *	@version		$Id$
 */
class XML_WDDX_FileReader
{
	/**	@var		string		$fileName		File Name of WDDX File */
	protected $fileName;

	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		string		$fileName		File Name of WDDX File
	 *	@return		void
	 */
	public function __construct( $fileName )
	{
		if( !function_exists( 'wddx_packet_start' ) )
			throw new RuntimeException( 'WDDX is not supported' );
		$this->fileName	= $fileName;
	}

	/**
	 *	Reads a WDDX File and deserializes it.
	 *	@access		public
	 *	@return		mixed
	 */
 	public function read()
	{
		return self::load( $this->fileName );
	}
	
	/**
	 *	Reads a WDDX File statically and returns deserialized Data.
	 *	@access		public
	 *	@static
	 *	@return		mixed
	 */
	public static function load( $fileName )
	{
		$packet	= FS_File_Reader::load( $fileName );
		return XML_WDDX_Parser::parse( $packet );
	}
}
?>