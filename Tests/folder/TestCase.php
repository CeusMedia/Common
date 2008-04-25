<?php
/**
 *	TestUnit of Folder Editor.
 *	@package		Tests.folder
 *	@extends		PHPUnit_Framework_TestCase
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			21.04.2008
 *	@version		0.1
 */
require_once( 'PHPUnit/Framework/TestCase.php' ); 
require_once( 'Tests/initLoaders.php5' );
/**
 *	TestUnit of Folder Editor.
 *	@package		Tests.folder
 *	@extends		PHPUnit_Framework_TestCase
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			21.04.2008
 *	@version		0.1
 */
class Tests_Folder_TestCase extends PHPUnit_Framework_TestCase
{
	/**
	 *	Constructor, creates File Structure.
	 *	@access		public
	 *	@return		void
	 */
	public function __construct()
	{
		$this->_path		= $path	= dirname( __FILE__ )."/";
		
		@mkDir( $path."folder" );
		@mkDir( $path."folder/.hidden" );
		@mkDir( $path."folder/sub1" );
		@mkDir( $path."folder/sub2" );
		@mkDir( $path."folder/sub1/sub1sub1" );
		@mkDir( $path."folder/sub1/sub1sub2" );
		@mkDir( $path."folder/sub2" );
		@mkDir( $path."folder/sub2/sub2sub1" );
		@file_put_contents( $path."folder/file1.txt", "test" );
		@file_put_contents( $path."folder/sub1/file1_1.txt", "test" );
		@file_put_contents( $path."folder/sub1/file1_2.txt", "test" );
		@file_put_contents( $path."folder/sub1/sub1sub1/file1_1_1.txt", "test" );
		@file_put_contents( $path."folder/sub1/sub1sub1/file1_1_2.txt", "test" );
		@file_put_contents( $path."folder/sub1/sub1sub2/file1_2_1.txt", "test" );
		@file_put_contents( $path."folder/sub1/sub1sub2/file1_2_2.txt", "test" );
		@file_put_contents( $path."folder/sub2/file2_1.txt", "test" );
		@file_put_contents( $path."folder/sub2/sub2sub1/file2_1_1.txt", "test" );
	}
	
	/**
	 *	Destructor, removes File Structure.
	 *	@access		public
	 *	@return		void
	 */
	public function __destruct()
	{
		if( file_exists( $this->_path."folder" ) )
			$this->removeFolder( $this->_path."folder", true );
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