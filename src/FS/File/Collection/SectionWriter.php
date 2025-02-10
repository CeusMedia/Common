<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

/**
 *	Writer for Section List.
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
 *	@package		CeusMedia_Common_FS_File_List
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2024 Christian Würker
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */

namespace CeusMedia\Common\FS\File\Collection;

use CeusMedia\Common\FS\File\Writer as FileWriter;

/**
 *	Writer for Section List.
 *	@category		Library
 *	@package		CeusMedia_Common_FS_File_List
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2024 Christian Würker
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */
class SectionWriter
{
	/**	@var		string		$fileName		File Name of Section List */
	protected $fileName;

	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		string		$fileName		File Name of Section List
	 *	@return		void
	 */
	public function __construct( string $fileName )
	{
		$this->fileName = $fileName;
	}

	/**
	 *	Saves a Section List to a File.
	 *	@access		public
	 *	@static
	 *	@param		string		$fileName		File Name of Section List
	 *	@param		array		$list			Section List to write
	 *	@return		int			Number of written bytes
	 */
	public static function save( string $fileName, array $list ): int
	{
		$lines = [];
		foreach( $list as $section => $data ){
			if( count( $lines ) )
				$lines[] = "";
			$lines[] = "[".$section."]";
			foreach( $data as $entry )
				$lines[] = $entry;
		}
		$writer	= new FileWriter( $fileName, 0755 );
		return $writer->writeArray( $lines );
	}

	/**
	 *	Writes Section List.
	 *	@access		public
	 *	@param		array		$list			Section List to write
	 *	@return		int			Number of written bytes
	 */
	public function write( array $list ): int
	{
		return self::save( $this->fileName, $list );
	}
}
