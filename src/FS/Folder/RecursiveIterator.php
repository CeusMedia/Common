<?php
/**
 *	Iterates all Folders and Files recursive within a Folder.
 *
 *	Copyright (c) 2008-2012 Christian Würker (ceusmedia.com)
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
 *	@package		Folder
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2008-2012 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			15.04.2008
 *	@version		$Id$
 */
/**
 *	Iterates all Folders and Files recursive within a Folder.
 *	@category		cmClasses
 *	@package		Folder
 *	@extends		FilterIterator
 *	@uses			RecursiveIteratorIterator
 *	@uses			RecursiveDirectoryIterator
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2008-2012 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			15.04.2008
 *	@version		$Id$
 */
class Folder_RecursiveIterator extends FilterIterator
{
	/**	@var		 string		$path				Path to iterate */
	protected $path;
	/**	@var		 bool		$showFiles			Flag: show Files */
	protected $showFiles;
	/**	@var		 bool		$showFolders		Flag: show Folders */
	protected $showFolders;
	/**	@var		 bool		$stripDotFolders	Flag: strip Folder with leading Dot */
	protected $stripDotFolders;

	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		string		$path				Path to Folder
	 *	@param		bool		$showFiles			Flag: show Files
	 *	@param		bool		$showFolders		Flag: show Folders
	 *	@param		bool		$stripDotEntries	Flag: strip Files and Folder with leading Dot
	 *	@return		void
	 */
	public function __construct( $path, $showFiles = TRUE, $showFolders = TRUE, $stripDotEntries = TRUE )
	{
		if( !file_exists( $path ) )
			throw new RuntimeException( 'Path "'.$path.'" is not existing.' );
		$this->path				= str_replace( "\\", "/", $path );
		$this->realPath			= str_replace( "\\", "/", realpath( $path ) );
		$this->realPathLength	= strlen( $this->realPath );
		$this->showFiles		= $showFiles;
		$this->showFiles		= $showFiles;
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
	public function accept()
	{
		if( $this->getInnerIterator()->isDot() )
			return FALSE;

		$isDir	= $this->getInnerIterator()->isDir();
		if( !$this->showFiles && !$isDir )
			return FALSE;

		if( $this->stripDotEntries )															//  skip all folders and files starting with a dot
		{
			if( substr( $this->getFilename(), 0, 1 ) == "." )									//  found file or folder is hidden
				return FALSE;
			
			if( substr( $this->getSubPathname(), 0, 1 ) == "." )								//  inner path is hidden
				return FALSE;

			$subPath	= str_replace( "\\", "/", $this->getSubPathname() );					//  be nice to Windows
			if( preg_match( '/\/\.\w/', $subPath ) )											//  atleast 1 folder in inner path is hidden
				return FALSE;
		}
		return TRUE;
	}

	/**
	 *	Returns Path to Folder to iterate.
	 *	@access		public
	 *	@return		string		Path to Folder to iterate
	 */
	public function getPath()
	{
		return $this->path;
	}
}
?>