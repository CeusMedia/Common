<?php
/**
 *	Lists PHP Files within a Path an applies Filter on Folder and File Names.
 *
 *	Copyright (c) 2007-2009 Christian Würker (ceus-media.de)
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
 *	@package		file.php
 *	@extends		FilterIterator
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2007-2009 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			04.08.08
 *	@version		0.3
 */
/**
 *	Lists PHP Files within a Path an applies Filter on Folder and File Names.
 *	@package		file.php
 *	@extends		FilterIterator
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2007-2009 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			04.08.08
 *	@version		0.3
 *	@todo			Code Doc
 */
class File_PHP_Lister extends FilterIterator
{
	public $ignoreFolders		= array();
	public $ignoreFiles			= array();
	public $skippedFiles		= array();
	public $skippedFolders		= array();

	public function __construct( $path, $folders = array(), $files = array(), $verbose = TRUE )
	{
		$path	= preg_replace( "@^(.*)/*$@U", "\\1/", $path );
		$this->path	= str_replace( "\\", "/", $path );
		$this->setIgnoredFiles( $files );
		$this->setIgnoredFolders( $folders );
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
		$pathName	= dirname( str_replace( "\\", "/", $this->current() ) );
		$innerPath	= substr( $pathName, strlen( $this->path) )."/";					//  get inner Path Name
		$innerFile	= $innerPath.$fileName;
		foreach( $this->ignoreFolders as $folder )										//  iterate Folders to be ignored
		{
			if( !trim( $folder ) )
				continue;
			$folder	= str_replace( ".", "\.", $folder );								//  replace Wildcard by RegEx Wildcard
			$folder	= preg_replace( "@/?\*/?@", ".*", $folder );						//  replace Wildcard by RegEx Wildcard
			$found	= preg_match( "@^".$folder."@", $innerPath );
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
			if( !trim( $file ) )
				continue;
			$file	= str_replace( ".", "\.", $file );									//  replace Wildcard by RegEx Wildcard
			$file	= preg_replace( "@\*@", ".*", $file );								//  replace Wildcard by RegEx Wildcard
			$found	= preg_match( "@^".$file."$@", $innerFile );
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

	protected function getSkippedFiles()
	{
		return $this->skippedFiles;
	}

	protected function getSkippedFolders()
	{
		return $this->skippedFolders;
	}

	private function logSkippedFolder( $path )
	{
		$this->skippedFolders[]	= $path;
	}

	private function logSkippedFile( $file )
	{
		$this->skippedFiles[]	= $file;
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