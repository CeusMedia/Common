<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

/**
 *	Writing comma separated values (CSV) data with or without column headers to File.
 *
 *	Copyright (c) 2007-2024 Christian Würker (ceusmedia.de)
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
 *	along with this program.  If not, see <https://www.gnu.org/licenses/>.
 *
 *	@category		Library
 *	@package		CeusMedia_Common_FS_File_CSV
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2024 Christian Würker
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */

namespace CeusMedia\Common\FS\File\CSV;

use CeusMedia\Common\FS\File\Writer as FileWriter;

/**
 *	Writing comma separated values (CSV) data with or without column headers to File.
 *	@category		Library
 *	@package		CeusMedia_Common_FS_File_CSV
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2024 Christian Würker
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */
class Writer
{
	/**	@var		string		$fileName		Flag: use ColumnHeaders in first line */
	protected $fileName;

	/**	@var		string		$separator		Separator Sign */
	protected $separator		= ",";

	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		string			$fileName		File name of CSV File
	 *	@param		string|NULL		$separator		Separator sign
	 *	@return		void
	 */
	public function __construct( string $fileName, ?string $separator = NULL )
	{
		$this->fileName	= $fileName;
		if( $separator )
			$this->setSeparator( $separator );
	}

	/**
	 *	Sets separating Sign.
	 *	@access		public
	 *	@param		string		$separator		Separator sign
	 *	@return		self
	 */
	public function setSeparator( string $separator ): self
	{
		$this->separator	= $separator;
		return $this;
	}

	/**
	 *	Saves an 2 dimensional array with or without column headers.
	 *	@access		public
	 *	@param		array		$data			2 dimensional array of data
	 *	@param		array		$headers		List of Column Headers
	 *	@return		int			Number of written bytes
	 */
	public function write( array $data, array $headers = [] ): int
	{
		$output = [];
		if( $headers )
			$output[] = implode( $this->separator, $headers );
		foreach( $data as $line ){
			//  iterate line values
			foreach( $line as $nr => $value )
				//  separator found in value
				if( substr_count( $value, $this->separator ) > 0 )
					//  value is not masked
					if( substr( $value, 0, 1 ).substr( $value, -1 ) != '""' )
						//  mask value
						$line[$nr]	= '"'.addslashes( $value ).'"';
			$line = implode( $this->separator, $line );
			$output[] = $line;
		}
		$file	= new FileWriter( $this->fileName );
		return $file->writeArray( $output );
	}
}
