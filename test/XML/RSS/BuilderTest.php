<?php
/** @noinspection PhpIllegalPsrClassPathInspection */
/** @noinspection PhpMultipleClassDeclarationsInspection */
/** @noinspection PhpUnhandledExceptionInspection */
/** @noinspection PhpDocMissingThrowsInspection */

declare( strict_types = 1 );

/**
 *	TestUnit of XML_RSS_Builder.
 *	@package		Tests.xml.rss
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 */

namespace CeusMedia\CommonTest\XML\RSS;

use CeusMedia\Common\XML\DOM\Builder as XmlDomBuilder;
use CeusMedia\Common\XML\RSS\Builder;
use CeusMedia\CommonTest\BaseCase;
use Exception;

/**
 *	TestUnit of XML_RSS_Builder.
 *	@package		Tests.xml.rss
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 */
class BuilderTest extends BaseCase
{
	protected $file;
	protected $serial;
	protected $path;
	protected $builder;
	protected $setup;

	/**
	 *	Setup for every Test.
	 *	@access		public
	 *	@return		void
	 */
	public function setUp(): void
	{
		$this->path		= dirname( __FILE__ )."/assets/";
		$this->file		= $this->path."builder.xml";
		$this->builder	= new TestBuilderInstance();
		$this->setup	= array(
			'channel'	=> array(
				'title'				=> "UnitTest created Feed",
				'description'		=> "This RSS Feed has been created by a PHPUnit Test.",
				'imageUrl'			=> "siegel_map.jpg",
				'link'				=> "http://nowhere.tld",
				'textInputTitle'	=> "Text Box",
			),
			'items'		=> array(
				array(
					'title'			=> "Test Entry 1",
					'description'	=> "Description of Test Entry 1",
					'pubDate'		=> "Wed, 20 Feb 2008 23:33:20 +0100",

				),
				array(
					'title'			=> "Test Entry 2",
					'description'	=> "Description of Test Entry 2",
					'pubDate'		=> "Tue, 19 Feb 2008 23:33:20 +0100",
				),
			)
		);

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
		$assertion	= new XmlDomBuilder();
		$creation	= $this->builder->getProtectedVar( 'builder' );
		$this->assertEquals( $assertion, $creation );

		$assertion	= "timezone";
		$creation	= key( array_slice( $this->builder->getProtectedVar( 'channel' ), 0, 1 ) );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'addItem'.
	 *	@access		public
	 *	@return		void
	 */
	public function testAddItem()
	{
		$item	= array(
			'title'			=> "Test Entry 1",
			'description'	=> "Description of Test Entry 1",
			'pubDate'		=> "Wed, 20 Feb 2008 23:33:20 +0100",
		);

		$this->builder->addItem( $item );

		$assertion	= 1;
		$creation	= count( $this->builder->getProtectedVar( 'items' ) );
		$this->assertEquals( $assertion, $creation );

		$items		= $this->builder->getProtectedVar( 'items' );
		$assertion	= $item;
		$creation	= array_pop( $items );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'build'.
	 *	@access		public
	 *	@return		void
	 */
	public function testBuild()
	{
		$this->builder->setChannelData( $this->setup['channel'] );
		$this->builder->setItemList( $this->setup['items'] );

		$this->builder->build();
		$this->assertXmlFileEqualsXmlFile( $this->file, $this->path."builder2.xml" );
	}

	/**
	 *	Tests Exception of Method 'build'.
	 *	@access		public
	 *	@return		void
	 */
	public function testBuildException()
	{
		$this->expectException( 'DomainException' );
		$this->builder->build();
	}

	/**
	 *	Tests Method 'setChannelPair'.
	 *	@access		public
	 *	@return		void
	 */
	public function testSetChannelPair()
	{
		$count	= count( $this->builder->getProtectedVar( 'channel' ) );
		$this->builder->setChannelPair( "key1", "value1" );

		$assertion	= $count + 1;
		$creation	= count( $this->builder->getProtectedVar( 'channel' ) );
		$this->assertEquals( $assertion, $creation );

		$assertion	= array( 'key1' => "value1" );
		$creation	= array_slice( $this->builder->getProtectedVar( 'channel' ), -1 );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'setChannelData'.
	 *	@access		public
	 *	@return		void
	 */
	public function testSetChannelData()
	{
		$count	= count( $this->builder->getProtectedVar( 'channel' ) );
		$pairs	= array(
			'key1'	=> "value1",
			'key2'	=> "value2",
		);
		$this->builder->setChannelData( $pairs );

		$assertion	= $count + 2;
		$creation	= count( $this->builder->getProtectedVar( 'channel' ) );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'setItemList'.
	 *	@access		public
	 *	@return		void
	 */
	public function testSetItemList()
	{
		$items	= $this->setup['items'];
		$this->builder->setItemList( $items );

		$assertion	= count( $items );
		$creation	= count( $this->builder->getProtectedVar( 'items' ) );
		$this->assertEquals( $assertion, $creation );

		$assertion	= $items;
		$creation	= $this->builder->getProtectedVar( 'items' );
		$this->assertEquals( $assertion, $creation );
	}
}

class TestBuilderInstance extends Builder
{
	public function getProtectedVar( $varName )
	{
		if( !in_array( $varName, array_keys( get_object_vars( $this ) ) ) )
			throw new Exception( 'Var "'.$varName.'" is not declared.' );
		return $this->$varName;
	}
}
