<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

/**
 *	A Class for reading Section List Files.
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
 *	@author			Chistian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2024 Christian Würker
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */

namespace CeusMedia\Common\FS\File\Collection;

use CeusMedia\Common\FS\File\Reader as FileReader;
use Exception;

/**
 *	A Class for reading Section List Files.
 *	@category		Library
 *	@package		CeusMedia_Common_FS_File_List
 *	@author			Chistian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2024 Christian Würker
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */
class SectionReader
{
	public static string $commentPattern	= '/^[#|-|*|:|;]/';
	public static string $sectionPattern	= '/^\[([a-z0-9_=.,:;# ])+\]$/i';

	protected array $list	= [];

	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		string		$fileName		File Name of sectioned List
	 *	@return		void
	 *	@throws		Exception
	 */
	public function __construct( string $fileName )
	{
		$this->list	= self::load( $fileName );
	}

	/**
	 *	Reads the List.
	 *	@access		public
	 *	@static
	 *	@param		string		$fileName		File Name of sectioned List
	 *	@return		array
	 *	@throws		Exception
	 */
	public static function load( string $fileName ): array
	{
		if( !file_exists( $fileName ) )
			throw new Exception( 'File "'.$fileName.'" is not existing.' );

		$reader	= new FileReader( $fileName );
		$lines	= $reader->readArray();

		$section	= NULL;
		$list		= [];
		foreach( $lines as $line ){
			$line = trim( $line );
			if( !$line )
				continue;
			if( preg_match( self::$commentPattern, $line ) )
				continue;

			if( preg_match( self::$sectionPattern, $line ) ){
				$section = substr( $line, 1, -1 );
				if( !isset( $list[$section] ) )
					$list[$section]	= [];
			}
			else if( $section )
				$list[$section][]	= $line;
		}
		return $list;
	}

	public function read(): array
	{
		return $this->list;
	}
}
