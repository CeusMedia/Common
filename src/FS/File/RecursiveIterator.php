<?php
/**
 *	Iterates all Folders and Files recursive within a Folder.
 *
 *	Copyright (c) 2007-2020 Christian Würker (ceusmedia.de)
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
 *	@extends		FilterIterator
 *	@uses			RecursiveIteratorIterator
 *	@uses			RecursiveDirectoryIterator
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2020 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 *	@since			15.04.2008
 */
/**
 *	Iterates all Folders and Files recursive within a Folder.
 *	@category		Library
 *	@package		CeusMedia_Common_FS_File
 *	@extends		FilterIterator
 *	@uses			RecursiveIteratorIterator
 *	@uses			RecursiveDirectoryIterator
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2020 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 *	@since			15.04.2008
 */
class FS_File_RecursiveIterator extends RecursiveIteratorIterator
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
	 *	@return		void
	 */
	public function __construct( $path )
	{
		if( !file_exists( $path ) )
			throw new RuntimeException( 'Path "'.$path.'" is not existing.' );
		$this->path				= $path;
		$selfIterator			= RecursiveIteratorIterator::LEAVES_ONLY;
		parent::__construct(
			new RecursiveDirectoryIterator(
				$path,
				0
			),
			$selfIterator
		);
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
