<?php
/**
 *	TestUnit of UI_Image_Creator.
 *	@package		Tests.ui.image
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@since			16.06.2008
 *	@version		0.1
 */
require_once dirname( dirname( __DIR__ ) ).'/initLoaders.php';
/**
 *	TestUnit of Inverter.
 *	@package		Tests.ui.image
 *	@extends		Test_Case
 *	@uses			UI_Image_Creator
 *	@uses			FS_File_Reader
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@since			16.06.2008
 *	@version		0.1
 */
class Test_UI_Image_CreatorTest extends Test_Case
{
	public function setUp()
	{
		if( !extension_loaded( 'gd' ) )
			$this->markTestSkipped( 'Missing gd support' );
		$this->path		= dirname( __FILE__ )."/";
		$this->image	= new UI_Image_Creator();
		$this->image->loadImage( $this->path."aptana_256.png" );
		$this->tearDown();
	}

	public function tearDown()
	{
		@unlink( $this->path."targetCreator.jpg" );
		@unlink( $this->path."targetCreator.gif" );
		@unlink( $this->path."targetCreator.png" );
		@unlink( $this->path."targetCreator1.png" );
	}

	public function testCreate()
	{
		$this->markTestSkipped( 'No image tests.' );
		$image	= new UI_Image_Creator();
		$image->create( 100, 200 );
		imagepng( $image->getResource(), $this->path."targetCreator1.png" );

		$image	= imagecreatefrompng( $this->path."targetCreator1.png" );
		$this->assertEquals( 100, imagesx( $image ) );
		$this->assertEquals( 200, imagesy( $image ) );
	}

	public function testLoadImagePng()
	{
		$this->markTestSkipped( 'No image tests.' );
		$image	= new UI_Image_Creator();
		$image->loadImage( $this->path."sourceCreator.png" );
		imagepng( $image->getResource(), $this->path."targetCreator.png" );

		$file		= new FS_File_Reader( $this->path."sourceCreator.png" );
		$this->assertTrue( $file->equals( $this->path."targetCreator.png" ) );
	}

	public function testLoadImageJpeg()
	{
		$this->markTestSkipped( 'No image tests.' );
		$image	= new UI_Image_Creator();
		$image->loadImage( $this->path."sourceCreator.jpg" );

		$assertion	= TRUE;
		$creation	= is_resource( $image->getResource() );
		$this->assertEquals( $assertion, $creation );
	}

	public function testLoadImageGif()
	{
		$this->markTestSkipped( 'No image tests.' );
		$image	= new UI_Image_Creator();
		$image->loadImage( $this->path."sourceCreator.gif" );
		imagegif( $image->getResource(), $this->path."targetCreator.gif" );

		$file		= new FS_File_Reader( $this->path."sourceCreator.gif" );
		$this->assertTrue( $file->equals( $this->path."targetCreator.gif" ) );
	}

	public function testLoadImageException1()
	{
		$this->expectException( 'InvalidArgumentException' );
		$image	= new UI_Image_Creator();
		$image->loadImage( $this->path."not_existing.gif" );
	}

	public function testLoadImageException2()
	{
		$this->expectException( 'InvalidArgumentException' );
		$image	= new UI_Image_Creator();
		$image->loadImage( $this->path."CreatorTest.php" );
	}

	public function testGetWidth()
	{
		$assertion	= 256;
		$creation	= $this->image->getWidth();
		$this->assertEquals( $assertion, $creation );
	}

	public function testGetHeight()
	{
		$assertion	= 256;
		$creation	= $this->image->getHeight();
		$this->assertEquals( $assertion, $creation );
	}

	public function testGetType()
	{
		$assertion	= IMAGETYPE_PNG;
		$creation	= $this->image->getType();
		$this->assertEquals( $assertion, $creation );
	}

	public function testGetResource()
	{
		$assertion	= TRUE;
		$creation	= is_resource( $this->image->getResource() );
		$this->assertEquals( $assertion, $creation );
	}
}
?>
