<?php
import ("de.ceus-media.file.File");
import ("de.ceus-media.file.folder.Folder");
/**
 *	Folder to read entries.
 *	@package		file.folder
 *	@extends		Folder
 *	@uses			File
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.4
 */
/**
 *	Folder to read entries.
 *	@package		file.folder
 *	@extends		Folder
 *	@uses			File
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.4
 */
class IndexFolder extends Folder
{
	/**	@var	array		$extensions		Array of allowed extensions */
	protected $extensions	= array();
	/**	@var	array		$folders		Array of Folder in this Folder */
	protected $folders		= array();
	/**	@var	array		$files			Array of Files in this Folder */
	protected $files		= array();

	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		string	path		URI of directory
	 *	@return		void
	 */
	public function __construct( $path, $extensions = array() )
	{
		parent::__construct( $path );
		$this->extensions = (array) $extensions;
	}

	/**
	 *	Returns all entries of this directory in a list.
	 *	@access		public
	 *	@return		array
	 */
 	public function getEntryList()
	{
		$this->read();
		$list	= array_merge( $this->getFolderList(), $this->getFileList() );
		return $list;
	}

	/**
	 *	Returns all entries of this directory in a associative array.
	 *	@access		public
	 *	@return		array
	 */
	public function getEntryArray()
	{
		$this->read();
		foreach( $this->getFolderList() as $folder )
			$array[]	= array( "name" => $folder, "type" => "folder" );
		foreach( $this->getFileList() as $file )
		{
			$uri	= $this->path.$file;
			if( is_file( $uri ) )		$type = "file";
			else if( is_link( $uri ) )	$type = "link";
			else 						$type = "unknown";
			$array[] = array( "name" => $file, "type" => $type );
		}
		return $array;
	}

	/**
	 *	Returns all entries of this directory in a list of objects.
	 *	@access		public
	 *	@return		array
	 */
	public function getEntryObjects()
	{
		foreach( $this->getFolderList() as $folder )
			$array[]	= new Folder( $folder );
		foreach( $this->getFileList() as $file )
		{
//			if( is_file( $file ) )		$type = "File";
//			else if( is_link( $file ) )	$type = "Link";
			$array[] = new File( $file );
		}
		return $array;
	}

	/**
	 *	Returns all folders of this directory in a list.
	 *	@access		public
	 *	@return		array
	 */
	public function getFolderList()
	{
		$this->read();
		return $this->folders;
	}
	
	/**
	 *	Returns all files of this directory in a list.
	 *	@access		public
	 *	@return		array
	 */
	public function getFileList()
	{
		$this->read();
		return $this->files;
	}

	//  --  RECURSIVE METHODS  --  //
	/**
	 *	Removing the directory with containing directories and files.
	 *	@access		public
	 *	@return		bool
	 */
	public function copyRecursive( $target )
	{
		$result	= true;
		if( !is_dir( $target ) )
			$result	= $result && mkdir( $target );
		if( $result )
		{
			foreach( $this->getFileList() as $file )
				$result	= $result && copy( $this->path.$file, $target.$file );
			$folders = $this->getFolderList();
			array_reverse( $folders ); 
			foreach( $folders as $folder )
			{
				$f = new IndexFolder( $this->path.$folder."/" );
				$result	= $result && $f->copyRecursive( $target.$folder."/" );
			}
		}
		return $result;
	}

	/**
	 *	Removing the directory with containing directories and files.
	 *	@access		public
	 *	@return		bool
	 */
	public function removeRecursive( $path )  
	{
		$result	= true;
		if( $res = opendir( $this->path.$path ) )
		{
			while( ( $file = readdir( $res ) ) !== false )
				if($file !== '.' && $file !== '..')
					if( is_dir( $this->path.$path.'/'.$file ) )
						$result	= $result && $this->removeRecursive( $path.'/'.$file );
					else
						$result	= $result && @unlink( $this->path.$path.'/'.$file );
			closedir( $res );
			if( $result )
				$result	= $result && @rmdir( $this->path.$path );
		}
		return $result;
	 }

	//  --  PRIVATE METHODS  --  //
	/**
	 *	Reads current Folder.
	 *	@access		protected
	 *	@return		void
	 */
	protected function read()
	{
		$this->folders	= array();
		$this->files		= array();

		$res	= opendir( $this->path );
		while( false !== ( $entry = readdir( $res ) ) )
		{
			if (!ereg ("^([.]{1,2})$", $entry))
			{
				if (is_dir ($this->path."/".$entry))
					$this->folders[$entry] = $entry;
				else if (is_file ($this->path."/".$entry))
				{
					if (count($this->extensions))
					{
						$info = pathinfo ($entry);
						$ext	= isset( $info['extension'] ) ? $info['extension'] : "";
						if( !in_array ($ext, $this->extensions) )
							continue;
					}
					$this->files[] = $entry;
				}
			}
		}
		closedir( $res );
	}
}
?>