<?php
/**
 *	TestUnit of ADT_Tree_AvlNode.
 *	@package		Tests.adt.tree
 *	@extends		PHPUnit_Framework_TestCase
 *	@uses			ADT_Tree_AvlNode
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			06.09.2008
 *	@version		0.1
 */
require_once( 'PHPUnit/Framework/TestCase.php' ); 
require_once '../autoload.php5';
import( 'de.ceus-media.adt.tree.AvlNode' );
/**
 *	TestUnit of ADT_Tree_AvlNode.
 *	@package		Tests.adt.tree
 *	@extends		PHPUnit_Framework_TestCase
 *	@uses			ADT_Tree_AvlNode
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			06.09.2008
 *	@version		0.1
 */
class ADT_Tree_AvlNodeTest extends PHPUnit_Framework_TestCase
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