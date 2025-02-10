<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

/**
 *	Tar File allows creation and manipulation of tar archives.
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
 *	@package		CeusMedia_Common_FS_File_Arc
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2024 Christian Würker
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */

namespace CeusMedia\Common\FS\File\Arc;

use CeusMedia\Common\FS\File\Reader as FileReader;
use CeusMedia\Common\FS\File\Writer as FileWriter;
use CeusMedia\Common\FS\Folder\Editor as FolderEditor;
use Exception;

/**
 *	Tar File allows creation and manipulation of tar archives.
 *	@category		Library
 *	@package		CeusMedia_Common_FS_File_Arc
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2024 Christian Würker
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */
class Tar
{
	// Unprocessed Archive Information
	protected ?string $fileName		= NULL;
	protected string $content		= '';

	// Processed Archive Information
	protected array $files			= [];
	protected array $folders		= [];
	protected int $numFiles			= 0;
	protected int $numFolders		= 0;

	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		string|NULL		$fileName			Name of TAR File
	 *	@return		void
	 *	@throws		Exception
	 */
	public function __construct( ?string $fileName = NULL )
	{
		if( $fileName )
			$this->open( $fileName );
	}

	/**
	 *	Adds a File to the TAR Archive by its Path, depending on current working Directory.
	 *	@access		public
	 *	@param		string		$fileName			Path of File to add
	 *	@return		bool
	 *	@throws		Exception
	 */
	public function addFile( string $fileName ): bool
	{
		// Make sure the file we are adding exists!
		if( !file_exists( $fileName ) )
			throw new Exception( 'File "'.$fileName.'" is not existing' );
		// Make sure there are no other files in the archive that have this same fileName
		if( $this->containsFile( $fileName ) )
			throw new Exception( 'File "'.$fileName.'" already existing in TAR' );

		$fileName	= str_replace( "\\", "/", $fileName );
		$fileName	= str_replace( "./", "", $fileName );
		// Get file information
		$fileInfo	= stat( $fileName );
		$file		= new FileReader( $fileName );

		// Add file to processed data
		$this->numFiles++;
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
		// Read in the file's contents
		$activeFile['file']			= $file->readString();
		return TRUE;
	}

