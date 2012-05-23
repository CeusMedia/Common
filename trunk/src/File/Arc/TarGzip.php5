<?php
/**
 *	Tar Gzip File allows creation and manipulation of gzipped tar archives.
 *
 *	Copyright (c) 2007-2012 Christian Würker (ceusmedia.com)
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
 *	@category		cmClasses
 *	@package		File.Arc
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2012 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@version		$Id$
 */
/**
 *	Tar Gzip File allows creation and manipulation of gzipped tar archives.
 *	@category		cmClasses
 *	@package		File.Arc
 *	@extends		File_Arc_Tar
 *	@uses			File_Arc_Gzip
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2012 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@version		$Id$
 */
class File_Arc_TarGzip extends File_Arc_Tar
{
	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		string		$fileName 		Name of Tar Gzip Archive to open
	 *	@return		void
	 */
	public function __construct( $fileName = false )
	{
		if( $fileName )
			$this->open( $fileName );
	}

	/**
	 *	Opens an existing Tar Gzip File and loads contents.
	 *	@access		public
	 *	@param		string		$fileName 		Name of Tar Gzip Archive to open
	 *	@return		bool
	 */
	public function open( $fileName )
	{
		if( !file_exists( $fileName ) )																		// If the tar file doesn't exist...
			throw new Exception( "TGZ file '".$fileName."' is not existing." );
		$this->fileName = $fileName;
		$this->readGzipTar( $fileName );
	}

	/**
	 *	Reads an existing Tar Gzip File.
	 *	@access		private
	 *	@param		string		$fileName 		Name of Tar Gzip Archive to read
	 *	@return		bool
	 */
	private function readGzipTar( $fileName )
	{
		$f = new File_Arc_Gzip( $fileName );
		$this->content = $f->readString();
		$this->parseTar();																			// Parse the TAR file
		return true;
	}

	/**
	 *	Write down the currently loaded Tar Gzip Archive.
	 *	@access		public
	 *	@param		string		$fileName 		Name of Tar Gzip Archive to save
	 *	@return		bool
	 */
	public function save( $fileName = false )
	{
		if( !$fileName )
		{
			if( !$this->fileName )
				throw new Exception( "No TGZ file name for saving given." );
			$fileName = $this->fileName;
		}
		$this->generateTar();												// Encode processed files into TAR file format
		$f = new File_Arc_Gzip( $fileName );
		$f->writeString( $this->content);
		return true;
	}
}
?>