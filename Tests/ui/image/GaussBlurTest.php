<?php
/**
 *	TestUnit of Gauss Blur.
 *	@package		Tests.ui.image
 *	@extends		PHPUnit_Framework_TestCase
 *	@uses			UI_Image_GaussBlur
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			16.02.2008
 *	@version		0.1
 */
require_once( 'PHPUnit/Framework/TestCase.php' ); 
require_once( 'Tests/initLoaders.php5' );
import( 'de.ceus-media.ui.image.GaussBlur' );
/**
 *	TestUnit of Gauss Blur.
 *	@package		Tests.ui.image
 *	@extends		PHPUnit_Framework_TestCase
 *	@uses			UI_Image_GaussBlur
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			16.02.2008
 *	@version		0.1
 */
class Tests_UI_Image_GaussBlurTest extends PHPUnit_Framework_TestCase
{
	public function testBlur()
	{
		$assertFile	= "Tests/ui/image/assertGauss.png";
		$sourceFile	= "Tests/ui/image/sourceGauss.png";
		$targetFile	= "Tests/ui/image/targetGauss.png";
		if( file_exists( $targetFile ) )
			unlink( $targetFile );
		
		$creator	= new UI_Image_GaussBlur( $sourceFile, $targetFile );
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
			$creator	= new UI_Image_GaussBlur( __FILE__, "notexisting.txt" );
			$this->fail( 'An expected Exception has not been thrown.' );
		}
		catch( Exception $e )
		{
		}
	}
}
?>