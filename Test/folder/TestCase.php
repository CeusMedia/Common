<?php
/**
 *	TestUnit of Folder Editor.
 *	@package		Tests.folder
 *	@since			21.04.2008
 *	@version		0.1
 */
require_once 'PHPUnit/Framework/TestCase.php';
/**
 *	TestUnit of Folder Editor.
 *	@package		Tests.folder
 *	@extends		PHPUnit_Framework_TestCase
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
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
class Test_Folder_TestCase extends PHPUnit_Framework_TestCase
{
	/**
	 *	Constructor, creates File Structure.
	 *	@access		public
	 *	@return		void
	 */
	public function __construct()
	{
		$this->path		= $path		= dirname( __FILE__ )."/";
		$this->folder	= $folder	= $path."folder/";
		
		@mkDir( $folder );
		@mkDir( $folder."sub1" );
		@mkDir( $folder."sub1/sub1sub1" );
		@mkDir( $folder."sub1/sub1sub2" );
		@mkDir( $folder."sub2" );
		@mkDir( $folder."sub2/sub2sub1" );
		@mkDir( $folder."sub2/.sub2sub2" );
		@mkDir( $folder.".sub3" );
		@mkDir( $folder.".sub3/sub3sub1" );
		@mkDir( $folder.".sub3/.sub3sub2" );
		@file_put_contents( $folder."file1.txt", "test" );
		@file_put_contents( $folder."file2.txt", "test" );
		@file_put_contents( $folder.".file3.txt", "test" );
		@file_put_contents( $folder."sub1/file1_1.txt", "test" );
		@file_put_contents( $folder."sub1/file1_2.txt", "test" );
		@file_put_contents( $folder."sub1/sub1sub1/file1_1_1.txt", "test" );
		@file_put_contents( $folder."sub1/sub1sub1/file1_1_2.txt", "test" );
		@file_put_contents( $folder."sub1/sub1sub2/file1_2_1.txt", "test" );
		@file_put_contents( $folder."sub1/sub1sub2/file1_2_2.txt", "test" );
		@file_put_contents( $folder."sub2/file2_1.txt", "test" );
		@file_put_contents( $folder."sub2/.file2_2.txt", "test" );
		@file_put_contents( $folder."sub2/sub2sub1/file2_1_1.txt", "test" );
		@file_put_contents( $folder."sub2/sub2sub1/.file2_1_2.txt", "test" );
		@file_put_contents( $folder."sub2/.sub2sub2/file2_2_1.txt", "test" );
		@file_put_contents( $folder."sub2/.sub2sub2/.file2_2_2.txt", "test" );
		@file_put_contents( $folder.".sub3/file3_1.txt", "test" );
		@file_put_contents( $folder.".sub3/.file3_2.txt", "test" );
		@file_put_contents( $folder.".sub3/sub3sub1/file3_1_1.txt", "test" );
		@file_put_contents( $folder.".sub3/sub3sub1/.file3_1_2.txt", "test" );
		@file_put_contents( $folder.".sub3/.sub3sub2/file3_2_1.txt", "test" );
		@file_put_contents( $folder.".sub3/.sub3sub2/.file3_2_2.txt", "test" );
	}

	/**
	 *	Destructor, removes File Structure.
	 *	@access		public
	 *	@return		void
	 */
	public function __destruct()
	{
		if( 0 && file_exists( $this->folder ) )
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
		$dir	= dir( $path );																	//  index Folder
		while( $entry = $dir->read() )															//  iterate Objects
		{
			if( preg_match( "@^(\.){1,2}$@", $entry ) )											//  if is Dot Object
				continue;																		//  continue
			if( !$force )
				throw new Exception( 'Folder '.$path.' is not empty. See Option "force".' );
			if( is_file( $path."/".$entry ) )													//  is nested File
				@unlink( $path."/".$entry );													//  remove File
			if( is_dir( $path."/".$entry ) )													//  is nested Folder
				$list[]	= $path."/".$entry;
		}
		$dir->close();
		foreach( $list as $folder )
			self::removeFolder( $folder, $force );												//  call Method with nested Folder

		@rmDir( $path );
	}
}
?>