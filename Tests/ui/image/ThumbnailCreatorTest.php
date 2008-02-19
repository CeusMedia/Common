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
	protected $assertFile	= "Tests/ui/image/assertThumbnail.png";
	protected $sourceFile	= "Tests/ui/image/sourceThumbnail.png";
	protected $targetFile	= "Tests/ui/image/targetThumbnail.png";
	

	public function testThumbize()
	{
		if( file_exists( $this->targetFile ) )
			unlink( $this->targetFile );
		
		$creator	= new UI_Image_ThumbnailCreator( $this->sourceFile, $this->targetFile );
		$creator->thumbize( 16, 16 );

		$assertion	= true;
		$creation	= file_exists( $this->targetFile );
		$this->assertEquals( $assertion, $creation );

		$assertion	= file_get_contents( $this->assertFile );
		$creation	= file_get_contents( $this->targetFile );
		$this->assertEquals( $assertion, $creation );
	}

	public function testThumbizeByLimit()
	{
		if( file_exists( $this->targetFile ) )
			unlink( $this->targetFile );
		
		$creator	= new UI_Image_ThumbnailCreator( $this->sourceFile, $this->targetFile );
		$creator->thumbizeByLimit( 100, 16 );

		$assertion	= true;
		$creation	= file_exists( $this->targetFile );
		$this->assertEquals( $assertion, $creation );

		$assertion	= file_get_contents( $this->assertFile );
		$creation	= file_get_contents( $this->targetFile );
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