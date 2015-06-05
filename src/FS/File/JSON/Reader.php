<?php
/**
 *	JSON Reader.
 *
 *	Copyright (c) 2007-2015 Christian Würker (ceusmedia.de)
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
 *	@package		CeusMedia_Common_FS_File_JSON
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2010-2015 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 *	@since			0.7.0
 *	@version		$Id$
 */
/**
 *	JSON Reader.
 *	@category		Library
 *	@package		CeusMedia_Common_FS_File_JSON
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2010-2015 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 *	@since			0.7.0
 *	@version		$Id$
 */
class FS_File_JSON_Reader
{
	protected $filePath;

	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		string		$filePath		Path to JSON file
	 *	@return		void
	 */
	public function __construct( $filePath )
	{
		if( !file_exists( $filePath ) )
			throw new RuntimeException( 'File "'.$filePath.'" is not existing' );
		$this->filePath	= $filePath;
	}

	/**
	 *	Reads a JSON file to an object or array statically.
	 *	@access		public
	 *	@param		string		$filePath		Path to JSON file
	 *	@param		bool		$asArray		Flag: read into an array
	 *	@return		object|array
	 */
	public static function load( $filePath, $asArray = NULL )
	{
		$reader	= new FS_File_JSON_Reader( $filePath );
		return $reader->read( $asArray );
	}

	/**
	 *	Reads the JSON file to an object or array.
	 *	@access		public
	 *	@param		bool		$asArray		Flag: read into an array
	 *	@return		object|array
	 */
	public function read( $asArray = NULL )
	{
		$json	= FS_File_Reader::load( $this->filePath );
		$data	= json_decode( $json, $asArray );
		if( $data === NULL ){
			if( !( strlen( trim( $json ) ) === 0 || trim( $json ) === "null" ) ){
				$message	= 'JSON file "'.$this->filePath.'" cannot be parsed: '.json_last_error_msg();
				throw new RuntimeException( $message, json_last_error() );
			}
		}
		return $data;
	}
}
?>
