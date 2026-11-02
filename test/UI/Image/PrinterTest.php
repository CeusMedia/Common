<?php
/** @noinspection PhpIllegalPsrClassPathInspection */
/** @noinspection PhpMultipleClassDeclarationsInspection */
/** @noinspection PhpUnhandledExceptionInspection */
/** @noinspection PhpDocMissingThrowsInspection */

declare( strict_types = 1 );

/**
 *	TestUnit of UI_Image_Printer.
 *	@package		Tests.ui.image
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 */

namespace CeusMedia\CommonTest\UI\Image;

use CeusMedia\Common\FS\File\Reader;
use CeusMedia\Common\UI\Image\Printer;
use CeusMedia\CommonTest\BaseCase;

/**
 *	TestUnit of Inverter.
 *	@package		Tests.ui.image
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 */
class PrinterTest extends BaseCase
{
	use ImageAssertions;

	/** @var string  */
	protected string $path;

	public function setUp(): void
	{
		if( !extension_loaded( 'gd' ) )
			$this->markTestSkipped( 'Missing gd support' );

		$this->path	= dirname( __FILE__ )."/assets/";
	}

	public function tearDown(): void
	{
		 @unlink( $this->path."targetPrinter.png" );
		 @unlink( $this->path."targetPrinter.jpg" );
		 @unlink( $this->path."targetPrinter.gif" );
	}

//	public function testConstructException()
//	{
//		$this->expectException( 'InvalidArgumentException' );
//		new Printer( "not_a_resource" );
//	}

	public function testShowPng()
	{
		$resource	= imagecreatefrompng( $this->path."sourceCreator.png" );
		$printer	= new Printer( $resource );

		ob_start();
		$printer->show( IMAGETYPE_PNG, 100, FALSE );
		$creation	= ob_get_clean();

		self::assertImageEquals( $this->path."sourceCreator.png", imagecreatefromstring( (string) $creation ) );
	}

	public function testShowJpeg()
	{
		$resource	= imagecreatefromjpeg( $this->path."sourceCreator.jpg" );
		$printer	= new Printer( $resource );

		ob_start();
		$printer->show( IMAGETYPE_JPEG, 100, FALSE );
		$creation	= ob_get_clean();

		self::assertImageEquals( $this->path."sourceCreator.jpg", imagecreatefromstring( (string) $creation ), 8 );
	}

	public function testShowGif()
	{
		$resource	= imagecreatefromgif( $this->path."sourceCreator.gif" );
		$printer	= new Printer( $resource );

		ob_start();
		$printer->show( IMAGETYPE_GIF, 0, FALSE );
		$creation	= ob_get_clean();

		self::assertImageEquals( $this->path."sourceCreator.gif", imagecreatefromstring( (string) $creation ) );
	}

	public function testShowException()
	{
		$this->expectException( 'InvalidArgumentException' );
		$resource	= imagecreatefrompng( $this->path."sourceCreator.png" );
		$printer	= new Printer( $resource );
		$printer->show( 15, 0 );
	}

	public function testSavePng()
	{
		$resource	= imagecreatefrompng( $this->path."sourceCreator.png" );
		$printer	= new Printer( $resource );
		$printer->save( $this->path."targetPrinter.png", IMAGETYPE_PNG, 0 );

		self::assertImageEquals( $this->path."sourceCreator.png", $this->path."targetPrinter.png" );
	}

	public function testSaveJpeg()
	{
		$resource	= imagecreatefromjpeg( $this->path."sourceCreator.jpg" );
		$printer	= new Printer( $resource );
		$printer->save( $this->path."targetPrinter.jpg", IMAGETYPE_JPEG, 100 );

		self::assertImageEquals( $this->path."sourceCreator.jpg", $this->path."targetPrinter.jpg", 8 );
	}

	public function testSaveGif()
	{
		$resource	= imagecreatefromgif( $this->path."sourceCreator.gif" );
		$printer	= new Printer( $resource );
		$printer->save( $this->path."targetPrinter.gif", IMAGETYPE_GIF, 0 );

		self::assertImageEquals( $this->path."sourceCreator.gif", $this->path."targetPrinter.gif" );
	}

	public function testSaveException()
	{
		$this->expectException( 'InvalidArgumentException' );
		$resource	= imagecreatefrompng( $this->path."sourceCreator.png" );
		$printer	= new Printer( $resource );
		$printer->save( $this->path."targetPrinter.png", 15, 0 );
	}
}
