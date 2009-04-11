<?php
/**
 *	TestUnit of XML RSS Writer.
 *	@package		Tests.xml.dom
 *	@extends		PHPUnit_Framework_TestCase
 *	@uses			XML_RSS_Writer
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			20.02.2008
 *	@version		0.1
 */
require_once( 'PHPUnit/Framework/TestCase.php' ); 
require_once( 'Tests/initLoaders.php5' );
import( 'de.ceus-media.xml.rss.Writer' );
/**
 *	TestUnit of XML RSS Writer.
 *	@package		Tests.xml.dom
 *	@extends		PHPUnit_Framework_TestCase
 *	@uses			XML_RSS_Writer
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			20.02.2008
 *	@version		0.1
 */
class Tests_XML_RSS_WriterTest extends PHPUnit_Framework_TestCase
{

	/**
	 *	Sets up Builder.
	 *	@access		public
	 *	@return		void
	 */
	public function setUp()
	{
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
	public function tearDown()
	{
		@unlink( $this->file );
#		date_default_timezone_set( $this->timeZone );
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

		$assertion	= 2469;
		$creation	= $writer->write( $this->file );
		$this->assertEquals( $assertion, $creation );

		$assertion	= preg_replace( "@\n(\r)*@", "", file_get_contents( $this->assert ) );
		$creation	= preg_replace( "@\n(\r)*@", "", file_get_contents( $this->file ) );
		$this->assertEquals( $assertion, $creation );
	}
}
?>