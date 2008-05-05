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
/**
 *	TestUnit of Inverter.
 *	@package		Tests.ui.image
 *	@extends		PHPUnit_Framework_TestCase
 *	@uses			UI_Image_Inverter
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			16.02.2008
 *	@version		0.1
 */
class Tests_UI_Image_InverterTest extends PHPUnit_Framework_TestCase
{
	public function testInvertGif()
	{
		$assertFile	= "Tests/ui/image/assertInverter.gif";
		$sourceFile	= "Tests/ui/image/sourceInverter.gif";
		$targetFile	= "Tests/ui/image/targetInverter.gif";
		if( file_exists( $targetFile ) )
			unlink( $targetFile );
		
		$creator	= new UI_Image_Inverter( $sourceFile, $targetFile );
		$creator->invert();

		$assertion	= true;
		$creation	= file_exists( $targetFile );
		$this->assertEquals( $assertion, $creation );

		$assertion	= file_get_contents( $assertFile );
		$creation	= file_get_contents( $targetFile );
		$this->assertEquals( $assertion, $creation );
	}

	public function testInvertJpg()
	{
		$assertFile	= "Tests/ui/image/assertInverter.jpg";
		$sourceFile	= "Tests/ui/image/sourceInverter.jpg";
		$targetFile	= "Tests/ui/image/targetInverter.jpg";
		if( file_exists( $targetFile ) )
			unlink( $targetFile );
		
		$creator	= new UI_Image_Inverter( $sourceFile, $targetFile );
		$creator->invert();

		$assertion	= true;
		$creation	= file_exists( $targetFile );
		$this->assertEquals( $assertion, $creation );

		$assertion	= file_get_contents( $assertFile );
		$creation	= file_get_contents( $targetFile );
		$this->assertEquals( $assertion, $creation );
	}

	public function testInvertPng()
	{
		$assertFile	= "Tests/ui/image/assertInverter.png";
		$sourceFile	= "Tests/ui/image/sourceInverter.png";
		$targetFile	= "Tests/ui/image/targetInverter.png";
		if( file_exists( $targetFile ) )
			unlink( $targetFile );
		
		$creator	= new UI_Image_Inverter( $sourceFile, $targetFile );
		$creator->invert();

		$assertion	= true;
		$creation	= file_exists( $targetFile );
		$this->assertEquals( $assertion, $creation );

		$assertion	= file_get_contents( $assertFile );
		$creation	= file_get_contents( $targetFile );
		$this->assertEquals( $assertion, $creation );
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