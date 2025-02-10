<?php
/** @noinspection PhpIllegalPsrClassPathInspection */
/** @noinspection PhpMultipleClassDeclarationsInspection */
/** @noinspection PhpUnhandledExceptionInspection */
/** @noinspection PhpDocMissingThrowsInspection */

declare( strict_types = 1 );

/**
 *	TestUnit of UI_HTML_Paging.
 *	@package		Tests.ui.html
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 */

namespace CeusMedia\CommonTest\UI\HTML;

use CeusMedia\Common\UI\HTML\Paging;
use CeusMedia\CommonTest\BaseCase;

/**
 *	TestUnit of UI_HTML_Paging.
 *	@package		Tests.ui.html
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 */
class PagingTest extends BaseCase
{
	protected $paging;

	protected $path;

	/**
	 *	Setup for every Test.
	 *	@access		public
	 *	@return		void
	 */
	public function setUp(): void
	{
		$this->path		= dirname( __FILE__ )."/assets/";
		$this->paging	= new Paging();
		$this->paging->setOption( 'text_next', 		"[next]" );
		$this->paging->setOption( 'text_previous',	"[prev]" );
		$this->paging->setOption( 'text_last',		"[last]" );
		$this->paging->setOption( 'text_first',		"[first]" );
		$this->paging->setOption( 'text_more',		"[more]" );
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
		$paging	= new Paging();

		$assertion	= "./";
		$creation	= $paging->getOption( 'uri' );
		self::assertEquals( $assertion, $creation );

		$assertion	= [];
		$creation	= $paging->getOption( 'param' );
		self::assertEquals( $assertion, $creation );

		$assertion	= "2";
		$creation	= $paging->getOption( 'coverage' );
		self::assertEquals( $assertion, $creation );

		$assertion	= "1";
		$creation	= $paging->getOption( 'extreme' );
		self::assertEquals( $assertion, $creation );

		$assertion	= "1";
		$creation	= $paging->getOption( 'more' );
		self::assertEquals( $assertion, $creation );

		$assertion	= "\n";
		$creation	= $paging->getOption( 'linebreak' );
		self::assertEquals( $assertion, $creation );

		$assertion	= "?";
		$creation	= $paging->getOption( 'key_request' );
		self::assertEquals( $assertion, $creation );

		$assertion	= "&";
		$creation	= $paging->getOption( 'key_param' );
		self::assertEquals( $assertion, $creation );

		$assertion	= "=";
		$creation	= $paging->getOption( 'key_assign' );
		self::assertEquals( $assertion, $creation );

		$assertion	= "offset";
		$creation	= $paging->getOption( 'key_offset' );
		self::assertEquals( $assertion, $creation );

		$assertion	= "pagingSpan";
		$creation	= $paging->getOption( 'class_span' );
		self::assertEquals( $assertion, $creation );

		$assertion	= "pagingLink";
		$creation	= $paging->getOption( 'class_link' );
		self::assertEquals( $assertion, $creation );

		$assertion	= "pagingText";
		$creation	= $paging->getOption( 'class_text' );
		self::assertEquals( $assertion, $creation );

		$assertion	= "<<";
		$creation	= $paging->getOption( 'text_first' );
		self::assertEquals( $assertion, $creation );

		$assertion	= "<";
		$creation	= $paging->getOption( 'text_previous' );
		self::assertEquals( $assertion, $creation );

		$assertion	= ">";
		$creation	= $paging->getOption( 'text_next' );
		self::assertEquals( $assertion, $creation );

		$assertion	= ">>";
		$creation	= $paging->getOption( 'text_last' );
		self::assertEquals( $assertion, $creation );

		$assertion	= "..";
		$creation	= $paging->getOption( 'text_more' );
		self::assertEquals( $assertion, $creation );

		$assertion	= "";
		$creation	= $paging->getOption( 'key_first' );
		self::assertEquals( $assertion, $creation );

		$assertion	= "";
		$creation	= $paging->getOption( 'key_previous' );
		self::assertEquals( $assertion, $creation );

		$assertion	= "";
		$creation	= $paging->getOption( 'key_next' );
		self::assertEquals( $assertion, $creation );

		$assertion	= "";
		$creation	= $paging->getOption( 'key_last' );
		self::assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'build'.
	 *	@access		public
	 *	@return		void
	 */
	public function testBuild1()
	{
		$assertion	= preg_replace( "@\r?\n *@s", "", file_get_contents( $this->path."paging1.html" ) );
		$creation	= preg_replace( "@\r?\n *@s", "", $this->paging->build( 100, 10, 0 ) );
		self::assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'build'.
	 *	@access		public
	 *	@return		void
	 */
	public function testBuild2()
	{
		$assertion	= preg_replace( "@\r?\n *@s", "", file_get_contents( $this->path."paging2.html" ) );
		$creation	= preg_replace( "@\r?\n *@s", "", $this->paging->build( 100, 10, 50 ) );
		file_put_contents( $this->path."test.html", $creation );
		self::assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'build'.
	 *	@access		public
	 *	@return		void
	 */
	public function testBuild3()
	{
		$assertion	= preg_replace( "@\r?\n *@s", "", file_get_contents( $this->path."paging3.html" ) );
		$creation	= preg_replace( "@\r?\n *@s", "", $this->paging->build( 100, 10, 90 ) );
		self::assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'build'.
	 *	@access		public
	 *	@return		void
	 */
	public function testBuild4()
	{
		$this->paging->setOption( 'coverage', 10 );
		$this->paging->setOption( 'class_span', "s" );
		$this->paging->setOption( 'class_text', "t" );
		$this->paging->setOption( 'class_link', "l" );
		$this->paging->setOption( 'uri', "./" );
		$this->paging->setOption( 'key_request', "page/" );
		$this->paging->setOption( 'key_param', "/" );
		$this->paging->setOption( 'key_assign', "/" );

		$assertion	= preg_replace( "@\r?\n *@s", "", file_get_contents( $this->path."paging4.html" ) );
		$creation	= preg_replace( "@\r?\n *@s", "", $this->paging->build( 100, 10, 50 ) );
		self::assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Exception Method 'build'.
	 *	@access		public
	 *	@return		void
	 */
	public function testBuildException()
	{
		$this->expectException( 'InvalidArgumentException' );
		$this->paging->setOption( 'text_first', '' );
		$this->paging->build( 100, 10, 50 );
	}
}
