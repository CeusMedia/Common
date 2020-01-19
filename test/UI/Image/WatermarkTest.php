<?php
/**
 *	TestUnit of UI_Image_Watermark.
 *	@package		Tests.ui.image
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@since			21.07.2008
 *	@version		0.1
 */
require_once dirname( dirname( __DIR__ ) ).'/initLoaders.php';
/**
 *	TestUnit of UI_Image_Watermark.
 *	@package		Tests.ui.image
 *	@extends		Test_Case
 *	@uses			UI_Image_Watermark
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@since			21.07.2008
 *	@version		0.1
 */
class Test_UI_Image_WatermarkTest extends Test_Case
{
	/**
	 *	Constructor.
	 *	@access		public
	 *	@return		void
	 */
	public function __construct()
	{
		$this->path	= dirname( __FILE__ )."/";
	}

	/**
	 *	Setup for every Test.
	 *	@access		public
	 *	@return		void
	 */
	public function setUp()
	{
		if( !extension_loaded( 'gd' ) )
			$this->markTestSkipped( 'Missing gd support' );
		$this->mark	= new Test_UI_Image_WatermarkInstance( $this->path."mark.png" );
	}

	/**
	 *	Cleanup after every Test.
	 *	@access		public
	 *	@return		void
	 */
	public function tearDown()
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
		$mark	= new Test_UI_Image_WatermarkInstance( $this->path."sourceWatermark.png", 99, 98 );

		$assertion	= 99;
		$creation	= $mark->getProtectedVar( 'alpha' );
		$this->assertEquals( $assertion, $creation );

		$assertion	= 98;
		$creation	= $mark->getProtectedVar( 'quality' );
		$this->assertEquals( $assertion, $creation );

		$assertion	= 100;
		$creation	= $mark->getProtectedVar( 'stamp' )->getHeight();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'markImage'.
	 *	@access		public
	 *	@return		void
	 */
	public function testMarkImage()
	{
		$this->markTestSkipped( 'No image tests.' );
		$mark	= new UI_Image_Watermark( $this->path."mark.png", 50, 100 );
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
		$this->mark->setAlpha( 75 );

		$assertion	= 75;
		$creation	= $this->mark->getProtectedVar( 'alpha' );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'setMargin'.
	 *	@access		public
	 *	@return		void
	 */
	public function testSetMargin()
	{
		$this->mark->setMargin( 1, 2 );

		$assertion	= 1;
		$creation	= $this->mark->getProtectedVar( 'marginX' );
		$this->assertEquals( $assertion, $creation );

		$assertion	= 2;
		$creation	= $this->mark->getProtectedVar( 'marginY' );
		$this->assertEquals( $assertion, $creation );
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

		$assertion	= 80;
		$creation	= $this->mark->getProtectedVar( 'quality' );
		$this->assertEquals( $assertion, $creation );
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

		$assertion	= TRUE;
		$creation	= is_resource( $stamp->getResource() );
		$this->assertEquals( $assertion, $creation );

		$assertion	= 100;
		$creation	= $stamp->getWidth();
		$this->assertEquals( $assertion, $creation );

		$assertion	= 100;
		$creation	= $stamp->getHeight();
		$this->assertEquals( $assertion, $creation );
	}
}
class Test_UI_Image_WatermarkInstance extends UI_Image_Watermark
{
	public function getProtectedVar( $varName )
	{
		if( !in_array( $varName, array_keys( get_object_vars( $this ) ) ) )
			throw new Exception( 'Var "'.$varName.'" is not declared.' );
		return $this->$varName;
	}

	public function setProtectedVar( $varName, $varValue )
	{
		if( !in_array( $varName, array_keys( get_object_vars( $this ) ) ) )
			throw new Exception( 'Var "'.$varName.'" is not declared.' );
		$this->$varName	= $varValue;
	}
}
?>
