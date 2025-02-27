<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

/**
 *	A Class for reading List Files.
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
use Countable;
use DomainException;
use RuntimeException;

/**
 *	A Class for reading List Files.
 *	@category		Library
 *	@package		CeusMedia_Common_FS_File_List
 *	@author			Chistian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2024 Christian Würker
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */
class Reader implements Countable
{
	/**	@var		array		$list			List */
	protected $list						= [];

	/**	@var		string		$commentPattern	RegEx Pattern of Comments */
	protected static $commentPattern	= '/^[#:;\/*-]/';

	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		string		$fileName		File Name of List, absolute or relative URI
	 *	@return		void
	 */
	public function __construct( string $fileName )
	{
		$this->list	= static::read($fileName);
	}

	/**
	 *	Returns current List as String.
	 *	@access		public
	 *	@return		string
	 */
	public function __toString(): string
	{
		return "{".implode( ", ", $this->list )."}";
	}

	/**
	 *	Returns the Index of a given Item in current List.
	 *	@access		public
	 *	@param		string		$item			Item to get Index for
	 *	@return		int
	 */
	public function getIndex( string $item ): int
	{
		$index	= array_search( $item, $this->list );
		if( $index === FALSE )
			throw new DomainException( 'Item "'.$item.'" is not in List.' );
		return $index;
	}

	/**
	 *	Returns current List.
	 *	@access		public
	 *	@return		array
	 */
	public function getList(): array
	{
		return $this->list;
	}

	/**
	 *	Returns the Size of current List.
	 *	@access		public
	 *	@return		int
	 */
	public function count(): int
	{
		return count( $this->list );
	}

	/**
	 *	Indicates whether an Item is in current List.
	 *	@access		public
	 *	@param		string		$item			Item to check
	 *	@return		bool
	 */
	public function hasItem( string $item ): bool
	{
		return in_array( $item, $this->list );
	}

	/**
	 *	Reads List File.
	 *	@access		public
	 *	@static
	 *	@param		string		$fileName		URI of list
	 *	@return		array
	 */
	public static function read( string $fileName ): array
	{
		$list	= [];
		if( !file_exists( $fileName ) )
			throw new RuntimeException( 'File "'.$fileName.'" is not existing' );
		$reader	= new FileReader( $fileName );
		$lines	= $reader->readArray();
		foreach( $lines as $line )
			if( $line = trim( $line ) )
				if( !preg_match( self::$commentPattern, $line ) )
					$list[]	= $line;
		return $list;
	}
}
