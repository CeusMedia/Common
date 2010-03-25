<?php
/**
 *	TestUnit of XML_RSS_Builder.
 *	@package		Tests.xml.rss
 *	@extends		PHPUnit_Framework_TestCase
 *	@uses			XML_RSS_Builder
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			08.05.2008
 *	@version		0.1
 */
require_once( 'PHPUnit/Framework/TestCase.php' ); 
require_once( 'Tests/initLoaders.php5' );
import( 'de.ceus-media.xml.rss.Builder' );
/**
 *	TestUnit of XML_RSS_Builder.
 *	@package		Tests.xml.rss
 *	@extends		PHPUnit_Framework_TestCase
 *	@uses			XML_RSS_Builder
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			08.05.2008
 *	@version		0.1
 */
class Tests_XML_RSS_BuilderTest extends PHPUnit_Framework_TestCase
{
	protected $file;
	protected $serial;

	/**
	 *	Constructor.
	 *	@access		public
	 *	@return		void
	 */
	public function __construct()
	{
		$this->path		= dirname( __FILE__ )."/";
		$this->file		= $this->path."builder.xml";
	}
	
	/**
	 *	Setup for every Test.
	 *	@access		public
	 *	@return		void
	 */
	public function setUp()
	{
		$this->builder	= new Tests_XML_RSS_BuilderInstance();
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
	public function tearDown()
	{
	}

	/**
	 *	Tests Method '__construct'.
	 *	@access		public
	 *	@return		void
	 */
	public function testConstruct()
	{
		$builder	= new Tests_XML_RSS_BuilderInstance();
	
		$assertion	= new XML_DOM_Builder();
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

		$assertion	= $item;
		$creation	= array_pop( $this->builder->getProtectedVar( 'items' ) );
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

		$assertion	= file_get_contents( $this->file );
		$creation	= $this->builder->build();
		file_put_contents( $this->path."builder2.xml", $creation );
		$this->assertEquals( $assertion, $creation );
		@unlink( $this->path."builder2.xml" );
	}

	/**
	 *	Tests Exception of Method 'build'.
	 *	@access		public
	 *	@return		void
	 */
	public function testBuildException()
	{
		$this->setExpectedException( 'Exception' );
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

class Tests_XML_RSS_BuilderInstance extends XML_RSS_Builder
{
	public function getProtectedVar( $varName )
	{
		if( !in_array( $varName, array_keys( get_object_vars( $this ) ) ) )
			throw new Exception( 'Var "'.$varName.'" is not declared.' );
		return $this->$varName;
	}
}
?>