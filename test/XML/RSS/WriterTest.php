<?php
/** @noinspection PhpIllegalPsrClassPathInspection */
/** @noinspection PhpMultipleClassDeclarationsInspection */
/** @noinspection PhpUnhandledExceptionInspection */
/** @noinspection PhpDocMissingThrowsInspection */

declare( strict_types = 1 );

/**
 *	TestUnit of XML RSS Writer.
 *	@package		Tests.xml.dom
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 */

namespace CeusMedia\CommonTest\XML\RSS;

use CeusMedia\Common\XML\RSS\Writer;
use CeusMedia\CommonTest\BaseCase;
use CeusMedia\CommonTest\MockAntiProtection;
use CeusMedia\CommonTest\XML\RSS\WriterMockAntiProtection as Mock;

/**
 *	TestUnit of XML RSS Writer.
 *	@package		Tests.xml.dom
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 */
class WriterTest extends BaseCase
{
	protected $writer;
	protected $path;
	protected $assert;
	protected $serial;
	protected $file;

	/**
	 *	Constructor.
	 *	@access		public
	 *	@return		void
	 */
	public function __construct($name)
	{
		parent::__construct($name);
		MockAntiProtection::createMockClass( Writer::class );
	}

	/**
	 *	Sets up Builder.
	 *	@access		public
	 *	@return		void
	 */
	public function setUp(): void
	{
		/** @noinspection PhpUndefinedClassInspection */
		$this->writer	= new Mock();
		$this->path		= dirname( __FILE__ )."/assets/";
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
		$this->assertCount( 1, $itemList );
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
		$writer	= new Writer();
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

		$assertion	= 2545;
		$creation	= $writer->write( $this->file );
		$this->assertEquals( $assertion, $creation );

		$this->assertXmlFileEqualsXmlFile( $this->assert, $this->file );
	}
}
