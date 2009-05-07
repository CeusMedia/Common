<?php
/**
 *	TestUnit of UI_HTML_Panel.
 *	@package		Tests.
 *	@extends		PHPUnit_Framework_TestCase
 *	@uses			UI_HTML_Panel
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			07.09.2008
 *	@version		0.1
 */
require_once( 'PHPUnit/Framework/TestCase.php' ); 
require_once( 'Tests/initLoaders.php5' );
import( 'de.ceus-media.ui.html.Panel' );
/**
 *	TestUnit of UI_HTML_Panel.
 *	@package		Tests.
 *	@extends		PHPUnit_Framework_TestCase
 *	@uses			UI_HTML_Panel
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			07.09.2008
 *	@version		0.1
 */
class Tests_UI_HTML_PanelTest extends PHPUnit_Framework_TestCase
{
	/**
	 *	Constructor.
	 *	@access		public
	 *	@return		void
	 */
	public function __construct()
	{
	}
	
	/**
	 *	Setup for every Test.
	 *	@access		public
	 *	@return		void
	 */
	public function setUp()
	{
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
	 *	Tests Method 'create'.
	 *	@access		public
	 *	@return		void
	 */
	public function testCreate()
	{
		$this->markTestIncomplete( 'Incomplete Test' );
		$assertion	= TRUE;
		$creation	= UI_HTML_Panel::create();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'wrap'.
	 *	@access		public
	 *	@return		void
	 */
	public function testWrap()
	{
		$this->markTestIncomplete( 'Incomplete Test' );
		$assertion	= TRUE;
		$creation	= UI_HTML_Panel::wrap();
		$this->assertEquals( $assertion, $creation );
	}
}
?>