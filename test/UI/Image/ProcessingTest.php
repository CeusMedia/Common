<?php
/** @noinspection PhpMultipleClassDeclarationsInspection */

declare( strict_types = 1 );

namespace CeusMedia\CommonTest\UI\Image;

use CeusMedia\Common\UI\Image;
use CeusMedia\Common\UI\Image\Drawer;
use CeusMedia\Common\UI\Image\Histogram;
use CeusMedia\Common\UI\Image\Processing;
use CeusMedia\Common\UI\Image\TransparentWatermark;
use CeusMedia\CommonTest\BaseCase;
use InvalidArgumentException;

/**
 *	TestUnit of UI\Image, Processing, Drawer, Histogram and TransparentWatermark.
 *	@package		Tests.UI.Image
 */
class ProcessingTest extends BaseCase
{
	public function setUp(): void
	{
		if( !extension_loaded( 'gd' ) )
			$this->markTestSkipped( 'Missing gd support' );
	}

	public function testScaleByFraction(): void
	{
		$image	= new Image();
		$image->create( 100, 60 );
		$processing	= new Processing( $image );
		self::assertTrue( $processing->scale( 0.5 ) );
		self::assertSame( 50, $image->getWidth() );
		self::assertSame( 30, $image->getHeight() );
		self::assertTrue( $processing->scale( 2, 1.5 ) );
		self::assertSame( [100, 45], [$image->getWidth(), $image->getHeight()] );
	}

	public function testRotateWithBackgroundColor(): void
	{
		$image	= new Image();
		$image->create( 10, 20 );
		( new Processing( $image ) )->rotate( 45, 0xFF0000 );
		self::assertSame( 0xFF0000, imagecolorat( $image->getResource(), 0, 0 ) );
	}

	public function testSetType(): void
	{
		$image	= new Image();
		self::assertNull( $image->getFileName() );
		$image->setType( IMAGETYPE_JPEG );
		self::assertSame( 'image/jpeg', $image->getMimeType() );
		$this->expectException( InvalidArgumentException::class );
		$image->setType( IMAGETYPE_BMP );
	}

	public function testDrawerColorZero(): void
	{
		$palette	= imagecreate( 2, 2 );
		self::assertSame( 0, ( new Drawer( $palette ) )->getColor( 10, 20, 30 ) );
	}

	public function testHistogramOfPaletteImage(): void
	{
		$image	= new Image();
		$image->create( 2, 1, FALSE );
		$resource	= $image->getResource();
		imagecolorallocate( $resource, 0, 0, 0 );
		$white	= imagecolorallocate( $resource, 255, 255, 255 );
		imagesetpixel( $resource, 1, 0, $white );
		$data	= Histogram::getData( $image );
		self::assertSame( 1, $data['r'][255] );
		self::assertSame( 1, $data['r'][0] );
	}

	public function testWatermarkWithoutStamp(): void
	{
		$watermark	= new TransparentWatermark();
		self::assertNotNull( $watermark->getLastError() );
		self::assertFalse( $watermark->markImage( imagecreatetruecolor( 2, 2 ) ) );
	}
}
