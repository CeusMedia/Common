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

	/**
	 *	Tests Method 'build'.
	 *	@access		public
	 *	@return		void
	 */
	public function testBuild()
	{
		$builder	= new XML_RSS_Builder();
		$data	= unserialize( file_get_contents( $this->serial ) );
		foreach( $data['channelData'] as $key => $value  )
		{
			if( is_array( $value ) )
			{
				foreach( $value as $subKey => $subValue )
				{
					$subKey	= $key.ucFirst( $subKey ); 
					$builder->setChannelPair( $subKey, $subValue );
				}
			}
			else
				$builder->setChannelPair( $key, $value );
		}
		foreach( $data['itemList'] as $item )
			$builder->addItem( $item );

		$assertion	= file_get_contents( $this->file );
		$creation	= $builder->build();
		$this->assertEquals( $assertion, $creation );
	}
}
?>