<?php
/**
 *	Writing comma separatad values (CSV) data with or without column headers to File. 
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
 *	@package		file.csv
 *	@uses			File_Writer
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2007-2010 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@version		$Id$
 */
import ("de.ceus-media.file.Writer");
/**
 *	Writing comma separatad values (CSV) data with or without column headers to File. 
 *	@category		cmClasses
 *	@package		file.csv
 *	@uses			File_Writer
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2007-2010 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@version		$Id$
 */
class File_CSV_Writer
{
	/**	@var		string		$fileName		Flag: use ColumnHeaders in first line */
	protected $fileName;
	/**	@var		string		$separator		Separator Sign */
	protected $separator		= ",";
	
	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		string		$fileName		File name of CSV File
	 *	@param		string		$separator		Separator sign
	 *	@return		void
	 */
	public function __construct( $fileName, $separator = NULL )
	{
		$this->fileName	= $fileName;
		if( $separator )
			$this->setSeparator( $separator );
	}

	/**
	 *	Saves an 2 dimensional array with or without column headers.
	 *	@access		public
	 *	@param		array		$data			2 dimensional array of data
	 *	@param		array		$headers		List of Column Headers
	 *	@return		bool
	 */
	public function write( $data, $headers = array() )
	{
		$output = array ();
		if( $headers )
		{
			$output[] = implode( $this->separator, $headers );
		}
		foreach( $data as $line )
		{
			$line = implode( $this->separator, $line );
			$output[] = $line;
		}
		$file	= new File_Writer( $this->fileName );
		return $file->writeArray( $output );
	}
}
?>