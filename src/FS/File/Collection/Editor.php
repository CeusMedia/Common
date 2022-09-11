<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

/**
 *	Editor for List Files.
 *
 *	Copyright (c) 2007-2022 Christian Würker (ceusmedia.de)
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
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2022 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 *	@since			25.04.2008
 */

namespace CeusMedia\Common\FS\File\Collection;

use CeusMedia\Common\FS\File\Writer as FileWriter;
use DomainException;

/**
 *	Editor for List Files.
 *	@category		Library
 *	@package		CeusMedia_Common_FS_File_List
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2022 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 *	@since			25.04.2008
 */
class Editor extends Reader
{
	/**	@var		string		$fileName		File Name of List, absolute or relative URI **/
	protected $fileName;
	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		string		$fileName		File Name of List, absolute or relative URI
	 *	@return		void
	 */
	public function __construct( string $fileName )
	{
		parent::__construct( $fileName );
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
			throw new DomainException( 'List Item "'.$item.'" is already existing. See Option "force".' );
		$this->list[]	= $item;
		return $this->write();
	}

	/**
	 *	Edits an existing Item of current List.
	 *	@access		public
	 *	@param		string		$oldItem		Item to replace
	 *	@param		string		$newItem		Item to set instead
	 *	@return		int			Number of written bytes
	 */
	public function edit( string $oldItem, string $newItem ): int
	{
		$index	= $this->getIndex( $oldItem );
		$this->list[$index]	= $newItem;
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
		$index	= $this->getIndex( $item );
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
			throw new DomainException( 'List Item with Index '.$index.' is not existing.' );
		unset( $this->list[$index] );
		return $this->write();
	}

	/**
	 *	Saves current List to File.
	 *	@access		protected
	 *	@param		string			$mode			UNIX rights for chmod()
	 *	@param		string|NULL		$user			User Name for chown()
	 *	@param		string|NULL		$group			Group Name for chgrp()
	 *	@return		int				Number of written bytes
	 */
	protected function write( $mode = 0755, ?string $user = NULL, ?string $group = NULL ): int
	{
		$file	= new FileWriter( $this->fileName, $mode, $user, $group );
		return $file->writeArray( $this->list );
	}
}
