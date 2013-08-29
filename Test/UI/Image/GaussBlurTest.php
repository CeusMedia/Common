<?php
/**
 *	TestUnit of Gauss Blur.
 *	@package		Tests.ui.image
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			16.02.2008
 *	@version		0.1
 */
require_once 'PHPUnit/Framework/TestCase.php';
require_once 'Test/initLoaders.php5';
/**
 *	TestUnit of Gauss Blur.
 *	@package		Tests.ui.image
 *	@extends		PHPUnit_Framework_TestCase
 *	@uses			UI_Image_GaussBlur
 *	@uses			File_Reader
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			16.02.2008
 *	@version		0.1
 */
class Test_UI_Image_GaussBlurTest extends PHPUnit_Framework_TestCase
{
	public function __construct()
	{
		$this->path	= dirname( __FILE__ )."/";
	}
	
	public function tearDown()
	{
		@unlink( $this->path."targetGauss.gif" );
		@unlink( $this->path."targetGauss.png" );
		@unlink( $this->path."targetGauss.jpg" );
	}

	public function testBlurGif()
	{
		$assertFile	= $this->path."assertGauss.gif";
		$sourceFile	= $this->path."sourceGauss.gif";
		$targetFile	= $this->path."targetGauss.gif";
		if( file_exists( $targetFile ) )
			unlink( $targetFile );
		
		$creator	= new UI_Image_GaussBlur( $sourceFile, $targetFile );
		$creator->blur();

		$assertion	= true;
		$creation	= file_exists( $targetFile );
		$this->assertEquals( $assertion, $creation );

		$file		= new File_Reader( $assertFile );
		$this->assertTrue( $file->equals( $targetFile ) );
	}

	public function testBlurJpg()
	{
		$assertFile	= $this->path."assertGauss.jpg";
		$sourceFile	= $this->path."sourceGauss.jpg";
		$targetFile	= $this->path."targetGauss.jpg";
		if( file_exists( $targetFile ) )
			unlink( $targetFile );
		
		$creator	= new UI_Image_GaussBlur( $sourceFile, $targetFile );
		$creator->blur();

		$assertion	= true;
		$creation	= file_exists( $targetFile );
		$this->assertEquals( $assertion, $creation );

#		$file		= new File_Reader( $assertFile );
#		$this->assertTrue( $file->equals( $targetFile ) );
	}

	public function testBlurPng()
	{
		$assertFile	= $this->path."assertGauss.png";
		$sourceFile	= $this->path."sourceGauss.png";
		$targetFile	= $this->path."targetGauss.png";
		if( file_exists( $targetFile ) )
			unlink( $targetFile );
		
		$creator	= new UI_Image_GaussBlur( $sourceFile, $targetFile );
		$creator->blur();

		$assertion	= true;
		$creation	= file_exists( $targetFile );
		$this->assertEquals( $assertion, $creation );

		$file		= new File_Reader( $assertFile );
		$this->assertTrue( $file->equals( $targetFile ) );
	}

	public function testBlurExceptions()
	{
		try
		{
			$creator	= new UI_Image_GaussBlur( __FILE__, "notexisting.txt" );
			$this->fail( 'An expected Exception has not been thrown.' );
		}
		catch( Exception $e )
		{
		}
	}
}
?>