<?php
/** @noinspection PhpIllegalPsrClassPathInspection */
/** @noinspection PhpMultipleClassDeclarationsInspection */
/** @noinspection PhpUnhandledExceptionInspection */
/** @noinspection PhpDocMissingThrowsInspection */

declare( strict_types = 1 );

/**
 *	TestUnit of Thumbnail Creator.
 *	@package		Tests.ui.image
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 */

namespace CeusMedia\CommonTest\UI\Image;

use CeusMedia\Common\UI\Image\ThumbnailCreator;
use CeusMedia\CommonTest\BaseCase;

/**
 *	TestUnit of Thumbnail Creator.
 *	@package		Tests.ui.image
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 */
class ThumbnailCreatorTest extends BaseCase
{
	protected $assertFile;
	protected $sourceFile;
	protected $targetFile;

	/** @var string  */
	protected $path;

	public function setUp(): void
	{
		if( !extension_loaded( 'gd' ) )
			$this->markTestSkipped( 'Missing gd support' );

		$this->path	= dirname( __FILE__ )."/assets/";
		$this->assertFile	= $this->path."assertThumbnail.png";
		$this->sourceFile	= $this->path."sourceThumbnail.png";
		$this->targetFile	= $this->path."targetThumbnail.png";
	}

	public function tearDown(): void
	{
		@unlink( $this->path."targetThumbnail.gif" );
		@unlink( $this->path."targetThumbnail.png" );
		@unlink( $this->path."targetThumbnail.jpg" );
	}

	public function testThumbizeGif()
	{
		$this->markTestSkipped( 'No image tests.' );
		$assertFile	= $this->path."assertThumbnail.gif";
		$sourceFile	= $this->path."sourceThumbnail.gif";
		$targetFile	= $this->path."targetThumbnail.gif";

		if( file_exists( $targetFile ) )
			unlink( $targetFile );

		$creator	= new ThumbnailCreator( $sourceFile, $targetFile );
		$creator->thumbize( 16, 16 );

		$assertion	= true;
		$creation	= file_exists( $targetFile );
		$this->assertEquals( $assertion, $creation );

		$file		= new FS_File_Reader( $assertFile );
		$this->assertTrue( $file->equals( $targetFile ) );
	}

	/**
	 * @todo	remove in 0.7.7
	 */
	public function testThumbizeJpg()
	{
		$this->markTestSkipped( 'No image tests.' );
		$assertFile	= $this->path."assertThumbnail.jpg";
		$sourceFile	= $this->path."sourceThumbnail.jpg";
		$targetFile	= $this->path."targetThumbnail.jpg";

		if( file_exists( $targetFile ) )
			unlink( $targetFile );

		$creator	= new ThumbnailCreator( $sourceFile, $targetFile );
		$creator->thumbize( 16, 16 );

		$assertion	= true;
		$creation	= file_exists( $targetFile );
		$this->assertEquals( $assertion, $creation );

		$file		= new FS_File_Reader( $assertFile );
		$this->assertTrue( $file->equals( $targetFile ) );
	}

	public function testThumbizePng()
	{
		$this->markTestSkipped( 'No image tests.' );
		$assertFile	= $this->path."assertThumbnail.png";
		$sourceFile	= $this->path."sourceThumbnail.png";
		$targetFile	= $this->path."targetThumbnail.png";

		if( file_exists( $targetFile ) )
			unlink( $targetFile );

		$creator	= new ThumbnailCreator( $sourceFile, $targetFile );
		$creator->thumbize( 16, 16 );

		$assertion	= true;
		$creation	= file_exists( $targetFile );
		$this->assertEquals( $assertion, $creation );

		$image	= imagecreatefrompng( $this->targetFile );
		$this->assertEquals( 16, imagesx( $image ) );
		$this->assertEquals( 16, imagesy( $image ) );
	}

	public function testThumbizeByLimit()
	{
		$this->markTestSkipped( 'No image tests.' );
		if( file_exists( $this->targetFile ) )
			unlink( $this->targetFile );

		$creator	= new ThumbnailCreator( $this->sourceFile, $this->targetFile );
		$creator->thumbizeByLimit( 100, 16 );

		$assertion	= true;
		$creation	= file_exists( $this->targetFile );
		$this->assertEquals( $assertion, $creation );

		$image	= imagecreatefrompng( $this->targetFile );
		$this->assertEquals( 16, imagesx( $image ) );
		$this->assertEquals( 16, imagesy( $image ) );
	}

	public function testThumbizeExceptions()
	{
		$this->expectException( 'Exception' );
		$creator	= new ThumbnailCreator( __FILE__, "notexisting.txt" );
	}
}
