<?php
/** @noinspection PhpIllegalPsrClassPathInspection */
/** @noinspection PhpMultipleClassDeclarationsInspection */
/** @noinspection PhpUnhandledExceptionInspection */
/** @noinspection PhpDocMissingThrowsInspection */

declare( strict_types = 1 );

/**
 *	TestUnit of Session.
 *	@package		Tests.net.http
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 */

namespace CeusMedia\CommonTest\Net\HTTP;

use CeusMedia\Common\Net\HTTP\Session;
use CeusMedia\CommonTest\BaseCase;

/**
 *	TestUnit of Session.
 *	@package		Tests.net.http
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 */
class SessionTest extends BaseCase
{
	private $session;

	public function setUp(): void
	{
		$this->session		= @new Session();
		$this->session->clear();
	}

	public function testClear()
	{
		$_SESSION['key1']	= "value1";
		$this->session->clear();
		$assertion	= [];
		$creation	= $_SESSION;
		self::assertEquals( $assertion, $creation );
	}

	public function testCount()
	{
		$_SESSION['key1']	= "value1";
		$assertion	= 1;
		$creation	= $this->session->count();
		self::assertEquals( $assertion, $creation );
	}

	public function testGet()
	{
		$_SESSION['key1']	= "value1";
		$assertion	= "value1";
		$creation	= $this->session->get( 'key1' );
		self::assertEquals( $assertion, $creation );
	}

	public function testGetAll()
	{
		$_SESSION['key1']	= "value1";
		$_SESSION['key2']	= "value2";
		$assertion	= $_SESSION;
		$creation	= $this->session->getAll();
		self::assertEquals( $assertion, $creation );
	}

	public function testHas()
	{
		$_SESSION['key3']	= "value3";
		$assertion	= "value3";
		$creation	= $this->session->get( 'key3' );
		self::assertEquals( $assertion, $creation );
	}

	public function testOffsetExists()
	{
		$_SESSION['key4']	= "value4";

		$creation	= isset( $this->session['key4'] );
		self::assertTrue( $creation );

	}

	public function testOffsetGet()
	{
		$_SESSION['key5']	= "value5";
		$assertion	= "value5";
		$creation	= $this->session['key5'];
		self::assertEquals( $assertion, $creation );
	}

	public function testOffsetSet()
	{
		$this->session['key6']	= "value6";
		$assertion	= "value6";
		$creation	= $_SESSION['key6'];
		self::assertEquals( $assertion, $creation );
	}

	public function testOffsetUnset()
	{
		$_SESSION['key7']	= "value7";
		unset( $this->session['key7'] );

		$creation	= isset( $_SESSION['key7'] );
		self::assertFalse( $creation );
	}

	public function testRemove()
	{
		$_SESSION['key8']	= "value8";
		$this->session->remove( 'key8' );

		$creation	= isset( $_SESSION['key8'] );
		self::assertFalse( $creation );
	}

	public function testSet()
	{
		$this->session->clear();
		$this->session->set( 'key9', "value9" );
		$assertion	= "value9";
		$creation	= $_SESSION['key9'];
		self::assertEquals( $assertion, $creation );
	}
}
