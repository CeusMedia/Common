<?php
/** @noinspection PhpMultipleClassDeclarationsInspection */
declare( strict_types = 1 );

/**
 *	TestUnit of XML DOM File Writer.
 *	@package		Tests.xml.dom
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 */

namespace CeusMedia\Common\XML\DOM;

use CeusMedia\Common\Test\BaseCase;
use CeusMedia\Common\XML\DOM\FileReader;
use CeusMedia\Common\XML\DOM\Node;

/**
 *	TestUnit of XML DOM File Writer.
 *	@package		Tests.xml.dom
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 */
class FileReaderTest extends BaseCase
{
	/** @var string  */
	protected $fileName;


	/**
	 *	Sets up Leaf.
	 *	@access		public
	 *	@return		void
	 */
	public function setUp(): void
	{
		$this->fileName		= dirname( __FILE__ ).'/assets/builder.xml';
	}

	/**
	 *	Tests Method 'read'.
	 *	@access		public
	 *	@return		void
	 */
	public function testRead1()
	{
		$reader	= new FileReader( $this->fileName );
		$tree	= new Node( "testRoot" );
		$node1	= new Node( "testNode1" );
		$node1->setAttribute( "testKeyNode1", "testValueNode1" );
		$leaf11	= new Node( "testLeaf11", "testContentLeaf11" );
		$leaf11->setAttribute( "testKeyLeaf11", "testValueLeaf11" );
		$leaf12	= new Node( "testLeaf12", "testContentLeaf12" );
		$leaf12->setAttribute( "testKeyLeaf12", "testValueLeaf12" );

		$node2	= new Node( "testNode2" );
		$node2->setAttribute( "testKeyNode2", "testValueNode2" );
		$leaf21	= new Node( "testLeaf21", "testContentLeaf21" );
		$leaf21->setAttribute( "testKeyLeaf21", "testValueLeaf21" );
		$leaf22	= new Node( "testLeaf22", "testContentLeaf22" );
		$leaf22->setAttribute( "testKeyLeaf22", "testValueLeaf22" );

		$node1->addChild( $leaf11 );
		$node1->addChild( $leaf12 );
		$node2->addChild( $leaf21 );
		$node2->addChild( $leaf22 );

		$leaf31	= new Node( "testLeaf31", "testContentLeaf31" );
		$leaf31->setAttribute( "testKeyLeaf31", "testValueLeaf31" );
		$leaf32	= new Node( "testLeaf32", "testContentLeaf32" );
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
		$this->expectException( 'RuntimeException' );
		$reader	= new FileReader( "not_existing_file.xml" );
		$reader->read();
	}

	/**
	 *	Tests Method 'read'.
	 *	@access		public
	 *	@return		void
	 */
	public function testLoad1()
	{
		$tree	= new Node( "testRoot" );
		$node1	= new Node( "testNode1" );
		$node1->setAttribute( "testKeyNode1", "testValueNode1" );
		$leaf11	= new Node( "testLeaf11", "testContentLeaf11" );
		$leaf11->setAttribute( "testKeyLeaf11", "testValueLeaf11" );
		$leaf12	= new Node( "testLeaf12", "testContentLeaf12" );
		$leaf12->setAttribute( "testKeyLeaf12", "testValueLeaf12" );

		$node2	= new Node( "testNode2" );
		$node2->setAttribute( "testKeyNode2", "testValueNode2" );
		$leaf21	= new Node( "testLeaf21", "testContentLeaf21" );
		$leaf21->setAttribute( "testKeyLeaf21", "testValueLeaf21" );
		$leaf22	= new Node( "testLeaf22", "testContentLeaf22" );
		$leaf22->setAttribute( "testKeyLeaf22", "testValueLeaf22" );

		$node1->addChild( $leaf11 );
		$node1->addChild( $leaf12 );
		$node2->addChild( $leaf21 );
		$node2->addChild( $leaf22 );

		$leaf31	= new Node( "testLeaf31", "testContentLeaf31" );
		$leaf31->setAttribute( "testKeyLeaf31", "testValueLeaf31" );
		$leaf32	= new Node( "testLeaf32", "testContentLeaf32" );
		$leaf32->setAttribute( "testKeyLeaf32", "testValueLeaf32" );

		$tree->addChild( $node1 );
		$tree->addChild( $node2 );
		$tree->addChild( $leaf31 );
		$tree->addChild( $leaf32 );

		$assertion	= $tree;
		$creation	= FileReader::load( $this->fileName );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'read' for Exceptions.
	 *	@access		public
	 *	@return		void
	 */
	public function testLoad2()
	{
		$this->expectException( 'RuntimeException' );
		FileReader::load( "not_existing_file.xml" );
	}
}
