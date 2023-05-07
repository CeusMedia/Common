<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

namespace CeusMedia\Common\FS;

use CeusMedia\Common\ADT\Collection\Dictionary;
use CeusMedia\Common\Exception\IO as IOException;
use CeusMedia\Common\FS;
use DirectoryIterator;

class Folder extends AbstractNode
{
	/**
	 *	@param		string		$pathName
	 *	@param		bool		$create
	 *	@param		int			$mode
	 *	@param		bool		$strict
	 *	@throws		IOException
	 */
	public function __construct( string $pathName, bool $create = FALSE, int $mode = 0777, bool $strict = TRUE )
	{
		parent::__construct( $pathName );
		if( $create && !$this->exists() )
			$this->create( $mode, $strict );
	}

	/**
	 *	@param		int			$type
	 *	@param		bool		$recursive
	 *	@param		bool		$strict
	 *	@return		int
	 */
	public function count( int $type = FS::TYPE_ALL, bool $recursive = FALSE, bool $strict = TRUE ): int
	{
		$index	= $this->index( $type, SORT_REGULAR, $strict );
		$count	= $index->count();
		if( $recursive ){
			foreach( $this->index( FS::TYPE_FOLDER ) as $item ){
				$count	+= $item->count( $type, TRUE, $strict );
			}
		}
		return $count;
	}

	/**
	 *	@param		int			$mode
	 *	@param		bool		$strict
	 *	@return		bool
	 *	@throws		IOException
	 */
	public function create( int $mode = 0777, bool $strict = TRUE ): bool
	{
		if( $this->exists() ){
			if( $strict ){
				if( is_dir( $this->pathName ) )
					throw new IOException( 'Folder is already existing', 0, $this->pathName );
				if( is_link( $this->pathName ) )
					throw new IOException( 'A link with this name is already existing', 0, $this->pathName );
				if( is_file( $this->pathName ) )
					throw new IOException( 'A file with this name is already existing', 0, $this->pathName );
			}
			return FALSE;
		}
		if( !mkdir( $this->pathName, $mode, TRUE ) ){
			return FALSE;
		}
		return TRUE;
	}

	/**
	 *	@param		string		$pathName
	 *	@param		string|NULL	$content
	 *	@param		int			$mode
	 *	@param		bool		$strict
	 *	@return		File
	 *	@throws		IOException
	 */
	public function createFile( string $pathName, string $content = NULL, int $mode = 0777, bool $strict = TRUE ): File
	{
		$file	= new File( $this->pathName.'/'.$pathName );
		$file->create( $mode, $strict );
		if( $content )
			$file->setContent( $content, $strict );
		return $file;
	}

	/**
	 *	@param		string		$pathName
	 *	@param		int			$mode
	 *	@param		bool		$strict
	 *	@return		Folder
	 *	@throws		IOException
	 */
	public function createFolder( string $pathName, int $mode = 0777, bool $strict = TRUE ): Folder
	{
		$folder	= new Folder( $this->pathName.'/'.$pathName );
		$folder->create( $mode, $strict );
		return $folder;
	}

	/**
	 *	@param		bool		$strict
	 *	@return		bool
	 *	@throws		IOException
	 */
	public function exists( bool $strict = FALSE ): bool
	{
		if( !file_exists( $this->pathName ) ){
			if( $strict )
				throw new IOException( 'Folder is not existing', 0, $this->pathName );
			return FALSE;
		}
		if( !is_dir( $this->pathName ) ){
			if( $strict )
				throw new IOException( 'Not a folder', 0, $this->pathName );
			return FALSE;
		}
		return TRUE;
	}

	/**
	 *	@param		string		$fileName
	 *	@return		File
	 *	@throws		IOException
	 */
	public function getFile( string $fileName ): File
	{
		return new File( $this->pathName.'/'.$fileName );
	}

	/**
	 *	@param		string		$fileName
	 *	@return		Folder
	 *	@throws		IOException
	 */
	public function getFolder( string $fileName ): Folder
	{
		return new Folder( $this->pathName.'/'.$fileName );
	}

	/**
	 *	@param		bool		$strict
	 *	@return		bool|int|NULL
	 *	@throws		IOException
	 */
	public function getTime( bool $strict = TRUE ): bool|int|null
	{
		if( !$this->exists( $strict ) )
			return NULL;
		return filemtime( $this->pathName );
	}

	/**
	 *	@param		string		$name
	 *	@param		int			$type
	 *	@return		bool
	 */
	public function has( string $name, int $type = FS::TYPE_ALL ): bool
	{
		$index	= $this->index( $type, SORT_REGULAR, FALSE );
		return $index->has( $name );
	}

	/**
	 *	@param		int			$type
	 *	@param		int			$sort
	 *	@param		bool		$strict
	 *	@return		Dictionary
	 *	@throws		IOException
	 */
	public function index( int $type = FS::TYPE_ALL, int $sort = SORT_REGULAR, bool $strict = TRUE ): Dictionary
	{
		if( !$this->exists( $strict ) )
 			return new Dictionary();
		$index	= new DirectoryIterator( $this->pathName );
		$list	= [];
		foreach( $index as $entry ){
			if( $entry->isDot() )
				continue;
			$fileName	= $entry->getFilename();
			if( $entry->isDir() && ( $type & ( FS::TYPE_ALL | FS::TYPE_FOLDER ) ) )
				$list[$fileName]	= new Folder( $this->pathName.'/'.$fileName );
//			else if( $entry->isLink() )
//				$list[]	= new Folder( $this->pathName.'/'.$entry->getFilename() );
			else if( $entry->isFile() && $type & ( FS::TYPE_ALL | FS::TYPE_FILE ) )
				$list[$fileName]	= new File( $this->pathName.'/'.$fileName );
		}
		ksort( $list, $sort );
		return new Dictionary( $list );
	}

	/**
	 *	@param		string		$targetPath
	 *	@param		bool		$strict
	 *	@return		bool
	 *	@throws		IOException
	 */
	public function rename( string $targetPath, bool $strict = TRUE ): bool
	{
		$target	= new Folder( $targetPath );
		if( $target->exists() ){
			if( $strict )
				throw new IOException( 'Target folder is already existing', 0, $targetPath );
			return FALSE;
		}
		if( !rename( $this->pathName, $targetPath ) )
			return FALSE;
		$this->setPathName( $targetPath );
		return TRUE;
	}
}
