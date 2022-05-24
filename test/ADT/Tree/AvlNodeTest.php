<?php
/**
 *	TestUnit of Test_ADT_Tree_AvlNode.
 *	@package		Tests.adt.tree
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@since			06.09.2008
 */
declare( strict_types = 1 );

use PHPUnit\Framework\TestCase;

/**
 *	TestUnit of Test_ADT_Tree_AvlNode.
 *	@package		Tests.adt.tree
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@since			06.09.2008
 */
class Test_ADT_Tree_AvlNodeTest extends Test_Case
{
	/**
	 *	Setup for every Test.
	 *	@access		public
	 *	@return		void
	 */
	public function setUp(): void
	{
	}

	/**
	 *	Cleanup after every Test.
	 *	@access		public
	 *	@return		void
	 */
	public function tearDown(): void
	{
	}

	/**
	 *	Tests Method '__construct'.
	 *	@access		public
	 *	@return		void
	 */
	public function test__construct()
	{
		$this->markTestIncomplete( 'Incomplete Test' );
		$assertion	= TRUE;
		$creation	= ADT_Tree_AvlNode::__construct();
		$this->assertEquals( $assertion, $creation );
	}
}
