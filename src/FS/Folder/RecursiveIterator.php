<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

/**
 *	Iterates all Folders and Files recursive within a Folder.
 *
 *	Copyright (c) 2008-2024 Christian Würker (ceusmedia.de)
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
 *	@copyright		2008-2024 Christian Würker
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */

namespace CeusMedia\Common\FS\Folder;

use FilterIterator;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use RuntimeException;

/**
 *	Iterates all Folders and Files recursive within a Folder.
 *	@category		Library
 *	@package		CeusMedia_Common_FS_Folder
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2008-2024 Christian Würker
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */
class RecursiveIterator extends FilterIterator
{
	/**	@var		 string		$path				Path to iterate */
	protected string $path;

	/**	@var		 bool		$showFiles			Flag: show Files */
	protected bool $showFiles;

	/**	@var		 bool		$showFolders		Flag: show Folders */
	protected bool $showFolders;

	/**	@var		 bool		$stripDotEntries	Flag: strip Folder with leading Dot */
	protected bool $stripDotEntries;

	protected string $realPath;

	protected int $realPathLength;

	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		string		$path				Path to Folder
	 *	@param		bool		$showFiles			Flag: show Files
	 *	@param		bool		$showFolders		Flag: show Folders
	 *	@param		bool		$stripDotEntries	Flag: strip Files and Folder with leading Dot
	 *	@return		void
	 */
	public function __construct( string $path, bool $showFiles = TRUE, bool $showFolders = TRUE, bool $stripDotEntries = TRUE )
	{
		if( !file_exists( $path ) )
			throw new RuntimeException( 'Path "'.$path.'" is not existing.' );
		$this->path				= str_replace( "\\", "/", $path );
		$this->realPath			= str_replace( "\\", "/", realpath( $path ) );
		$this->realPathLength	= strlen( $this->realPath );
		$this->showFiles		= $showFiles;
		$this->showFolders		= $showFolders;
		$this->stripDotEntries	= $stripDotEntries;
		$selfIterator			= $showFolders ? RecursiveIteratorIterator::SELF_FIRST : RecursiveIteratorIterator::LEAVES_ONLY;
		parent::__construct(
			new RecursiveIteratorIterator(
				new RecursiveDirectoryIterator(
					$path,
					0
				),
				$selfIterator
			)
		);
	}

	/**
	 *	Decides which Entry should be indexed.
	 *	@access		public
	 *	@return		bool
	 */
	public function accept(): bool
	{
		/** @var RecursiveDirectoryIterator $innerIterator */
		$innerIterator	= $this->getInnerIterator();

		if( $innerIterator->isDot() )
			return FALSE;

		$isDir	= $innerIterator->isDir();
		if( !$this->showFiles && !$isDir )
			return FALSE;

		//  skip all folders and files starting with a dot
		if( $this->stripDotEntries ){
			//  found file or folder is hidden
			if( str_starts_with( $innerIterator->getFilename(), '.' ) )
				return FALSE;

			//  inner path is hidden
			if( str_starts_with( $innerIterator->getSubPathname(), '.' ) )
				return FALSE;

			//  be nice to Windows
			$subPath	= str_replace( "\\", "/", $innerIterator->getSubPathname() );
			//  at least 1 folder in inner path is hidden
			if( preg_match( '/\/\.\w/', $subPath ) )
				return FALSE;
		}
		return TRUE;
	}

	/**
	 *	Returns Path to Folder to iterate.
	 *	@access		public
	 *	@return		string		Path to Folder to iterate
	 */
	public function getPath(): string
	{
		return $this->path;
	}
}
