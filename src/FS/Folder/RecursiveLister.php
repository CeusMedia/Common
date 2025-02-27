<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

/**
 *	Lists Folders and Files within a Folder recursive.
 *	Entries can be filtered with a RegEx Pattern or allowed Extensions.
 *	The resulting List is a FilterIterator and contains SplInfo Entries.
 *	It is possible to hide Folders or Files from the List.
 *	Folders starting with a Dot can be stripped from the List.
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
 *	@package		CeusMedia_Common_FS_Folder
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2024 Christian Würker
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */

namespace CeusMedia\Common\FS\Folder;

use FilterIterator;

/**
 *	Lists Folders and Files within a Folder recursive.
 *	Entries can be filtered with a RegEx Pattern or allowed Extensions.
 *	The resulting List is a FilterIterator and contains SplInfo Entries.
 *	It is possible to hide Folders or Files from the List.
 *	Folders starting with a Dot can be stripped from the List.
 *	@category		Library
 *	@package		CeusMedia_Common_FS_Folder
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2024 Christian Würker
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */
class RecursiveLister extends Lister
{
	/**
	 *	Returns List as FilterIterator.
	 *	@access		public
	 *	@return		FilterIterator
	 */
	public function getList(): FilterIterator
	{
		if( $this->pattern )
			return new RecursiveRegexFilter(
				$this->path,
				$this->pattern,
				$this->showFiles,
				$this->showFolders,
				$this->stripDotEntries
			);
		return new RecursiveIterator(
			$this->path,
			$this->showFiles,
			$this->showFolders,
			$this->stripDotEntries
		);
	}

	/**
	 *	Returns List of Files statically.
	 *	@access		public
	 *	@static
	 *	@param		string		$path				Path to Folder
	 *	@param		string		$pattern			RegEx Pattern to match with File Name
	 *	@return		FilterIterator
	 */
	public static function getFileList( string $path, ?string $pattern = NULL ): FilterIterator
	{
		$index	= new RecursiveLister( $path );
		if( $pattern !== NULL )
			$index->setPattern( $pattern );
		$index->showFiles( TRUE );
		$index->showFolders( FALSE );
		return $index->getList();
	}

	/**
	 *	Returns List of Folders statically.
	 *	@access		public
	 *	@static
	 *	@param		string		$path				Path to Folder
	 *	@param		string|NULL	$pattern			RegEx Pattern to match with Folder Name
	 *	@param		boolean		$stripDotEntries	Flag: strip Files and Folders starting with a Dot
	 *	@return		FilterIterator
	 */
	public static function getFolderList( string $path, ?string $pattern = NULL, bool $stripDotEntries = TRUE ): FilterIterator
	{
		$index	= new RecursiveLister( $path );
		if( $pattern !== NULL )
			$index->setPattern( $pattern );
		$index->showFiles( FALSE );
		$index->showFolders( TRUE );
		$index->stripDotEntries( $stripDotEntries );
		return $index->getList();
	}

	/**
	 *	Returns List of Folders and Files statically.
	 *	@access		public
	 *	@static
	 *	@param		string		$path				Path to Folder
	 *	@param		string|NULL	$pattern			RegEx Pattern to match with Entry Name
	 *	@param		boolean		$stripDotEntries	Flag: strip Files and Folders starting with a Dot
	 *	@return		FilterIterator
	 */
	public static function getMixedList( string $path, ?string $pattern = NULL, bool $stripDotEntries = TRUE ): FilterIterator
	{
		$index	= new RecursiveLister( $path );
		if( $pattern !== NULL )
			$index->setPattern( $pattern );
		$index->showFiles( TRUE );
		$index->showFolders( TRUE );
		$index->stripDotEntries( $stripDotEntries );
		return $index->getList();
	}
}
