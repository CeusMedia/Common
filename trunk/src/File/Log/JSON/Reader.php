<?php
/**
 *	Reader for Log Files containing JSON Serials.
 *
 *	Copyright (c) 2007-2012 Christian Würker (ceusmedia.com)
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
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2012 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			01.12.2008
 *	@version		$Id$
 */
/**
 *	Reader for Log Files containing JSON Serials.
 *	@category		cmClasses
 *	@package		File.Log.JSON
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2012 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			01.12.2008
 *	@version		$Id$
 */
class File_Log_JSON_Reader
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
	 *	Returns List of parsed Lines.
	 *	@access		public
	 *	@param		bool		$reverse		Flag: revert List
	 *	@param		int			$limit			Optional: limit List
	 *	@return		array
	 */
	public function getList( $reverse = FALSE, $limit = 0 )
	{
		return $this->read( $this->fileName, $reverse, $limit );
	}
	
	/**
	 *	Reads and returns List of parsed Lines statically.
	 *	@access		public
	 *	@static
	 *	@param		string		$fileName		File Name of Log File
	 *	@param		bool		$reverse		Flag: revert List
	 *	@param		int			$limit			Optional: limit List
	 *	@return		array
	 */
	public static function read( $fileName, $reverse = FALSE, $limit = 0 )
	{
		$data	= array();
		if( !file_exists( $fileName ) )
			throw new Exception( 'Log File "'.$fileName.'" is not existing.' );
		$lines		= file( $fileName );
		foreach( $lines as $line )
		{
			$line	= trim( $line );
			if( !$line )
				continue;
			$data[]	= json_decode( $line, TRUE );
		}
		if( $reverse )
			$data	= array_reverse( $data );
		if( $limit )
			$data	= array_slice( $data, 0, $limit );
		return $data;
	}
}
?>