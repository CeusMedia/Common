<?php
/**
 *	TestUnit of Dictionary
 *	@package		Tests.adt.list
 *	@extends		PHPUnit_Framework_TestCase
 *	@uses			Test_ADT_List_Dictionay
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@version		0.1
 */
if( !class_exists( 'PHPUnit_Framework_TestCase' ) )
	require_once 'PHPUnit/Framework/TestCase.php';
require_once 'Test/initLoaders.php5';
/**
 *	TestUnit of Dictionary
 *	@package		Tests.adt.list
 *	@extends		PHPUnit_Framework_TestCase
 *	@uses			Test_ADT_List_Dictionay
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@version		0.1
 */
class Test_ADT_List_DictionaryTest extends PHPUnit_Framework_TestCase
{
	/**	@var	ADT_List_Dictionary		$list		Instance of Dictionary */
	private $dictionary;

	public function setUp()
	{
		$this->dictionary	= new ADT_List_Dictionary();
		$this->dictionary->set( 'key0', 0 );
		$this->dictionary->set( 'key1', 'value1' );
		$this->dictionary->set( 'key2', 'value2' );
		$this->dictionary->set( 'key3', array( 'value3-1', 'value3-2' ) );
		$this->dictionary->set( 'key4', array( 'key4-1' => 'value4-1', 'key4-2' => 'value4-2' ) );
		$this->dictionary->set( 'key5', new ADT_List_Dictionary( '0', '1' ) );
	}

	public function testConstruct()
	{
		$dictionary	= new ADT_List_Dictionary();
		$assertion	= 0;
		$creation	= $dictionary->count();
		$this->assertEquals( $assertion, $creation );

		$assertion	= array();
		$creation	= $dictionary->getAll();
		$this->assertEquals( $assertion, $creation );

		$dictionary	= new ADT_List_Dictionary( array( 1, 2, 3 ) );
		$assertion	= 3;
		$creation	= $dictionary->count();
		$this->assertEquals( $assertion, $creation );

		$assertion	= array( 1, 2, 3 );
		$creation	= $dictionary->getAll();
		$this->assertEquals( $assertion, $creation );

		$dictionary	= new ADT_List_Dictionary( array( 'a' => 'b', 'b' => 'c', 'c' => 'd' ) );
		$assertion	= array( 'a' => 'b', 'b' => 'c', 'c' => 'd' );
		$creation	= $dictionary->getAll();
		$this->assertEquals( $assertion, $creation );
	}

	public function testCast()
	{
		$assertion	= 2;
		$creation	= $this->dictionary->cast( "2", 'key0' );
		$this->assertEquals( $assertion, $creation );

		$assertion	= 3;
		$creation	= $this->dictionary->cast( M_PI, 'key0' );
		$this->assertEquals( $assertion, $creation );

		$assertion	= (string) M_PI;
		$creation	= $this->dictionary->cast( M_PI, 'key1' );
		$this->assertEquals( $assertion, $creation );

	}

	/**
	 *	@expectedException		InvalidArgumentException
	 */
	public function testCastException1()
	{
		$fp	= fopen( __FILE__, 'r' );
		$this->dictionary->cast( $fp, 'key1' );
	}

	/**
	 *	@expectedException		OutOfRangeException
	 */
	public function testCastException2()
	{
		$this->dictionary->cast( 'whatever', 'invalid' );
	}

	/**
	 *	@expectedException		UnexpectedValueException
	 */
	public function testCastException3()
	{
		$this->dictionary->cast( array(), 'key1' );
	}

	public function testCount()
	{
		$assertion	= 6;
		$creation	= $this->dictionary->count();
		$this->assertEquals( $assertion, $creation );
	}

	public function testCountableInterface()
	{
		$assertion	= 6;
		$creation	= count( $this->dictionary );
		$this->assertEquals( $assertion, $creation );
	}

	public function testFlush()
	{
		$this->dictionary->next();
		$this->dictionary->next();
		$this->assertEquals( 'value2', $this->dictionary->current() );
		$this->assertEquals( 'value2', $this->dictionary->current() );
		$this->dictionary->flush();
		$this->assertEquals( 0, $this->dictionary->count() );
		$this->dictionary->set( 'key1', 'value1' );
		$this->dictionary->set( 'key2', 'value2' );
		$this->dictionary->set( 'key3', 'value3' );
		$this->assertEquals( 'value1', $this->dictionary->current() );
	}

	public function testGet()
	{
		$assertion	= "value2";
		$creation	= $this->dictionary->get( 'key2' );
		$this->assertEquals( $assertion, $creation );

		$assertion	= array( 'value3-1', 'value3-2' );
		$creation	= $this->dictionary->get( 'key3' );
		$this->assertEquals( $assertion, $creation );

		$assertion	= NULL;
		$creation	= $this->dictionary->get( 'invalid' );
		$this->assertEquals( $assertion, $creation );
	}

