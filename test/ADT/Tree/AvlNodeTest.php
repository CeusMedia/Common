<?php
/**
 *	TestUnit of Test_ADT_Tree_AvlNode.
 *	@package		Tests.adt.tree
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@since			06.09.2008
 *	@version		0.1
 */
require_once dirname( dirname( __DIR__ ) ).'/initLoaders.php';
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
?>