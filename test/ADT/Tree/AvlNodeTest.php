<?php
declare( strict_types = 1 );
/**
 *	TestUnit of ADT\Tree\AvlNode.
 *	@package		Tests.ADT.Tree
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 */

namespace CeusMedia\Common\Test\ADT\Tree;

use CeusMedia\Common\ADT\Tree\AvlNode;
use CeusMedia\Common\Test\BaseCase;

/**
 *	TestUnit of Test_ADT_Tree_AvlNode.
 *	@package		Tests.ADT.Tree
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 */
class AvlNodeTest extends BaseCase
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
		$creation	= AvlNode::__construct();
		$this->assertEquals( $assertion, $creation );
	}
}
