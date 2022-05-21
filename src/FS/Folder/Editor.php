<?php
/**
 *	Editor for Folders.
 *	All Methods to create, copy, move or remove a Folder are working recursive.
 *	Files and Folders with a leading Dot are ignored if not set otherwise with Option 'skipDotEntries'.
 *	By default copy, move and remove are not overwriting existing Files or deleting Folders containing Files or Folders.
 *	It can be forced to overwrite or remove everything with Option 'force'.
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
 *	@package		CeusMedia_Common_FS_Folder
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2020 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 *	@since			15.04.2008
 */
/**
 *	Editor for Folders.
 *	All Methods to create, copy, move or remove a Folder are working recursive.
 *	Files and Folders with a leading Dot are ignored if not set otherwise with Option 'skipDotEntries'.
 *	By default copy, move and remove are not overwriting existing Files or deleting Folders containing Files or Folders.
 *	It can be forced to overwrite or remove everything with Option 'force'.
 *	@category		Library
 *	@package		CeusMedia_Common_FS_Folder
 *	@extends	 	FS_Folder_Reader
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2020 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 *	@since			15.04.2008
 */
class FS_Folder_Editor extends FS_Folder_Reader
{
	/**
	 *	Constructor, Creates Folder if not existing and Creation Mode is set.
	 *	@access		public
	 *	@param		string		$folderName		Folder Name, relative or absolute
	 *	@param		string		$creationMode	UNIX rights for chmod()
	 *	@param		string|NULL	$creationUser	User Name for chown()
	 *	@param		string|NULL	$creationGroup	Group Name for chgrp()
	 *	@return		void
	 */
	public function __construct( string $folderName, $creationMode = NULL, ?string $creationUser = NULL, ?string $creationGroup = NULL )
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
	 *	@return		int			Number of affected files and folders
	 */
	public function changeGroup( string $groupName, bool $recursive = FALSE ): int
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
	public function changeMode( $mode, bool $recursive = FALSE ): int
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
	 *	@return		int			Number of affected files and folders
	 */
	public function changeOwner( string $userName, bool $recursive = FALSE ): int
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
	 *	Copies a Folder recursive to another Path and returns Number of copied Files and Folders.
	 *	@access		public
	 *	@static
	 *	@param		string		$sourceFolder	Folder Name of Folder to copy
	 *	@param		string		$targetFolder	Folder Name to Target Folder
	 *	@param		bool		$force			Flag: force Copy if file is existing
	 *	@param		bool		$skipDotEntries	Flag: skip Files and Folders starting with Dot
	 *	@return		int							Number of copied files and folders
	 */
	public static function copyFolder( string $sourceFolder, string $targetFolder, bool $force = FALSE, bool $skipDotEntries = TRUE ): int
	{
		//  Source Folder not existing
		if( !self::isFolder( $sourceFolder ) )
			throw new RuntimeException( 'Folder "'.$sourceFolder.'" cannot be copied because it is not existing' );

		//  initialize Object Counter
		$count			= 0;
		//  add Slash to Source Folder
		$sourceFolder	= self::correctPath( $sourceFolder );
		//  add Slash to Target Folder
		$targetFolder	= self::correctPath( $targetFolder );
		//  Target Folder is existing, not forced
		if( self::isFolder( $targetFolder ) && !$force )
			throw new RuntimeException( 'Folder "'.$targetFolder.'" is already existing. See Option "force"' );
		//  Target Folder is not existing
		else if( !self::isFolder( $targetFolder ) )
			//  create TargetFolder and count
			$count	+= (int) self::createFolder( $targetFolder );

		//  Index of Source Folder
		$index	= new FS_Folder_Iterator( $sourceFolder, TRUE, TRUE, $skipDotEntries );
		foreach( $index as $entry ){
			//  Dot Folders
			if( $entry->isDot() )
				//  skip them
				continue;
			//  nested Folder
			if( $entry->isDir() ){
				//  Source Folder Name
				$source	= $entry->getPathname();
				//  Target Folder Name
				$target	= $targetFolder.$entry->getFilename()."/";
				//  copy Folder recursive and count
				$count	+= self::copyFolder( $source, $target, $force, $skipDotEntries );
			}
			//  nested File
			else if( $entry->isFile() ){
				$targetFile	= $targetFolder.$entry->getFilename();
				if( file_exists( $targetFile ) && !$force )
					throw new RuntimeException( 'File "'.$targetFile.'" is already existing. See Option "force"' );
				//  copy File and count
				$count	+= (int) copy( $entry->getPathname(), $targetFile );
			}
		}
		//  return Object Count
		return $count;
	}

