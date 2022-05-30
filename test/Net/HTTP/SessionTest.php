<?php
/**
 *	TestUnit of Session.
 *	@package		Tests.net.http
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@since			16.02.2008
 *
 */
declare( strict_types = 1 );

use CeusMedia\Common\Test\BaseCase;

/**
 *	TestUnit of Session.
 *	@package		Tests.net.http
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@since			16.02.2008
 *
 */
class Test_Net_HTTP_SessionTest extends BaseCase
{
	private $session;

	public function setUp(): void
	{
		$this->session		= @new Net_HTTP_Session();
		$this->session->clear();
	}

	public function testClear()
	{
		$_SESSION['key1']	= "value1";
		$this->session->clear();
		$assertion	= array();
		$creation	= $_SESSION;
		$this->assertEquals( $assertion, $creation );
	}

	public function testCount()
	{
		$_SESSION['key1']	= "value1";
		$assertion	= 1;
		$creation	= $this->session->count();
		$this->assertEquals( $assertion, $creation );
	}

	public function testGet()
	{
		$_SESSION['key1']	= "value1";
		$assertion	= "value1";
		$creation	= $this->session->get( 'key1' );
		$this->assertEquals( $assertion, $creation );
	}

	public function testGetAll()
	{
		$_SESSION['key1']	= "value1";
		$_SESSION['key2']	= "value2";
		$assertion	= (array) $_SESSION;
		$creation	= $this->session->getAll();
		$this->assertEquals( $assertion, $creation );
	}

	public function testHas()
	{
		$_SESSION['key3']	= "value3";
		$assertion	= "value3";
		$creation	= $this->session->get( 'key3' );
		$this->assertEquals( $assertion, $creation );
	}

	public function testOffsetExists()
	{
		$_SESSION['key4']	= "value4";
		$assertion	= true;
		$creation	= isset( $this->session['key4'] );
		$this->assertEquals( $assertion, $creation );

	}

	public function testOffsetGet()
	{
		$_SESSION['key5']	= "value5";
		$assertion	= "value5";
		$creation	= $this->session['key5'];
		$this->assertEquals( $assertion, $creation );
	}

	public function testOffsetSet()
	{
		$this->session['key6']	= "value6";
		$assertion	= "value6";
		$creation	= $_SESSION['key6'];
		$this->assertEquals( $assertion, $creation );
	}

	public function testOffsetUnset()
	{
		$_SESSION['key7']	= "value7";
		unset( $this->session['key7'] );
		$assertion	= false;
		$creation	= isset( $_SESSION['key7'] );
		$this->assertEquals( $assertion, $creation );
	}

	public function testRemove()
	{
		$_SESSION['key8']	= "value8";
		$this->session->remove( 'key8' );
		$assertion	= false;
		$creation	= isset( $_SESSION['key8'] );
		$this->assertEquals( $assertion, $creation );
	}

	public function testSet()
	{
		$this->session->clear();
		$this->session->set( 'key9', "value9" );
		$assertion	= "value9";
		$creation	= $_SESSION['key9'];
		$this->assertEquals( $assertion, $creation );
	}
}
