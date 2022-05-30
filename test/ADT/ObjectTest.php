<?php
declare( strict_types = 1 );
/**
 *	TestUnit of ADT\Object_.
 *	@package		Tests.ADT
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 */

namespace CeusMedia\Common\Test;

use CeusMedia\Common\ADT\Object_;
use CeusMedia\Common\Test\BaseCase;
use CeusMedia\Common\Test\Object_ as TestObject;

/**
 *	TestUnit of ADT\Object_.
 *	@package		Tests.ADT
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 */
class ObjectTest extends BaseCase
{
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
		$assertion	= 'CeusMedia\\Common\\Test\\TestObjectClass';
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
			'name'		=> 'CeusMedia\\Common\\Test\\TestObjectClass',
			'parent'	=> 'CeusMedia\\Common\\ADT\\Object_',
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
		$assertion	= 'CeusMedia\\Common\\ADT\\Object_';
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
		$creation	= $this->object->isInstanceOf( 'CeusMedia\\Common\\ADT\\Object_' );
		$this->assertEquals( $assertion, $creation );

		$assertion	= TRUE;
		$creation	= $this->object->isInstanceOf( 'CeusMedia\\Common\\ADT\\OBJECT_' );
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
		$creation	= $this->object->isSubclassOf( 'CeusMedia\\Common\\ADT\\Object_' );
		$this->assertEquals( $assertion, $creation );

		$assertion	= TRUE;
		$creation	= $this->object->isSubclassOf( 'CeusMedia\\Common\\ADT\\OBJECT_' );
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
class TestObjectClass extends Object_
{
	public		$publicVar		= FALSE;
	protected	$protectedVar	= FALSE;
	private		$privateVar		= FALSE;
	public		function publicMethod(){}
	protected	function protectedMethod(){}
	private		function privateMethod(){}
}
