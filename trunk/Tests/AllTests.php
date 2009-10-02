<?php
if( !defined('PHPUnit_MAIN_METHOD' ) )
	define( 'PHPUnit_MAIN_METHOD', 'Tests_AllTests::main' );

error_reporting( E_ALL ^ E_NOTICE );

require_once( 'PHPUnit/Framework/TestSuite.php' );
require_once( 'PHPUnit/TextUI/TestRunner.php' );
require_once( 'Tests/adt/AllTests.php' );
require_once( 'Tests/alg/AllTests.php' );
require_once( 'Tests/console/AllTests.php' );
require_once( 'Tests/database/AllTests.php' );
require_once( 'Tests/file/AllTests.php' );
require_once( 'Tests/folder/AllTests.php' );
require_once( 'Tests/framework/AllTests.php' );
require_once( 'Tests/math/AllTests.php' );
require_once( 'Tests/net/AllTests.php' );
require_once( 'Tests/ui/AllTests.php' );
require_once( 'Tests/xml/AllTests.php' );

PHPUnit_Util_Filter::addDirectoryToFilter( dirname( __FILE__ ) );

class Tests_AllTests
{
	public static function main()
	{
		PHPUnit_TextUI_TestRunner::run( self::suite() );
	}

	public static function suite()
	{
		$count	= 0;
		$size	= 0;
		echo "loading all classes\n";
		self::requireClassesRecursive( "de/", $count, $size ); 
		echo "\nloaded ".$count." classes (".Alg_UnitFormater::formatBytes( $size ).")\n\n";

		$suite = new PHPUnit_Framework_TestSuite( 'cmClasses' );
		$suite->addTest( Tests_ADT_AllTests::suite() );
		$suite->addTest( Tests_Alg_AllTests::suite() );
		$suite->addTest( Tests_Console_AllTests::suite() );
		$suite->addTest( Tests_Database_AllTests::suite() );
		$suite->addTest( Tests_File_AllTests::suite() );
		$suite->addTest( Tests_Folder_AllTests::suite() );
		$suite->addTest( Tests_Framework_AllTests::suite() );
		$suite->addTest( Tests_Math_AllTests::suite() );
		$suite->addTest( Tests_Net_AllTests::suite() );
		$suite->addTest( Tests_UI_AllTests::suite() );
		$suite->addTest( Tests_XML_AllTests::suite() );
		return $suite;
	}

	protected static function requireClassesRecursive( $path, &$count , &$size )
	{
		$index	= new DirectoryIterator( $path );
		foreach( $index as $entry )
		{
			if( $entry->isDot() )
				continue;
			if( $entry->getFilename() == ".svn" )
				continue;
			if( $entry->isDir() )
			{
	#			echo "Path: ".$entry->getPath()."\n";
				self::requireClassesRecursive( $entry->getPathname(), $count, $size );
			}
			else if( $entry->isFile() )
			{
				$info	= pathinfo( $entry->getPathname() );
				if( $info['extension'] !== "php5" )
					continue;
				if( !preg_match( '/^[A-Z]/', $info['basename'] ) )
					continue;
				if( preg_match( '/hydrogen/', $entry->getPathname() )  )
					continue;
	#			echo "File: ".$entry->getPathname()."\n"; 	
				echo ".";
				$count++;
				$size+=filesize( $entry->getPathname() );
				$fileName	= $entry->getPathname();
				require_once( $entry->getPathname() );
			}
		}
	}	
}

if( PHPUnit_MAIN_METHOD == 'Tests_AllTests::main' )
	Tests_AllTests::main();
?>
