<?php
/**
 *	TestUnit of UI_HTML_Indicator.
 *	@package		Tests.ui.html
 *	@extends		PHPUnit_Framework_TestCase
 *	@uses			UI_HTML_Indicator
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			09.07.2008
 *	@version		0.1
 */
require_once( 'PHPUnit/Framework/TestCase.php' ); 
require_once( 'Tests/initLoaders.php5' );
import( 'de.ceus-media.ui.html.Indicator' );
/**
 *	TestUnit of UI_HTML_Indicator.
 *	@package		Tests.ui.html
 *	@extends		PHPUnit_Framework_TestCase
 *	@uses			UI_HTML_Indicator
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			09.07.2008
 *	@version		0.1
 */
class Tests_UI_HTML_IndicatorTest extends PHPUnit_Framework_TestCase
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
		$this->indicator	= new UI_HTML_Indicator();
	}
	
	/**
	 *	Cleanup after every Test.
	 *	@access		public
	 *	@return		void
	 */
	public function tearDown()
	{
	}

	/**
	 *	Tests Method '__construct'.
	 *	@access		public
	 *	@return		void
	 */
	public function testConstruct()
	{
		$indicator	= new UI_HTML_Indicator();

		$assertion	= TRUE;
		$creation	= $indicator->getOption( 'useColor' );
		$this->assertEquals( $assertion, $creation );

		$assertion	= FALSE;
		$creation	= $indicator->getOption( 'useRatio' );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'build'.
	 *	@access		public
	 *	@return		void
	 */
	public function testBuild()
	{
		$assertion	= file_get_contents(  $this->path.'indicator1.html' );
		$creation	= $this->indicator->build( 1, 2 );
		$this->assertEquals( $assertion, $creation );

		$this->indicator->setOption( 'useColor', FALSE );

		$assertion	= file_get_contents( $this->path.'indicator2.html' );
		$creation	= $this->indicator->build( 1, 2 );
		$this->assertEquals( $assertion, $creation );

		$this->indicator->setOption( 'useColor', TRUE );
		$this->indicator->setOption( 'useRatio', TRUE );
		$this->indicator->setOption( 'usePercentage', FALSE );
		$this->indicator->setInnerClass( 'testInnerClass' );
		$this->indicator->setOuterClass( 'testOuterClass' );
		$this->indicator->setIndicatorClass( 'testIndicatorClass' );
		$this->indicator->setRatioClass( 'testRatioClass' );
		$this->indicator->setPercentageClass( 'testPercentageClass' );

		$assertion	= file_get_contents( $this->path.'indicator3.html' );
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
		$assertion	= TRUE;
		$creation	= $this->indicator->setOption( 'useColor', FALSE );
		$this->assertEquals( $assertion, $creation );

		$assertion	= TRUE;
		$creation	= $this->indicator->setOption( 'useColor', TRUE );
		$this->assertEquals( $assertion, $creation );

		$assertion	= FALSE;
		$creation	= $this->indicator->setOption( 'useColor', TRUE );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Exception of Method 'setOption'.
	 *	@access		public
	 *	@return		void
	 */
	public function testSetOptionException()
	{
		$this->setExpectedException( 'OutOfRangeException' );
		$creation	= $this->indicator->setOption( 'not_existing', 'not_relevant' );
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
?>