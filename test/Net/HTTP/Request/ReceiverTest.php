<?php
declare( strict_types = 1 );

/**
 *	TestUnit of Request Receiver.
 *	@package		Tests.net.http.request
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *
 */

namespace CeusMedia\CommonTest\Net\HTTP;

use CeusMedia\CommonTest\BaseCase;
use CeusMedia\Common\Net\HTTP\Request\Receiver;

/**
 *	TestUnit of Request Receiver.
 *	@package		Tests.net.http.request
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *
 */
class ReceiverTest extends BaseCase
{
	/**	@var	array		$list		Instance of Request Receiver */
	private $receiver;

	public function setUp(): void
	{
		$this->receiver	= new Receiver();
		$this->receiver->set( 'key1', 'value1' );
		$this->receiver->set( 'key2', 'value2' );
		$this->receiver->set( 'key3', 'value3' );
	}

	public function testCount()
	{
		$assertion	= 3;
		$creation	= $this->receiver->count();
		self::assertEquals( $assertion, $creation );

		$assertion	= 3;
		$creation	= count( $this->receiver );
		self::assertEquals( $assertion, $creation );
	}

	public function testGet()
	{
		$assertion	= "value3";
		$creation	= $this->receiver->get( 'key3' );
		self::assertEquals( $assertion, $creation );
		$assertion	= null;
		$creation	= $this->receiver->get( 'key4' );
		self::assertEquals( $assertion, $creation );
	}

	public function testGetAll()
	{
		$assertion	= array(
			'key1' => 'value1',
			'key2' => 'value2',
			'key3' => 'value3',
			);
		$creation	= $this->receiver->getAll();
		self::assertEquals( $assertion, $creation );
	}

	public function testGetKeyOf()
	{
		$assertion	= 'key2';
		$creation	= $this->receiver->getKeyOf( 'value2' );
		self::assertEquals( $assertion, $creation );
		$assertion	= null;
		$creation	= $this->receiver->getKeyOf( 'value4' );
		self::assertEquals( $assertion, $creation );
	}

	public function testHas()
	{
		$assertion	= true;
		$creation	= $this->receiver->has( 'key2' );
		self::assertEquals( $assertion, $creation );
		$assertion	= false;
		$creation	= $this->receiver->has( 'key4' );
		self::assertEquals( $assertion, $creation );
	}

	public function testRemove()
	{
		$this->receiver->remove( 'key2' );
		$assertion	= false;
		$creation	= $this->receiver->has( 'key2' );
		self::assertEquals( $assertion, $creation );
	}

	public function testSet()
	{
		$this->receiver->set( 'key4', 'value4' );
		$assertion	= 'value4';
		$creation	= $this->receiver->get( 'key4' );
		self::assertEquals( $assertion, $creation );
	}

/*	public function testToString()
	{
		$this->receiver->remove( 'key3' );
		$assertion	= "{(key1=>value1), (key2=>value2)}";
		$creation	= $this->receiver->__toString();
		self::assertEquals( $assertion, $creation );
	}
*/

	//  --  TESTS OF ARRAY ACCESS INTERFACE  --  //
	public function testOffsetExists()
	{
		$assertion	= true;
		$creation	= isset( $this->receiver['key2'] );
		self::assertEquals( $assertion, $creation );
	}

	public function testOffsetGet()
	{
		$assertion	= "value2";
		$creation	= $this->receiver['key2'];
		self::assertEquals( $assertion, $creation );
	}

	public function testOffsetSet()
	{
		$this->receiver['key4']	= "value4";
		$assertion	= "value4";
		$creation	= $this->receiver['key4'];;
		self::assertEquals( $assertion, $creation );
	}

	public function testOffsetUnset()
	{
		unset( $this->receiver['key2'] );
		$assertion	= false;
		$creation	= $this->receiver->has( 'key2' );
		self::assertEquals( $assertion, $creation );
	}

	//  --  TESTS OF ITERATOR INTERFACE  --  //
	public function testKey()
	{
		$assertion	= 'key1';
		$creation	= $this->receiver->key();
		self::assertEquals( $assertion, $creation );
	}

	public function testCurrent()
	{
		$assertion	= 'value1';
		$creation	= $this->receiver->current();
		self::assertEquals( $assertion, $creation );
	}

	public function testNext()
	{
		$this->receiver->next();
		$assertion	= 'value2';
		$creation	= $this->receiver->current();
		self::assertEquals( $assertion, $creation );
	}

	public function testRewind()
	{
		$this->receiver->next();
		$this->receiver->rewind();
		$assertion	= 'value1';
		$creation	= $this->receiver->current();
		self::assertEquals( $assertion, $creation );
	}

	public function testValid()
	{
		$this->receiver->next();
		$this->receiver->next();
		$this->receiver->next();
		$this->receiver->next();
		$assertion	= false;
		$creation	= $this->receiver->valid();
		self::assertEquals( $assertion, $creation );
	}

	public function testGetAllFromSource()
	{
		$_GET['key1']	= "value2";
		$assertion	= array( 'key1' => "value2" );
		$creation	= $this->receiver->getAllFromSource( 'get' );
		self::assertEquals( $assertion, $creation );
	}
}
