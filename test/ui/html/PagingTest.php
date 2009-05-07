<?php
/**
 *	TestUnit of UI_HTML_Paging.
 *	@package		Tests.ui.html
 *	@extends		PHPUnit_Framework_TestCase
 *	@uses			UI_HTML_Paging
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			20.07.2008
 *	@version		0.1
 */
require_once '../autoload.php5';
require_once( 'PHPUnit/Framework/TestCase.php' ); 
/**
 *	TestUnit of UI_HTML_Paging.
 *	@package		Tests.ui.html
 *	@extends		PHPUnit_Framework_TestCase
 *	@uses			UI_HTML_Paging
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			20.07.2008
 *	@version		0.1
 */
class UI_HTML_PagingTest extends PHPUnit_Framework_TestCase
{
	/**
	 *	Constructor.
	 *	@access		public
	 *	@return		void
	 */
	public function __construct()
	{
		$this->path		= dirname( __FILE__ )."/";
	}
	
	/**
	 *	Setup for every Test.
	 *	@access		public
	 *	@return		void
	 */
	public function setUp()
	{
		$this->paging	= new UI_HTML_Paging();
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
		$paging	= new UI_HTML_Paging();

		$assertion	= "./";
		$creation	= $paging->getOption( 'uri' );
		$this->assertEquals( $assertion, $creation );
		
		$assertion	= array();
		$creation	= $paging->getOption( 'param' );
		$this->assertEquals( $assertion, $creation );

		$assertion	= "2";
		$creation	= $paging->getOption( 'coverage' );
		$this->assertEquals( $assertion, $creation );

		$assertion	= "1";
		$creation	= $paging->getOption( 'extreme' );
		$this->assertEquals( $assertion, $creation );

		$assertion	= "1";
		$creation	= $paging->getOption( 'more' );
		$this->assertEquals( $assertion, $creation );

		$assertion	= "\n";
		$creation	= $paging->getOption( 'linebreak' );
		$this->assertEquals( $assertion, $creation );

		$assertion	= "?";
		$creation	= $paging->getOption( 'key_request' );
		$this->assertEquals( $assertion, $creation );

		$assertion	= "&";
		$creation	= $paging->getOption( 'key_param' );
		$this->assertEquals( $assertion, $creation );

		$assertion	= "=";
		$creation	= $paging->getOption( 'key_assign' );
		$this->assertEquals( $assertion, $creation );

		$assertion	= "offset";
		$creation	= $paging->getOption( 'key_offset' );
		$this->assertEquals( $assertion, $creation );

		$assertion	= "pagingSpan";
		$creation	= $paging->getOption( 'class_span' );
		$this->assertEquals( $assertion, $creation );

		$assertion	= "pagingLink";
		$creation	= $paging->getOption( 'class_link' );
		$this->assertEquals( $assertion, $creation );

		$assertion	= "pagingText";
		$creation	= $paging->getOption( 'class_text' );
		$this->assertEquals( $assertion, $creation );

		$assertion	= "<<";
		$creation	= $paging->getOption( 'text_first' );
		$this->assertEquals( $assertion, $creation );

		$assertion	= "<";
		$creation	= $paging->getOption( 'text_previous' );
		$this->assertEquals( $assertion, $creation );

		$assertion	= ">";
		$creation	= $paging->getOption( 'text_next' );
		$this->assertEquals( $assertion, $creation );

		$assertion	= ">>";
		$creation	= $paging->getOption( 'text_last' );
		$this->assertEquals( $assertion, $creation );

		$assertion	= "..";
		$creation	= $paging->getOption( 'text_more' );
		$this->assertEquals( $assertion, $creation );

		$assertion	= "";
		$creation	= $paging->getOption( 'key_first' );
		$this->assertEquals( $assertion, $creation );

		$assertion	= "";
		$creation	= $paging->getOption( 'key_previous' );
		$this->assertEquals( $assertion, $creation );

		$assertion	= "";
		$creation	= $paging->getOption( 'key_next' );
		$this->assertEquals( $assertion, $creation );

		$assertion	= "";
		$creation	= $paging->getOption( 'key_last' );
		$this->assertEquals( $assertion, $creation );
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
		$this->assertEquals( $assertion, $creation );
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
		$this->assertEquals( $assertion, $creation );
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
		$this->assertEquals( $assertion, $creation );
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
		$creation	= preg_replace( "@\r?\n *@s", "", $this->paging->build( 100, 10, 50, $options ) );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Exception Method 'build'.
	 *	@access		public
	 *	@return		void
	 */
	public function testBuildException()
	{
		$this->setExpectedException( 'InvalidArgumentException' );
		$this->paging->setOption( 'text_first', '' );
		$this->paging->build( 100, 10, 50 );
	}
}
?>