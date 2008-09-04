<?php
/**
 *	TestUnit of UI_Image_Printer.
 *	@package		Tests.ui.image
 *	@extends		PHPUnit_Framework_TestCase
 *	@uses			UI_Image_Printer
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			16.06.2008
 *	@version		0.1
 */
require_once( 'PHPUnit/Framework/TestCase.php' ); 
require_once( 'Tests/initLoaders.php5' );
import( 'de.ceus-media.ui.image.Printer' );
/**
 *	TestUnit of Inverter.
 *	@package		Tests.ui.image
 *	@extends		PHPUnit_Framework_TestCase
 *	@uses			UI_Image_Printer
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			16.06.2008
 *	@version		0.1
 */
class Tests_UI_Image_PrinterTest extends PHPUnit_Framework_TestCase
{
	public function __construct()
	{
		$this->path	= dirname( __FILE__ )."/";
	}
	
	public function tearDown()
	{
		 @unlink( $this->path."targetPrinter.png" ); 
		 @unlink( $this->path."targetPrinter.jpg" ); 
		 @unlink( $this->path."targetPrinter.gif" ); 
	}
	
	public function testConstructException()
	{
		$this->setExpectedException( 'InvalidArgumentException' );
		new UI_Image_Printer( "not_a_resource" );
	}
		
	public function testShowPng()
	{
		$resource	= imagecreatefrompng( $this->path."sourceCreator.png" );
		$printer	= new UI_Image_Printer( $resource );
		
		ob_start();
		$printer->show( UI_Image_Printer::TYPE_PNG, 0, FALSE );
		$creation	= ob_get_clean();
				
		$assertion	= TRUE;
		$creation	= file_get_contents( $this->path."sourceCreator.png" );
		$this->assertEquals( $assertion, $creation );
	}
		
	public function testShowJpeg()
	{
		$resource	= imagecreatefromjpeg( $this->path."sourceCreator.jpg" );
		$printer	= new UI_Image_Printer( $resource );
		
		ob_start();
		$printer->show( UI_Image_Printer::TYPE_JPEG, 100, FALSE );
		$creation	= ob_get_clean();
				
		$assertion	= TRUE;
		$creation	= file_get_contents( $this->path."sourceCreator.jpg" );
		$this->assertEquals( $assertion, $creation );
	}
		
	public function testShowGif()
	{
		$resource	= imagecreatefromgif( $this->path."sourceCreator.gif" );
		$printer	= new UI_Image_Printer( $resource );
		
		ob_start();
		$printer->show( UI_Image_Printer::TYPE_GIF, 0, FALSE );
		$creation	= ob_get_clean();
				
		$assertion	= TRUE;
		$creation	= file_get_contents( $this->path."sourceCreator.gif" );
		$this->assertEquals( $assertion, $creation );
	}
		
	public function testShowException()
	{
		$this->setExpectedException( 'InvalidArgumentException' );
		$resource	= imagecreatefrompng( $this->path."sourceCreator.png" );
		$printer	= new UI_Image_Printer( $resource );
		$printer->show( 15, 0 );
	}

	public function testSavePng()
	{
		$resource	= imagecreatefrompng( $this->path."sourceCreator.png" );
		$printer	= new UI_Image_Printer( $resource );
		$printer->save( $this->path."targetPrinter.png", UI_Image_Printer::TYPE_PNG, 0 );
				
		$assertion	= file_get_contents( $this->path."sourceCreator.png" );
		$creation	= file_get_contents( $this->path."targetPrinter.png" );
		$this->assertEquals( $assertion, $creation );
	}
		
	public function testSaveJpeg()
	{
		$resource	= imagecreatefromjpeg( $this->path."sourceCreator.jpg" );
		$printer	= new UI_Image_Printer( $resource );
		$printer->save( $this->path."targetPrinter.jpg", UI_Image_Printer::TYPE_JPEG, 100 );
				
		$assertion	= TRUE;
		$creation	= file_exists( $this->path."targetPrinter.jpg" );
		$this->assertEquals( $assertion, $creation );
	}
		
	public function testSaveGif()
	{
		$resource	= imagecreatefromgif( $this->path."sourceCreator.gif" );
		$printer	= new UI_Image_Printer( $resource );
		$printer->save( $this->path."targetPrinter.gif", UI_Image_Printer::TYPE_GIF, 0 );
				
		$assertion	= file_get_contents( $this->path."sourceCreator.gif" );
		$creation	= file_get_contents( $this->path."targetPrinter.gif" );
		$this->assertEquals( $assertion, $creation );
	}

	public function testSaveException()
	{
		$this->setExpectedException( 'InvalidArgumentException' );
		$resource	= imagecreatefrompng( $this->path."sourceCreator.png" );
		$printer	= new UI_Image_Printer( $resource );
		$printer->save( $this->path."targetPrinter.png", 15, 0 );
	}
}
?>