<?php
/**
 *	TestUnit of XML DOM File Writer.
 *	@package		Tests.xml.dom
 *	@extends		PHPUnit_Framework_TestCase
 *	@uses			XML_DOM_FileReader
 *	@uses			XML_DOM_Node
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			11.12.2007
 *	@version		0.1
 */
require_once 'PHPUnit/Framework/TestCase.php'; 
require_once 'Tests/initLoaders.php5' ;
import( 'de.ceus-media.xml.dom.FileReader' );
import( 'de.ceus-media.xml.dom.Node' );
/**
 *	TestUnit of XML DOM File Writer.
 *	@package		Tests.xml.dom
 *	@extends		PHPUnit_Framework_TestCase
 *	@uses			XML_DOM_FileReader
 *	@uses			XML_DOM_Node
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			11.12.2007
 *	@version		0.1
 */
class Tests_XML_DOM_FileReaderTest extends PHPUnit_Framework_TestCase
{
	/**
	 *	Constructor.
	 *	@access		public
	 *	@return		void
	 */
	public function __construct()
	{
		$this->fileName		= "Tests/xml/dom/builder.xml";
	}

	/**
	 *	Sets up Leaf.
	 *	@access		public
	 *	@return		void
	 */
	public function setUp()
	{
	}

	/**
	 *	Tests Method 'read'.
	 *	@access		public
	 *	@return		void
	 */
	public function testRead1()
	{
		$reader	= new XML_DOM_FileReader( $this->fileName );
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

		$assertion	= $tree;
		$creation	= $reader->read();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'read' for Exceptions.
	 *	@access		public
	 *	@return		void
	 */
	public function testRead2()
	{
		try
		{
			$reader	= new XML_DOM_FileReader( "not_existing_file.xml" );
			$reader->read(  );
		}
		catch( Exception $e )
		{
			return;
		}
		$this->fail();
	}

	/**
	 *	Tests Method 'read'.
	 *	@access		public
	 *	@return		void
	 */
	public function testLoad1()
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

		$assertion	= $tree;
		$creation	= XML_DOM_FileReader::load( $this->fileName );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'read' for Exceptions.
	 *	@access		public
	 *	@return		void
	 */
	public function testLoad2()
	{
		try
		{
			XML_DOM_FileReader::load( "not_existing_file.xml" );
		}
		catch( Exception $e )
		{
			return;
		}
		$this->fail();
	}
}
?>