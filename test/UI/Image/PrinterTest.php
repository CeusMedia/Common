<?php
/**
 *	TestUnit of UI_Image_Printer.
 *	@package		Tests.ui.image
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@since			16.06.2008
 *	@version		0.1
 */
require_once dirname( dirname( __DIR__ ) ).'/initLoaders.php';
/**
 *	TestUnit of Inverter.
 *	@package		Tests.ui.image
 *	@extends		Test_Case
 *	@uses			UI_Image_Printer
 *	@uses			FS_File_Reader
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@since			16.06.2008
 *	@version		0.1
 */
class Test_UI_Image_PrinterTest extends Test_Case
{
	public function __construct()
	{
		$this->path	= dirname( __FILE__ )."/";
	}

	public function setUp(){
		if( !extension_loaded( 'gd' ) )
			$this->markTestSkipped( 'Missing gd support' );
	}

	public function tearDown()
	{
		 @unlink( $this->path."targetPrinter.png" );
		 @unlink( $this->path."targetPrinter.jpg" );
		 @unlink( $this->path."targetPrinter.gif" );
	}

	public function testConstructException()
	{
		$this->expectException( 'InvalidArgumentException' );
		new UI_Image_Printer( "not_a_resource" );
	}

	public function _testShowPng()
	{
		$resource	= imagecreatefrompng( $this->path."sourceCreator.png" );
		$printer	= new UI_Image_Printer( $resource );

		ob_start();
		$printer->show( IMAGETYPE_PNG, 100, FALSE );
		$creation	= ob_get_clean();

		$assertion	= file_get_contents( $this->path."sourceCreator.png" );
		$this->assertEquals( $assertion, $creation );
	}

	public function _testShowJpeg()
	{
		$resource	= imagecreatefromjpeg( $this->path."sourceCreator.jpg" );
		$printer	= new UI_Image_Printer( $resource );

		ob_start();
		$printer->show( IMAGETYPE_JPEG, 100, FALSE );
		$creation	= ob_get_clean();

		$assertion	= file_get_contents( $this->path."sourceCreator.jpg" );
		$this->assertEquals( $assertion, $creation );
	}

	public function _testShowGif()
	{
		$resource	= imagecreatefromgif( $this->path."sourceCreator.gif" );
		$printer	= new UI_Image_Printer( $resource );

		ob_start();
		$printer->show( IMAGETYPE_GIF, 0, FALSE );
		$creation	= ob_get_clean();

		$assertion	= TRUE;
		$creation	= file_get_contents( $this->path."sourceCreator.gif" );
		$this->assertEquals( $assertion, $creation );
	}

	public function testShowException()
	{
		$this->expectException( 'InvalidArgumentException' );
		$resource	= imagecreatefrompng( $this->path."sourceCreator.png" );
		$printer	= new UI_Image_Printer( $resource );
		$printer->show( 15, 0 );
	}

	public function testSavePng()
	{
		$this->markTestSkipped( 'No image tests.' );
		$resource	= imagecreatefrompng( $this->path."sourceCreator.png" );
		$printer	= new UI_Image_Printer( $resource );
		$printer->save( $this->path."targetPrinter.png", IMAGETYPE_PNG, 0 );

		$file		= new FS_File_Reader( $this->path."targetPrinter.png" );
		$this->assertTrue( $file->equals( $this->path."sourceCreator.png" ) );
	}

	public function testSaveJpeg()
	{
		$this->markTestSkipped( 'No image tests.' );
		$resource	= imagecreatefromjpeg( $this->path."sourceCreator.jpg" );
		$printer	= new UI_Image_Printer( $resource );
		$printer->save( $this->path."targetPrinter.jpg", IMAGETYPE_JPEG, 100 );

		$assertion	= TRUE;
		$creation	= file_exists( $this->path."targetPrinter.jpg" );
		$this->assertEquals( $assertion, $creation );
	}

	public function testSaveGif()
	{
		$this->markTestSkipped( 'No image tests.' );
		$resource	= imagecreatefromgif( $this->path."sourceCreator.gif" );
		$printer	= new UI_Image_Printer( $resource );
		$printer->save( $this->path."targetPrinter.gif", IMAGETYPE_GIF, 0 );

		$file		= new FS_File_Reader( $this->path."targetPrinter.gif" );
		$this->assertTrue( $file->equals( $this->path."sourceCreator.gif" ) );
	}

	public function testSaveException()
	{
		$this->expectException( 'InvalidArgumentException' );
		$resource	= imagecreatefrompng( $this->path."sourceCreator.png" );
		$printer	= new UI_Image_Printer( $resource );
		$printer->save( $this->path."targetPrinter.png", 15, 0 );
	}
}
?>
