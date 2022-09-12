<?php
/** @noinspection PhpMultipleClassDeclarationsInspection */
declare( strict_types = 1 );

/**
 *	TestUnit of XML DOM Parser.
 *	@package		Tests.xml.dom
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 */

namespace CeusMedia\Common\Test\XML\DOM;

use CeusMedia\Common\Test\BaseCase;
use CeusMedia\Common\XML\DOM\Builder;
use CeusMedia\Common\XML\DOM\Node;
use CeusMedia\Common\XML\DOM\Parser;
use DOMDocument;

/**
 *	TestUnit of XML DOM Parser.
 *	@package		Tests.xml.dom
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 */
class ParserTest extends BaseCase
{
	protected $builder;
	protected $parser;

	/**
	 *	Sets up Leaf.
	 *	@access		public
	 *	@return		void
	 */
	public function setUp(): void
	{
		$this->builder	= new Builder();
		$this->parser	= new Parser();
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

		$this->assertIsObject( $document );
		$this->assertInstanceOf( DOMDocument::class, $document );
	}

	/**
	 *	Tests Method 'parse'.
	 *	@access		public
	 *	@return		void
	 */
	public function testParse()
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

		$xml	= $this->builder->build( $tree );
		$assertion	= $tree;
		$creation	= $this->parser->parse( $xml );
		$this->assertEquals( $assertion, $creation );
	}
}
