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

use CeusMedia\Common\FS\File\Reader as FileReader;
use CeusMedia\Common\UI\Image\ThumbnailCreator;
use CeusMedia\CommonTest\BaseCase;

/**
 *	TestUnit of Thumbnail Creator.
 *	@package		Tests.ui.image
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 */
class ThumbnailCreatorTest extends BaseCase
{
	protected string $assertFile;
	protected string $sourceFile;
	protected string $targetFile;

	/** @var string  */
	protected string $path;

	public function testThumbizeGif(): void
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
		self::assertEquals( $assertion, $creation );

		$file		= new FileReader( $assertFile );
		self::assertTrue( $file->equals( $targetFile ) );
	}

	/**
	 * @todo	remove in 0.7.7
	 */
	public function testThumbizeJpg(): void
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
		self::assertEquals( $assertion, $creation );

		$file		= new FileReader( $assertFile );
		self::assertTrue( $file->equals( $targetFile ) );
	}

	public function testThumbizePng(): void
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
		self::assertEquals( $assertion, $creation );

		$image	= imagecreatefrompng( $this->targetFile );
		self::assertEquals( 16, imagesx( $image ) );
		self::assertEquals( 16, imagesy( $image ) );
	}

	public function testThumbizeByLimit(): void
	{
		$this->markTestSkipped( 'No image tests.' );
		if( file_exists( $this->targetFile ) )
			unlink( $this->targetFile );

		$creator	= new ThumbnailCreator( $this->sourceFile, $this->targetFile );
		$creator->thumbizeByLimit( 100, 16 );

		$assertion	= true;
		$creation	= file_exists( $this->targetFile );
		self::assertEquals( $assertion, $creation );

		$image	= imagecreatefrompng( $this->targetFile );
		self::assertEquals( 16, imagesx( $image ) );
		self::assertEquals( 16, imagesy( $image ) );
	}

	public function testThumbizeExceptions(): void
	{
		$this->expectException( 'Exception' );
		$creator	= new ThumbnailCreator( __FILE__, "notexisting.txt" );
	}

	protected function setUp(): void
	{
		if( !extension_loaded( 'gd' ) )
			$this->markTestSkipped( 'Missing gd support' );

		$this->path	= dirname( __FILE__ )."/assets/";
		$this->assertFile	= $this->path."assertThumbnail.png";
		$this->sourceFile	= $this->path."sourceThumbnail.png";
		$this->targetFile	= $this->path."targetThumbnail.png";
	}

	protected function tearDown(): void
	{
		@unlink( $this->path."targetThumbnail.gif" );
		@unlink( $this->path."targetThumbnail.png" );
		@unlink( $this->path."targetThumbnail.jpg" );
	}
}
