<?php
/**
 *	TestUnit of LinkList
 *	@package		Tests.adt.list
 *	@extends		PHPUnit_Framework_TestCase
 *	@uses			LinkList
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.1
 */
require_once 'PHPUnit/Framework/TestCase.php'; 
/**
 *	TestUnit of LinkList
 *	@package		Tests.adt.list
 *	@extends		PHPUnit_Framework_TestCase
 *	@uses			LinkList
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.1
 */
class Tests_ADT_List_LinkListTest extends PHPUnit_Framework_TestCase
{
	private $template;
	
	/**
	 *	Constructor, changes Directory.
	 *	@access		public
	 *	@return		void
	 */
	public function __construct()
	{
		import( 'de.ceus-media.adt.list.LinkList' );
	}

	public function setUp()
	{
	}
	
	public function testAddAndGet()
	{
		$list	= new LinkList();
		$list->addEntry( 1 );
		$assertion	= 1;
		$creation	= $list->getNext()->getContent();
		$this->assertEquals( $assertion, $creation );
	}
	
	public function testHasNext()
	{
		$list	= new LinkList();
		$list->addEntry( 1 );
		$list->addEntry( 2 );
		$list->reset();
		$array	= array();
		while( $list->hasNext() )
			$array[]	= $list->getNext()->getContent();
		$assertion	= array( 1, 2 );
		$creation	= $array;
		$this->assertEquals( $assertion, $creation );
	}
}
?>
