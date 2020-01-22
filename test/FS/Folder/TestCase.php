<?php
/**
 *	TestUnit of Folder Editor.
 *	@package		Tests.folder
 *	@since			21.04.2008
 *	@version		0.1
 */
declare( strict_types = 1 );

use PHPUnit\Framework\TestCase;

/**
 *	TestUnit of Folder Editor.
 *	@package		Tests.folder
 *	@extends		Test_Case
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@since			21.04.2008
 *	@version		0.1
 *
 *	This Class creates and removed this File Structure:
 *	# folder
 *	  ° file1
 *	  ° file2
 *	  ° .file3
 *	  # sub1
 *	    ° file1_1
 *	    ° file1_2
 *	    # sub1sub1
 *	      ° file1_1_1
 *	      ° file1_1_2
 *	    # sub1sub2
 *	      ° file1_2_1
 *	      ° file1_2_2
 *	  # sub2
 *	    ° file2_1
 *	    ° .file2_2
 *	    # sub2sub1
 *	      ° file2_1_1
 *	      ° .file2_1_2
 *	    # .sub2sub2
 *	      ° file2_2_1
 *	      ° .file2_2_2
 *	  # .sub3
 *	    ° file3_1
 *	    ° .file3_2
 *	    # sub3sub1
 *	      ° file3_1_1
 *	      ° .file3_1_2
 *	    # .sub3sub2
 *	      ° file3_2_1
 *	      ° .file3_2_2
 */
class Test_FS_Folder_TestCase extends Test_Case
{

	public function __construct()
	{
		parent::__construct();
		$this->path		= dirname( __FILE__ )."/";
		$this->folder	= $this->path."folder/";
	}

	/**
	 *	Setup for every Test.
	 *	@access		public
	 *	@return		void
	 */
	public function setUp()
	{
		@mkDir( $this->folder );
		@mkDir( $this->folder."sub1" );
		@mkDir( $this->folder."sub1/sub1sub1" );
		@mkDir( $this->folder."sub1/sub1sub2" );
		@mkDir( $this->folder."sub2" );
		@mkDir( $this->folder."sub2/sub2sub1" );
		@mkDir( $this->folder."sub2/.sub2sub2" );
		@mkDir( $this->folder.".sub3" );
		@mkDir( $this->folder.".sub3/sub3sub1" );
		@mkDir( $this->folder.".sub3/.sub3sub2" );
		@file_put_contents( $this->folder."file1.txt", "test" );
		@file_put_contents( $this->folder."file2.txt", "test" );
		@file_put_contents( $this->folder.".file3.txt", "test" );
		@file_put_contents( $this->folder."sub1/file1_1.txt", "test" );
		@file_put_contents( $this->folder."sub1/file1_2.txt", "test" );
		@file_put_contents( $this->folder."sub1/sub1sub1/file1_1_1.txt", "test" );
		@file_put_contents( $this->folder."sub1/sub1sub1/file1_1_2.txt", "test" );
		@file_put_contents( $this->folder."sub1/sub1sub2/file1_2_1.txt", "test" );
		@file_put_contents( $this->folder."sub1/sub1sub2/file1_2_2.txt", "test" );
		@file_put_contents( $this->folder."sub2/file2_1.txt", "test" );
		@file_put_contents( $this->folder."sub2/.file2_2.txt", "test" );
		@file_put_contents( $this->folder."sub2/sub2sub1/file2_1_1.txt", "test" );
		@file_put_contents( $this->folder."sub2/sub2sub1/.file2_1_2.txt", "test" );
		@file_put_contents( $this->folder."sub2/.sub2sub2/file2_2_1.txt", "test" );
		@file_put_contents( $this->folder."sub2/.sub2sub2/.file2_2_2.txt", "test" );
		@file_put_contents( $this->folder.".sub3/file3_1.txt", "test" );
		@file_put_contents( $this->folder.".sub3/.file3_2.txt", "test" );
		@file_put_contents( $this->folder.".sub3/sub3sub1/file3_1_1.txt", "test" );
		@file_put_contents( $this->folder.".sub3/sub3sub1/.file3_1_2.txt", "test" );
		@file_put_contents( $this->folder.".sub3/.sub3sub2/file3_2_1.txt", "test" );
		@file_put_contents( $this->folder.".sub3/.sub3sub2/.file3_2_2.txt", "test" );
	}

	/**
	 *	Cleanup after every Test.
	 *	@access		public
	 *	@return		void
	 */
	public function tearDown()
	{
		if( file_exists( $this->folder ) )
			$this->removeFolder( $this->folder, true );
	}

	/**
	 *	Returns Array of plain File and Folder Lists from Directory Iterator or Filter Iterator.
	 *	@access		private
	 *	@return		array
	 */
	protected function getListFromIndex( $index )
	{
		$folders	= array();
		$files		= array();
		foreach( $index as $entry )
		{
			if( $entry->getFilename() == "." || $entry->getFilename() == ".." )
				continue;
			$name	= $entry->getFilename();
			if( $entry->isDir() )
				$folders[]	= $name;
			else if( $entry->isFile() )
				$files[]	= $name;
		}
		return array(
			'folders'	=> $folders,
			'files'		=> $files,
		);
	}
	/**
	 *	Removes Folders and Files recursive and returns number of removed Objects.
	 *	@access		protected
	 *	@param		string		$path			Path of Folder to remove
	 *	@param		bool		$force			Flag: force to remove nested Files and Folders
	 *	@return		int
	 */
	protected static function removeFolder( $path, $force = false )
	{
		$list	= array();
		$path	= str_replace( "\\", "/", $path );
		//  index Folder
		$dir	= dir( $path );
		//  iterate Objects
		while( $entry = $dir->read() )
		{
			//  if is Dot Object
			if( preg_match( "@^(\.){1,2}$@", $entry ) )
				//  continue
				continue;
			if( !$force )
				throw new Exception( 'Folder '.$path.' is not empty. See Option "force".' );
			//  is nested File
			if( is_file( $path."/".$entry ) )
				//  remove File
				@unlink( $path."/".$entry );
			//  is nested Folder
			if( is_dir( $path."/".$entry ) )
				$list[]	= $path."/".$entry;
		}
		$dir->close();
		foreach( $list as $folder )
			//  call Method with nested Folder
			self::removeFolder( $folder, $force );

		@rmDir( $path );
	}
}
