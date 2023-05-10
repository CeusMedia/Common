<?php
/** @noinspection PhpIllegalPsrClassPathInspection */
/** @noinspection PhpMultipleClassDeclarationsInspection */
/** @noinspection PhpUnhandledExceptionInspection */
/** @noinspection PhpDocMissingThrowsInspection */

declare( strict_types = 1 );

/**
 *	TestUnit of UI_Image_Watermark.
 *	@package		Tests.ui.image
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 */

namespace CeusMedia\CommonTest\UI\HTML;

use CeusMedia\CommonTest\BaseCase;
use CeusMedia\Common\UI\Image\Watermark;
use CeusMedia\CommonTest\MockAntiProtection;
use Exception;
use InvalidArgumentException;

/**
 *	TestUnit of UI_Image_Watermark.
 *	@package		Tests.ui.image
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 */
class WatermarkTest extends BaseCase
{
	/** @var Watermark  */
	protected Watermark $mark;

	/** @var string  */
	protected string $path;
	/**
	 *	Setup for every Test.
	 *	@access		public
	 *	@return		void
	 */
	public function setUp(): void
	{
		$this->path	= dirname( __FILE__ )."/assets/";
		if( !extension_loaded( 'gd' ) )
			$this->markTestSkipped( 'Missing gd support' );
//		else
		$this->mark	= MockAntiProtection::getInstance( Watermark::class, $this->path."mark.png" );
	}

	/**
	 *	Cleanup after every Test.
	 *	@access		public
	 *	@return		void
	 */
	public function tearDown(): void
	{
		@unlink( $this->path."targetWatermark.png" );
	}

	/**
	 *	Tests Method '__construct'.
	 *	@access		public
	 *	@return		void
	 */
	public function testConstruct()
	{
		$alpha		= 99;
		$quality	= 98;
		$mark		= MockAntiProtection::getInstance( Watermark::class, $this->path."sourceWatermark.png", $alpha, $quality );

		$this->assertEquals( $alpha, $mark->getProtectedVar( 'alpha' ) );
		$this->assertEquals( $quality, $mark->getProtectedVar( 'quality' ) );

		$height		= 100;
		$this->assertEquals( $height, $mark->getProtectedVar( 'stamp' )->getHeight() );
	}

	/**
	 *	Tests Method 'markImage'.
	 *	@access		public
	 *	@return		void
	 */
	public function testMarkImage()
	{
//		$this->markTestSkipped( 'No image tests.' );
		$mark	= new Watermark( $this->path."mark.png", 50, 100 );
		$mark->setMargin( 10, 10 );
		$mark->markImage( $this->path."sourceWatermark.png", $this->path."targetWatermark.png" );

		$this->assertFileEquals( $this->path."targetWatermark.png", $this->path."assertWatermark.png", "Watermark file not identical." );
	}

	/**
	 *	Tests Method 'setAlpha'.
	 *	@access		public
	 *	@return		void
	 */
	public function testSetAlpha()
	{
		$alpha	= 75;
		$this->mark->setAlpha( $alpha );

		$this->assertEquals( $alpha, $this->mark->getProtectedVar( 'alpha' ) );
	}

	/**
	 *	Tests Method 'setMargin'.
	 *	@access		public
	 *	@return		void
	 */
	public function testSetMargin()
	{
		$this->mark->setMargin( 1, 2 );
		$this->assertEquals( 1, $this->mark->getProtectedVar( 'marginX' ) );
		$this->assertEquals( 2, $this->mark->getProtectedVar( 'marginY' ) );
	}

	/**
	 *	Tests Method 'setPosition'.
	 *	@access		public
	 *	@return		void
	 */
	public function testSetPosition()
	{
		$this->mark->setPosition( 'center', 'middle' );

		$assertion	= 'center';
		$creation	= $this->mark->getProtectedVar( 'positionH' );
		$this->assertEquals( $assertion, $creation );

		$assertion	= 'middle';
		$creation	= $this->mark->getProtectedVar( 'positionV' );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Exception of Method 'setPosition'.
	 *	@access		public
	 *	@return		void
	 */
	public function testSetPositionException1()
	{
		$this->expectException( 'InvalidArgumentException' );
		$this->mark->setPosition( 'invalid', 'top' );
	}

	/**
	 *	Tests Exception of Method 'setPosition'.
	 *	@access		public
	 *	@return		void
	 */
	public function testSetPositionException2()
	{
		$this->expectException( 'InvalidArgumentException' );
		$this->mark->setPosition( 'left', 'invalid' );
	}

	/**
	 *	Tests Method 'setQuality'.
	 *	@access		public
	 *	@return		void
	 */
	public function testSetQuality()
	{
		$this->mark->setQuality( 80 );

		$creation	= $this->mark->getProtectedVar( 'quality' );
		$this->assertEquals( 80, $creation );
	}

	/**
	 *	Tests Method 'setStamp'.
	 *	@access		public
	 *	@return		void
	 */
	public function testSetStamp()
	{
		$this->mark->setStamp( $this->path."sourceWatermark.png" );
		$stamp		= $this->mark->getProtectedVar( 'stamp' );

		$this->assertIsObject( $stamp->getResource() );
		$this->assertInstanceOf( \GdImage::class, $stamp->getResource() );

		$creation	= $stamp->getWidth();
		$this->assertEquals( 100, $creation );

		$creation	= $stamp->getHeight();
		$this->assertEquals( 100, $creation );
	}
}

/**
 *
 */
class WatermarkInstance extends Watermark
{
	/**
	 * @param $varName
	 * @return mixed
	 * @throws Exception
	 */
	public function getProtectedVar($varName )
	{
		if( !in_array( $varName, array_keys( get_object_vars( $this ) ) ) )
			throw new Exception( 'Var "'.$varName.'" is not declared.' );
		return $this->$varName;
	}

	/**
	 * @param $varName
	 * @param $varValue
	 * @return void
	 * @throws Exception
	 */
	public function setProtectedVar( $varName, $varValue )
	{
		if( !in_array( $varName, array_keys( get_object_vars( $this ) ) ) )
			throw new Exception( 'Var "'.$varName.'" is not declared.' );
		$this->$varName	= $varValue;
	}
}
