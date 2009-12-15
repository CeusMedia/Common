<?php
/**
 *	TestUnit of Option Object
 *	@package		adt
 *	@extends		PHPUnit_Framework_TestCase
 *	@uses			ADT_OptionObject
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.1
 */
require_once 'PHPUnit/Framework/TestCase.php';
require_once 'test/initLoaders.php5';
/**
 *	TestUnit of LinkList
 *	@package		Tests.adt.list
 *	@extends		PHPUnit_Framework_TestCase
 *	@uses			ADT_OptionObject
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.1
 */
class Test_ADT_OptionObjectTest extends PHPUnit_Framework_TestCase
{
	public function setUp()
	{
		$this->object	= new ADT_OptionObject();
		$this->object->setOption( "string1", "value1" );
		$this->object->setOption( "boolean1", true );
		$this->object->setOption( "double1", M_PI );
		$this->object->setOption( "array1", array( "key" => "value" ) );
	}

	/**
	 *	Tests Method '__construct'.
	 *	@access		public
	 *	@return		void
	 */
	public function testConstruct()
	{
		$object		= new ADT_OptionObject();
		$assertion	= array();
		$creation	= $object->getOptions();
		$this->assertEquals( $assertion, $creation );

		$pairs		= array(
			'key1'	=> "param1",
			'key2'	=> "param2",
		);

		$object		= new ADT_OptionObject( $pairs );
		$assertion	= $pairs;
		$creation	= $object->getOptions();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Exception of Method '__construct'.
	 *	@access		public
	 *	@return		void
	 */
	public function testConstructException1()
	{
		$this->setExpectedException( 'InvalidArgumentException' );
		new ADT_OptionObject( "string" );
	}

	/**
	 *	Tests Exception of Method '__construct'.
	 *	@access		public
	 *	@return		void
	 */
	public function testConstructException2()
	{
		$this->setExpectedException( 'InvalidArgumentException' );
		new ADT_OptionObject( array( 1, 2 ) );
	}

	/**
	 *	Tests Method 'clearOptions'.
	 *	@access		public
	 *	@return		void
	 */
	public function testClearOptions()
	{
		$assertion	= TRUE;
		$creation	= $this->object->clearOptions();
		$this->assertEquals( $assertion, $creation );

		$assertion	= array();
		$creation	= $this->object->getOptions();
		$this->assertEquals( $assertion, $creation );

		$assertion	= FALSE;
		$creation	= $this->object->clearOptions();
		$this->assertEquals( $assertion, $creation );

		$assertion	= array();
		$creation	= $this->object->getOptions();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'clearOptions'.
	 *	@access		public
	 *	@return		void
	 */
	public function testCount()
	{
		$assertion	= 4;
		$creation	= $this->object->count();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'declareOptions'.
	 *	@access		public
	 *	@return		void
	 */
	public function testDeclareOptions()
	{
		$optionKeys	= array( "key1", "key2" );
		$object		= new ADT_OptionObject();
		$object->declareOptions( $optionKeys );
		
		$assertion	= $optionKeys;
		$creation	= array_keys( $object->getOptions() );
		$this->assertEquals( $assertion, $creation );
		
		$assertion	= NULL;
		$creation	= $object->getOption( "key1" );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Exception of Method 'declareOptions'.
	 *	@access		public
	 *	@return		void
	 */
	public function testDeclareOptionsException1()
	{
		$this->setExpectedException( 'InvalidArgumentException' );
		$object		= new ADT_OptionObject();
		$object->declareOptions( "string" );
	}

	/**
	 *	Tests Exception of Method 'declareOptions'.
	 *	@access		public
	 *	@return		void
	 */
	public function testDeclareOptionsException2()
	{
		$this->setExpectedException( 'InvalidArgumentException' );
		$object		= new ADT_OptionObject();
		$object->declareOptions( array( "a", 1 )  );
	}


	/**
	 *	Tests Method 'testGetOption'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetOption()
	{
		//  get String
		$assertion	= "value1";
		$creation	= $this->object->getOption( "string1" );
		$this->assertEquals( $assertion, $creation );

		//  get Boolean
		$assertion	= true;
		$creation	= $this->object->getOption( "boolean1" );
		$this->assertEquals( $assertion, $creation );

		//  get Double
		$assertion	= M_PI;
		$creation	= $this->object->getOption( "double1" );
		$this->assertEquals( $assertion, $creation );

		//  get Array
		$assertion	= array( "key" => "value" );
		$creation	= $this->object->getOption( "array1" );
		$this->assertEquals( $assertion, $creation );

		//  get NULL
		$assertion	= NULL;
		$creation	= $this->object->getOption( "not_existing", FALSE );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Exception of Method 'testGetOption'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetOptionException()
	{
		$this->setExpectedException( 'OutOfRangeException' );
		$this->object->getOption( 'not_existing' );
	}

	/**
	 *	Tests Method 'getOptions'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetOptions()
	{

		$assertion	= array(
			"string1"	=> "value1",
			"boolean1"	=> true,
			"double1"	=> M_PI,
			"array1"	=> array( "key" => "value" )
		);
		$creation	= $this->object->getOptions();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'hasOption'.
	 *	@access		public
	 *	@return		void
	 */
	public function testHasOptions()
	{
		//  check existing Option
		$assertion	= true;
		$creation	= $this->object->hasOption( "string1" );
		$this->assertEquals( $assertion, $creation );

		//  check not existing Option
		$assertion	= false;
		$creation	= $this->object->hasOption( "string2" );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'offsetExists'.
	 *	@access		public
	 *	@return		void
	 */
	public function testOffsetExists()
	{
		//  check existing Option
		$assertion	= true;
		$creation	= isset( $this->object["string1"] );
		$this->assertEquals( $assertion, $creation );

		//  check not existing Option
		$assertion	= false;
		$creation	= isset( $this->object["string2"] );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'offsetGet'.
	 *	@access		public
	 *	@return		void
	 */
	public function testOffsetGet()
	{
		//  get String
		$assertion	= "value1";
		$creation	= $this->object["string1"];
		$this->assertEquals( $assertion, $creation );

		//  get Boolean
		$assertion	= true;
		$creation	= $this->object["boolean1"];
		$this->assertEquals( $assertion, $creation );

		//  get Double
		$assertion	= M_PI;
		$creation	= $this->object["double1"];
		$this->assertEquals( $assertion, $creation );

		//  get Array
		$assertion	= array( "key" => "value" );
		$creation	= $this->object["array1"];
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'offsetSet'
	 *	@access		public
	 *	@return		void
	 */
	public function testOffsetSet()
	{
		//  set Option
		$this->object["string2"]	= "value2";

		//  check set Option
		$assertion	= true;
		$creation	= isset( $this->object["string2"] );
		$this->assertEquals( $assertion, $creation );

		//  check set Option
		$assertion	= "value2";
		$creation	= $this->object["string2"];
		$this->assertEquals( $assertion, $creation );

		//  overwrite to set Option again
		$this->object["string2"] = "value2-2";

		//  check set Option
		$assertion	= "value2-2";
		$creation	= $this->object["string2"];
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'offsetUnset'
	 *	@access		public
	 *	@return		void
	 */
	public function testOffsetUnset()
	{
		//  remove Option
		unset( $this->object["string1"] );

		//  check removed Option
		$assertion	= false;
		$creation	= isset( $this->object["string1"] );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'removeOption'
	 *	@access		public
	 *	@return		void
	 */
	public function testRemoveOption()
	{
		//  remove Option
		$assertion	= true;
		$creation	= $this->object->removeOption( "string1" );
		$this->assertEquals( $assertion, $creation );

		//  check removed Option
		$assertion	= false;
		$creation	= $this->object->hasOption( "string1" );
		$this->assertEquals( $assertion, $creation );

		//  try to remove Option again
		$assertion	= false;
		$creation	= $this->object->removeOption( "string1" );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'setOption'
	 *	@access		public
	 *	@return		void
	 */
	public function testSetOption()
	{
		//  set Option
		$assertion	= true;
		$creation	= $this->object->setOption( "string2", "value2" );
		$this->assertEquals( $assertion, $creation );

		//  check set Option
		$assertion	= true;
		$creation	= $this->object->hasOption( "string2" );
		$this->assertEquals( $assertion, $creation );

		//  check set Option
		$assertion	= "value2";
		$creation	= $this->object->getOption( "string2" );
		$this->assertEquals( $assertion, $creation );

		//  try to set Option again
		$assertion	= false;
		$creation	= $this->object->setOption( "string2", "value2" );
		$this->assertEquals( $assertion, $creation );

		//  overwrite Option
		$assertion	= true;
		$creation	= $this->object->setOption( "string2", "value2-2" );
		$this->assertEquals( $assertion, $creation );

		//  check set Option
		$assertion	= "value2-2";
		$creation	= $this->object->getOption( "string2" );
		$this->assertEquals( $assertion, $creation );
	}
}
?>