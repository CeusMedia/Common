<?php
/**
 *	Base ZIP File implementation.
 *
 *	Copyright (c) 2015-2018 Christian Würker (ceusmedia.de)
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
 *	@copyright		2015-2018 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 *	@version		$Id$
 */
/**
 *	Base ZIP File implementation.
 *
 *	@category		Library
 *	@package		CeusMedia_Common_FS_File_Arc
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2015-2018 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 *	@version		$Id$
 *	@todo			ATTENTION!!! This is a hydrid of existing gzip class and ZIP injection.
 *	@todo			kriss: TEST!!!
 *	@todo			code doc
 */
class File_Arc_Zip{

	static public $errors	= array(
		0	=> 'No error',
		1	=> 'Multi-disk zip archives not supported',
		2	=> 'Renaming temporary file failed',
		3	=> 'Closing zip archive failed',
		4	=> 'Seek error',
		5	=> 'Read error',
		6	=> 'Write error',
		7	=> 'CRC error',
		8	=> 'Containing zip archive was closed',
		9	=> 'No such file',
		10	=> 'File already exists',
		11	=> 'Can\'t open file',
		12	=> 'Failure to create temporary file',
		13	=> 'Zlib error',
		14	=> 'Malloc failure',
		15	=> 'Entry has been changed',
		16	=> 'Compression method not supported',
		17	=> 'Premature EOF',
		18	=> 'Invalid argument',
		19	=> 'Not a zip archive',
		20	=> 'Internal error',
		21	=> 'Zip archive inconsistent',
		22	=> 'Can\'t remove file',
		23	=> 'Entry has been deleted',
	);

	protected $fileName	= NULL;

	public function __construct( $fileName ){
		$this->checkSupport();
		$this->archive	= new ZipArchive();
		$this->setFileName( $fileName );
	}

	public function addFile( $fileName, $localFileName = NULL ){
		$this->checkFileOpened();
		return $this->archive->addFile( $fileName, $localFileName );
	}

	protected function checkFileOpened(){
		if( !$this->fileName )
			throw new RuntimeException( 'No archive file opened' );
	}

	protected function checkSupport( $throwException = TRUE ){
		$hasZipSupport	= self::hasSupport();
		if( $throwException && !$hasZipSupport )
			throw new RuntimeException( 'PHP extension for ZIP support is not loaded' );
		return $hasZipSupport;
	}

	public function getFileName(){
		return $this->fileName;
	}

	static public function hasSupport(){
		return class_exists( 'ZipArchive' );
	}

	public function save( $fileName = NULL ){
		$instance	= $this;
		if( !is_null( $fileName ) ){
			$instance	= clone $this;
			$instance->setFileName( $fileName );
		}
		$instance->archive->close();
	}

	public function setFileName( $fileName ){
		$this->fileName	= $fileName;
	}

	public function index(){
		if( $this->checkFileOpened( FALSE ) )
			
		return $this->index();
	}
}



class File_Arc_ZipTarTemplate {

	// Unprocessed Archive Information
	protected $fileName;
	protected $content;

	// Processed Archive Information
	protected $files		= array();
	protected $folders		= array();
	protected $numFiles		= 0;
	protected $numFolders	= 0;

	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		string		$fileName			Name of TAR File
	 *	@return		void
	 */
	public function __construct( $fileName = NULL )
	{
		if( $fileName )
			$this->open( $fileName );
	}

	static public function getErrorMessage( $errorCode ){
		if( !array_key_exists( (int) $errorCode, self::$errors ) )
			throw new InvalidArgumentException( 'Invalid error code' );
		return self::$errors[(int) $errorCode];
	}

	/**
	 *	Adds a File to the TAR Archive by its Path, depending on current working Directory.
	 *	@access		public
	 *	@param		stromg		$fileName			Path of File to add
	 *	@return		bool
	 */
	public function addFile( $fileName )
	{
		if( !file_exists( $fileName ) )													// Make sure the file we are adding exists!
			throw new Exception( 'File "'.$fileName.'" is not existing' );
		if( $this->containsFile( $fileName ) )											// Make sure there are no other files in the archive that have this same fileName
			throw new Exception( 'File "'.$fileName.'" already existing in TAR' );

		$fileName	= str_replace( "\\", "/", $fileName );
		$fileName	= str_replace( "./", "", $fileName );
		$fileInfo	= stat( $fileName );												// Get file information
		$file		= new File_Reader( $fileName );

		$this->numFiles++;																// Add file to processed data
		$activeFile					= &$this->files[];
		$activeFile['name']			= $fileName;
		$activeFile['mode']			= $fileInfo['mode'];
		$activeFile['user_id']		= $fileInfo['uid'];
		$activeFile['group_id']		= $fileInfo['gid'];
		$activeFile['size']			= $fileInfo['size'];
		$activeFile['time']			= $fileInfo['mtime'];
#		$activeFile['checksum']		= $checksum;
		$activeFile['user_name']	= '';
		$activeFile['group_name']	= '';
		$activeFile['file']			= $file->readString();								// Read in the file's contents
		return TRUE;
	}

