<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

/**
 *	Iterates all Files within a Folder.
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
 *	@package		CeusMedia_Common_FS_File
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2024 Christian Würker
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */

namespace CeusMedia\Common\FS\File;

use DirectoryIterator;
use FilterIterator;
use RuntimeException;

/**
 *	Iterates all Files within a Folder.
 *	@category		Library
 *	@package		CeusMedia_Common_FS_File
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2024 Christian Würker
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */
class Iterator extends FilterIterator
{
	/**	@var		 bool		$stripDotFiles		Flag: strip Files with leading Dot */
	protected $stripDotFiles;

	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		string		$path				Path to Folder
	 *	@param		bool		$stripDotFiles		Flag: strip Files with leading Dot
	 *	@return		void
	 */
	public function __construct( string $path, bool $stripDotFiles = TRUE )
	{
		if( !file_exists( $path ) )
			throw new RuntimeException( 'Path "'.$path.'" is not existing.' );
		$this->stripDotFiles	= $stripDotFiles;
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
		if( $this->getInnerIterator()->isDir() )
			return FALSE;

		if( $this->stripDotFiles ){
			$fileName	= $this->getInnerIterator()->getFilename();
			if( $fileName[0] == "." )
				return FALSE;
		}
		return TRUE;
	}
}
