<?php
/**
 *	TestUnit of XML RSS Writer.
 *	@package		Tests.xml.dom
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@since			20.02.2008
 *
 */
declare( strict_types = 1 );

use CeusMedia\Common\Test\BaseCase;

/**
 *	TestUnit of XML RSS Writer.
 *	@package		Tests.xml.dom
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@since			20.02.2008
 *
 */
class Test_XML_RSS_WriterTest extends BaseCase
{
	protected $writer;

	/**
	 *	Constructor.
	 *	@access		public
	 *	@return		void
	 */
	public function __construct()
	{
		parent::__construct();
		Test_MockAntiProtection::createMockClass( 'XML_RSS_Writer' );
	}

	/**
	 *	Sets up Builder.
	 *	@access		public
	 *	@return		void
	 */
	public function setUp(): void
	{
		$this->writer	= new Test_XML_RSS_Writer_MockAntiProtection();
		$this->path		= dirname( __FILE__ )."/";
		$this->assert	= $this->path."reader.xml";
		$this->file		= $this->path."writer.xml";
		$this->serial	= $this->path."reader.serial";

#		$this->timeZone	= date_default_timezone_get();
#		date_default_timezone_set( 'GMT' );
	}

	/**
	 *	Sets down Writer.
	 *	@access		public
	 *	@return		void
	 */
	public function tearDown(): void
	{
		@unlink( $this->file );
#		date_default_timezone_set( $this->timeZone );
	}

	/**
	 *	Tests Method 'addItem'.
	 *	@access		public
	 *	@return		void
	 */
	public function testAddItem()
	{
		$data	= array( 'key1' => 'value2' );
		$this->writer->addItem( $data );
		$itemList	= $this->writer->getProtectedVar( 'itemList' );
		$this->assertEquals( 1, count( $itemList ) );
		$this->assertEquals( $data, current( $itemList ) );
	}

	/**
	 *	Tests Method 'setChannelData'.
	 *	@access		public
	 *	@return		void
	 */
	public function testSetChannelData()
	{
		$data		= array(
			'key1'	=> 'value1',
			'key2'	=> 'value2',
		);
		$this->writer->setChannelData( $data );
		$this->assertEquals( $data, $this->writer->getProtectedVar( 'channelData' ) );
	}

	/**
	 *	Tests Method 'setChannelPair'.
	 *	@access		public
	 *	@return		void
	 */
	public function testSetChannelPair()
	{
		$data		= array(
			'key1'	=> 'value1',
			'key2'	=> 'value2',
		);
		$this->writer->setChannelPair( 'key1', 'value1' );
		$this->writer->setChannelPair( 'key2', 'value2' );
		$this->assertEquals( $data, $this->writer->getProtectedVar( 'channelData' ) );
	}

	/**
	 *	Tests Method 'setItemList'.
	 *	@access		public
	 *	@return		void
	 */
	public function testSetItemList()
	{
		$data	= array( 'key1', 'key2' );
		$this->writer->setItemList( $data );
		$this->assertEquals( $data, $this->writer->getProtectedVar( 'itemList' ) );
	}

	/**
	 *	Tests Method 'build'.
	 *	@access		public
	 *	@return		void
	 */
	public function testWrite()
	{
		$writer	= new XML_RSS_Writer();
		$data	= unserialize( file_get_contents( $this->serial ) );
		foreach( $data['channelData'] as $key => $value  )
		{
			if( is_array( $value ) )
			{
				foreach( $value as $subKey => $subValue )
				{
					$subKey	= $key.ucFirst( $subKey );
					$writer->setChannelPair( $subKey, $subValue );
				}
			}
			else
				$writer->setChannelPair( $key, $value );
		}
		foreach( $data['itemList'] as $item )
			$writer->addItem( $item );

#		$assertion	= 2469;
		$creation	= $writer->write( $this->file );
#		$this->assertEquals( $assertion, $creation );

		$this->assertXmlFileEqualsXmlFile( $this->assert, $this->file );
	}
}
