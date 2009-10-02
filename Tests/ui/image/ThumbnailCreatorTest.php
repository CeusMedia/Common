<?php
/**
 *	TestUnit of Thumbnail Creator.
 *	@package		Tests.ui.image
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			16.02.2008
 *	@version		0.1
 */
require_once( 'PHPUnit/Framework/TestCase.php' ); 
require_once( 'Tests/initLoaders.php5' );
import( 'de.ceus-media.ui.image.ThumbnailCreator' );
import( 'de.ceus-media.file.Reader' );
/**
 *	TestUnit of Thumbnail Creator.
 *	@package		Tests.ui.image
 *	@extends		PHPUnit_Framework_TestCase
 *	@uses			UI_Image_ThumbnailCreator
 *	@uses			File_Reader
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			16.02.2008
 *	@version		0.1
 */
class Tests_UI_Image_ThumbnailCreatorTest extends PHPUnit_Framework_TestCase
{
	protected $assertFile;
	protected $sourceFile;
	protected $targetFile;

	public function __construct()
	{
		$this->path	= dirname( __FILE__ )."/";
		$this->assertFile	= $this->path."assertThumbnail.png";
		$this->sourceFile	= $this->path."sourceThumbnail.png";
		$this->targetFile	= $this->path."targetThumbnail.png";	
	}
	
	public function tearDown()
	{
		@unlink( $this->path."targetThumbnail.gif" );
		@unlink( $this->path."targetThumbnail.png" );
		@unlink( $this->path."targetThumbnail.jpg" );
	}

	public function testThumbizeGif()
	{
		$assertFile	= $this->path."assertThumbnail.gif";
		$sourceFile	= $this->path."sourceThumbnail.gif";
		$targetFile	= $this->path."targetThumbnail.gif";

		if( file_exists( $targetFile ) )
			unlink( $targetFile );
		
		$creator	= new UI_Image_ThumbnailCreator( $sourceFile, $targetFile );
		$creator->thumbize( 16, 16 );

		$assertion	= true;
		$creation	= file_exists( $targetFile );
		$this->assertEquals( $assertion, $creation );

		$file		= new File_Reader( $assertFile );
		$this->assertTrue( $file->equals( $targetFile ) );
	}

	public function testThumbizeJpg()
	{
		$assertFile	= $this->path."assertThumbnail.jpg";
		$sourceFile	= $this->path."sourceThumbnail.jpg";
		$targetFile	= $this->path."targetThumbnail.jpg";

		if( file_exists( $targetFile ) )
			unlink( $targetFile );
		
		$creator	= new UI_Image_ThumbnailCreator( $sourceFile, $targetFile );
		$creator->thumbize( 16, 16 );

		$assertion	= true;
		$creation	= file_exists( $targetFile );
		$this->assertEquals( $assertion, $creation );

		$file		= new File_Reader( $assertFile );
		$this->assertTrue( $file->equals( $targetFile ) );
	}

	public function testThumbizePng()
	{
		$assertFile	= $this->path."assertThumbnail.png";
		$sourceFile	= $this->path."sourceThumbnail.png";
		$targetFile	= $this->path."targetThumbnail.png";

		if( file_exists( $targetFile ) )
			unlink( $targetFile );
		
		$creator	= new UI_Image_ThumbnailCreator( $sourceFile, $targetFile );
		$creator->thumbize( 16, 16 );

		$assertion	= true;
		$creation	= file_exists( $targetFile );
		$this->assertEquals( $assertion, $creation );

		$file		= new File_Reader( $assertFile );
		$this->assertTrue( $file->equals( $targetFile ) );
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

		$file		= new File_Reader( $this->assertFile );
		$this->assertTrue( $file->equals( $this->targetFile ) );
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