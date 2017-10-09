<?php
/**
 *	JSON Writer.
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
 *	JSON Writer.
 *	@category		Library
 *	@package		CeusMedia_Common_FS_File_JSON
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2010-2015 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 *	@since			0.7.0
 *	@version		$Id$
 */
class FS_File_JSON_Writer
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
	 *	@param		string		$filePath		Path to JSON file
	 *	@param		mixed		$value			Value to write into JSON file
	 *	@param		bool		$format			Flag: format JSON serial
	 *	@return		int			Number of written bytes
	 */
	public static function save( $filePath, $value, $format = FALSE )
	{
		$writer	= new FS_File_JSON_Writer( $filePath );
		return $writer->write( $value, $format );
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
		if( $format )
		{
			if( version_compare( phpversion(), '5.4.0' ) >= 0 )
				$json	= json_encode( $value, JSON_PRETTY_PRINT );
			else
				$json	= ADT_JSON_Formater::format( json_encode( $value ) );
		}
		else
			$json	= json_encode( $value );
		return FS_File_Writer::save( $this->filePath, $json );
	}
}
?>
