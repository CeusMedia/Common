<?php
/**
 *	TestUnit of Thumbnail Creator.
 *	@package		Tests.ui.image
 *	@extends		PHPUnit_Framework_TestCase
 *	@uses			UI_Image_ThumbnailCreator
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			16.02.2008
 *	@version		0.1
 */
require_once( 'PHPUnit/Framework/TestCase.php' ); 
require_once( 'Tests/initLoaders.php5' );
import( 'de.ceus-media.ui.image.ThumbnailCreator' );
/**
 *	TestUnit of Thumbnail Creator.
 *	@package		Tests.ui.image
 *	@extends		PHPUnit_Framework_TestCase
 *	@uses			UI_Image_ThumbnailCreator
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			16.02.2008
 *	@version		0.1
 */
class Tests_UI_Image_ThumbnailCreatorTest extends PHPUnit_Framework_TestCase
{
	public function testThumbize()
	{
		$assertFile	= "Tests/ui/image/assertThumbnail.png";
		$sourceFile	= "Tests/ui/image/sourceThumbnail.png";
		$targetFile	= "Tests/ui/image/targetThumbnail.png";
		if( file_exists( $targetFile ) )
			unlink( $targetFile );
		
		$creator	= new UI_Image_ThumbnailCreator( $sourceFile, $targetFile );
		$creator->thumbize( 16, 16 );

		$assertion	= true;
		$creation	= file_exists( $targetFile );
		$this->assertEquals( $assertion, $creation );

		$assertion	= file_get_contents( $assertFile );
		$creation	= file_get_contents( $targetFile );
		$this->assertEquals( $assertion, $creation );
	}

	public function testThumbizeExceptions()
	{
		try
		{
			$creator	= new UI_Image_ThumbnailCreator( __FILE__, "notexisting.txt" );
			$this->fail( 'An expected Exception has not been thrown.' );
		}
		catch( Exception $e )
		{
		}
	}
}
?>