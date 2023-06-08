<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

/**
 *	Tar Gzip File allows creation and manipulation of gzipped tar archives.
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

/**
 *	Tar Gzip File allows creation and manipulation of gzipped tar archives.
 *	@category		Library
 *	@package		CeusMedia_Common_FS_File_Arc
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2023 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */
class TarGzip extends Tar
{
	/**
	 *	Opens an existing Tar Gzip File and loads contents.
	 *	@access		public
	 *	@param		string		$fileName 		Name of Tar Gzip Archive to open
	 *	@return		bool
	 *	@throws		Exception
	 */
	public function open( string $fileName ): bool
	{
		// If the tar file doesn't exist...
		if( !file_exists( $fileName ) )
			throw new Exception( "TGZ file '".$fileName."' is not existing." );
		$this->fileName = $fileName;
		return $this->readGzipTar( $fileName );
	}

	/**
	 *	Reads an existing Tar Gzip File.
	 *	@access		private
	 *	@param		string		$fileName 		Name of Tar Gzip Archive to read
	 *	@return		bool
	 *	@throws		Exception
	 */
	private function readGzipTar( string $fileName ): bool
	{
		$f = new Gzip( $fileName );
		$this->content = $f->readString();
		// Parse the TAR file
		$this->parseTar();
		return TRUE;
	}

	/**
	 *	Write down the currently loaded Tar Gzip Archive.
	 *	@access		public
	 *	@param		string|NULL		$fileName 		Name of Tar Gzip Archive to save
	 *	@return		int				Number of written bytes
	 *	@throws		Exception
	 */
	public function save( ?string $fileName = NULL ): int
	{
		if( !$fileName ){
			if( !$this->fileName )
				throw new Exception( "No TGZ file name for saving given." );
			$fileName = $this->fileName;
		}
		// Encode processed files into TAR file format
		$this->generateTar();
		$f = new Gzip( $fileName );
		return $f->writeString( $this->content);
	}
}
