<?php
/** @noinspection PhpIllegalPsrClassPathInspection */
/** @noinspection PhpMultipleClassDeclarationsInspection */
/** @noinspection PhpUnhandledExceptionInspection */
/** @noinspection PhpDocMissingThrowsInspection */

declare( strict_types = 1 );

/**
 *	TestUnit of LinkList
 *	@package		Tests.adt.list
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 */

namespace CeusMedia\CommonTest\ADT\JSON;

use CeusMedia\Common\ADT\JSON\Builder;
use CeusMedia\CommonTest\BaseCase;
use CeusMedia\CommonTest\Object_;
use InvalidArgumentException;

/**
 *	TestUnit of LinkList
 *	@package		Tests.adt.json
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 */
class BuilderTest extends BaseCase
{
	protected $object;

	/**
	 *	Setup.
	 *	@access		public
	 *	@return		void
	 */
	public function setUp(): void
	{
		$this->object		= new Object_();
		$this->object->a	= "test";
	}

	/**
	 *	Tests Exception of Method 'encodeStatic'.
	 *	@access		public
	 *	@return		void
	 */
	public function testEncode()
	{
		$data		= array( 1, 2.3, "string", TRUE, NULL, $this->object );
		$builder	= new Builder();
		$assertion	= '[1,2.3,"string",true,null,{"a":"test"}]';
		$creation	= $builder->encode( $data );
		$this->assertEquals( $assertion, $creation );

		$data		= array( array( 1, 2 ), array( 3, 4 ) );
		$builder	= new Builder();
		$assertion	= "[[1,2],[3,4]]";
		$creation	= $builder->encode( $data );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Exception of Method 'encodeStatic'.
	 *	@access		public
	 *	@return		void
	 */
	public function testEncodeStatic()
	{
		$data		= array( 1, 2.3, "string", TRUE, NULL, $this->object );
		$assertion	= '[1,2.3,"string",true,null,{"a":"test"}]';
		$creation	= Builder::encode( $data );
		$this->assertEquals( $assertion, $creation );

		$data		= array( array( 1, 2 ), array( 3, 4 ) );
		$assertion	= "[[1,2],[3,4]]";
		$creation	= Builder::encode( $data );
		$this->assertEquals( $assertion, $creation );

	}

	/**
	 *	Tests Exception of Method 'encodeStatic'.
	 *	@access		public
	 *	@return		void
	 */
	public function testEncodeStaticException()
	{
		$this->expectException( InvalidArgumentException::class );
		Builder::encode( dir( dirname( __FILE__ ) ) );
	}
}
