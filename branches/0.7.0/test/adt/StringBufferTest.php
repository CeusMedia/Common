<?php
/**
 *	TestUnit of Test_ADT_StringBuffer.
 *	@package		Tests.adt
 *	@extends		PHPUnit_Framework_TestCase
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			21.07.2008
 *	@version		0.1
 */
require_once( 'PHPUnit/Framework/TestCase.php' ); 
require_once 'test/initLoaders.php5';
/**
 *	TestUnit of Test_ADT_StringBuffer.
 *	@package		Tests.adt
 *	@extends		PHPUnit_Framework_TestCase
 *	@uses			ADT_StringBuffer
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			21.07.2008
 *	@version		0.1
 */
class Test_ADT_StringBufferTest extends PHPUnit_Framework_TestCase
{
	/**
	 *	Constructor.
	 *	@access		public
	 *	@return		void
	 */
	public function __construct()
	{
	}
	
	/**
	 *	Setup for every Test.
	 *	@access		public
	 *	@return		void
	 */
	public function setUp()
	{
		$this->buffer	= new ADT_StringBuffer( "test" );
	}
	
	/**
	 *	Cleanup after every Test.
	 *	@access		public
	 *	@return		void
	 */
	public function tearDown()
	{
	}

	/**
	 *	Tests Method '__construct'.
	 *	@access		public
	 *	@return		void
	 */
	public function testConstruct()
	{
		$buffer		= new ADT_StringBuffer( "construct" );
		$assertion	= "construct";
		$creation	= $buffer->toString();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'count'.
	 *	@access		public
	 *	@return		void
	 */
	public function testCount()
	{
		$assertion	= 4;
		$creation	= $this->buffer->count();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'deleteCharAt'.
	 *	@access		public
	 *	@return		void
	 */
	public function testDeleteCharAt()
	{
		$assertion	= "tet";
		$creation	= $this->buffer->deleteCharAt( 2 );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'getCharAt'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetCharAt()
	{
		$assertion	= "t";
		$creation	= $this->buffer->getCharAt( 3 );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'getCurrentPos'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetCurrentPos()
	{
		$assertion	= 0;
		$creation	= $this->buffer->getCurrentPos();
		$this->assertEquals( $assertion, $creation );

		$this->buffer->getNextChar();
	
		$assertion	= 1;
		$creation	= $this->buffer->getCurrentPos();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'getCurrentChar'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetCurrentChar()
	{
		$assertion	= "t";
		$creation	= $this->buffer->getCurrentChar();
		$this->assertEquals( $assertion, $creation );

		$this->buffer->getNextChar();

		$assertion	= "e";
		$creation	= $this->buffer->getCurrentChar();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'getNextChar'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetNextChar()
	{
		$assertion	= "t";
		$creation	= $this->buffer->getNextChar();
		$this->assertEquals( $assertion, $creation );

		$assertion	= "e";
		$creation	= $this->buffer->getNextChar();
		$this->assertEquals( $assertion, $creation );

		$assertion	= "s";
		$creation	= $this->buffer->getNextChar();
		$this->assertEquals( $assertion, $creation );

		$assertion	= "t";
		$creation	= $this->buffer->getNextChar();
		$this->assertEquals( $assertion, $creation );

		$assertion	= NULL;
		$creation	= $this->buffer->getNextChar();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'getPrevChar'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetPrevChar()
	{
		$this->buffer->getNextChar();
		$this->buffer->getNextChar();
		$this->buffer->getNextChar();
		$this->buffer->getNextChar();

		$assertion	= "s";
		$creation	= $this->buffer->getPrevChar();
		$this->assertEquals( $assertion, $creation );

		$assertion	= "e";
		$creation	= $this->buffer->getPrevChar();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'hasLess'.
	 *	@access		public
	 *	@return		void
	 */
	public function testHasLess()
	{
		$assertion	= FALSE;
		$creation	= $this->buffer->hasLess();
		$this->assertEquals( $assertion, $creation );
		
		$this->buffer->getNextChar();

		$assertion	= TRUE;
		$creation	= $this->buffer->hasLess();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'hasMore'.
	 *	@access		public
	 *	@return		void
	 */
	public function testHasMore()
	{
		$assertion	= TRUE;
		$creation	= $this->buffer->hasMore();
		$this->assertEquals( $assertion, $creation );

		$this->buffer->getNextChar();
		$this->buffer->getNextChar();
		$this->buffer->getNextChar();
		$this->buffer->getNextChar();

		$assertion	= FALSE;
		$creation	= $this->buffer->hasMore();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'insert'.
	 *	@access		public
	 *	@return		void
	 */
	public function testInsert()
	{
		$assertion	= "te123st";
		$creation	= $this->buffer->insert( 2, "123" );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'reset'.
	 *	@access		public
	 *	@return		void
	 */
	public function testReset()
	{
		$this->buffer->reset();
		
		$assertion	= "";
		$creation	= $this->buffer->toString();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'resetPointer'.
	 *	@access		public
	 *	@return		void
	 */
	public function testResetPointer()
	{
		$this->buffer->getNextChar();
		
		$assertion	= "e";
		$creation	= $this->buffer->getCurrentChar();
		$this->assertEquals( $assertion, $creation );

		$this->buffer->resetPointer();

		$assertion	= "t";
		$creation	= $this->buffer->getCurrentChar();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'setCharAt'.
	 *	@access		public
	 *	@return		void
	 */
	public function testSetCharAt()
	{
		$assertion	= "text";
		$creation	= $this->buffer->setCharAt( 2, "x" );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'toString'.
	 *	@access		public
	 *	@return		void
	 */
	public function testToString()
	{
		$assertion	= "test";
		$creation	= $this->buffer->toString();
		$this->assertEquals( $assertion, $creation );
	}
}
?>