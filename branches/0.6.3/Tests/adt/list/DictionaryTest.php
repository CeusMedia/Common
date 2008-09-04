<?php
/**
 *	TestUnit of Dictionary
 *	@package		Tests.adt.list
 *	@extends		PHPUnit_Framework_TestCase
 *	@uses			ADT_List_Dictionay
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.1
 */
require_once 'PHPUnit/Framework/TestCase.php'; 
require_once( 'Tests/initLoaders.php5' );
import( 'de.ceus-media.adt.list.Dictionary' );
/**
 *	TestUnit of Dictionary
 *	@package		Tests.adt.list
 *	@extends		PHPUnit_Framework_TestCase
 *	@uses			ADT_List_Dictionay
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.1
 */
class Tests_ADT_List_DictionaryTest extends PHPUnit_Framework_TestCase
{
	/**	@var	array		$list		Instance of Dictionary */
	private $dictionary;
	
	public function setUp()
	{
		$this->dictionary	= new ADT_List_Dictionary();
		$this->dictionary->set( 'key1', 'value1' );
		$this->dictionary->set( 'key2', 'value2' );
		$this->dictionary->set( 'key3', 'value3' );
	}

	public function testCount()
	{
		$assertion	= 3;
		$creation	= $this->dictionary->count();
		$this->assertEquals( $assertion, $creation );

		$assertion	= 3;
		$creation	= count( $this->dictionary );
		$this->assertEquals( $assertion, $creation );
	}

	public function testGet()
	{
		$assertion	= "value3";
		$creation	= $this->dictionary->get( 'key3' );
		$this->assertEquals( $assertion, $creation );
		$assertion	= null;
		$creation	= $this->dictionary->get( 'key4' );
		$this->assertEquals( $assertion, $creation );
	}

	public function testGetAll()
	{
		$assertion	= array(
			'key1' => 'value1',
			'key2' => 'value2',
			'key3' => 'value3',
			);
		$creation	= $this->dictionary->getAll();
		$this->assertEquals( $assertion, $creation );
	}

	public function testGetKeyOf()
	{
		$assertion	= 'key2';
		$creation	= $this->dictionary->getKeyOf( 'value2' );
		$this->assertEquals( $assertion, $creation );
		$assertion	= null;
		$creation	= $this->dictionary->getKeyOf( 'value4' );
		$this->assertEquals( $assertion, $creation );
	}

	public function testHas()
	{
		$assertion	= true;
		$creation	= $this->dictionary->has( 'key2' );
		$this->assertEquals( $assertion, $creation );
		$assertion	= false;
		$creation	= $this->dictionary->has( 'key4' );
		$this->assertEquals( $assertion, $creation );
	}
	
	public function testRemove()
	{
		$this->dictionary->remove( 'key2' );
		$assertion	= false;
		$creation	= $this->dictionary->has( 'key2' );
		$this->assertEquals( $assertion, $creation );
	}
	
	public function testSet()
	{
		$this->dictionary->set( 'key4', 'value4' );
		$assertion	= 'value4';
		$creation	= $this->dictionary->get( 'key4' );
		$this->assertEquals( $assertion, $creation );
	}

	public function testToString()
	{
		$this->dictionary->remove( 'key3' );
		$assertion	= "{(key1=>value1), (key2=>value2)}";
		$creation	= $this->dictionary->__toString();
		$this->assertEquals( $assertion, $creation );
	}


	//  --  TESTS OF ARRAY ACCESS INTERFACE  --  //
	public function testOffsetExists()
	{
		$assertion	= true;
		$creation	=$this->dictionary['key2'];
		$this->assertEquals( $assertion, $creation );
	}
	
	public function testOffsetGet()
	{
		$assertion	= "value2";
		$creation	=$this->dictionary['key2'];
		$this->assertEquals( $assertion, $creation );
	}
	
	public function testOffsetSet()
	{
		$this->dictionary['key4']	= "value4";
		$assertion	= "value4";
		$creation	= $this->dictionary['key4'];;
		$this->assertEquals( $assertion, $creation );
	}
	
	public function testOffsetUnset()
	{
		unset( $this->dictionary['key2'] );
		$assertion	= false;
		$creation	= $this->dictionary->has( 'key2' );
		$this->assertEquals( $assertion, $creation );
	}
	

	//  --  TESTS OF ITERATOR INTERFACE  --  //
	public function testKey()
	{
		$assertion	= 'key1';
		$creation	= $this->dictionary->key();
		$this->assertEquals( $assertion, $creation );
	}

	public function testCurrent()
	{
		$assertion	= 'value1';
		$creation	= $this->dictionary->current();
		$this->assertEquals( $assertion, $creation );
	}

	public function testNext()
	{
		$this->dictionary->next();
		$assertion	= 'value2';
		$creation	= $this->dictionary->current();
		$this->assertEquals( $assertion, $creation );
	}

	public function testRewind()
	{
		$this->dictionary->next();
		$this->dictionary->rewind();
		$assertion	= 'value1';
		$creation	= $this->dictionary->current();
		$this->assertEquals( $assertion, $creation );
	}

	public function testValid()
	{
		$this->dictionary->next();
		$this->dictionary->next();
		$this->dictionary->next();
		$this->dictionary->next();
		$assertion	= false;
		$creation	= $this->dictionary->valid();
		$this->assertEquals( $assertion, $creation );
	}


	//  --  TEST OF ITERATOR AGGREGATE INTERFACE  --  //
/*	public function testGetIterator()
	{
		$it	= $this->dictionary->getIterator();
		foreach( $it as $key => $value )
			$array[$key]	= $value;
		$assertion	= array(
			'key1' => 'value1',
			'key2' => 'value2',
			'key3' => 'value3',
			);
		$creation	= $array;
		$this->assertEquals( $assertion, $creation );
	}*/
}
?>