	/**
	 *	Adds a Folder to this TAR Archive.
	 *	@access		public
	 *	@param		string		$dirName			Path of Folder to add
	 *	@return		bool
	 */
	public function addFolder( string $dirName ): bool
	{
		if( !file_exists( $dirName ) )
			return FALSE;
		// Get folder information
		$fileInfo = stat( $dirName );
		// Add folder to processed data
		$this->numFolders++;
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
	 *	Appends a TAR File to the end of the currently opened TAR File.
	 *	@access		public
	 *	@param		string		$fileName			TAR File to add to current TAR File
	 *	@return		bool
	 *	@throws		Exception
	 */
	public function appendTar( string $fileName ): bool
	{
		// If the tar file doesn't exist...
		if( !file_exists( $fileName ) )
			throw new Exception( 'TAR File "'.$fileName.'" is not existing' );
		$this->readTar( $fileName );
		return TRUE;
	}

	/**
	 *	Computes the unsigned Checksum of a File's header to try to ensure valid File.
	 *	@access		private
	 *	@param		string		$byteString			String of Bytes
	 *	@return		integer
	 */
	private function computeUnsignedChecksum( string $byteString ): int
	{
		$unsignedChecksum	= 0;
		for( $i=0; $i<512; $i++ )
			$unsignedChecksum += ord( $byteString[$i] );
		for( $i=0; $i<8; $i++ )
			$unsignedChecksum -= ord( $byteString[148 + $i]) ;
		$unsignedChecksum += ord( " " ) * 8;
		return $unsignedChecksum;
	}

	/**
	 *	Checks whether this Archive contains a specific File.
	 *	@access		public
	 *	@param		string		$fileName			Name of File to check
	 *	@return		bool
	 */
	public function containsFile( string $fileName ): bool
	{
		foreach( $this->files as $information )
			if( $information['name'] == $fileName )
				return TRUE;
		return FALSE;
	}

	/**
	 *	Checks whether this Archive contains a specific Folder.
	 *	@access		public
	 *	@param		string		$dirName			Name of Folder to check
	 *	@return		bool
	 */
	public function containsFolder( string $dirName ): bool
	{
		foreach( $this->folders as $information )
			if( $information['name'] == $dirName )
				return TRUE;
		return FALSE;
	}

	/**
	 *	Extracts all Folders and Files to a Path and returns Number of extracted Files.
	 *	@access		public
	 *	@param		string|NULL		$targetPath			Path to extract to
	 *	@return		int				Number of extracted Files
	 */
	public function extract( ?string $targetPath = NULL ): int
	{
		$counter	= 0;
		if( $targetPath ){
			$cwd	= getCwd();
			FolderEditor::createFolder( $targetPath );
			chdir( $targetPath );
		}
		foreach( $this->folders as $folder )
			FolderEditor::createFolder( $folder['name'] );
		foreach( $this->files as $file ){
			if( $folder = dirname( $file['name'] ) )
				FolderEditor::createFolder( $folder );
			$counter	+= (int)(bool) FileWriter::save( $file['name'], $file['file'] );
		}
		if( $targetPath )
			chDir( $cwd );
		return $counter;
	}

	/**
	 *	Generates a TAR File from the processed data.
	 *	@access		protected
	 *	@return		bool
	 */
	protected function generateTar(): bool
	{
		// Clear any data currently in $this->content
		$this->content		= "";
		if( $this->numFolders > 0 ){
		// Generate Records for each folder, if we have directories
			foreach( $this->folders as $information ){
				// Generate tar header for this folder
				// Filename, Permissions, UID, GID, size, Time, checksum, typeflag, linkname, magic, version, user name, group name, devmajor, devminor, prefix, end
				$header	= '';
				$header .= str_pad($information['name'],100,chr(0));
				$header .= str_pad(decoct($information['mode']),7,'0',STR_PAD_LEFT) . chr(0);
				$header .= str_pad(decoct($information['user_id']),7,'0',STR_PAD_LEFT) . chr(0);
				$header .= str_pad(decoct($information['group_id']),7,'0',STR_PAD_LEFT) . chr(0);
				$header .= str_pad(decoct(0),11,'0',STR_PAD_LEFT) . chr(0);
				$header .= str_pad(decoct($information['time']),11,'0',STR_PAD_LEFT) . chr(0);
				$header .= str_repeat(' ',8);
				$header .= '5';
				$header .= str_repeat(chr(0),100);
				$header .= str_pad('ustar',6,chr(32));
				$header .= chr(32) . chr(0);
				$header .= str_pad('',32,chr(0));
				$header .= str_pad('',32,chr(0));
				$header .= str_repeat(chr(0),8);
				$header .= str_repeat(chr(0),8);
				$header .= str_repeat(chr(0),155);
				$header .= str_repeat(chr(0),12);
				// Compute header checksum
				$checksum = str_pad(decoct($this->computeUnsignedChecksum($header)),6,'0',STR_PAD_LEFT);
				for($i=0; $i<6; $i++)
					$header[(148 + $i)] = substr($checksum,$i,1);
				$header[154] = chr(0);
				$header[155] = chr(32);
				// Add new tar formatted data to tar file contents
				$this->content .= $header;
			}
		}
		// Generate Records for each file, if we have files( We should...)
		if( $this->numFiles > 0 ){
			foreach( $this->files as $key => $information ){
				// Generate the TAR header for this file
				// Filename, Permissions, UID, GID, size, Time, checksum, typeflag, linkname, magic, version, user name, group name, devmajor, devminor, prefix, end
				$header	= '';
				$header .= str_pad($information['name'],100,chr(0));
				$header .= str_pad(decoct($information['mode']),7,'0',STR_PAD_LEFT) . chr(0);
				$header .= str_pad(decoct($information['user_id']),7,'0',STR_PAD_LEFT) . chr(0);
				$header .= str_pad(decoct($information['group_id']),7,'0',STR_PAD_LEFT) . chr(0);
				$header .= str_pad(decoct($information['size']),11,'0',STR_PAD_LEFT) . chr(0);
				$header .= str_pad(decoct($information['time']),11,'0',STR_PAD_LEFT) . chr(0);
				$header .= str_repeat(' ',8);
				$header .= '0';
				$header .= str_repeat(chr(0),100);
				$header .= str_pad('ustar',6,chr(32));
				$header .= chr(32) . chr(0);
				// How do I get a file's username from PHP?
				$header .= str_pad($information['user_name'],32,chr(0));
				// How do I get a file's group name from PHP?
				$header .= str_pad($information['group_name'],32,chr(0));
				$header .= str_repeat(chr(0),8);
				$header .= str_repeat(chr(0),8);
				$header .= str_repeat(chr(0),155);
				$header .= str_repeat(chr(0),12);
				// Compute header checksum
				$checksum = str_pad(decoct($this->computeUnsignedChecksum($header)),6,'0',STR_PAD_LEFT);
				for($i=0; $i<6; $i++)
					$header[(148 + $i)] = substr($checksum,$i,1);
				$header[154] = chr(0);
				$header[155] = chr(32);
				// Pad file contents to byte count divisible by 512
				$fileContents = str_pad($information['file'],((int) ceil($information['size'] / 512) * 512),chr(0));
				// Add new tar formatted data to tar file contents
				$this->content .= $header . $fileContents;
			}
		}
		// Add 512 bytes of NULLs to designate EOF
		$this->content .= str_repeat(chr(0),512);
		return TRUE;
	}

	/**
	 *	Retrieves information about a File in the current TAR Archive.
	 *	@access		public
	 *	@param		string		$fileName			File Name to get Information for
	 *	@return		array|NULL
	 */
	public function getFile( string $fileName ): ?array
	{
		foreach( $this->files as $information )
			if( $information['name'] == $fileName )
				return $information;
		return NULL;
	}

	/**
	 *	Returns a List of Files within Archive.
	 *	@access		public
	 *	@return		array
	 */
	public function getFileList(): array
	{
		$list	= [];
		foreach( $this->files as $file )
			$list[$file['name']]	= $file['size'];
		return $list;
	}

	/**
	 *	Retrieves information about a Folder in the current TAR Archive.
	 *	@access		public
	 *	@param		string		$dirName			Folder Name to get Information for
	 *	@return		array|NULL
	 */
	public function getFolder( string $dirName ): ?array
	{
		foreach( $this->folders as $information )
			if( $information['name'] == $dirName )
				return $information;
		return NULL;
	}

	/**
	 *	Returns a List of Folders within Archive.
	 *	@access		public
	 *	@return		array
	 */
	public function getFolderList(): array
	{
		$list	= [];
		foreach( $this->folders as $folder )
			$list[]	= $folder['name'];
		return $list;
	}

	/**
	 *	Opens and reads a TAR File.
	 *	@access		public
	 *	@param		string		$fileName		File Name of TAR Archive
	 *	@return		bool
	 *	@throws		Exception
	 */
	public function open( string $fileName ): bool
	{
		// If the tar file doesn't exist...
		if( !file_exists( $fileName ) )
			throw new Exception( 'TAR File "'.$fileName.'" is not existing' );
		$this->content		= "";
		$this->files		= [];
		$this->folders		= [];
		$this->numFiles		= 0;
		$this->numFolders	= 0;
		$this->fileName		= $fileName;
		return $this->readTar( $fileName );
	}

	/**
	 *	Converts a NULL padded string to a non-NULL padded string.
	 *	@access		private
	 *	@param		string		$string				String to clear
	 *	@return		string
	 */
	private function parseNullPaddedString( string $string ): string
	{
		$position = strpos( $string, chr( 0 ) );
		return substr( $string, 0, $position );
	}

	/**
	 *	This function parses the current TAR File.
	 *	@access		private
	 *	@return		bool
	 */
	protected function parseTar(): bool
	{
		// Read Files from archive
		$tarLength = strlen( $this->content );
		$mainOffset = 0;
		while( $mainOffset < $tarLength ){
			// If we read a block of 512 nulls, we are at the end of the archive
			if(substr($this->content,$mainOffset,512) == str_repeat(chr(0),512))
				break;
			// Parse file name
			$fileName		= $this->parseNullPaddedString(substr($this->content,$mainOffset,100));
			// Parse the file mode
			$fileMode		= substr($this->content,$mainOffset + 100,8);
			// Parse the file user ID
			$fileUid		= octdec(substr($this->content,$mainOffset + 108,8));
			// Parse the file group ID
			$fileGid		= octdec(substr($this->content,$mainOffset + 116,8));
			// Parse the file size
			$fileSize		= octdec(substr($this->content,$mainOffset + 124,12));
			// Parse the file update time - unix timestamp format
			$fileTime		= octdec(substr($this->content,$mainOffset + 136,12));
			// Parse Checksum
			$fileChksum	= octdec(substr($this->content,$mainOffset + 148,6));
			// Parse username
			$fileUname		= $this->parseNullPaddedString(substr($this->content,$mainOffset + 265,32));
			// Parse Group name
			$fileGname		= $this->parseNullPaddedString(substr($this->content,$mainOffset + 297,32));
			// Make sure our file is valid
			if($this->computeUnsignedChecksum(substr($this->content,$mainOffset,512)) != $fileChksum)
				return false;
			// Parse File Contents
			$fileContents		= substr($this->content,$mainOffset + 512,$fileSize);
			if( $fileSize > 0 ){
				if(!$this->containsFile( $fileName ) ){
					// Increment number of files
					$this->numFiles++;
					// Create us a new file in our array
					$activeFile = &$this->files[];
					// Assign Values
					$activeFile['name']			= $fileName;
					$activeFile['mode']			= $fileMode;
					$activeFile['size']			= $fileSize;
					$activeFile['time']			= $fileTime;
					$activeFile['user_id']		= $fileUid;
					$activeFile['group_id']		= $fileGid;
					$activeFile['user_name']	= $fileUname;
					$activeFile['group_name']	= $fileGname;
					$activeFile['checksum']		= $fileChksum;
					$activeFile['file']			= $fileContents;
				}
			}
			else{
				if( !$this->containsFolder( $fileName ) ){
					// Increment number of directories
					$this->numFolders++;
					// Create a new folder in our array
					$activeDir = &$this->folders[];
					// Assign values
					$activeDir['name']			= $fileName;
					$activeDir['mode']			= $fileMode;
					$activeDir['time']			= $fileTime;
					$activeDir['user_id']		= $fileUid;
					$activeDir['group_id']		= $fileGid;
					$activeDir['user_name']		= $fileUname;
					$activeDir['group_name']	= $fileGname;
					$activeDir['checksum']		= $fileChksum;
				}
			}
			// Move our offset the number of blocks we have processed
			$mainOffset += 512 + ( ceil( $fileSize / 512 ) * 512 );
		}
		return true;
	}

	/**
	 *	Read a non gzipped TAR File in for processing.
	 *	@access		protected
	 *	@param		string		$fileName		Reads TAR Archive
	 *	@return		bool
	 */
	protected function readTar( string $fileName ): bool
	{
 		$file	= new FileReader( $fileName );
		$this->content = $file->readString();
		// Parse the TAR file
		return $this->parseTar();
	}

	/**
	 *	Removes a File from the Archive.
	 *	@access		public
	 *	@param		string		$fileName		Name of File to remove
	 *	@return		bool
	 */
	public function removeFile( string $fileName ): bool
	{
		foreach( $this->files as $key => $information ){
			if( $information['name'] !== $fileName )
				continue;
			$this->numFiles--;
			unset( $this->files[$key] );
			return TRUE;
		}
		return FALSE;
	}

	/**
	 *	Removes a Folder from the Archive.
	 *	@access		public
	 *	@param		string		$dirName		Name of Folder to remove
	 *	@return		bool
	 */
	public function removeFolder( string $dirName ): bool
	{
		foreach( $this->folders as $key => $information ){
			if( $information['name'] !== $dirName )
				continue;
			$this->numFolders--;
			unset( $this->folders[$key] );
			return TRUE;
		}
		return FALSE;
	}

	/**
	 *	Write down the currently loaded Tar Archive.
	 *	@access		public
	 *	@param		string|NULL		$fileName 	Name of Tar Archive to save
	 *	@return		int				Number of written bytes
	 *	@throws		Exception
	 */
	public function save( ?string $fileName = NULL ): int
	{
		if( empty( $fileName ) ){
			if( empty( $this->fileName ) )
				throw new Exception( 'No TAR file name for saving given' );
			$fileName = $this->fileName;
		}
		// Encode processed files into TAR file format
		$this->generateTar();
		//  write archive file
		return FileWriter::save( $fileName, $this->content );
	}
}
