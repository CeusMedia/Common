<?php
/** @noinspection PhpIllegalPsrClassPathInspection */
/** @noinspection PhpMultipleClassDeclarationsInspection */
/** @noinspection PhpUnhandledExceptionInspection */
/** @noinspection PhpDocMissingThrowsInspection */

declare( strict_types = 1 );

/**
 *	TestUnit of ADT\Object_.
 *	@package		Tests.ADT
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 */

namespace CeusMedia\CommonTest\ADT;

use CeusMedia\Common\ADT\Object_;
use CeusMedia\CommonTest\BaseCase;

/**
 *	TestUnit of ADT\Object_.
 *	@package		Tests.ADT
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 */
class ObjectTest extends BaseCase
{
	protected $object;
	protected $methods;
	protected $vars;

	/**
	 *	Constructor.
	 *	@access		public
	 *	@return		void
	 */
	public function setUp(): void
	{
		$this->object	= new TestObjectClass;
		$this->methods	= array(
			'publicMethod',
			'protectedMethod',
#			'privateMethod',
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
		$assertion	= TestObjectClass::class;
		$creation	= $this->object->getClass();
		self::assertEquals( $assertion, $creation );
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
		self::assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'getObjectInfo'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetObjectInfo()
	{
		$assertion	= array(
			'name'		=> 'CeusMedia\\CommonTest\\ADT\\TestObjectClass',
			'parent'	=> 'CeusMedia\\Common\\ADT\\Object_',
			'methods'	=> $this->methods,
			'vars'		=> $this->vars,
		);
		$creation	= $this->object->getObjectInfo();
		self::assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'getParent'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetParent()
	{
		self::assertEquals( Object_::class, $this->object->getParent() );

		$object		= new Object_();
		self::assertEquals( NULL, $object->getParent() );

		$object		= new ChildTestObjectClass();
		self::assertEquals( TestObjectClass::class, $object->getParent() );
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
		self::assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'hasMethod'.
	 *	@access		public
	 *	@return		void
	 */
	public function testHasMethod()
	{
		$creation	= $this->object->hasMethod( 'getClass' );
		self::assertTrue( $creation );

		$creation	= $this->object->hasMethod( 'publicMethod' );
		self::assertTrue( $creation );

		$creation	= $this->object->hasMethod( 'protectedMethod' );
		self::assertTrue( $creation );

		$creation	= $this->object->hasMethod( 'privateMethod', FALSE );
		self::assertTrue( $creation );

		$creation	= $this->object->hasMethod( 'privateMethod' );
		self::assertFalse( $creation );
	}

	/**
	 *	Tests Method 'isInstanceOf'.
	 *	@access		public
	 *	@return		void
	 */
	public function testIsInstanceOf()
	{
		$creation	= $this->object->isInstanceOf( 'CeusMedia\\Common\\ADT\\Object_' );
		self::assertTrue( $creation );

		$creation	= $this->object->isInstanceOf( 'CeusMedia\\Common\\ADT\\OBJECT_' );
		self::assertTrue( $creation );

		$creation	= $this->object->isInstanceOf( "NOT_A_PARENT_CLASS" );
		self::assertFalse( $creation );
	}

	/**
	 *	Tests Method 'isSubclassOf'.
	 *	@access		public
	 *	@return		void
	 */
	public function testIsSubclassOf()
	{
		$creation	= $this->object->isSubclassOf( 'CeusMedia\\Common\\ADT\\Object_' );
		self::assertTrue( $creation );

		$creation	= $this->object->isSubclassOf( 'CeusMedia\\Common\\ADT\\OBJECT_' );
		self::assertTrue( $creation );

		$creation	= $this->object->isSubclassOf( "NOT_A_PARENT_CLASS" );
		self::assertFalse( $creation );
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
		self::assertEquals( $assertion, $creation );
	}
}
class TestObjectClass extends Object_
{
	public		$publicVar		= FALSE;
	protected	$protectedVar	= FALSE;
	private		$privateVar		= FALSE;
	public		function publicMethod(){}
	protected	function protectedMethod(){}
	private		function privateMethod(){}
}

class ChildTestObjectClass extends TestObjectClass
{
}
