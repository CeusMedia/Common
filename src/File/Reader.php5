<?php
/**
 *	Basic File Reader.
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
 *	@package		File
 *	@uses			Alg_UnitFormater
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2012 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@version		$Id$
 */
/**
 *	Basic File Reader.
 *	@category		cmClasses
 *	@package		File
 *	@uses			Alg_UnitFormater
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2012 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@version		$Id$
 */
class File_Reader
{
	/**	@var		string		$fileName		File Name or URI of File */
	protected $fileName;

	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		string		$fileName		File Name or URI of File
	 *	@return		void
	 */
	public function __construct( $fileName, $check = FALSE )
	{
		if( !is_string( $fileName ) )
			throw new InvalidArgumentException( 'File name must a string' );
		if( $check && !$this->exists( $fileName ) )
			throw new RuntimeException( 'File "'.addslashes( $fileName ).'" is not existing' );
		if( $check && !$this->isReadable( $fileName ) )
			throw new RuntimeException( 'File "'.$fileName.'" is not readable' );
		$this->fileName = $fileName;
	}

	/**
	 *	Indicates whether current File is equal to another File.
	 *	@access		public
	 *	@param		string		$fileName		Name of File to compare with
	 *	@return		bool
	 */
	public function equals( $fileName )
	{
		$toCompare	= File_Reader::load( $fileName );
		$thisFile	= File_Reader::load( $this->fileName );
		return( $thisFile == $toCompare );
	}

	/**
	 *	Indicates whether current URI is an existing File.
	 *	@access		public
	 *	@return		bool
	 */
	public function exists()
	{
		$exists	= file_exists( $this->fileName );
		$isFile	= is_file( $this->fileName );
		return $exists && $isFile;
	}

	/**
	 *	Returns Basename of current File.
	 *	@access		public
	 *	@return		string
	 */
	public function getBasename()
	{
		return basename( $this->fileName );
	}

	/**
	 *	Returns the file date as timestamp.
	 *	@access		public
	 *	@return		int
	 */
	public function getDate()
	{
		return filemtime( $this->fileName );
	}

	/**
	 *	Returns the encoding (character set) of current File.
	 *	@access		public
	 *	@return		string
	 *	@throws		RuntimeException	if Fileinfo is not installed
	 */
	public function getEncoding()
	{
		if( function_exists( 'finfo_open' ) )
		{
			$magicFile	= ini_get( 'mime_magic.magicfile' );
//			$magicFile	= str_replace( "\\", "/", $magicFile );
//			$magicFile	= preg_replace( "@\.mime$@", "", $magicFile );
			$fileInfo	= finfo_open( FILEINFO_MIME_ENCODING, $magicFile );
			$mimeType	= finfo_file( $fileInfo, realpath( $this->fileName ) );
			finfo_close( $fileInfo );
			return $mimeType;
		}
		else if( substr( PHP_OS, 0, 3 ) != "WIN" )
		{
			$command	= 'file -b --mime-encoding '.escapeshellarg( $this->fileName );
			return trim( exec( $command ) );
		}
		throw new RuntimeException( 'PHP extension Fileinfo is missing' );
	}

	/**
	 *	Returns Extension of current File.
	 *	@access		public
	 *	@return		string
	 */
	public function getExtension()
	{
		$info = pathinfo( $this->fileName );
		$ext = $info['extension'];
		return $ext;
	}

	/**
	 *	Returns File Name of current File.
	 *	@access		public
	 *	@return		string
	 */
	public function getFileName()
	{
		return $this->fileName;
	}

	public function getGroup()
	{
		$group	= filegroup( $this->fileName );
		if( FALSE === $group )
			throw new RuntimeException( 'Could not get group of file "'.$this->fileName.'"' );
		return $group;
	}

