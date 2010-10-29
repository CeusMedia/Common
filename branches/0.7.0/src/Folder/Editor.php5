<?php
/**
 *	Editor for Folders.
 *	All Methods to create, copy, move or remove a Folder are working recursive.
 *	Files and Folders with a leading Dot are ignored if not set otherwise with Option 'skipDotEntries'.
 *	By default copy, move and remove are not overwriting existing Files or deleting Folders containing Files or Folders.
 *	It can be forced to overwrite or remove everything with Option 'force'.
 *
 *	Copyright (c) 2007-2010 Christian Würker (ceus-media.de)
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
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2007-2010 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			15.04.2008
 *	@version		$Id$
 */
/**
 *	Editor for Folders.
 *	All Methods to create, copy, move or remove a Folder are working recursive.
 *	Files and Folders with a leading Dot are ignored if not set otherwise with Option 'skipDotEntries'.
 *	By default copy, move and remove are not overwriting existing Files or deleting Folders containing Files or Folders.
 *	It can be forced to overwrite or remove everything with Option 'force'.
 *	@category		cmClasses
 *	@package		Folder
 *	@extends	 	Folder_Reader
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2007-2010 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			15.04.2008
 *	@version		$Id$
 */
class Folder_Editor extends Folder_Reader
{
	/**
	 *	Constructor, Creates Folder if not existing and Creation Mode is set.
	 *	@access		public
	 *	@param		string		$folderName		Folder Name, relative or absolute
	 *	@param		string		$creationMode	UNIX rights for chmod()
	 *	@param		string		$creationUser	User Name for chown()
	 *	@param		string		$creationGroup	Group Name for chgrp()
	 *	@return		void
	 */
	public function __construct( $folderName, $creationMode = NULL, $creationUser = NULL, $creationGroup = NULL )
	{
		parent::__construct( $folderName );
		if( !self::isFolder( $folderName ) && $creationMode !== NULL )
			self::createFolder( $folderName, $creationMode, $creationUser, $creationGroup );
	}

	/**
	 *	Sets group of current folder.
	 *	@access		public
	 *	@param		string		$groupName		Group to set
	 *	@param		bool		$recursive		Flag: change nested files and folders,too
	 *	@return		bool
	 */
	public function changeGroup( $groupName, $recursive = FALSE )
	{
		if( !$groupName )
			throw new InvalidArgumentException( 'Group is missing' );
		$number	= (int) chgrp( $this->folderName, $groupName );

		if( !$recursive )
			return $number;
		$iterator = new RecursiveIteratorIterator(
			new RecursiveDirectoryIterator( $this->folderName ),
			RecursiveIteratorIterator::SELF_FIRST
		);
		foreach( $iterator as $item )
			$number += (int) chgrp( $item, $groupName );
		return $number;
	}

	/**
	 *	Sets permissions on current folder and its containing files and folders.
	 *	@access		public
	 *	@param		int			$mode			Permission mode, like 0750, 01770, 02755
	 *	@param		bool		$recursive		Flag: change nested files and folders,too
	 *	@return		int			Number of affected files and folders
	 */
	public function changeMode( $mode, $recursive = FALSE )
	{
		if( !is_int( $mode ) )
			throw new InvalidArgumentException( 'Mode must be of integer' );

		$number	= (int) chmod( $this->folderName, $mode );
		if( !$recursive )
			return $number;

		$iterator = new RecursiveIteratorIterator(
			new RecursiveDirectoryIterator( $this->folderName ),
			RecursiveIteratorIterator::SELF_FIRST
		);
		foreach( $iterator as $item )
			$number += (int) chmod( $item, $mode );
		return $number;
 	}

	/**
	 *	Sets owner of current folder.
	 *	@access		public
	 *	@param		string		$userName		User to set
	 *	@param		bool		$recursive		Flag: change nested files and folders,too
	 *	@return		bool
	 */
	public function changeOwner( $userName, $recursive = FALSE )
	{
		if( !$userName )
			throw new InvalidArgumentException( 'User missing' );
		$number	= (int) chown( $this->folderName, $userName );

		if( !$recursive )
			return $number;
		$iterator = new RecursiveIteratorIterator(
			new RecursiveDirectoryIterator( $this->folderName ),
			RecursiveIteratorIterator::SELF_FIRST
		);
		foreach( $iterator as $item )
			$number += (int) chown( $item, $userName );
		return $number;
	}

