<?php
declare( strict_types = 1 );

/**
 *	TestUnit of UI_DevOutput.
 *	@package		Tests.UI
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 */

namespace CeusMedia\CommonTest\UI;

use CeusMedia\Common\UI\DevOutput;
use CeusMedia\CommonTest\BaseCase;

/**
 *	TestUnit of UI_DevOutput.
 *	@package		Tests.UI
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 */
class DevOutputTest extends BaseCase
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
	 *	Tests Method 'indentSign'.
	 *	@access		public
	 *	@return		void
	 */
	public function testIndentSign()
	{
		$this->markTestIncomplete( 'Incomplete Test' );
		$assertion	= TRUE;
		$creation	= DevOutput::indentSign();
		self::assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'printArray'.
	 *	@access		public
	 *	@return		void
	 */
	public function testPrintArray()
	{
		$output		= new DevOutput();
		$array		= array( 'a' => 1, 'b' => "2" );
		$assertion	= "  [I] a => 1\n  [S] b => 2\n";
		$creation	= $output->printMixed( $array, 0, NULL, NULL, NULL, TRUE );
		self::assertEquals( $assertion, $creation );

		$output->setChannel( 'html' );
		$assertion	= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;[I] a => 1<br/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;[S] b => 2<br/>";
		$creation	= $output->printMixed( $array, 0, NULL, NULL, NULL, TRUE );
		self::assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'printBoolean'.
	 *	@access		public
	 *	@return		void
	 */
	public function testPrintBoolean()
	{
		$output		= new DevOutput();
		$assertion	= "[B] TRUE\n";
		$creation	= $output->printMixed( TRUE, 0, NULL, NULL, NULL, TRUE );
		self::assertEquals( $assertion, $creation );

		$assertion	= "[B] FALSE\n";
		$creation	= $output->printMixed( FALSE, 0, NULL, NULL, NULL, TRUE );
		self::assertEquals( $assertion, $creation );

		$output->setChannel( 'html' );
		$assertion	= "[B] <em>TRUE</em><br/>";
		$creation	= $output->printMixed( TRUE, 0, NULL, NULL, NULL, TRUE );
		self::assertEquals( $assertion, $creation );

		$assertion	= "[B] <em>FALSE</em><br/>";
		$creation	= $output->printMixed( FALSE, 0, NULL, NULL, NULL, TRUE );
		self::assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'printDouble'.
	 *	@access		public
	 *	@return		void
	 */
	public function testPrintDouble()
	{
		$output		= new DevOutput();
		$assertion	= "[F] 3.1415926\n";
		$creation	= $output->printMixed( (double) 3.1415926, 0, NULL, NULL, NULL, TRUE );
		self::assertEquals( $assertion, $creation );

		$output->setChannel( 'html' );
		$assertion	= "[F] 3.1415926<br/>";
		$creation	= $output->printMixed( (double) 3.1415926, 0, NULL, NULL, NULL, TRUE );
		self::assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'printFloat'.
	 *	@access		public
	 *	@return		void
	 */
	public function testPrintFloat()
	{
		$output		= new DevOutput();
		$assertion	= "[F] 3.1415926\n";
		$creation	= $output->printMixed( (float) 3.1415926, 0, NULL, NULL, NULL, TRUE );
		self::assertEquals( $assertion, $creation );

		$output->setChannel( 'html' );
		$assertion	= "[F] 3.1415926<br/>";
		$creation	= $output->printMixed( (float) 3.1415926, 0, NULL, NULL, NULL, TRUE );
		self::assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'printInteger'.
	 *	@access		public
	 *	@return		void
	 */
	public function testPrintInteger()
	{
		$output		= new DevOutput();
		$assertion	= "[I] 3\n";
		$creation	= $output->printMixed( (int) 3.1415926, 0, NULL, NULL, NULL, TRUE );
		self::assertEquals( $assertion, $creation );

		$output->setChannel( 'html' );
		$assertion	= "[I] 3<br/>";
		$creation	= $output->printMixed( (int) 3.1415926, 0, NULL, NULL, NULL, TRUE );
		self::assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'printMixed'.
	 *	@access		public
	 *	@return		void
	 */
	public function testPrint_M()
	{
		$assertion	= "\n[S] 123\n";
		$creation	= print_m( "123", NULL, NULL, TRUE );
		self::assertEquals( $assertion, $creation );

		$assertion	= "<br/>[S] 123<br/>";
		$creation	= print_m( "123", NULL, NULL, TRUE, 'html' );
		self::assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'printMixed'.
	 *	@access		public
	 *	@return		void
	 */
	public function testPrintMixed()
	{
		$output		= new DevOutput();
		$assertion	= "[S] 123\n";
		$creation	= $output->printMixed( "123", 0, NULL, NULL, NULL, TRUE );
		self::assertEquals( $assertion, $creation );

		$output->setChannel( 'html' );
		$assertion	= "[S] 123<br/>";
		$creation	= $output->printMixed( "123", 0, NULL, NULL, NULL, TRUE );
		self::assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'printNull'.
	 *	@access		public
	 *	@return		void
	 */
	public function testPrintNull()
	{
		$output		= new DevOutput();
		$assertion	= "[N] NULL\n";
		$creation	= $output->printMixed( NULL, 0, NULL, NULL, NULL, TRUE );
		self::assertEquals( $assertion, $creation );

		$output->setChannel( 'html' );
		$assertion	= "[N] <em>NULL</em><br/>";
		$creation	= $output->printMixed( NULL, 0, NULL, NULL, NULL, TRUE );
		self::assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'printObject'.
	 *	@access		public
	 *	@return		void
	 */
	public function testPrintObject()
	{
		$output		= new DevOutput();
		$object		= (object) array( 'a' => 1, 'b' => "2" );
		$assertion	= "[O] stdClass\n  [I] a => 1\n  [S] b => 2\n";
		$creation	= $output->printMixed( $object, 0, NULL, NULL, NULL, TRUE );
		self::assertEquals( $assertion, $creation );

		$output->setChannel( 'html' );
		$assertion	= "[O] <b>stdClass</b><br/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;[I] a => 1<br/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;[S] b => 2<br/>";
		$creation	= $output->printMixed( $object, 0, NULL, NULL, NULL, TRUE );
		self::assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'printResource'.
	 *	@access		public
	 *	@return		void
	 */
	public function testPrintResource()
	{
		$output		= new DevOutput();
		$resource	= fopen( __FILE__, "r" );
		$assertion	= "[R] Resource id #\n";
		$creation	= $output->printMixed( $resource, 0, NULL, NULL, NULL, TRUE );
		self::assertEquals( $assertion,preg_replace( "/[0-9]/", "", $creation ) );

		$output->setChannel( 'html' );
		$resource	= fopen( __FILE__, "r" );
		$assertion	= "[R] Resource id #<br/>";
		$creation	= $output->printMixed( $resource, 0, NULL, NULL, NULL, TRUE );
		self::assertEquals( $assertion,preg_replace( "/[0-9]/", "", $creation ) );
	}

	/**
	 *	Tests Method 'printString'.
	 *	@access		public
	 *	@return		void
	 */
	public function testPrintString()
	{
		$output		= new DevOutput();
		$assertion	= "[S] 123\n";
		$creation	= $output->printMixed( "123", 0, NULL, NULL, NULL, TRUE );
		self::assertEquals( $assertion, $creation );

		$output->setChannel( 'html' );
		$assertion	= "[S] 123<br/>";
		$creation	= $output->printMixed( "123", 0, NULL, NULL, NULL, TRUE );
		self::assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'remark'.
	 *	@access		public
	 *	@return		void
	 */
	public function testRemark()
	{
		$this->markTestIncomplete( 'Incomplete Test' );
		$assertion	= TRUE;
		$creation	= DevOutput::remark();
		self::assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'showDOM'.
	 *	@access		public
	 *	@return		void
	 */
	public function testShowDOM()
	{
		$this->markTestIncomplete( 'Incomplete Test' );
		$assertion	= TRUE;
		$creation	= DevOutput::showDOM();
		self::assertEquals( $assertion, $creation );
	}
}
