<?php
/**
 *	TestUnit of Test_ADT_Object.
 *	@package		Tests.{classPackage}
 *	@extends		PHPUnit_Framework_TestCase
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			03.05.2008
 *	@version		0.1
 */
require_once 'PHPUnit/Framework/TestCase.php';
require_once 'Test/initLoaders.php5';
/**
 *	TestUnit of Test_ADT_Object.
 *	@package		Tests.{classPackage}
 *	@extends		PHPUnit_Framework_TestCase
 *	@uses			ADT_Object
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			03.05.2008
 *	@version		0.1
 */
class Test_ADT_ObjectTest extends PHPUnit_Framework_TestCase
{
	/**
	 *	Constructor.
	 *	@access		public
	 *	@return		void
	 */
	public function __construct()
	{
		$this->object	= new TestObjectClass;
		$this->methods	= array(
					'publicMethod',
					'protectedMethod',
#					'privateMethod',
					'getClass',
					'getMethods',
					'getObjectInfo',
					'getParent',
					'getVars',
					'hasMethod',
					'isInstanceOf',
					'isSubclassOf',
					'serialize',
				);
		$this->vars	= array(
			'publicVar'		=> FALSE,
			'protectedVar'	=> FALSE,
#			'privateVar'	=> FALSE,
		);
	}
	
	/**
	 *	Tests Method 'getClass'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetClass()
	{
		$assertion	= "TestObjectClass";
		$creation	= $this->object->getClass();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'getMethods'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetMethods()
	{
		$assertion	= $this->methods;
		$creation	= $this->object->getMethods();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'getObjectInfo'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetObjectInfo()
	{
		$assertion	= array(
			'name'		=> "TestObjectClass",
			'parent'	=> "ADT_Object",
			'methods'	=> $this->methods,
			'vars'		=> $this->vars,
		);
		$creation	= $this->object->getObjectInfo();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'getParent'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetParent()
	{
		$assertion	= "ADT_Object";
		$creation	= $this->object->getParent();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'getVars'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetVars()
	{
		$assertion	= $this->vars;
		$creation	= $this->object->getVars();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'hasMethod'.
	 *	@access		public
	 *	@return		void
	 */
	public function testHasMethod()
	{
		$assertion	= TRUE;
		$creation	= $this->object->hasMethod( 'getClass' );
		$this->assertEquals( $assertion, $creation );

		$assertion	= TRUE;
		$creation	= $this->object->hasMethod( 'publicMethod' );
		$this->assertEquals( $assertion, $creation );

		$assertion	= TRUE;
		$creation	= $this->object->hasMethod( 'protectedMethod' );
		$this->assertEquals( $assertion, $creation );

		$assertion	= TRUE;
		$creation	= $this->object->hasMethod( 'privateMethod', FALSE );
		$this->assertEquals( $assertion, $creation );

		$assertion	= FALSE;
		$creation	= $this->object->hasMethod( 'privateMethod' );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'isInstanceOf'.
	 *	@access		public
	 *	@return		void
	 */
	public function testIsInstanceOf()
	{
		$assertion	= TRUE;
		$creation	= $this->object->isInstanceOf( "ADT_Object" );
		$this->assertEquals( $assertion, $creation );

		$assertion	= TRUE;
		$creation	= $this->object->isInstanceOf( "ADT_OBJECT" );
		$this->assertEquals( $assertion, $creation );

		$assertion	= FALSE;
		$creation	= $this->object->isInstanceOf( "NOT_A_PARENT_CLASS" );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'isSubclassOf'.
	 *	@access		public
	 *	@return		void
	 */
	public function testIsSubclassOf()
	{
		$assertion	= TRUE;
		$creation	= $this->object->isSubclassOf( "ADT_Object" );
		$this->assertEquals( $assertion, $creation );

		$assertion	= TRUE;
		$creation	= $this->object->isSubclassOf( "ADT_OBJECT" );
		$this->assertEquals( $assertion, $creation );

		$assertion	= FALSE;
		$creation	= $this->object->isSubclassOf( "NOT_A_PARENT_CLASS" );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'serialize'.
	 *	@access		public
	 *	@return		void
	 */
	public function testSerialize()
	{
		$assertion	= serialize( $this->object );
		$creation	= $this->object->serialize();
		$this->assertEquals( $assertion, $creation );
	}
}
class TestObjectClass extends ADT_Object
{
	public		$publicVar		= FALSE;
	protected	$protectedVar	= FALSE;
	private		$privateVar		= FALSE;
	public		function publicMethod(){}
	protected	function protectedMethod(){}
	private		function privateMethod(){}
}
?>