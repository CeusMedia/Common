<?php
/**
 *	TestUnit of T File.
 *	@package		Tests.file.arc
 *	@author			Christian W�rker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.1
 */
require_once 'PHPUnit/Framework/TestCase.php'; 
require_once 'Test/initLoaders.php5';
/**
 *	TestUnit of Tar File.
 *	@package		Tests.file.arc
 *	@extends		PHPUnit_Framework_TestCase
 *	@uses			File_Arc_Tar
 *	@author			Christian W�rker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.1
 */
class Test_File_Arc_TarTest extends PHPUnit_Framework_TestCase
{
	/**	@var	string		$fileName		URL of Archive File Name */
	private $fileName;
	
	public function __construct()
	{
		$this->path	= dirname( __FILE__ )."/";
	}
	
	public function setUp()
	{
		$this->fileName	= $this->path."test.tar";
	}

	public function tearDown()
	{
		@unlink( $this->fileName );
	}

	public function testAddFile()
	{
		$arc	= new File_Arc_Tar();
		$arc->addFile( $this->path."TarTest.php" );

		$this->assertTrue( $arc->save( $this->fileName ) > 0 );

		$assertion	= TRUE;
		$creation	= file_exists( $this->fileName );
		$this->assertEquals( $assertion, $creation );
	}
}
?>