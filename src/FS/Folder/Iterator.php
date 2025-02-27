<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

/**
 *	Iterates all Folders and Files within a Folder.
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

use DirectoryIterator;
use FilterIterator;
use RuntimeException;

/**
 *	Iterates all Folders and Files within a Folder.
 *	@category		Library
 *	@package		CeusMedia_Common_FS_Folder
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2024 Christian Würker
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */
class Iterator extends FilterIterator
{
	/**	@var		 bool		$showFiles			Flag: show Files */
	protected $showFiles;

	/**	@var		 bool		$showFolders		Flag: show Folders */
	protected $showFolders;

	/**	@var		 bool		$stripDotEntries	Flag: strip Files and Folder with leading Dot */
	protected $stripDotEntries;

	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		string		$path				Path to Folder
	 *	@param		bool		$showFiles			Flag: show Files
	 *	@param		bool		$showFolders		Flag: show Folders
	 *	@param		bool		$stripDotEntries	Flag: strip Files and Folders with leading Dot
	 *	@return		void
	 */
	public function __construct( string $path, bool $showFiles = TRUE, bool $showFolders = TRUE, bool $stripDotEntries = TRUE )
	{
		if( !file_exists( $path ) )
			throw new RuntimeException( 'Path "'.$path.'" is not existing.' );
		$this->showFiles		= $showFiles;
		$this->showFolders		= $showFolders;
		$this->stripDotEntries	= $stripDotEntries;
		parent::__construct( new DirectoryIterator( $path ) );
	}

	/**
	 *	Decides which Entry should be indexed.
	 *	@access		public
	 *	@return		bool
	 */
	public function accept(): bool
	{
		if( $this->getInnerIterator()->isDot() )
			return FALSE;
		$isDir	= $this->getInnerIterator()->isDir();
		if( !$this->showFolders && $isDir )
			return FALSE;
		if( !$this->showFiles && !$isDir )
			return FALSE;

		if( $this->stripDotEntries )
		{
			$folderName	= $this->getInnerIterator()->getFilename();
			if( preg_match( "@^\.\w@", $folderName ) )
				return FALSE;
		}
		return TRUE;
	}
}
