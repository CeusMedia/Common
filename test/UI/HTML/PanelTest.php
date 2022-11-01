<?php
/** @noinspection PhpIllegalPsrClassPathInspection */
/** @noinspection PhpMultipleClassDeclarationsInspection */
/** @noinspection PhpUnhandledExceptionInspection */
/** @noinspection PhpDocMissingThrowsInspection */

declare( strict_types = 1 );

/**
 *	TestUnit of UI_HTML_Panel.
 *	@package		Tests.
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 */

namespace CeusMedia\CommonTest\UI\HTML;

use CeusMedia\Common\UI\HTML\Panel;
use CeusMedia\CommonTest\BaseCase;
use Exception;

/**
 *	TestUnit of UI_HTML_Panel.
 *	@package		Tests.
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 */
class PanelTest extends BaseCase
{
	/**
	 *	Setup for every Test.
	 *	@access		public
	 *	@return		void
	 */
	public function setUp(): void
	{
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
	 *	Tests Method 'build'.
	 *	@access		public
	 *	@return		void
	 */
	public function testBuild1()
	{
		$panel		= new Panel();
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
		$panel		= new Panel();
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
		$creation	= Panel::create( "a1", NULL, NULL );
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
		$creation	= Panel::create( "a1", "content1", "header1", NULL, "footer1" );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'setContent'.
	 *	@access		public
	 *	@return		void
	 */
	public function testSetContent()
	{
		$panel		= new Test_PanelInstance();

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
		$panel		= new Test_PanelInstance();

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
		$panel		= new Test_PanelInstance();

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
class Test_PanelInstance extends Panel
{
	public function getProtectedVar( $varName )
	{
		if( !in_array( $varName, array_keys( get_object_vars( $this ) ) ) )
			throw new Exception( 'Var "'.$varName.'" is not declared.' );
		return $this->$varName;
	}
}