	/**
	 *	Returns the MIME type of current File.
	 *	@access		public
	 *	@return		string
	 *	@throws		RuntimeException	if Fileinfo is not installed
	 */
	public function getMimeType()
	{
		if( function_exists( 'finfo_open' ) )
		{
			$magicFile	= ini_get( 'mime_magic.magicfile' );
//			$magicFile	= str_replace( "\\", "/", $magicFile );
//			$magicFile	= preg_replace( "@\.mime$@", "", $magicFile );
			$fileInfo	= finfo_open( FILEINFO_MIME_TYPE, $magicFile );
			$mimeType	= finfo_file( $fileInfo, realpath( $this->fileName ) );
			finfo_close( $fileInfo );
			return $mimeType;
		}
		else if( substr( PHP_OS, 0, 3 ) != "WIN" )
		{
			$command	= 'file -b --mime-type '.escapeshellarg( $this->fileName );
			return trim( exec( $command ) );
		}
		else if( function_exists( 'mime_content_type' ) && $mimeType = mime_content_type( $this->fileName ) )
		{
			return $mimeType;
		}
		throw new RuntimeException( 'PHP extension Fileinfo is missing' );
	}

	public function getOwner()
	{
		$user	= fileowner( $this->fileName );
		if( FALSE === $user )
			throw new RuntimeException( 'Could not get owner of file "'.$this->fileName.'"' );
		return $user;
	}

	/**
	 *	Returns canonical Path to the current File.
	 *	@access		public
	 *	@return		string
	 */
	public function getPath()
	{
		$realpath	= realpath( $this->fileName );
		$path	= dirname( $realpath );
		$path	= str_replace( "\\", "/", $path );
		$path	.= "/";
		return	$path;
	}

	/**
	 *	Returns OS permissions of current file as octal value.
	 *	@access		public
	 *	@return		File_Permissions		File permissions object
	 */
	public function getPermissions()
	{
		return new File_Permissions( $this->fileName );
	}

	/**
	 *	Returns Size of current File.
	 *	@access		public
	 *	@param		int			$precision		Precision of rounded Size (only if unit is set)
	 *	@return		int
	 */
	public function getSize( $precision = NULL )
	{
		$size	= filesize( $this->fileName );
		if( $precision )
		{
			$size	= Alg_UnitFormater::formatBytes( $size, $precision );
		}
		return $size;
	}

	/**
	 *	Indicates whether a given user is owner of current file.
	 *	On Windows this method always returns TRUE.
	 *	@access		public
	 *	@param		string		$user		Name of user to check ownership for, current user by default
	 *	@return		boolean
	 */
	public function isOwner( $user = NULL )
	{
		$user	= $user ? $user : get_current_user();
		if( !function_exists( 'posix_getpwuid' ) )
			return TRUE;
		$uid	= fileowner( $this->fileName );
		if( !$uid )
			return TRUE;
		$owner	= posix_getpwuid( $uid );
		if( !$owner )
			return TRUE;
		print_m( $owner );
		return $user == $owner['name'];
	}

	/**
	 *	Indicates whether a file is readable.
	 *	@access		public
	 *	@return		bool
	 */
	public function isReadable()
	{
		return is_readable( $this->fileName );
	}

	/**
	 *	Loads a File into a String statically.
	 *	@access		public
	 *	@static
	 *	@param		string		$fileName		Name of File to load
	 *	@return		string
	 */
	public static function load( $fileName )
	{
		$reader	= new File_Reader( $fileName );
		return $reader->readString();
	}
	
	/**
	 *	Loads a File into an Array statically.
	 *	@access		public
	 *	@static
	 *	@param		string		$fileName		Name of File to load
	 *	@return		array
	 */
	public static function loadArray( $fileName )
	{
		$reader	= new File_Reader( $fileName );
		return $reader->readArray();
	}

	/**
	 *	Reads file and returns it as array.
	 *	@access		public
	 *	@return		array
	 */
 	public function readArray()
	{
		$content	= $this->readString();
		return preg_split( '/\r?\n/', $content );
	}

	/**
	 *	Reads file and returns it as string.
	 *	@access		public
	 *	@return		string
	 *	@throws		RuntimeException			if File is not existing
	 *	@throws		RuntimeException			if File is not readable
	 */
 	public function readString()
	{
		if( !$this->exists( $this->fileName ) )
			throw new RuntimeException( 'File "'.$this->fileName.'" is not existing' );
		if( !$this->isReadable( $this->fileName ) )
			throw new RuntimeException( 'File "'.$this->fileName.'" is not readable' );
		return file_get_contents( $this->fileName );
	}
}
?>