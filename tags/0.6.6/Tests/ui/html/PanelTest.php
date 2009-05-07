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
	 *	Tests Method 'build'.
	 *	@access		public
	 *	@return		void
	 */
	public function testBuild1()
	{
		$panel		= new UI_HTML_Panel();
		$assertion	= '<div id="a1" class="panel default"><div class="panelContent"><div class="panelContentInner"></div></div></div>';
		$creation	= $panel->build( "a1" );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'build'.
	 *	@access		public
	 *	@return		void
	 */
	public function testBuild2()
	{
		$panel		= new UI_HTML_Panel();
		$panel->setHeader( "header1" );
		$panel->setFooter( "footer1" );
		$panel->setContent( "content1" );
		$assertion	= '<div id="a1" class="panel default"><div class="panelHead"><div class="panelHeadInner">header1</div></div><div class="panelContent"><div class="panelContentInner">content1</div></div><div class="panelFoot"><div class="panelFootInner">footer1</div></div></div>';
		$creation	= $panel->build( "a1" );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'create'.
	 *	@access		public
	 *	@return		void
	 */
	public function testCreate1()
	{
		$assertion	= '<div id="a1" class="panel default"><div class="panelContent"><div class="panelContentInner"></div></div></div>';
		$creation	= UI_HTML_Panel::create( "a1", NULL, NULL );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'create'.
	 *	@access		public
	 *	@return		void
	 */
	public function testCreate2()
	{
		$assertion	= '<div id="a1" class="panel default"><div class="panelHead"><div class="panelHeadInner">header1</div></div><div class="panelContent"><div class="panelContentInner">content1</div></div><div class="panelFoot"><div class="panelFootInner">footer1</div></div></div>';
		$creation	= UI_HTML_Panel::create( "a1", "header1", "content1", "footer1" );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'setContent'.
	 *	@access		public
	 *	@return		void
	 */
	public function testSetContent()
	{
		$panel		= new Tests_UI_HTML_PanelInstance();

		$panel->setContent( "1" );
		$assertion	= "1";
		$creation	= $panel->getProtectedVar( 'content' );
		$this->assertEquals( $assertion, $creation );

		$panel->setContent( "a2" );
		$assertion	= "a2";
		$creation	= $panel->getProtectedVar( 'content' );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'setContent'.
	 *	@access		public
	 *	@return		void
	 */
	public function testSetHeader()
	{
		$panel		= new Tests_UI_HTML_PanelInstance();

		$panel->setHeader( "1" );
		$assertion	= "1";
		$creation	= $panel->getProtectedVar( 'header' );
		$this->assertEquals( $assertion, $creation );

		$panel->setHeader( "a2" );
		$assertion	= "a2";
		$creation	= $panel->getProtectedVar( 'header' );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'setFooter'.
	 *	@access		public
	 *	@return		void
	 */
	public function testSetFooter()
	{
		$panel		= new Tests_UI_HTML_PanelInstance();

		$panel->setFooter( "1" );
		$assertion	= "1";
		$creation	= $panel->getProtectedVar( 'footer' );
		$this->assertEquals( $assertion, $creation );

		$panel->setFooter( "a2" );
		$assertion	= "a2";
		$creation	= $panel->getProtectedVar( 'footer' );
		$this->assertEquals( $assertion, $creation );
	}
}
class Tests_UI_HTML_PanelInstance extends UI_HTML_Panel
{
	public function getProtectedVar( $varName )
	{
		if( !in_array( $varName, array_keys( get_object_vars( $this ) ) ) )
			throw new Exception( 'Var "'.$varName.'" is not declared.' );
		return $this->$varName;
	}
}
?>