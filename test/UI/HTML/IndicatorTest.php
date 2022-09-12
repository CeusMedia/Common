<?php
/** @noinspection PhpIllegalPsrClassPathInspection */
/** @noinspection PhpMultipleClassDeclarationsInspection */
/** @noinspection PhpUnhandledExceptionInspection */
/** @noinspection PhpDocMissingThrowsInspection */

declare( strict_types = 1 );

/**
 *	TestUnit of UI_HTML_Indicator.
 *	@package		Tests.ui.html
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 */

namespace CeusMedia\CommonTest\UI\HTML;

use CeusMedia\Common\UI\HTML\Indicator;
use CeusMedia\CommonTest\BaseCase;

/**
 *	TestUnit of UI_HTML_Indicator.
 *	@package		Tests.ui.html
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 */
class IndicatorTest extends BaseCase
{
	/** @var Indicator $indicator */
	protected $indicator;

	/** @var string $path */
	protected $path;

	/**
	 *	Setup for every Test.
	 *	@access		public
	 *	@return		void
	 */
	public function setUp(): void
	{
		$this->path	= dirname( __FILE__ )."/assets/";
		$this->indicator	= new Indicator();
	}

	/**
	 *	Cleanup after every Test.
	 *	@access		public
	 *	@return		void
	 */
	public function tearDown(): void
	{
	}

	/**
	 *	Tests Method '__construct'.
	 *	@access		public
	 *	@return		void
	 */
	public function testConstruct()
	{
		$indicator	= new Indicator();

		$creation	= $indicator->getOption( 'useColor' );
		$this->assertTrue( $creation );

		$creation	= $indicator->getOption( 'useRatio' );
		$this->assertFalse( $creation );
	}

	/**
	 *	Tests Method 'build'.
	 *	@access		public
	 *	@return		void
	 */
	public function testBuild()
	{
		$assertion	= trim( file_get_contents(  $this->path.'indicator1.html' ) );
		$creation	= $this->indicator->build( 1, 2 );
		$this->assertEquals( $assertion, $creation );

		$this->indicator->setOption( 'useColor', FALSE );

		$assertion	= trim( file_get_contents( $this->path.'indicator2.html' ) );
		$creation	= $this->indicator->build( 1, 2 );
		$this->assertEquals( $assertion, $creation );

		$this->indicator->setOption( 'useColor', TRUE );
		$this->indicator->setOption( 'useRatio', TRUE );
		$this->indicator->setOption( 'usePercentage', TRUE );
		$this->indicator->setInnerClass( 'testInnerClass' );
		$this->indicator->setOuterClass( 'testOuterClass' );
		$this->indicator->setIndicatorClass( 'testIndicatorClass' );
		$this->indicator->setRatioClass( 'testRatioClass' );
		$this->indicator->setPercentageClass( 'testPercentageClass' );

		$assertion	= trim( file_get_contents( $this->path.'indicator3.html' ) );
		$creation	= $this->indicator->build( 49, 100, 200 );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'getIndicatorClass'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetIndicatorClass()
	{
		$this->indicator->setIndicatorClass( "testClass" );

		$assertion	= "testClass";
		$creation	= $this->indicator->getIndicatorClass();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'getInnerClass'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetInnerClass()
	{
		$this->indicator->setInnerClass( "testClass" );

		$assertion	= "testClass";
		$creation	= $this->indicator->getInnerClass();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'getOuterClass'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetOuterClass()
	{
		$this->indicator->setOuterClass( "testClass" );

		$assertion	= "testClass";
		$creation	= $this->indicator->getOuterClass();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'getPercentageClass'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetPercentageClass()
	{
		$this->indicator->setPercentageClass( "testClass" );

		$assertion	= "testClass";
		$creation	= $this->indicator->getPercentageClass();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'getRatioClass'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetRatioClass()
	{
		$this->indicator->setRatioClass( "testClass" );

		$assertion	= "testClass";
		$creation	= $this->indicator->getRatioClass();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'setIndicatorClass'.
	 *	@access		public
	 *	@return		void
	 */
	public function testSetIndicatorClass()
	{
		$this->indicator->setIndicatorClass( "testClass" );

		$assertion	= "testClass";
		$creation	= $this->indicator->getIndicatorClass();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'setInnerClass'.
	 *	@access		public
	 *	@return		void
	 */
	public function testSetInnerClass()
	{
		$this->indicator->setInnerClass( "testClass" );

		$assertion	= "testClass";
		$creation	= $this->indicator->getInnerClass();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'setOption'.
	 *	@access		public
	 *	@return		void
	 */
	public function testSetOption()
	{
		$creation	= $this->indicator->setOption( 'useColor', FALSE );
		$this->assertTrue( $creation );

		$creation	= $this->indicator->setOption( 'useColor', TRUE );
		$this->assertTrue( $creation );

		$creation	= $this->indicator->setOption( 'useColor', TRUE );
		$this->assertFalse( $creation );
	}

	/**
	 *	Tests Exception of Method 'setOption'.
	 *	@access		public
	 *	@return		void
	 */
	public function testSetOptionException()
	{
		$this->expectException( 'OutOfRangeException' );
		$this->indicator->setOption( 'not_existing', 'not_relevant' );
	}

	/**
	 *	Tests Method 'setOuterClass'.
	 *	@access		public
	 *	@return		void
	 */
	public function testSetOuterClass()
	{
		$this->indicator->setOuterClass( "testClass" );

		$assertion	= "testClass";
		$creation	= $this->indicator->getOuterClass();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'setPercentageClass'.
	 *	@access		public
	 *	@return		void
	 */
	public function testSetPercentageClass()
	{
		$this->indicator->setPercentageClass( "testClass" );

		$assertion	= "testClass";
		$creation	= $this->indicator->getPercentageClass();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'setRatioClass'.
	 *	@access		public
	 *	@return		void
	 */
	public function testSetRatioClass()
	{
		$this->indicator->setRatioClass( "testClass" );

		$assertion	= "testClass";
		$creation	= $this->indicator->getRatioClass();
		$this->assertEquals( $assertion, $creation );
	}
}