	/**
	 *	Creates a Folder by creating all Folders in Path recursive.
	 *	@access		public
	 *	@static
	 *	@param		string		$folderName		Folder to create
	 *	@param		int			$mode			Permission Mode, default: 0755
	 *	@param		string		$userName		User Name
	 *	@param		string		$groupName		Group Name
	 *	@return		bool
	 */
	public static function createFolder( $folderName, $mode = 0770, $userName = NULL, $groupName = NULL )
	{
		if( self::isFolder( $folderName ) )
			return FALSE;
		if( false === @mkdir( $folderName, $mode, TRUE ) )									//  create Folder recursive
			throw new RuntimeException( 'Folder "'.$folderName.'" could not be created' );
		if( $userName )																		//  User is set
			chown( $folderName, $userName );												//  change Owner to User
		if( $groupName )																	//  Group is set
			chgrp( $folderName, $groupName );
		return TRUE;
	}

	/**
	 *	Copies a Folder recursive to another Path and returns Number of copied Files and Folders.
	 *	@access		public
	 *	@static
	 *	@param		string		$sourceFolder	Folder Name of Folder to copy
	 *	@param		string		$targetFolder	Folder Name to Target Folder
	 *	@param		bool		$force			Flag: force Copy if file is existing
	 *	@param		bool		$skipDotEntries	Flag: skip Files and Folders starting with Dot
	 *	@return		int
	 */
	public static function copyFolder( $sourceFolder, $targetFolder, $force = FALSE, $skipDotEntries = TRUE )
	{
		if( !self::isFolder( $sourceFolder ) )												//  Source Folder not existing
			throw new RuntimeException( 'Folder "'.$sourceFolder.'" cannot be copied because it is not existing' );

		$count			= 0;																//  initialize Object Counter 
		$sourceFolder	= self::correctPath( $sourceFolder );								//  add Slash to Source Folder
		$targetFolder	= self::correctPath( $targetFolder );								//  add Slash to Target Folder
		if( self::isFolder( $targetFolder ) && !$force )									//  Target Folder is existing, not forced
			throw new RuntimeException( 'Folder "'.$targetFolder.'" is already existing. See Option "force"' );
		else if( !self::isFolder( $targetFolder ) )											//  Target Folder is not existing
			$count	+= (int) self::createFolder( $targetFolder );							//  create TargetFolder and count

		$index	= new Folder_Iterator( $sourceFolder, TRUE, TRUE, $skipDotEntries );		//  Index of Source Folder
		foreach( $index as $entry )														
		{
			if( $entry->isDot() )															//  Dot Folders
				continue;																	//  skip them
			if( $entry->isDir() )															//  nested Folder
			{
				$source	= $entry->getPathname();											//  Source Folder Name
				$target	= $targetFolder.$entry->getFilename()."/";							//  Target Folder Name
				$count	+= self::copyFolder( $source, $target, $force, $skipDotEntries );	//  copy Folder recursive and count
			}
			else if( $entry->isFile() )														//  nested File
			{
				$targetFile	= $targetFolder.$entry->getFilename();
				if( file_exists( $targetFile ) && !$force )
					throw new RuntimeException( 'File "'.$targetFile.'" is already existing. See Option "force"' );
				$count	+= (int) copy( $entry->getPathname(), $targetFile );				//  copy File and count
			}
		}
		return $count;																		//  return Object Count
	}

	/**
	 *	Copies current Folder to another Folder and returns Number of copied Files and Folders.
	 *	@access		public
	 *	@param		string		$targetFolder	Folder Name of Target Folder
	 *	@param		bool		$useCopy		Flag: switch current Folder to Copy afterwards
	 *	@param		bool		$force			Flag: force Copy if file is existing
	 *	@param		bool		$skipDotEntries	Flag: skip Files and Folders starting with Dot
	 *	@return		int
	 */
	public function copy( $targetFolder, $force = FALSE, $skipDotEntries = TRUE, $useCopy = FALSE )
	{
		$result	= self::copyFolder( $this->folderName, $targetFolder, $force, $skipDotEntries );
		if( $result && $useCopy )
			$this->folderName	= $targetFolder;
		return $result;
	}

	/**
	 *	Moves a Folder to another Path.
	 *	@access		public
	 *	@static
	 *	@param		string		$sourceFolder	Folder Name of Source Folder, eg. /path/to/source/folder
	 *	@param		string		$targetPath		Folder Path of Target Folder, eg. /path/to/target
	 *	@param		string		$force			Flag: continue if Target Folder is already existing, otherwise break
	 *	@return		bool
	 */
	public static function moveFolder( $sourceFolder, $targetPath, $force = FALSE )
	{
		$sourceName	= basename( $sourceFolder );											//  Folder Name of Source Folder
		$sourcePath	= dirname( $sourceFolder );												//  Path to Source Folder
		$sourceFolder	= self::correctPath( $sourceFolder );								//  add Slash to Source Path
		$targetPath		= self::correctPath( $targetPath );									//  add Slash to Target Path
		if( !self::isFolder( $sourcePath ) )												//  Path of Source Folder not existing
			throw new RuntimeException( 'Folder "'.$sourceFolder.'" cannot be moved since it is not existing' );
		if( self::isFolder( $targetPath.$sourceName ) && !$force )							//  Path of Target Folder is already existing
			throw new RuntimeException( 'Folder "'.$targetPath.$sourceName.'" is already existing' );
		if( !self::isFolder( $targetPath ) )												//  Path to Target Folder not existing
			self::createFolder( $targetPath );												//  
		if( $sourceFolder == $targetPath )													//  Source and Target Path are equal
			return FALSE;																	//  do nothing and return
		if( FALSE === @rename( $sourceFolder, $targetPath.$sourceName ) )					//  move Source Folder to Target Path
			throw new RuntimeException( 'Folder "'.$sourceFolder.'" cannot be moved to "'.$targetPath.'"' );
		return TRUE;
	}
	
