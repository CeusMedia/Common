<?php
/** @noinspection PhpIllegalPsrClassPathInspection */
/** @noinspection PhpMultipleClassDeclarationsInspection */
/** @noinspection PhpUnhandledExceptionInspection */
/** @noinspection PhpDocMissingThrowsInspection */

declare( strict_types = 1 );

/**
 *	TestUnit of UI_Image_Creator.
 *	@package		Tests.ui.image
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 */

namespace CeusMedia\CommonTest\UI\Image;

use CeusMedia\Common\FS\File\Reader as FileReader;
use CeusMedia\Common\UI\Image\Creator;
use CeusMedia\CommonTest\BaseCase;

/**
 *	TestUnit of Inverter.
 *	@package		Tests.ui.image
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 */
class CreatorTest extends BaseCase
{
	/** @var Creator  */
	protected $creator;
	protected $path;

	public function setUp(): void
	{
		if( !extension_loaded( 'gd' ) )
			$this->markTestSkipped( 'Missing gd support' );
		$this->path		= dirname( __FILE__ )."/assets/";
		$this->creator	= new Creator();
		$this->creator->loadImage( $this->path."aptana_256.png" );
		$this->tearDown();
	}

	public function tearDown(): void
	{
		@unlink( $this->path."targetCreator.jpg" );
		@unlink( $this->path."targetCreator.gif" );
		@unlink( $this->path."targetCreator.png" );
		@unlink( $this->path."targetCreator1.png" );
	}

	public function testCreate()
	{
		$this->markTestSkipped( 'No image tests.' );
		$creator	= new Creator();
		$creator->create( 100, 200 );
		imagepng( $creator->getResource(), $this->path."targetCreator1.png" );

		$image	= imagecreatefrompng( $this->path."targetCreator1.png" );
		self::assertEquals( 100, imagesx( $image ) );
		self::assertEquals( 200, imagesy( $image ) );
	}

	public function testLoadImagePng()
	{
		$this->markTestSkipped( 'No image tests.' );
		$image	= new Creator();
		$image->loadImage( $this->path."sourceCreator.png" );
		imagepng( $image->getResource(), $this->path."targetCreator.png" );

		$file		= new FileReader( $this->path."sourceCreator.png" );
		self::assertTrue( $file->equals( $this->path."targetCreator.png" ) );
	}

	public function testLoadImageJpeg()
	{
		$this->markTestSkipped( 'No image tests.' );
		$image	= new Creator();
		$image->loadImage( $this->path."sourceCreator.jpg" );

		self::assertIsResource( $image->getResource() );
	}

	public function testLoadImageGif()
	{
		$this->markTestSkipped( 'No image tests.' );
		$creator	= new Creator();
		$creator->loadImage( $this->path."sourceCreator.gif" );
		imagegif( $creator->getResource(), $this->path."targetCreator.gif" );

		$file		= new FileReader( $this->path."sourceCreator.gif" );
		self::assertTrue( $file->equals( $this->path."targetCreator.gif" ) );
	}

	public function testLoadImageException1()
	{
		$this->expectException( 'InvalidArgumentException' );
		$creator	= new Creator();
		$creator->loadImage( $this->path."not_existing.gif" );
	}

	public function testLoadImageException2()
	{
		$this->expectException( 'InvalidArgumentException' );
		$creator	= new Creator();
		$creator->loadImage( $this->path."CreatorTest.php" );
	}

	public function testGetWidth()
	{
		$assertion	= 256;
		$creation	= $this->creator->getWidth();
		self::assertEquals( $assertion, $creation );
	}

	public function testGetHeight()
	{
		$assertion	= 256;
		$creation	= $this->creator->getHeight();
		self::assertEquals( $assertion, $creation );
	}

	public function testGetType()
	{
		$assertion	= IMAGETYPE_PNG;
		$creation	= $this->creator->getType();
		self::assertEquals( $assertion, $creation );
	}

	public function test_get_returnsResource()
	{
		$gdObject	= $this->creator->getResource();
		self::assertIsObject( $gdObject );
	}
}
