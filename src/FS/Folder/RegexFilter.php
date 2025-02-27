<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

/**
 *	Searchs for Folders by given RegEx Pattern (as File Name) in Folder.
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
use RegexIterator;
use RuntimeException;

/**
 *	Searchs for Folders by given RegEx Pattern (as File Name) in Folder.
 *	@category		Library
 *	@package		CeusMedia_Common_FS_Folder
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2024 Christian Würker
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 *	@todo			Fix Error while comparing File Name to Current File with Path
 */
class RegexFilter extends RegexIterator
{
	/**	@var		 bool		$showFiles			Flag: show Files */
	protected $showFiles;

	/**	@var		 bool		$showFolders		Flag: show Folders */
	protected $showFolders;

	/**	@var		 bool		$stripDotEntries	Flag: strip Folder with leading Dot */
	protected $stripDotEntries;


	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		string		$path				Path to search in
	 *	@param		string		$pattern			Regular Expression to match with File Name
	 *	@param		bool		$showFiles			Flag: show Files
	 *	@param		bool		$showFolders		Flag: show Folders
	 *	@param		bool		$stripDotEntries	Flag: strip Files and Folder with leading Dot
	 *	@return		void
	 */
	public function __construct( string $path, string $pattern, bool $showFiles = TRUE, bool $showFolders = TRUE, bool $stripDotEntries = TRUE  )
	{
		if( !file_exists( $path ) )
			throw new RuntimeException( 'Path "'.$path.'" is not existing.' );
		$this->showFiles		= $showFiles;
		$this->showFolders		= $showFolders;
		$this->stripDotEntries	= $stripDotEntries;
		parent::__construct(
			new DirectoryIterator( $path ),
			$pattern
		);
	}

	/**
	 *	Filter Callback.
	 *	@access		public
	 *	@return		bool
	 */
	public function accept(): bool
	{
		/** @var DirectoryIterator $innerIterator */
		$innerIterator	= $this->getInnerIterator();

		if( $innerIterator->isDot() )
			return FALSE;
		$isDir	= $innerIterator->isDir();
		if( !$this->showFiles && !$isDir )
			return FALSE;
		if( !$this->showFolders && $isDir )
			return FALSE;
		if( $this->stripDotEntries )
		{
			if( preg_match( "@^\.\w@", $innerIterator->getFilename() ) )
				return FALSE;
		}
		return parent::accept();
	}
}
