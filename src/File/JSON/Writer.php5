<?php
/**
 *	JSON Writer.
 *
 *	Copyright (c) 2007-2010 Christian Würker (ceus-media.de)
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
 *	@category		cmClasses
 *	@package		File.JSON
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2010 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			0.7.0
 *	@version		$Id$
 */
/**
 *	JSON Writer.
 *	@category		cmClasses
 *	@package		File.JSON
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2010 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			0.7.0
 *	@version		$Id$
 */
class File_JSON_Writer
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
		$this->filePath	= $filePath;
	}

	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		mixed		$value			Value to write into JSON file
	 *	@param		bool		$format			Flag: format JSON serial
	 *	@return		int			Number of written bytes
	 */
	public function write( $value, $format = FALSE )
	{
		$json	= json_encode( $value );
		if( $format )
			$json	= ADT_JSON_Formater::format( $json );
		return File_Writer::save( $this->filePath, $json );
	}

	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		string		$filePath		Path to JSON file
	 *	@param		mixed		$value			Value to write into JSON file
	 *	@param		bool		$format			Flag: format JSON serial
	 *	@return		int			Number of written bytes
	 */
	public static function save( $filePath, $value, $format = FALSE )
	{
		$writer	= new File_JSON_Writer( $filePath );
		return $writer->write( $value, $format );
	}
}
?>