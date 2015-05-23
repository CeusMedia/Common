<?php
/**
 *	Lists PHP Files within a Path an applies Filter on Folder and File Names.
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
 *	@package		File.PHP
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2012 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			04.08.08
 *	@version		$Id$
 */
/**
 *	Lists PHP Files within a Path an applies Filter on Folder and File Names.
 *	@category		cmClasses
 *	@package		File.PHP
 *	@extends		FilterIterator
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2012 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			04.08.08
 *	@version		$Id$
 *	@todo			Code Doc
 */
class File_PHP_Lister extends FilterIterator
{
	public $extensions			= array();
	public $ignoreFolders		= array();
	public $ignoreFiles			= array();
	public $skippedFiles		= array();
	public $skippedFolders		= array();

	public function __construct( $path, $extensions = array(), $ignoreFolders = array(), $ignoreFiles = array(), $verbose = TRUE )
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

	public function accept()
	{
		$fileName	= basename( $this->current() );
		if( $this->extensions )
		{
			$info		= pathinfo( $fileName );
			if( empty( $info['extension'] ) )
				return FALSE;
			if( !in_array( $info['extension'], $this->extensions ) )
				return FALSE;
		}
		$pathName	= dirname( str_replace( "\\", "/", $this->current() ) );
		$innerPath	= substr( $pathName, strlen( $this->path) )."/";					//  get inner Path Name
		$innerFile	= $innerPath.$fileName;
		foreach( $this->ignoreFolders as $folder )										//  iterate Folders to be ignored
		{
			if( !trim( (string) $folder ) )
				continue;
			$found	= preg_match( (string) $folder, $innerPath );
#			remark( $file." @ ".$innerPath." : ".$found );
			if( $found )																//  ...
			{
				$this->logSkippedFolder( $this->current() );							//  log Folder
				if( $this->verbose )
					remark( "Skipping Folder: ".$innerPath );
				return FALSE;
			}
		}

		foreach( $this->ignoreFiles as $file )											//  iterate Files to be ignored
		{
			if( !trim( (string) $file ) )
				continue;
			$found	= preg_match( (string) $file, $fileName );
			if( $found )																//  ...
			{
				$this->logSkippedFile( $this->current() );								//  log File
				if( $this->verbose )
					remark( "Skipping File: ".$innerPath.$this->current()->getFilename() );
				return FALSE;
			}
		}
		return TRUE;
	}

	public function getExtensions()
	{
		return $this->extensions;	
	}

	public function getSkippedFiles()
	{
		return $this->skippedFiles;
	}

	protected function getSkippedFolders()
	{
		return $this->skippedFolders;
	}

	private function logSkippedFile( $file )
	{
		$this->skippedFiles[]	= $file;
	}

	private function logSkippedFolder( $path )
	{
		$this->skippedFolders[]	= $path;
	}

	public function setExtensions( $extensions )
	{
		$this->extensions	= $extensions;
	}
	
	public function setIgnoredFiles( $files = array() )
	{
		$this->ignoreFiles	= $files;
	}
	
	public function setIgnoredFolders( $folders = array() )
	{
		$this->ignoreFolders	= $folders;
	}
}
?>