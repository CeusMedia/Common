<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

/**
 *	Searchs for a File by given File Name in Folder recursive.
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
 *	@package		CeusMedia_Common_FS_File
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2022 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */

namespace CeusMedia\Common\FS\File;

use FilterIterator;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use RuntimeException;

/**
 *	Searchs for a File by given File Name in Folder recursive.
 *	@category		Library
 *	@package		CeusMedia_Common_FS_File
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2022 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 *	@todo			Fix Error while comparing File Name to Current File with Path
 */
class RecursiveNameFilter extends FilterIterator
{
	/**	@var	string		$fileName		Name of File to be found */
	private string $fileName;

	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		string		$path		Path to search in
	 *	@param		string		$fileName	Name of File to be found
	 *	@return		void
	 */
	public function __construct( string $path, string $fileName )
	{
		if( !file_exists( $path ) )
			throw new RuntimeException( 'Path "'.$path.'" is not existing.' );
		$this->fileName = $fileName;
		parent::__construct(
			new RecursiveIteratorIterator(
				new RecursiveDirectoryIterator( $path ),
				RecursiveIteratorIterator::LEAVES_ONLY
			)
		);
	}

	/**
	 *	Filter Callback.
	 *	@access		public
	 *	@return		bool
	 */
	public function accept(): bool
	{
		return !strcmp( basename( $this->current() ), $this->fileName );
	}
}
