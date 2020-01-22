<?php
/**
 *	TestUnit of Test_ADT_Tree_AvlNode.
 *	@package		Tests.adt.tree
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@since			06.09.2008
 *	@version		0.1
 */
declare( strict_types = 1 );

use PHPUnit\Framework\TestCase;

/**
 *	TestUnit of Test_ADT_Tree_AvlNode.
 *	@package		Tests.adt.tree
 *	@extends		Test_Case
 *	@uses			Test_ADT_Tree_AvlNode
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@since			06.09.2008
 *	@version		0.1
 */
class Test_ADT_Tree_AvlNodeTest extends Test_Case
{
	/**
	 *	Setup for every Test.
	 *	@access		public
	 *	@return		void
	 */
	public function setUp()
	{
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
	public function test__construct()
	{
		$this->markTestIncomplete( 'Incomplete Test' );
		$assertion	= TRUE;
		$creation	= ADT_Tree_AvlNode::__construct();
		$this->assertEquals( $assertion, $creation );
	}
}
