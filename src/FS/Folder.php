<?php
namespace CeusMedia\Common\FS;

use CeusMedia\Common\ADT\Collection\Dictionary;
use CeusMedia\Common\Exception\IO as IOException;
#use CeusMedia\Common\FS;

class Folder extends AbstractNode
{
	protected $pathName;

	public function __construct( $pathName, $create = FALSE, $mode = 0777, $strict = TRUE )
	{
		$this->setPathName( $pathName );
		if( $create && !$this->exists() )
			$this->create( $mode, $strict );
	}

	public function count( $type = FS::TYPE_ALL, $recursive = FALSE, $strict = TRUE ): int
	{
		$index	= $this->index( $type, $strict );
		$count	= $index->count();
		if( $recursive ){
			foreach( $this->index( FS::TYPE_FOLDER ) as $item ){
				$count	+= $item->count( $type, TRUE, $strict );
			}
		}
		return $count;
	}

	public function create( $mode = 0777, $strict = TRUE )
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
		if( !mkdir( $this->pathName, 0777, TRUE ) ){
			return FALSE;
		}
		return TRUE;
	}

	public function createFile( $pathName, $content = NULL, $mode = 0777, $strict = TRUE ): File
	{
		$file	= new File( $this->pathName.'/'.$pathName );
		$file->create( $mode, $strict );
		if( $content )
			$file->setContent( $content, $strict );
		return $file;
	}

	public function createFolder( $pathName, $mode = 0777, $strict = TRUE ): Folder
	{
		$folder	= new FS_Folder( $this->pathName.'/'.$pathName );
		$folder->create( $mode, $strict );
		return $folder;
	}

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

	public function getFile( string $fileName ): File
	{
		return new File( $this->pathName.'/'.$fileName );
	}

	public function getFolder( string $fileName ): Folder
	{
		return new Folder( $this->pathName.'/'.$fileName );
	}

	public function getTime( $strict = TRUE )
	{
		if( !$this->exists( $strict ) )
			return NULL;
		return filemtime( $this->pathName );
	}

	public function has( $name, $type = FS::TYPE_ALL ){
		$index	= $this->index( $type, FALSE );
		return $index->has( $name );
	}

	public function index( $type = FS::TYPE_ALL, $sort = SORT_REGULAR, $strict = TRUE ){
		if( !$this->exists( $strict ) )
 			return new Dictionary();
		$index	= new \DirectoryIterator( $this->pathName );
		$list	= array();
		foreach( $index as $entry ){
			if( $entry->isDot() )
				continue;
			$fileName	= $entry->getFilename();
			if( $entry->isDir() && ( $type & ( FS::TYPE_ALL | FS::TYPE_FOLDER ) ) )
				$list[$fileName]	= new Folder( $this->pathName.'/'.$fileName );
//			else if( $entry->isLink() )
//				$list[]	= new FS_Folder( $this->pathName.'/'.$entry->getFilename() );
			else if( $entry->isFile() && $type & ( FS::TYPE_ALL | FS::TYPE_FILE ) )
				$list[$fileName]	= new File( $this->pathName.'/'.$fileName );
		}
		ksort( $list, $sort );
		return new Dictionary( $list );
	}

	public function rename( $targetPath, $strict = TRUE ){
		$target	= new Folder( $targetPath );
		if( $target->exists() ){
			if( $strict )
				throw new IOException( 'Target folder is already existing', 0, $targetPath );
			return FALSE;
		}
		if( !rename( $this->pathName, $targetPath ) )
			return FALSE;
		$this->setPathName( $targetPath );
	}
}
