<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

/**
 *	A Class for reading and writing List Files.
 *
 *	Copyright (c) 2007-2023 Christian Würker (ceusmedia.de)
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
 *	@package		CeusMedia_Common_FS_File_List
 *	@author			Chistian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2023 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */

namespace CeusMedia\Common\FS\File\Collection;

use CeusMedia\Common\FS\File\Writer as FileWriter;
use DomainException;

/**
 *	A Class for reading and writing List Files.
 *	@category		Library
 *	@package		CeusMedia_Common_FS_File_List
 *	@author			Chistian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2023 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */
class Writer
{
	/**	@var		array			$list			List **/
	protected array $list			= [];

	/**	@var		string			$fileName		File Name of List, absolute or relative URI **/
	protected string $fileName;

	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		string		$fileName		File Name of List, absolute or relative URI
	 *	@return		void
	 */
	public function __construct( string $fileName )
	{
		$this->fileName	= $fileName;
	}

	/**
	 *	Adds an Item to the current List.
	 *	@access		public
	 *	@param		string		$item			Item to add
	 *	@param		bool		$force			Flag: force overwriting
	 *	@return		int			Number of written bytes
	 */
	public function add( string $item, bool $force = FALSE ): int
	{
		if( in_array( $item, $this->list ) && !$force )
			throw new DomainException( 'Item "'.$item.'" is already existing. See Option "force".' );
		$this->list[]	= $item;
		return $this->write();
	}

	/**
	 *	Removes an Item from current List.
	 *	@access		public
	 *	@param		string		$item			Item to remove
	 *	@return		int			Number of written bytes
	 */
	public function remove( string $item ): int
	{
		if( !in_array( $item, $this->list ) )
			throw new DomainException( 'Item "'.$item.'" is not existing.' );
		$index	= array_search( $item, $this->list );
		unset( $this->list[$index] );
		return $this->write();
	}

	/**
	 *	Removes an Item from current List by its Index.
	 *	@access		public
	 *	@param		int			$index			Index of Item
	 *	@return		int			Number of written bytes
	 */
	public function removeIndex( int $index ): int
	{
		if( !isset( $this->list[$index] ) )
			throw new DomainException( 'Item with Index '.$index.' is not existing.' );
		unset( $this->list[$index] );
		return $this->write();
	}

	/**
	 *	Saves a List to File.
	 *	@access		public
	 *	@static
	 *	@param		string			$fileName		File Name of List, absolute or relative URI
	 *	@param		array			$list			List to save
	 *	@param		integer			$mode			UNIX rights for chmod()
	 *	@param		string|NULL		$user			Username for chown()
	 *	@param		string|NULL		$group			Group Name for chgrp()
	 *	@return		int				Number of written bytes
	 */
	public static function save( string $fileName, array $list, $mode = 0755, ?string $user = NULL, ?string $group = NULL ): int
	{
		$file	= new FileWriter( $fileName, $mode, $user, $group );
		return $file->writeArray( $list );
	}

	/**
	 *	Writes the current List to File.
	 *	@access		protected
	 *	@param		integer			$mode			UNIX rights for chmod() as octal integer
	 *	@param		string|NULL		$user			Username for chown()
	 *	@param		string|NULL		$group			Group Name for chgrp()
	 *	@return		int				Number of written bytes
	 */
	protected function write( int $mode = 0755, ?string $user = NULL, ?string $group = NULL ): int
	{
		return static::save($this->fileName, $this->list, $mode, $user, $group);
	}
}