	public function testGetWithDefault()
	{
		$assertion	= "value2";
		$creation	= $this->dictionary->get( 'key2', -1 );
		$this->assertEquals( $assertion, $creation );

		$assertion	= array( 'value3-1', 'value3-2' );
		$creation	= $this->dictionary->get( 'key3', -1 );
		$this->assertEquals( $assertion, $creation );

		$assertion	= -1;
		$creation	= $this->dictionary->get( 'invalid', -1 );
		$this->assertEquals( $assertion, $creation );
	}

	public function testGetAll()
	{
		$assertion	= array(
			'key0'	=> 0,
			'key1'	=> 'value1',
			'key2'	=> 'value2',
			'key3'	=> array( 'value3-1', 'value3-2' ),
			'key4'	=> array( 'key4-1' => 'value4-1', 'key4-2' => 'value4-2' ),
			'key5'	=> new ADT_List_Dictionary( '0', '1' ),
			);
		$creation	= $this->dictionary->getAll();
		$this->assertEquals( $assertion, $creation );
	}

	public function testGetAllWithPrefix()
	{
		$dictionary	= new ADT_List_Dictionary( array(
			'A.a'		=> 0,
			'A.a.1'		=> 1,
			'A.a.2'		=> 2,
			'A.b.1'		=> 3,
			'A.b.2'		=> 4,
			'B.a.1'		=> 5,
			'B.a.2'		=> 6,
			'B.b.1'		=> 7,
			'B.b.2'		=> 8,
		) );

		$assertion	= array( '1' => 7, '2' => 8  );
		$creation	= $dictionary->getAll( 'B.b.' );
		$this->assertEquals( $assertion, $creation );

		$assertion	= array( '.1' => 7, '.2' => 8  );
		$creation	= $dictionary->getAll( 'B.b' );
		$this->assertEquals( $assertion, $creation );

		$assertion	= array( 'a.1' => 5, 'a.2' => 6, 'b.1' => 7, 'b.2' => 8  );
		$creation	= $dictionary->getAll( 'B.' );
		$this->assertEquals( $assertion, $creation );

		$assertion	= array( '.a.1' => 5, '.a.2' => 6, '.b.1' => 7, '.b.2' => 8  );
		$creation	= $dictionary->getAll( 'B' );
		$this->assertEquals( $assertion, $creation );

		$assertion	= array( 'a' => 0, 'a.1' => 1, 'a.2' => 2, 'b.1' => 3, 'b.2' => 4 );
		$creation	= $dictionary->getAll( 'A.' );
		$this->assertEquals( $assertion, $creation );
	}

	public function testGetKeyOf()
	{
		$assertion	= 'key2';
		$creation	= $this->dictionary->getKeyOf( 'value2' );
		$this->assertEquals( $assertion, $creation );

		$assertion	= 'key0';
		$creation	= $this->dictionary->getKeyOf( 0 );
		$this->assertEquals( $assertion, $creation );

		$assertion	= null;
		$creation	= $this->dictionary->getKeyOf( 'invalid' );
		$this->assertEquals( $assertion, $creation );


		$array		= $this->dictionary->get( 'key3' );
		$assertion	= 0;
		$creation	= array_search( 'value3-1', $array );
		$this->assertEquals( $assertion, $creation );
	}

	public function testHas()
	{
		$creation	= $this->dictionary->has( 'key2' );
		$this->assertTrue( $creation );

		$creation	= $this->dictionary->has( 'invalid' );
		$this->assertFalse( $creation );

		$creation	= $this->dictionary->has( '0' );
		$this->assertFalse( $creation );
	}

	public function testRemove()
	{
		$this->dictionary->remove( 'key2' );
		$creation	= $this->dictionary->has( 'key2' );
		$this->assertFalse( $creation );

		$this->dictionary->remove( 'invalid' );
		$assertion	= 5;
		$creation	= $this->dictionary->count();
		$this->assertEquals( $assertion, $creation );
	}

	public function testIterator()
	{
		$list	= array();
		foreach( $this->dictionary as $key => $value )
			$list[$key]	= $value;
		$this->assertEquals( $list, $this->dictionary->getAll() );
	}

	public function testRemove2()
	{
		foreach( $this->dictionary->getKeys() as $key )
			$this->dictionary->remove( $key );

		$assertion	= 0;
		$creation	= $this->dictionary->count();
		$this->assertEquals( $assertion, $creation );
	}