	/**
	 *	Creates a Folder by creating all Folders in Path recursive.
	 *	@access		public
	 *	@static
	 *	@param		string		$folderName		Folder to create
	 *	@param		int			$mode			Permission Mode, default: 0770
	 *	@param		string|NULL	$userName		User Name
	 *	@param		string|NULL	$groupName		Group Name
	 *	@return		bool
	 */
	public static function createFolder( string $folderName, $mode = 0770, ?string $userName = NULL, ?string $groupName = NULL ): bool
	{
		if( self::isFolder( $folderName ) )
			return FALSE;
		//  create Folder recursive
		if( false === @mkdir( $folderName, $mode, TRUE ) )
			throw new RuntimeException( 'Folder "'.$folderName.'" could not be created' );
		chmod( $folderName, $mode );
		//  User is set
		if( $userName )
			//  change Owner to User
			chown( $folderName, $userName );
		//  Group is set
		if( $groupName )
			chgrp( $folderName, $groupName );
		return TRUE;
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
	public function copy( string $targetFolder, bool $force = FALSE, bool $skipDotEntries = TRUE, bool $useCopy = FALSE ): int
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
	public static function moveFolder( string $sourceFolder, string $targetPath, bool $force = FALSE ): bool
	{
		//  Folder Name of Source Folder
		$sourceName	= basename( $sourceFolder );
		//  Path to Source Folder
		$sourcePath	= dirname( $sourceFolder );
		//  add Slash to Source Path
		$sourceFolder	= self::correctPath( $sourceFolder );
		//  add Slash to Target Path
		$targetPath		= self::correctPath( $targetPath );
		//  Path of Source Folder not existing
		if( !self::isFolder( $sourcePath ) )
			throw new RuntimeException( 'Folder "'.$sourceFolder.'" cannot be moved since it is not existing' );
		//  Path of Target Folder is already existing
		if( self::isFolder( $targetPath.$sourceName ) && !$force )
			throw new RuntimeException( 'Folder "'.$targetPath.$sourceName.'" is already existing' );
		//  Path to Target Folder not existing
		if( !self::isFolder( $targetPath ) )
			self::createFolder( $targetPath );												//
		//  Source and Target Path are equal
		if( $sourceFolder == $targetPath )
			//  do nothing and return
			return FALSE;
		//  move Source Folder to Target Path
		if( FALSE === @rename( $sourceFolder, $targetPath.$sourceName ) )
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
	public function move( string $folderPath, bool $force = FALSE ): bool
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
	public function rename( string $folderName ): bool
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
	public static function renameFolder( string $sourceFolder, string $targetName ): bool
	{
		$targetName	= basename( $targetName );
		//  Source Folder not existing
		if( !self::isFolder( $sourceFolder ) )
			throw new RuntimeException( 'Folder "'.$sourceFolder.'" is not existing' );

		//  Path to Source Folder
		$sourcePath	= self::correctPath( dirname( $sourceFolder ) );
		//  Source Name and Target name is equal
		if( basename( $sourceFolder ) == $targetName )
			return FALSE;
		//  Target Folder is already existing
		if( self::isFolder( $sourcePath.$targetName ) )
			throw new RuntimeException( 'Folder "'.$sourcePath.$targetName.'" is already existing' );
		//  rename Source Folder to Target Folder
		if( FALSE === @rename( $sourceFolder, $sourcePath.$targetName ) )
			throw new RuntimeException( 'Folder "'.$sourceFolder.'" cannot be renamed to "'.$sourcePath.$targetName.'"' );
		return TRUE;
	}

	/**
	 *	Removes current Folder recursive and returns Number of removed Folders and Files
	 *	@access		public
	 *	@param		bool		$force			Flag: force to remove Files within Folder
	 *	@return		int							Number of removed files and folders
	 */
	public function remove( bool $force = false ): int
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
	 *	@param		bool		$strict			Flag: throw exception on failure
	 *	@return		int							Number of removed files and folders
	 */
	public static function removeFolder( string $folderName, bool $force = FALSE, bool $strict = TRUE ): int
	{
		$folderName	= self::correctPath( $folderName);
		//  current Folder is first Object
		$count	= 1;
		if( !file_exists( $folderName ) ){
			if( $strict )
				throw new RuntimeException( 'Folder "'.$folderName.'" is not existing' );
			return 0;
		}
		$index	= new DirectoryIterator( $folderName );							//  nested files or folders
		foreach( $index as $entry ){
			if( $entry->isDot() )
				continue;
			if( !$force )
				throw new RuntimeException( 'Folder '.$folderName.' is not empty. See Option "force"' );
			if( $entry->isFile() || $entry->isLink() ){
				if( FALSE === @unlink( $entry->getPathname() ) )				//  remove file and count
					throw new RuntimeException( 'File "'.$folderName.$entry->getFilename().'" is not removable' );
				$count++;
			}
			else if( $entry->isDir() )
				$count	+= self::removeFolder( $entry->getPathname(), $force );	//  call Method with nested Folder
		}
		rmdir( $folderName );													//  remove Folder
		return $count;
	}
}