	/**
	 *	Moves current Folder to another Path.
	 *	@access		public
	 *	@param		string		$folderPath		Folder Path of Target Folder
	 *	@param		string		$force			Flag: continue if Target Folder is already existing, otherwise break
	 *	@return		bool
	 */
	public function move( $folderPath, $force = FALSE )
	{
		if( !$this->moveFolder( $this->folderName, $folderPath, $force ) )
			return FALSE;
		$this->folderName	= $folderPath;
		return TRUE;
	}

	/**
	 *	Renames current Folder.
	 *	@access		public
	 *	@param		string		$folderName		Folder Name to rename to
	 *	@return		bool
	 */
	public function rename( $folderName )
	{
		if( !$this->renameFolder( $this->folderName, $folderName ) )
			return FALSE;
		$this->folderName	= dirname( $this->folderName )."/".basename( $folderName );
		return TRUE;
	}
	
	/**
	 *	Renames a Folder to another Folder Name.
	 *	@access		public
	 *	@static
	 *	@param		string		$sourceFolder	Folder to rename
	 *	@param		string		$targetName		New Name of Folder
	 *	@return		bool
	 */
	public static function renameFolder( $sourceFolder, $targetName )
	{
		$targetName	= basename( $targetName );
		if( !self::isFolder( $sourceFolder ) )												//  Source Folder not existing
			throw new RuntimeException( 'Folder "'.$sourceFolder.'" is not existing' );

		$sourcePath	= self::correctPath( dirname( $sourceFolder ) );						//  Path to Source Folder
		if( basename( $sourceFolder ) == $targetName )										//  Source Name and Target name is equal
			return FALSE;
		if( self::isFolder( $sourcePath.$targetName ) )										//  Target Folder is already existing
			throw new RuntimeException( 'Folder "'.$sourcePath.$targetName.'" is already existing' );
		if( FALSE === @rename( $sourceFolder, $sourcePath.$targetName ) )					//  rename Source Folder to Target Folder
			throw new RuntimeException( 'Folder "'.$sourceFolder.'" cannot be renamed to "'.$sourcePath.$targetName.'"' );
		return TRUE;
	}

	/**
	 *	Removes current Folder recursive and returns Number of removed Folders and Files
	 *	@access		public
	 *	@param		bool		$force			Flag: force to remove Files within Folder
	 *	@return		int
	 */
	public function remove( $force = false )
	{
		return $this->removeFolder( $this->folderName, $force );
	}
	
	/**
	 *	Removes a Folder recursive and returns Number of removed Folders and Files.
	 *	Because there where Permission Issues with DirectoryIterator it uses the old 'dir' command.
	 *	@access		public
	 *	@static
	 *	@param		string		$folderName		Folder to be removed
	 *	@param		bool		$force			Flag: force to remove nested Files and Folders
	 *	@return		int
	 */
	public static function removeFolder( $folderName, $force = false )
	{
		$folderName	= self::correctPath( $folderName);
		$count	= 1;																		//  current Folder is first Object
		$dir	= dir( $folderName );														//  index Folder
		while( $entry = $dir->read() )														//  iterate Objects
		{
			if( preg_match( "@^(\.){1,2}$@", $entry ) )										//  if is Dot Object
				continue;																	//  continue
			$pathName	= $folderName.$entry;												//  Name of nested Object
			if( !$force )																	//  nested Files or Folders found
				throw new RuntimeException( 'Folder '.$folderName.' is not empty. See Option "force"' );
			if( is_file( $pathName ) )														//  is nested File
			{
				if( FALSE === @unlink( $pathName ) )										//  remove File and count
					throw new RuntimeException( 'File "'.$pathName.'" is not removable' );	//  throw Exception for File
				$count	++;
			}
			if( is_dir( $pathName ) )														//  is nested Folder
				$count	+= self::removeFolder( $pathName, $force );							//  call Method with nested Folder
		}
		$dir->close();																		//  close Directory Handler
		rmDir( $folderName );																//  remove Folder
		return $count;																		//  return counted Objects
	}
}
?>