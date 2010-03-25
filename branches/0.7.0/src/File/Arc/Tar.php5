<?php
/**
 *	Tar File allows creation and manipulation of tar archives.
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
 *	@package		file.arc
 *	@uses			File_Reader
 *	@uses			File_Writer
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2007-2010 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@version		$Id$.7
 */
import( 'de.ceus-media.file.Reader' );
import( 'de.ceus-media.file.Writer' );
/**
 *	Tar File allows creation and manipulation of tar archives.
 *	@category		cmClasses
 *	@package		file.arc
 *	@uses			File_Reader
 *	@uses			File_Writer
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2007-2010 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@version		$Id$.7
 */
class File_Arc_Tar
{
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

	/**
	 *	Adds a Folder to this TAR Archive.
	 *	@access		public
	 *	@param		string		$dirName			Path of Folder to add
	 *	@return		bool
	 */
	public function addFolder( $dirName )
	{
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
	 *	Appends a TAR File to the end of the currently opened TAR File.
	 *	@access		public
	 *	@param		string		$fileName			TAR File to add to current TAR File
	 *	@return		bool
	 */
	public function appendTar( $fileName )
	{
		if( !file_exists( $fileName ) )																		// If the tar file doesn't exist...
			throw new Exception( 'TAR File "'.$fileName.'" is not existing' );
		$this->readTar( $fileName );
		return TRUE;
	}

	/**
	 *	Computes the unsigned Checksum of a File's header to try to ensure valid File.
	 *	@access		private
	 *	@param		string		$bytestring			String of Bytes
	 *	@return		string
	 */
	private function computeUnsignedChecksum( $bytestring )
	{
		$unsigned_chksum	= 0;
		for( $i=0; $i<512; $i++ )
			$unsigned_chksum += ord( $bytestring[$i] );
		for( $i=0; $i<8; $i++ )
			$unsigned_chksum -= ord( $bytestring[148 + $i]) ;
		$unsigned_chksum += ord( " " ) * 8;
		return $unsigned_chksum;
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
			@mkdir( $targetPath );
			chdir( $targetPath );
		}
		foreach( $this->folders as $folder )
			@mkdir( $folder['name'] );
		foreach( $this->files as $file )
		{
			if( $folder = dirname( $file['name'] ) )
				@mkdir( $folder );
			$counter	+= (int)(bool) File_Writer::save( $file['name'], $file['file'] );
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
	protected function generateTar()
	{
		unset( $this->content );																				// Clear any data currently in $this->content	
		if( $this->numFolders > 0 )
		{																									// Generate Records for each folder, if we have directories
			foreach( $this->folders as $key => $information )
			{
				unset( $header );
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
				$checksum = str_pad(decoct($this->computeUnsignedChecksum($header)),6,'0',STR_PAD_LEFT);	// Compute header checksum
				for($i=0; $i<6; $i++)
					$header[(148 + $i)] = substr($checksum,$i,1);
				$header[154] = chr(0);
				$header[155] = chr(32);
				$this->content .= $header;																	// Add new tar formatted data to tar file contents
			}
		}
		if( $this->numFiles > 0 )																				// Generate Records for each file, if we have files( We should...)
		{
			foreach( $this->files as $key => $information )
			{
				unset($header);
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
				$header .= str_pad($information['user_name'],32,chr(0));									// How do I get a file's user name from PHP?
				$header .= str_pad($information['group_name'],32,chr(0));									// How do I get a file's group name from PHP?
				$header .= str_repeat(chr(0),8);
				$header .= str_repeat(chr(0),8);
				$header .= str_repeat(chr(0),155);
				$header .= str_repeat(chr(0),12);
				$checksum = str_pad(decoct($this->computeUnsignedChecksum($header)),6,'0',STR_PAD_LEFT);	// Compute header checksum
				for($i=0; $i<6; $i++)
					$header[(148 + $i)] = substr($checksum,$i,1);
				$header[154] = chr(0);
				$header[155] = chr(32);
				$filecontents = str_pad($information['file'],(ceil($information['size'] / 512) * 512),chr(0));			// Pad file contents to byte count divisible by 512
				$this->content .= $header . $filecontents;													// Add new tar formatted data to tar file contents
			}
		}
		$this->content .= str_repeat(chr(0),512);															// Add 512 bytes of NULLs to designate EOF
		return true;
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
	 *	Converts a NULL padded string to a non-NULL padded string.
	 *	@access		private
	 *	@param		string		$string				String to clear
	 *	@return		string
	 */
	private function parseNullPaddedString( $string )
	{
		$position = strpos( $string, chr( 0 ) );
		return substr( $string, 0, $position );
	}

	/**
	 *	This function parses the current TAR File.
	 *	@access		private
	 *	@return		bool
	 */
	protected function parseTar()
	{
		$tarLength = strlen( $this->content );																// Read Files from archive
		$mainOffset = 0;
		while( $mainOffset < $tarLength )
		{
			if(substr($this->content,$mainOffset,512) == str_repeat(chr(0),512))							// If we read a block of 512 nulls, we are at the end of the archive
				break;
			$fileName		= $this->parseNullPaddedString(substr($this->content,$mainOffset,100));			// Parse file name
			$fileMode		= substr($this->content,$mainOffset + 100,8);									// Parse the file mode
			$fileUid		= octdec(substr($this->content,$mainOffset + 108,8));							// Parse the file user ID
			$fileGid		= octdec(substr($this->content,$mainOffset + 116,8));							// Parse the file group ID
			$fileSize		= octdec(substr($this->content,$mainOffset + 124,12));							// Parse the file size
			$fileTime		= octdec(substr($this->content,$mainOffset + 136,12));							// Parse the file update time - unix timestamp format
			$fileChksum	= octdec(substr($this->content,$mainOffset + 148,6));								// Parse Checksum
			$fileUname		= $this->parseNullPaddedString(substr($this->content,$mainOffset + 265,32));	// Parse user name
			$fileGname		= $this->parseNullPaddedString(substr($this->content,$mainOffset + 297,32));	// Parse Group name
			if($this->computeUnsignedChecksum(substr($this->content,$mainOffset,512)) != $fileChksum)		// Make sure our file is valid
				return false;
			$filecontents		= substr($this->content,$mainOffset + 512,$fileSize);						// Parse File Contents
			if( $fileSize > 0 )
			{
				if(!$this->containsFile( $fileName ) )
				{
					$this->numFiles++;																		// Increment number of files
					$activeFile = &$this->files[];															// Create us a new file in our array
					$activeFile['name']			= $fileName;												// Asign Values
					$activeFile['mode']			= $fileMode;
					$activeFile['size']			= $fileSize;
					$activeFile['time']			= $fileTime;
					$activeFile['user_id']		= $fileUid;
					$activeFile['group_id']		= $fileGid;
					$activeFile['user_name']	= $fileUname;
					$activeFile['group_name']	= $fileGname;
					$activeFile['checksum']		= $fileChksum;
					$activeFile['file']			= $filecontents;
				}
			}
			else
			{
				if( !$this->containsFolder( $fileName ) )
				{
					$this->numFolders++;																	// Increment number of directories
					$activeDir = &$this->folders[];															// Create a new folder in our array
					$activeDir['name']			= $fileName;												// Assign values
					$activeDir['mode']			= $fileMode;
					$activeDir['time']			= $fileTime;
					$activeDir['user_id']		= $fileUid;
					$activeDir['group_id']		= $fileGid;
					$activeDir['user_name']		= $fileUname;
					$activeDir['group_name']	= $fileGname;
					$activeDir['checksum']		= $fileChksum;
				}
			}
			$mainOffset += 512 + ( ceil( $fileSize / 512 ) * 512 );												// Move our offset the number of blocks we have processed
		}
		return true;
	}

	/**
	 *	Opens and reads a TAR File.
	 *	@access		public
	 *	@param		string		$fileName		File Name of TAR Archive
	 *	@return		bool
	 */
	public function open( $fileName )
	{
		if( !file_exists( $fileName ) )																		// If the tar file doesn't exist...
			throw new Exception( 'TAR File "'.$fileName.'" is not existing' );
		unset( $this->content );
		$this->files		= array();
		$this->folders		= array();
		$this->numFiles		= 0;
		$this->numFolders	= 0;
		$this->fileName		= $fileName;
		return $this->readTar( $fileName );
	}

	/**
	 *	Read a non gzipped TAR File in for processing.
	 *	@access		protected
	 *	@param		string		$fileName		Reads TAR Archive
	 *	@return		bool
	 */
	protected function readTar( $fileName )
	{
 		$file	= new File_Reader( $fileName );
		$this->content = $file->readString(); 		
		return $this->parseTar();																					// Parse the TAR file
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
	 *	@param		string	fileName 	Name of Tar Archive to save
	 *	@return		bool
	 */
	public function save( $fileName = FALSE )
	{
		if( !$fileName )
		{
			if( !$this->fileName )
				throw new Exception( 'No TAR file name for saving given' );
			$fileName = $this->fileName;
		}
		$this->generateTar();												// Encode processed files into TAR file format
		$f = new File_Writer( $fileName );
		$f->writeString( $this->content );
		return TRUE;
	}
}
?>