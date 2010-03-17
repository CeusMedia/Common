<?php
/**
 *	TestUnit of Request Receiver.
 *	@package		Tests.net.http.request
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			16.02.2008
 *	@version		0.1
 */
require_once 'PHPUnit/Framework/TestCase.php';
require_once 'Test/initLoaders.php5';
/**
 *	TestUnit of Request Receiver.
 *	@package		Tests.net.http.request
 *	@extends		PHPUnit_Framework_TestCase
 *	@uses			Net_HTTP_Request_Receiver
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			16.02.2008
 *	@version		0.1
 */
class Test_Net_HTTP_Request_ReceiverTest extends PHPUnit_Framework_TestCase
{
	/**	@var	array		$list		Instance of Request Receiver */
	private $receiver;
	
	public function setUp()
	{
		$this->receiver	= new Net_HTTP_Request_Receiver();
		$this->receiver->set( 'key1', 'value1' );
		$this->receiver->set( 'key2', 'value2' );
		$this->receiver->set( 'key3', 'value3' );
	}

	public function testCount()
	{
		$assertion	= 3;
		$creation	= $this->receiver->count();
		$this->assertEquals( $assertion, $creation );

		$assertion	= 3;
		$creation	= count( $this->receiver );
		$this->assertEquals( $assertion, $creation );
	}

	public function testGet()
	{
		$assertion	= "value3";
		$creation	= $this->receiver->get( 'key3' );
		$this->assertEquals( $assertion, $creation );
		$assertion	= null;
		$creation	= $this->receiver->get( 'key4' );
		$this->assertEquals( $assertion, $creation );
	}

	public function testGetAll()
	{
		$assertion	= array(
			'key1' => 'value1',
			'key2' => 'value2',
			'key3' => 'value3',
			);
		$creation	= $this->receiver->getAll();
		$this->assertEquals( $assertion, $creation );
	}

	public function testGetKeyOf()
	{
		$assertion	= 'key2';
		$creation	= $this->receiver->getKeyOf( 'value2' );
		$this->assertEquals( $assertion, $creation );
		$assertion	= null;
		$creation	= $this->receiver->getKeyOf( 'value4' );
		$this->assertEquals( $assertion, $creation );
	}

	public function testHas()
	{
		$assertion	= true;
		$creation	= $this->receiver->has( 'key2' );
		$this->assertEquals( $assertion, $creation );
		$assertion	= false;
		$creation	= $this->receiver->has( 'key4' );
		$this->assertEquals( $assertion, $creation );
	}
	
	public function testRemove()
	{
		$this->receiver->remove( 'key2' );
		$assertion	= false;
		$creation	= $this->receiver->has( 'key2' );
		$this->assertEquals( $assertion, $creation );
	}
	
	public function testSet()
	{
		$this->receiver->set( 'key4', 'value4' );
		$assertion	= 'value4';
		$creation	= $this->receiver->get( 'key4' );
		$this->assertEquals( $assertion, $creation );
	}

	public function testToString()
	{
		$this->receiver->remove( 'key3' );
		$assertion	= "{(key1=>value1), (key2=>value2)}";
		$creation	= $this->receiver->__toString();
		$this->assertEquals( $assertion, $creation );
	}


	//  --  TESTS OF ARRAY ACCESS INTERFACE  --  //
	public function testOffsetExists()
	{
		$assertion	= true;
		$creation	=$this->receiver['key2'];
		$this->assertEquals( $assertion, $creation );
	}
	
	public function testOffsetGet()
	{
		$assertion	= "value2";
		$creation	=$this->receiver['key2'];
		$this->assertEquals( $assertion, $creation );
	}
	
	public function testOffsetSet()
	{
		$this->receiver['key4']	= "value4";
		$assertion	= "value4";
		$creation	= $this->receiver['key4'];;
		$this->assertEquals( $assertion, $creation );
	}
	
	public function testOffsetUnset()
	{
		unset( $this->receiver['key2'] );
		$assertion	= false;
		$creation	= $this->receiver->has( 'key2' );
		$this->assertEquals( $assertion, $creation );
	}
	

	//  --  TESTS OF ITERATOR INTERFACE  --  //
	public function testKey()
	{
		$assertion	= 'key1';
		$creation	= $this->receiver->key();
		$this->assertEquals( $assertion, $creation );
	}

	public function testCurrent()
	{
		$assertion	= 'value1';
		$creation	= $this->receiver->current();
		$this->assertEquals( $assertion, $creation );
	}

	public function testNext()
	{
		$this->receiver->next();
		$assertion	= 'value2';
		$creation	= $this->receiver->current();
		$this->assertEquals( $assertion, $creation );
	}

	public function testRewind()
	{
		$this->receiver->next();
		$this->receiver->rewind();
		$assertion	= 'value1';
		$creation	= $this->receiver->current();
		$this->assertEquals( $assertion, $creation );
	}

	public function testValid()
	{
		$this->receiver->next();
		$this->receiver->next();
		$this->receiver->next();
		$this->receiver->next();
		$assertion	= false;
		$creation	= $this->receiver->valid();
		$this->assertEquals( $assertion, $creation );
	}
	
	public function testGetAllFromSource()
	{
		$_GET['key1']	= "value2";
		$assertion	= array( 'key1' => "value2" );
		$creation	= $this->receiver->getAllFromSource( 'get' );
		$this->assertEquals( $assertion, $creation );
	}
}
?>