<?php
/** @noinspection PhpMultipleClassDeclarationsInspection */
declare( strict_types = 1 );

/**
 *	TestUnit of XML DOM Builder.
 *	@package		Tests.xml.dom
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 */

namespace CeusMedia\Common\Test\XML\DOM;

use CeusMedia\Common\Test\BaseCase;
use CeusMedia\Common\XML\DOM\Builder;
use CeusMedia\Common\XML\DOM\Node;

/**
 *	TestUnit of XML DOM Builder.
 *	@package		Tests.xml.dom
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 */
class BuilderTest extends BaseCase
{
	/** @var Builder  */
	protected $builder;

	/** @var string  */
	protected $fileName;

	/**
	 *	Sets up Builder.
	 *	@access		public
	 *	@return		void
	 */
	public function setUp(): void
	{
		$this->builder		= new Builder();
		$this->fileName		= dirname( __FILE__ )."/assets/builder.xml";
	}

	/**
	 *	Sets down Writer.
	 *	@access		public
	 *	@return		void
	 */
	public function tearDown(): void
	{
	}

	/**
	 *	Tests Method 'build'.
	 *	@access		public
	 *	@return		void
	 */
	public function testBuild()
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

		$assertion	= file_get_contents( $this->fileName );
		$creation	= Builder::build( $tree );
		$this->assertEquals( $assertion, $creation );
	}
}
