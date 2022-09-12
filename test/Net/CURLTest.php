<?php
/** @noinspection PhpIllegalPsrClassPathInspection */
/** @noinspection PhpMultipleClassDeclarationsInspection */
/** @noinspection PhpUnhandledExceptionInspection */
/** @noinspection PhpDocMissingThrowsInspection */

declare( strict_types = 1 );

/**
 *	TestUnit of Net_CURL.
 *	@package		Tests.Net
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 */

namespace CeusMedia\CommonTest\Net;

use CeusMedia\Common\Net\CURL as Net_CURL;
use CeusMedia\CommonTest\BaseCase;

/**
 *	TestUnit of Net_CURL.
 *	@package		Tests.Net
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 */
class CURLTest extends BaseCase
{
	/**
	 *	Setup for every Test.
	 *	@access		public
	 *	@return		void
	 */
	public function setUp(): void
	{
		if( !extension_loaded( 'curl' ) )
			$this->markTestSkipped( 'Missing cURL support' );
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
	public function test__construct()
	{
		$this->markTestIncomplete( 'Incomplete Test' );
		$assertion	= TRUE;
		$creation	= Net_CURL::__construct();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'close'.
	 *	@access		public
	 *	@return		void
	 */
	public function testClose()
	{
		$this->markTestIncomplete( 'Incomplete Test' );
		$assertion	= TRUE;
		$creation	= Net_CURL::close();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'exec'.
	 *	@access		public
	 *	@return		void
	 */
	public function testExec()
	{
		$this->markTestIncomplete( 'Incomplete Test' );
		$assertion	= TRUE;
		$creation	= Net_CURL::exec();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Exception of Method 'exec'.
	 *	@access		public
	 *	@return		void
	 */
	public function testExecException1()
	{
		$this->markTestIncomplete( 'Incomplete Test' );
		$this->expectException( 'RuntimeException' );
		Net_CURL::exec();
	}

	/**
	 *	Tests Exception of Method 'exec'.
	 *	@access		public
	 *	@return		void
	 */
	public function testExecException2()
	{
		$this->markTestIncomplete( 'Incomplete Test' );
		$this->expectException( 'InvalidArgumentException' );
		Net_CURL::exec();
	}

	/**
	 *	Tests Exception of Method 'exec'.
	 *	@access		public
	 *	@return		void
	 */
	public function testExecException3()
	{
		$this->markTestIncomplete( 'Incomplete Test' );
		$this->expectException( 'RuntimeException' );
		Net_CURL::exec();
	}

	/**
	 *	Tests Method 'getHeader'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetHeader()
	{
		$this->markTestIncomplete( 'Incomplete Test' );
		$assertion	= TRUE;
		$creation	= Net_CURL::getHeader();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'getOption'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetOption()
	{
		$this->markTestIncomplete( 'Incomplete Test' );
		$assertion	= TRUE;
		$creation	= Net_CURL::getOption();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'getStatus'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetStatus()
	{
		$this->markTestIncomplete( 'Incomplete Test' );
		$assertion	= TRUE;
		$creation	= Net_CURL::getStatus();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Exception of Method 'getStatus'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetStatusException()
	{
		$this->markTestIncomplete( 'Incomplete Test' );
		$this->expectException( 'RuntimeException' );
		Net_CURL::getStatus();
	}

	/**
	 *	Tests Method 'hasError'.
	 *	@access		public
	 *	@return		void
	 */
	public function testHasError()
	{
		$this->markTestIncomplete( 'Incomplete Test' );
		$assertion	= TRUE;
		$creation	= Net_CURL::hasError();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'parseHeader'.
	 *	@access		public
	 *	@return		void
	 */
	public function testParseHeader()
	{
		$this->markTestIncomplete( 'Incomplete Test' );
		$assertion	= TRUE;
		$creation	= Net_CURL::parseHeader();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'setOption'.
	 *	@access		public
	 *	@return		void
	 */
	public function testSetOption()
	{
		$this->markTestIncomplete( 'Incomplete Test' );
		$assertion	= TRUE;
		$creation	= Net_CURL::setOption();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Exception of Method 'setOption'.
	 *	@access		public
	 *	@return		void
	 */
	public function testSetOptionException1()
	{
		$this->markTestIncomplete( 'Incomplete Test' );
		$this->expectException( 'InvalidArgumentException' );
		Net_CURL::setOption();
	}

	/**
	 *	Tests Exception of Method 'setOption'.
	 *	@access		public
	 *	@return		void
	 */
	public function testSetOptionException2()
	{
		$this->markTestIncomplete( 'Incomplete Test' );
		$this->expectException( 'InvalidArgumentException' );
		Net_CURL::setOption();
	}

	/**
	 *	Tests Method 'setTimeOut'.
	 *	@access		public
	 *	@return		void
	 */
	public function testSetTimeOut()
	{
		$this->markTestIncomplete( 'Incomplete Test' );
		$assertion	= TRUE;
		$creation	= Net_CURL::setTimeOut();
		$this->assertEquals( $assertion, $creation );
	}
}
