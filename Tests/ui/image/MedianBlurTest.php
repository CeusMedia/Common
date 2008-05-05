<?php
/**
 *	TestUnit of Median Blur.
 *	@package		Tests.ui.image
 *	@extends		PHPUnit_Framework_TestCase
 *	@uses			UI_Image_MedianBlur
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			16.02.2008
 *	@version		0.1
 */
require_once( 'PHPUnit/Framework/TestCase.php' ); 
require_once( 'Tests/initLoaders.php5' );
import( 'de.ceus-media.ui.image.MedianBlur' );
/**
 *	TestUnit of Median Blur.
 *	@package		Tests.ui.image
 *	@extends		PHPUnit_Framework_TestCase
 *	@uses			UI_Image_MedianBlur
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			16.02.2008
 *	@version		0.1
 */
class Tests_UI_Image_MedianBlurTest extends PHPUnit_Framework_TestCase
{
	public function testBlurGif()
	{
		$assertFile	= "Tests/ui/image/assertMedian.gif";
		$sourceFile	= "Tests/ui/image/sourceMedian.gif";
		$targetFile	= "Tests/ui/image/targetMedian.gif";
		if( file_exists( $targetFile ) )
			unlink( $targetFile );
		
		$creator	= new UI_Image_MedianBlur( $sourceFile, $targetFile );
		$creator->blur();

		$assertion	= true;
		$creation	= file_exists( $targetFile );
		$this->assertEquals( $assertion, $creation );

		$assertion	= file_get_contents( $assertFile );
		$creation	= file_get_contents( $targetFile );
		$this->assertEquals( $assertion, $creation );
	}

	public function testBlurJpg()
	{
		$assertFile	= "Tests/ui/image/assertMedian.jpg";
		$sourceFile	= "Tests/ui/image/sourceMedian.jpg";
		$targetFile	= "Tests/ui/image/targetMedian.jpg";
		if( file_exists( $targetFile ) )
			unlink( $targetFile );
		
		$creator	= new UI_Image_MedianBlur( $sourceFile, $targetFile );
		$creator->blur();

		$assertion	= true;
		$creation	= file_exists( $targetFile );
		$this->assertEquals( $assertion, $creation );

		$assertion	= file_get_contents( $assertFile );
		$creation	= file_get_contents( $targetFile );
		$this->assertEquals( $assertion, $creation );
	}

	public function testBlurPng()
	{
		$assertFile	= "Tests/ui/image/assertMedian.png";
		$sourceFile	= "Tests/ui/image/sourceMedian.png";
		$targetFile	= "Tests/ui/image/targetMedian.png";
		if( file_exists( $targetFile ) )
			unlink( $targetFile );
		
		$creator	= new UI_Image_MedianBlur( $sourceFile, $targetFile );
		$creator->blur();

		$assertion	= true;
		$creation	= file_exists( $targetFile );
		$this->assertEquals( $assertion, $creation );

		$assertion	= file_get_contents( $assertFile );
		$creation	= file_get_contents( $targetFile );
		$this->assertEquals( $assertion, $creation );
	}

	public function testBlurExceptions()
	{
		try
		{
			$creator	= new UI_Image_MedianBlur( __FILE__, "notexisting.txt" );
			$this->fail( 'An expected Exception has not been thrown.' );
		}
		catch( Exception $e )
		{
		}
	}
}
?>