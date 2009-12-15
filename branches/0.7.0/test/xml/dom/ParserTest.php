<?php
/**
 *	TestUnit of XML DOM Parser.
 *	@package		Tests.xml.dom
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			11.12.2007
 *	@version		0.1
 */
require_once 'PHPUnit/Framework/TestCase.php'; 
require_once 'test/initLoaders.php5';
/**
 *	TestUnit of XML DOM Parser.
 *	@package		Tests.xml.dom
 *	@extends		PHPUnit_Framework_TestCase
 *	@uses			XML_DOM_Parser
 *	@uses			XML_DOM_Builder
 *	@uses			XML_DOM_Node
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			11.12.2007
 *	@version		0.1
 */
class Test_XML_DOM_ParserTest extends PHPUnit_Framework_TestCase
{
	/**
	 *	Sets up Leaf.
	 *	@access		public
	 *	@return		void
	 */
	public function setUp()
	{
		$this->builder	= new XML_DOM_Builder();
		$this->parser	= new XML_DOM_Parser();
	}

	/**
	 *	Tests Method 'getDocument'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetDocument()
	{
		$xml		= '<?xml version="1.0"?><!-- Comment --><root/>';
		$this->parser->parse( $xml );
		$document	= $this->parser->getDocument();

		$assertion	= true;
		$creation	= is_object( $document );
		$this->assertEquals( $assertion, $creation );

		$assertion	= true;
		$creation	= $document instanceof DOMDocument;
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'parse'.
	 *	@access		public
	 *	@return		void
	 */
	public function testParse()
	{
		$tree	= new XML_DOM_Node( "testRoot" );
		$node1	= new XML_DOM_Node( "testNode1" );
		$node1->setAttribute( "testKeyNode1", "testValueNode1" );
		$leaf11	= new XML_DOM_Node( "testLeaf11", "testContentLeaf11" );
		$leaf11->setAttribute( "testKeyLeaf11", "testValueLeaf11" );
		$leaf12	= new XML_DOM_Node( "testLeaf12", "testContentLeaf12" );
		$leaf12->setAttribute( "testKeyLeaf12", "testValueLeaf12" );

		$node2	= new XML_DOM_Node( "testNode2" );
		$node2->setAttribute( "testKeyNode2", "testValueNode2" );
		$leaf21	= new XML_DOM_Node( "testLeaf21", "testContentLeaf21" );
		$leaf21->setAttribute( "testKeyLeaf21", "testValueLeaf21" );
		$leaf22	= new XML_DOM_Node( "testLeaf22", "testContentLeaf22" );
		$leaf22->setAttribute( "testKeyLeaf22", "testValueLeaf22" );

		$node1->addChild( $leaf11 );
		$node1->addChild( $leaf12 );
		$node2->addChild( $leaf21 );
		$node2->addChild( $leaf22 );

		$leaf31	= new XML_DOM_Node( "testLeaf31", "testContentLeaf31" );
		$leaf31->setAttribute( "testKeyLeaf31", "testValueLeaf31" );
		$leaf32	= new XML_DOM_Node( "testLeaf32", "testContentLeaf32" );
		$leaf32->setAttribute( "testKeyLeaf32", "testValueLeaf32" );

		$tree->addChild( $node1 );
		$tree->addChild( $node2 );
		$tree->addChild( $leaf31 );
		$tree->addChild( $leaf32 );
		
		$xml	= $this->builder->build( $tree );
		$assertion	= $tree;
		$creation	= $this->parser->parse( $xml );
		$this->assertEquals( $assertion, $creation );
	}
}
?>