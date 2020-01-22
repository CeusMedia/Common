<?php
/**
 *	TestUnit of partioned Session.
 *	@package		Tests.net.http
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@since			16.02.2008
 *	@version		0.1
 */
declare( strict_types = 1 );

use PHPUnit\Framework\TestCase;

/**
 *	TestUnit of partioned Session.
 *	@package		Tests.net.http
 *	@extends		Test_Case
 *	@uses			Net_HTTP_PartitionSession
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@since			16.02.2008
 *	@version		0.1
 */
class Test_Net_HTTP_PartitionSessionTest extends Test_Case
{
	private $session;

	public function setUp()
	{
//		if( session_status() === PHP_SESSION_ACTIVE )
//			session_destroy();
		$this->session		= new Net_HTTP_PartitionSession( 'test' );
		$this->session->clear();
	}

	public function tearDown()
	{
//		if( session_status() === PHP_SESSION_ACTIVE )
//			session_destroy();
	}

	public function testClear()
	{
		$this->session->set( 'key1', "value1" );
		$this->session->clear();
		$assertion	= array();
		$creation	= $this->session->getAll();
		$this->assertEquals( $assertion, $creation );
	}

	public function testCount()
	{
		$this->session->set( 'key1', "value1" );
		$assertion	= 1;
		$creation	= $this->session->count();
		$this->assertEquals( $assertion, $creation );
	}

	public function testGet()
	{
		$this->session->set( 'key1', "value1" );
		$assertion	= "value1";
		$creation	= $this->session->get( 'key1' );
		$this->assertEquals( $assertion, $creation );
	}

	public function testGetAll()
	{
		$this->session->set( 'key1', "value1" );
		$this->session->set( 'key2', "value2" );
		$assertion	= array( 'key1' => 'value1', 'key2' => 'value2' );
		$creation	= $this->session->getAll();
		$this->assertEquals( $assertion, $creation );
	}

	public function testHas()
	{
		$this->session->set( 'key3', "value3" );
		$assertion	= "value3";
		$creation	= $this->session->get( 'key3' );
		$this->assertEquals( $assertion, $creation );
	}

	public function testOffsetExists()
	{
		$this->session->set( 'key4', "value4" );
		$assertion	= true;
		$creation	= isset( $this->session['key4'] );
		$this->assertEquals( $assertion, $creation );

	}

	public function testOffsetGet()
	{
		$this->session->set( 'key5', "value5" );
		$assertion	= "value5";
		$creation	= $this->session['key5'];
		$this->assertEquals( $assertion, $creation );
	}

	public function testOffsetSet()
	{
		$this->session['key6']	= "value6";
		$assertion	= "value6";
		$creation	= $this->session->get( 'key6' );
		$this->assertEquals( $assertion, $creation );
	}

	public function testOffsetUnset()
	{
		$this->session->set( 'key7', "value7" );
		unset( $this->session['key7'] );
		$assertion	= FALSE;
		$creation	= $this->session->has( 'key7' );
		$this->assertEquals( $assertion, $creation );
	}

	public function testRemove()
	{
		$this->session->set( 'key8', "value8" );
		$this->session->remove( 'key8' );
		$assertion	= FALSE;
		$creation	= $this->session->has( 'key8' );
		$this->assertEquals( $assertion, $creation );
	}

	public function testSet()
	{
		$this->session->clear();
		$this->session->set( 'key9', "value9" );
		$assertion	= "value9";
		$creation	= $this->session->get( 'key9' );
		$this->assertEquals( $assertion, $creation );
	}
}
