<?php
/** @noinspection PhpIllegalPsrClassPathInspection */
/** @noinspection PhpMultipleClassDeclarationsInspection */
/** @noinspection PhpUnhandledExceptionInspection */
/** @noinspection PhpDocMissingThrowsInspection */

declare( strict_types = 1 );

/**
 *	TestUnit of XML_OPML_Builder.
 *	@package		Tests.xml.opml
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 */

namespace CeusMedia\CommonTest\XML\OPML;

use CeusMedia\CommonTest\BaseCase;
use CeusMedia\Common\XML\OPML\Builder as OpmlBuilder;
use CeusMedia\Common\XML\OPML\Outline as OpmlOutline;

/**
 *	TestUnit of XML_RSS_Builder.
 *	@package		Tests.xml.opml
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 */
class BuilderTest extends BaseCase
{
	protected $builder;

	protected $path;

	/**
	 *	Setup for every Test.
	 *	@access		public
	 *	@return		void
	 */
	public function setUp(): void
	{
		$this->path		= dirname( __FILE__ )."/";
		$this->builder	= new OpmlBuilder();
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
	public function testConstruct()
	{
		$this->markTestIncomplete();
	}

	/**
	 *	Tests Method 'addItem'.
	 *	@access		public
	 *	@return		void
	 */
	public function testAddOutline()
	{
		$this->markTestIncomplete();
	}

	/**
	 *	Tests Method 'build'.
	 *	@access		public
	 *	@return		void
	 */
	public function testBuild()
	{
		$this->builder->addOutline( ( new OpmlOutline() )
			->setAttribute( 'title', 'Test 1' )
			->addOutline( ( new OpmlOutline() )->setAttribute( 'title', 'Test 1-1' ) ) );
		$actual		= $this->builder->build();
		$expected	= file_get_contents( $this->path."buildResult.xml" );
		self::assertEquals( $expected, $actual );
	}

	/**
	 *	Tests Exception of Method 'build'.
	 *	@access		public
	 *	@return		void
	 */
	public function testBuildException()
	{
		$this->markTestIncomplete();
	}

	/**
	 *	Tests Method 'setChannelPair'.
	 *	@access		public
	 *	@return		void
	 */
	public function testSetHeader()
	{
		$this->markTestIncomplete();
	}
}
/*
class TestBuilderInstance extends OpmlBuilder
{
	public function getProtectedVar( $varName )
	{
		if( !in_array( $varName, array_keys( get_object_vars( $this ) ) ) )
			throw new \Exception( 'Var "'.$varName.'" is not declared.' );
		return $this->$varName;
	}
}
*/