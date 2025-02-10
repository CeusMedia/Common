<?php
/**
 *	Lists PHP Files within a Path an applies Filter on Folder and File Names.
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
 *	@package		CeusMedia_Common_FS_File_PHP
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2024 Christian Würker
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */

namespace CeusMedia\Common\FS\File\PHP;

use FilterIterator;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;

/**
 *	Lists PHP Files within a Path an applies Filter on Folder and File Names.
 *	@category		Library
 *	@package		CeusMedia_Common_FS_File_PHP
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2024 Christian Würker
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 *	@todo			Code Doc
 */
class Lister extends FilterIterator
{
	public array $extensions			= [];

	public array $ignoreFolders			= [];

	public array $ignoreFiles			= [];

	public array $skippedFiles			= [];

	public array $skippedFolders		= [];

	protected string $path;

	protected bool $verbose;

	public function __construct( string $path, array $extensions = [], array $ignoreFolders = [], array $ignoreFiles = [], bool $verbose = TRUE )
	{
		$path	= preg_replace( "@^(.*)/*$@U", "\\1/", $path );
		$this->path	= str_replace( "\\", "/", $path );
		$this->setExtensions( $extensions );
		$this->setIgnoredFolders( $ignoreFolders );
		$this->setIgnoredFiles( $ignoreFiles );
		$this->verbose	= $verbose;
		parent::__construct(
			new RecursiveIteratorIterator(
				new RecursiveDirectoryIterator( $path )
			)
		);
	}

	public function accept(): bool
	{
		$fileName	= basename( $this->current() );
		if( $this->extensions ){
			$info		= pathinfo( $fileName );
			if( empty( $info['extension'] ) )
				return FALSE;
			if( !in_array( $info['extension'], $this->extensions ) )
				return FALSE;
		}
		$pathName	= dirname( str_replace( "\\", "/", $this->current() ) );
		//  get inner Path Name
		$innerPath	= substr( $pathName, strlen( $this->path) )."/";
		$innerFile	= $innerPath.$fileName;
		//  iterate Folders to be ignored
		foreach( $this->ignoreFolders as $folder )
		{
			if( !trim( (string) $folder ) )
				continue;
			$found	= preg_match( (string) $folder, $innerPath );
#			remark( $file." @ ".$innerPath." : ".$found );
			//  ...
			if( $found )
			{
				//  log Folder
				$this->logSkippedFolder( $this->current() );
				if( $this->verbose )
					remark( "Skipping Folder: ".$innerPath );
				return FALSE;
			}
		}

		//  iterate Files to be ignored
		foreach( $this->ignoreFiles as $file )
		{
			if( !trim( (string) $file ) )
				continue;
			$found	= preg_match( (string) $file, $fileName );
			//  ...
			if( $found )
			{
				//  log File
				$this->logSkippedFile( $this->current() );
				if( $this->verbose )
					remark( "Skipping File: ".$innerPath.$this->current()->getFilename() );
				return FALSE;
			}
		}
		return TRUE;
	}

	public function getExtensions(): array
	{
		return $this->extensions;
	}

	public function getSkippedFiles(): array
	{
		return $this->skippedFiles;
	}

	protected function getSkippedFolders(): array
	{
		return $this->skippedFolders;
	}

	private function logSkippedFile( string $file ): void
	{
		$this->skippedFiles[]	= $file;
	}

	private function logSkippedFolder( string $path ): void
	{
		$this->skippedFolders[]	= $path;
	}

	public function setExtensions( array $extensions ): self
	{
		$this->extensions	= $extensions;
		return $this;
	}

	public function setIgnoredFiles( array $files = [] ): self
	{
		$this->ignoreFiles	= $files;
		return $this;
	}

	public function setIgnoredFolders( array $folders = [] ): self
	{
		$this->ignoreFolders	= $folders;
		return $this;
	}
}