	public function testSet()
	{
		$this->dictionary->set( 'key2', 'value2#' );
		$assertion	= 'value2#';
		$creation	= $this->dictionary->get( 'key2' );
		$this->assertEquals( $assertion, $creation );

		$this->dictionary->set( 'key6', 'value6' );
		$assertion	= 'value6';
		$creation	= $this->dictionary->get( 'key6' );
		$this->assertEquals( $assertion, $creation );
	}

	//  --  TESTS OF ARRAY ACCESS INTERFACE  --  //
	public function testOffsetExists()
	{
		$assertion	= true;
		$creation	= isset( $this->dictionary['key2'] );
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
		$this->dictionary['key2']	= "value2#";
		$assertion	= "value2#";
		$creation	= $this->dictionary['key2'];
		$this->assertEquals( $assertion, $creation );

		$this->dictionary['key6']	= "value6";
		$assertion	= "value6";
		$creation	= $this->dictionary['key6'];
		$this->assertEquals( $assertion, $creation );
	}

	public function testOffsetUnset()
	{
		unset( $this->dictionary['key2'] );
		$creation	= $this->dictionary->has( 'key2' );
		$this->assertFalse( $creation );

		unset( $this->dictionary['key2'] );
		$assertion	= 5;
		$creation	= $this->dictionary->count();
		$this->assertEquals( $assertion, $creation );
	}

	public function testOffsetUnset2()
	{
		foreach( $this->dictionary as $key => $value )
			unset( $this->dictionary[$key] );

		$assertion	= 0;
		$creation	= count( $this->dictionary );
		$this->assertEquals( $assertion, $creation );
	}

	//  --  TESTS OF ITERATOR INTERFACE  --  //
	public function testKey()
	{
		$assertion	= 'key0';
		$creation	= $this->dictionary->key();
		$this->assertEquals( $assertion, $creation );

		$assertion	= 'key0';
		$creation	= $this->dictionary->key();
		$this->assertEquals( $assertion, $creation );

		$this->dictionary->next();
		$assertion	= 'key1';
		$creation	= $this->dictionary->key();
		$this->assertEquals( $assertion, $creation );

		$assertion	= 6;
		$creation	= $this->dictionary->count();
		$this->assertEquals( $assertion, $creation );
	}

	public function testCurrent()
	{
		$assertion	= 0;
		$creation	= $this->dictionary->current();
		$this->assertEquals( $assertion, $creation );

		$assertion	= 0;
		$creation	= $this->dictionary->current();
		$this->assertEquals( $assertion, $creation );

		$this->dictionary->next();
		$assertion	= 'value1';
		$creation	= $this->dictionary->current();
		$this->assertEquals( $assertion, $creation );

		$assertion	= 6;
		$creation	= $this->dictionary->count();
		$this->assertEquals( $assertion, $creation );
	}

	public function testNext()
	{
		$assertion	= 0;
		$creation	= $this->dictionary->current();
		$this->assertEquals( $assertion, $creation );

		$assertion	= NULL;
		$creation	= $this->dictionary->next();
		$this->assertEquals( $assertion, $creation );

		$assertion	= 'value1';
		$creation	= $this->dictionary->current();
		$this->assertEquals( $assertion, $creation );

		$this->dictionary->next();
		$this->dictionary->next();
		$assertion	= array( 'value3-1', 'value3-2' );
		$creation	= $this->dictionary->current();
		$this->assertEquals( $assertion, $creation );

		$this->dictionary->next();
		$this->dictionary->next();
		$this->dictionary->next();
		$creation	= $this->dictionary->current();
		$this->assertNull( $creation );
	}

	public function testRewind()
	{
		$assertion	= 0;
		$creation	= $this->dictionary->current();
		$this->assertEquals( $assertion, $creation );

		$this->dictionary->next();
		$assertion	= 'value1';
		$creation	= $this->dictionary->current();
		$this->assertEquals( $assertion, $creation );

		$this->dictionary->rewind();
		$assertion	= 0;
		$creation	= $this->dictionary->current();
		$this->assertEquals( $assertion, $creation );
	}

 	public function testValid()
	{
		$this->dictionary->next();
		$creation	= $this->dictionary->valid();
		$this->assertTrue( $creation );

		$this->dictionary->next();
		$creation	= $this->dictionary->valid();
		$this->assertTrue( $creation );

		$this->dictionary->next();
		$creation	= $this->dictionary->valid();
		$this->assertTrue( $creation );

		$this->dictionary->next();
		$creation	= $this->dictionary->valid();
		$this->assertTrue( $creation );

		$this->dictionary->next();
		$creation	= $this->dictionary->valid();
		$this->assertTrue( $creation );

		$this->dictionary->next();
		$creation	= $this->dictionary->valid();
		$this->assertFalse( $creation );
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