	public function addFolder( $path ){
		if( !file_exists( $dirName ) )
			return FALSE;
		$fileInfo = stat( $dirName );													// Get folder information
		$this->numFolders++;															// Add folder to processed data
		$activeDir				= &$this->folders[];
		$activeDir['name']		= $dirName;
		$activeDir['mode']		= $fileInfo['mode'];
		$activeDir['time']		= $fileInfo['mtime'];
		$activeDir['user_id']	= $fileInfo['uid'];
		$activeDir['group_id']	= $fileInfo['gid'];
#		$activeDir['checksum']	= $checksum;
		return TRUE;
	}

	/**
	 *	Checks whether this Archive contains a specific File.
	 *	@access		public
	 *	@param		string		$fileName			Name of File to check
	 *	@return		bool
	 */
	public function containsFile( $fileName )
	{
		if( !$this->numFiles )
			return FALSE;
		foreach( $this->files as $key => $information )
			if( $information['name'] == $fileName )
				return TRUE;
	}

	/**
	 *	Checks whether this Archive contains a specific Folder.
	 *	@access		public
	 *	@param		string		$dirName			Name of Folder to check
	 *	@return		bool
	 */
	public function containsFolder( $dirName )
	{
		if( !$this->numFolders )
			return FALSE;
		foreach( $this->folders as $key => $information )
			if( $information['name'] == $dirName )
				return TRUE;
	}

	/**
	 *	Extracts all Folders and Files to a Path and returns Number of extracted Files.
	 *	@access		public
	 *	@param		string		$targetPath			Path to extract to
	 *	@return		int			Number of extracted Files
	 */
	public function extract( $targetPath = NULL )
	{
		$counter	= 0;
		if( $targetPath )
		{
			$cwd	= getCwd();
			Folder_Editor::createFolder( $targetPath );
			chdir( $targetPath );
		}
		foreach( $this->folders as $folder )
			Folder_Editor::createFolder( $folder['name'] );
		foreach( $this->files as $file )
		{
			if( $folder = dirname( $file['name'] ) )
				Folder_Editor::createFolder( $folder );
			$counter	+= (int)(bool) File_Writer::save( $file['name'], $file['file'] );
		}
		if( $targetPath )
			chDir( $cwd );
		return $counter;
	}

	protected function generateZip(){
		$archive	= new ZipArchive();
		$fileName	= tempnam( sys_get_temp_dir(), 'download_' );
		if( !( $archive->open( $fileName, ZipArchive::CREATE ) ) )
			throw new RuntimeException( 'Cannot open '.$fileName );

		foreach( $this->files as $key => $information ){
			$archive->addFromString( $information['name'], $information['file'] );
		}
		$archive->close();
		$this->content	= File_Reader::load( $fileName );
		unlink( $fileName );
	}

	/**
	 *	Retrieves information about a File in the current TAR Archive.
	 *	@access		public
	 *	@param		string		$fileName			File Name to get Information for
	 *	@return		array
	 */
	public function getFile( $fileName )
	{
		if( !$this->numFiles )
			return NULL;
		foreach( $this->files as $key => $information )
			if( $information['name'] == $fileName )
				return $information;
	}

	/**
	 *	Returns a List of Files within Archive.
	 *	@access		public
	 *	@return		array
	 */
	public function getFileList()
	{
		$list	= array();
		foreach( $this->files as $file )
			$list[$file['name']]	= $file['size'];
		return $list;
	}

	/**
	 *	Retrieves information about a Folder in the current TAR Archive.
	 *	@access		public
	 *	@param		string		$dirName			Folder Name to get Information for
	 *	@return		array
	 */
	public function getFolder( $dirName )
	{
		if( !$this->numFolders )
			return NULL;
		foreach( $this->folders as $key => $information )
			if( $information['name'] == $dirName )
				return $information;
	}

	/**
	 *	Returns a List of Folders within Archive.
	 *	@access		public
	 *	@return		array
	 */
	public function getFolderList()
	{
		$list	= array();
		foreach( $this->folders as $folder )
			$list[]	= $folder['name'];
		return $list;
	}

	/**
	 *	Removes a File from the Archive.
	 *	@access		public
	 *	@param		string		$fileName		Name of File to remove
	 *	@return		bool
	 */
	public function removeFile( $fileName )
	{
		if( !$this->numFiles )
			return FALSE;
		foreach( $this->files as $key => $information )
		{
			if( $information['name'] !== $fileName )
				continue;
			$this->numFiles--;
			unset( $this->files[$key] );
			return TRUE;
		}
	}

	/**
	 *	Removes a Folder from the Archive.
	 *	@access		public
	 *	@param		string		$dirName		Name of Folder to remove
	 *	@return		bool
	 */
	public function removeFolder( $dirName )
	{
		if( !$this->numFolders )
			return FALSE;
		foreach( $this->folders as $key => $information )
		{
			if( $information['name'] !== $dirName )
				continue;
			$this->numFolders--;
			unset( $this->folders[$key] );
			return TRUE;
		}
	}

	/**
	 *	Write down the currently loaded Tar Archive.
	 *	@access		public
	 *	@param		string	$fileName 	Name of Tar Archive to save
	 *	@return		int					Number of written bytes
	 */
	public function save( $fileName = NULL )
	{
		if( empty( $fileName ) )
		{
			if( empty( $this->fileName ) )
				throw new Exception( 'No TAR file name for saving given' );
			$fileName = $this->fileName;
		}
		$this->generateZip();																		// Encode processed files into TAR file format
		return File_Writer::save( $fileName, $this->content );										//  write archive file
	}
}
