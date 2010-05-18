<?php
/**
 *	Writer for Log Files containing JSON Serials.
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
 *	@package		File.Log.JSON
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2007-2010 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			01.12.2008
 *	@version		$Id$
 */
/**
 *	Writer for Log Files containing JSON Serials.
 *	@category		cmClasses
 *	@package		File.Log.JSON
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2007-2010 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			01.12.2008
 *	@version		$Id$
 */
class File_Log_JSON_Writer
{
	/**	@var		string		$fileName		File Name of Log File */
	protected $fileName;

	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		string		$fileName		File Name of Log File
	 *	@return		void
	 */
	public function __construct( $fileName )
	{
		$this->fileName	= $fileName;	
	}

	/**
	 *	Adds Data to Log File.
	 *	@access		public
	 *	@param		array		$data			Data Array to note
	 *	@return		bool
	 */
	public function note( $data )
	{
		return self::noteData( $this->fileName, $data );
	}

	/**
	 *	Adds Data to Log File statically.
	 *	@access		public
	 *	@static
	 *	@param		string		$fileName		File Name of Log File
	 *	@param		array		$data			Data Array to note
	 *	@return		bool
	 */
	public static function noteData( $fileName, $data )
	{
		$data	= array_merge(
			array(
				'timestamp' => time()
			),
			$data
		);
		$serial	= json_encode( $data )."\n";
		if( !file_exists( dirname( $fileName ) ) )
			mkDir( dirname( $fileName ), 0700, TRUE );
		return error_log( $serial, 3, $fileName );
	}
}
?>