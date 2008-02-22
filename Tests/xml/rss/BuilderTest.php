<?php
/**
 *	TestUnit of XML RSS Builder.
 *	@package		Tests.xml.dom
 *	@extends		PHPUnit_Framework_TestCase
 *	@uses			XML_RSS_Builder
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			20.02.2008
 *	@version		0.1
 */
require_once( 'PHPUnit/Framework/TestCase.php' ); 
require_once( 'Tests/initLoaders.php5' );
import( 'de.ceus-media.xml.rss.Builder' );
/**
 *	TestUnit of XML RSS Builder.
 *	@package		Tests.xml.dom
 *	@extends		PHPUnit_Framework_TestCase
 *	@uses			XML_RSS_Builder
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			20.02.2008
 *	@version		0.1
 */
class Tests_XML_RSS_BuilderTest extends PHPUnit_Framework_TestCase
{
	protected $file		= "Tests/xml/rss/reader.xml";
	protected $serial	= "Tests/xml/rss/reader.serial";

	public function setUp()
	{
		$this->builder	= new XML_RSS_Builder();

		$this->setup	= array(
			array(
				'channel'	=> array(
					'title'			=> "UnitTest created Feed",
					'description'	=> "This RSS Feed has been created by a PHPUnit Test.",
					'imageUrl'		=> "siegel_map.jpg",
					'link'			=> "http://nowhere.tld",
				),
				'items'		=> array(
					array(
						'title'		=> "Test Entry 1",
						'pubDate'	=> "Wed, 20 Feb 2008 23:33:20 +0100",

					),
					array(
						'title'	=> "Test Entry 2",
						'pubDate'	=> "Wed, 19 Feb 2008 23:33:20 +0100",
					),
				)
			),
		);
	}

	/**
	 *	Tests Method 'build'.
	 *	@access		public
	 *	@return		void
	 */
	public function testBuild()
	{
		$data	= unserialize( file_get_contents( $this->serial ) );

		$this->builder->setChannelData( $data['channelData'] ); 
		$this->builder->setItemList( $data['itemList'] ); 

		$assertion	= file_get_contents( $this->file );
		$creation	= $this->builder->build();
#		file_put_contents( $this->serial, serialize( $creation ) );
		$this->assertEquals( $assertion, $creation );
	}

	public function testBuildException1()
	{
		try
		{
			$this->builder->build();
			$this->fail( 'An expected Exception has not been thrown.' );
		}
		catch( Exception $e ){}
	}

	public function testBuildException2()
	{
		try
		{
			$this->builder->setChannelData( array( 'title' => "Test" ) );
			$this->builder->build();
			$this->fail( 'An expected Exception has not been thrown.' );
		}
		catch( Exception $e ){}
	}

	public function testBuild2()
	{
		$builder	= new XML_RSS_Builder();
		$this->builder->setChannelData( $this->setup[0]['channel'] );
		$this->builder->setItemList( $this->setup[0]['items'] );

		$assertion	= file_get_contents( "Tests/xml/rss/builder.xml" );
		$creation	= $this->builder->build( "iso-8859-1" );
#		file_put_contents( "Tests/xml/rss/creation.xml", $creation );
#		file_put_contents( "Tests/xml/rss/builder.xml", $creation );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'build'.
	 *	@access		public
	 *	@return		void
	 */
	public function testAddItem()
	{
	
	}
}
?>