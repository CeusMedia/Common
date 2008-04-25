<?php
import ("de.ceus-media.file.Reader");
import ("de.ceus-media.file.folder.Folder");
/**
 *	TreeFolder to read a file structur recusivly.
 *	@package		file.folder
 *	@extends		Folder
 *	@uses			File_Reader
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.4
 */
/**
 *	TreeFolder to read a file structur recusivly.
 *	@package		file.folder
 *	@extends		Folder
 *	@uses			File_Reader
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.4
 */
class TreeFolder extends Folder
{
	/**	@var	array		$extensions		Array of allowed extensions */
	var $extensions	= array();
	/**	@var	array		$folders		Array of Folder in this Folder */
	var $folders	= array();
	/**	@var	array		$files			Array of Files in this Folder */
	var $files		= array();
	
	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		string		$path			Path of Folder to read
	 *	@param		array		$extensions		Array of allowed extensions
	 *	@param		bool		$filter			Flag: filter empty Folders if extension filter is set
	 *	@return 	void
	 */
	public function __construct( $path, $extensions = array(), $filter = false )
	{
		parent::__construct( $path );
		$this->filterFolders	= (bool) $filter;
		$this->extensions		= (array) $extensions;
		$this->read();
	}

	/**
	 *	Returns an Array of all found folders.
	 *	@access		public
	 *	@param		string		$path			Current Path of this Folder
	 *	@return		array
	 */
	public function getTotalFolders( $path = "" )
	{
		$list = array();
		foreach ($this->folders as $foldername => $folder)
			$list[]	= $folder->getTotalFolders( $path.$foldername."/" );
		if( $path )
			$list[] = $path;
		return $list;
	}

	/**
	 *	Returns an Array of all found folders.
	 *	@access		public
	 *	@param		string		$path			Current Path of this Folder
	 *	@return		array
	 */
	public function getTotalFolderList( $path = "" )
	{
		$list	= array();
		foreach( $this->folders as $foldername => $folder )
			foreach( $folder->getTotalFolderList( $path.$foldername."/" ) as $_folder )
				$list[]	= $_folder;
		if( $path )
			$list[] 	= $path;
		return $list;
	}

	/**
	 *	Returns an array of all found Files in all Folders.
	 *	@access		public
	 *	@param		string		$path			Current Path of this Folder
	 *	@return 	array
	 */
	public function getTotalFiles( $path = "" )
	{
		$list = array();
		$c = is_array( $this->extensions ) && count( $this->extensions ) > 0;
		foreach( $this->folders as $foldername => $folder )
			$list[] = $folder->getTotalFiles( $path.$foldername."/" );
		foreach( $this->files as $filename => $file )
			$list[] = $path.$filename;
		return $list;
	}
	
	/**
	 *	Returns an array of all found Files in all Folders.
	 *	@access		public
	 *	@param		string		$path			Current Path of this Folder
	 *	@return 	array
	 */
	public function getTotalFileList( $path = "" )
	{
		$list	= array();
		$c	= is_array( $this->extensions ) && count( $this->extensions ) > 0;
		foreach( $this->folders as $foldername => $folder )
			foreach( $folder->getTotalFileList( $path.$foldername."/" ) as $file )
				$list[]	= $file;
		foreach( $this->files as $filename => $file )
			$list[]	= $path.$filename;
		return $list;
	}
	
	/**
	 *	Counts Size of all Files recursivly.
	 *	@access		public
	 *	@return 	void
	 */
	public function getTotalSize()
	{
		$size = 0;
		$c = is_array( $this->extensions ) && count( $this->extensions ) > 0;
		foreach( $this->folders as $foldername => $folder )
			$size += $folder->getTotalSize();
		foreach( $this->files as $filename => $file )
			$size += filesize( $this->path."/".$filename );
		return $size;
	}

	/**
	 *	Counts Lines of Code recursivly.
	 *	@access		public
	 *	@return 	void
	 */
	public function getTotalLOC()
	{
		$loc = 0;
		$c = is_array( $this->extensions ) && count( $this->extensions ) > 0;
		foreach( $this->folders as $foldername => $folder )
			$loc += $folder->getTotalLOC();
		foreach( $this->files as $filename => $file )
		{
			$f = new File_Reader( $this->path."/".$filename );
			$loc += count( $f->readArray() );
		}
		return $loc;
	}

	public function sort()
	{
		ksort( $this->files );
		ksort( $this->folders );
		foreach( $this->folders as $folder )
			$folder->sort();
	}

	//  --  PROTECTED METHODS  --  //
	/**
	 *	Reads current Folder recursive.
	 *	@access		protected
	 *	@return 	void
	 */
	protected function read()
	{
		$res	= opendir( $this->path );
		while( false !== ( $entry = readdir( $res ) ) )
		{
			if( !ereg( "^([.]{1,2})$", $entry ) )
			{
				if( is_dir( $this->path."/".$entry ) )
				{
					$child = new TreeFolder( $this->path."/".$entry, $this->extensions );
					if( $this->filterFolders && count( $this->extensions ) && count( $child->getTotalFiles() ) == 0 )
						continue;
					$this->folders[$entry] = $child;
				}
				else if( is_file( $this->path."/".$entry ) )
				{
					if( count( $this->extensions ) )
					{
						$info = pathinfo( $entry );
						if( isset( $info['extension'] ) && !in_array( $info['extension'], $this->extensions ) )
							continue;
					}
					$this->files[$entry] = new File_Reader( $this->path."/".$entry );
				}
			}
		}
		closedir( $res );
	}
}
?>