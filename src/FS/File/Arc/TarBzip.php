<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

/**
 *	Tar Bzip File allows creation and manipulation of bzipped tar archives.
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
 *	@package		CeusMedia_Common_FS_File_Arc
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2023 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */

namespace CeusMedia\Common\FS\File\Arc;

use Exception;
use RuntimeException;

/**
 *	Tar Bzip File allows creation and manipulation of bzipped tar archives.
 *	@category		Library
 *	@package		CeusMedia_Common_FS_File_Arc
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2023 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */
class TarBzip extends Tar
{
	/**
	 *	Opens an existing Tar Bzip File and loads contents.
	 *	@access		public
	 *	@param		string		$fileName 		Name of Tar Bzip Archive to open
	 *	@return		bool
	 *	@throws		Exception
	 *	@throws		RuntimeException
	 */
	public function open( string $fileName ): bool
	{
		// If the tar file doesn't exist...
		if( !file_exists( $fileName ) )
			throw new RuntimeException( 'TBZ file "'.$fileName.'" is not existing.' );
		$this->fileName = $fileName;
		return $this->readBzipTar( $fileName );
	}

	/**
	 *	Reads an existing Tar Bzip File.
	 *	@access		private
	 *	@param		string		$fileName 		Name of Tar Bzip Archive to read
	 *	@return		bool
	 *	@throws		Exception
	 */
	private function readBzipTar( string $fileName ): bool
	{
		$f = new Bzip( $fileName );
		$this->content = $f->readString();
		// Parse the TAR file
		return $this->parseTar();
	}

	/**
	 *	Write down the currently loaded Tar Bzip Archive.
	 *	@access		public
	 *	@param		string|NULL		$fileName 		Name of Tar Bzip Archive to save
	 *	@return		int				Number of written bytes
	 *	@throws		Exception
	 *	@throws		RuntimeException
	 */
	public function save( ?string $fileName = NULL ): int
	{
		if( !$fileName ){
			if( !$this->fileName )
				throw new RuntimeException( 'No TBZ file name for saving given.' );
			$fileName = $this->fileName;
		}
		// Encode processed files into TAR file format
		$this->generateTar();
		$f = new Bzip( $fileName );
		return $f->writeString( $this->content );
	}
}
