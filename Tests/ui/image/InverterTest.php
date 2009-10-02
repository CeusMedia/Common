<?php
/**
 *	TestUnit of Inverter.
 *	@package		Tests.ui.image
 *	@extends		PHPUnit_Framework_TestCase
 *	@uses			UI_Image_Inverter
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			16.02.2008
 *	@version		0.1
 */
require_once( 'PHPUnit/Framework/TestCase.php' ); 
require_once( 'Tests/initLoaders.php5' );
import( 'de.ceus-media.ui.image.Inverter' );
import( 'de.ceus-media.file.Reader' );
/**
 *	TestUnit of Inverter.
 *	@package		Tests.ui.image
 *	@extends		PHPUnit_Framework_TestCase
 *	@uses			UI_Image_Inverter
 *	@uses			File_Reader
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			16.02.2008
 *	@version		0.1
 */
class Tests_UI_Image_InverterTest extends PHPUnit_Framework_TestCase
{
	public function __construct()
	{
		$this->path	= dirname( __FILE__ )."/";
	}
	
	public function tearDown()
	{
		@unlink( $this->path."targetInverter.gif" );
		@unlink( $this->path."targetInverter.png" );
		@unlink( $this->path."targetInverter.jpg" );
	}

	public function testInvertGif()
	{
		$assertFile	= $this->path."assertInverter.gif";
		$sourceFile	= $this->path."sourceInverter.gif";
		$targetFile	= $this->path."targetInverter.gif";
		if( file_exists( $targetFile ) )
			unlink( $targetFile );
		
		$creator	= new UI_Image_Inverter( $sourceFile, $targetFile );
		$creator->invert();

		$assertion	= true;
		$creation	= file_exists( $targetFile );
		$this->assertEquals( $assertion, $creation );

		$file		= new File_Reader( $assertFile );
		$this->assertTrue( $file->equals( $targetFile ) );
	}

	public function testInvertJpg()
	{
		$assertFile	= $this->path."assertInverter.jpg";
		$sourceFile	= $this->path."sourceInverter.jpg";
		$targetFile	= $this->path."targetInverter.jpg";
		if( file_exists( $targetFile ) )
			unlink( $targetFile );
		
		$creator	= new UI_Image_Inverter( $sourceFile, $targetFile );
		$creator->invert();

		$assertion	= true;
		$creation	= file_exists( $targetFile );
		$this->assertEquals( $assertion, $creation );

		$file		= new File_Reader( $assertFile );
		$this->assertTrue( $file->equals( $targetFile ) );
	}

	public function testInvertPng()
	{
		$assertFile	= $this->path."assertInverter.png";
		$sourceFile	= $this->path."sourceInverter.png";
		$targetFile	= $this->path."targetInverter.png";
		if( file_exists( $targetFile ) )
			unlink( $targetFile );
		
		$creator	= new UI_Image_Inverter( $sourceFile, $targetFile );
		$creator->invert();

		$assertion	= true;
		$creation	= file_exists( $targetFile );
		$this->assertEquals( $assertion, $creation );

		$file		= new File_Reader( $assertFile );
		$this->assertTrue( $file->equals( $targetFile ) );
	}

	public function testInvertExceptions()
	{
		try
		{
			$creator	= new UI_Image_Inverter( __FILE__, "notexisting.txt" );
			$this->fail( 'An expected Exception has not been thrown.' );
		}
		catch( Exception $e )
		{
		}
	}
}
?>