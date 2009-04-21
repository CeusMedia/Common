<?php
/**
 *	TestUnit of Alg_SgmlTagReader.
 *	@package		Tests.alg
 *	@extends		PHPUnit_Framework_TestCase
 *	@uses			Alg_SgmlTagReader
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			03.08.2008
 *	@version		0.1
 */
require_once( 'PHPUnit/Framework/TestCase.php' ); 
require_once( 'Tests/initLoaders.php5' );
import( 'de.ceus-media.alg.SgmlTagReader' );
/**
 *	TestUnit of Alg_SgmlTagReader.
 *	@package		Tests.alg
 *	@extends		PHPUnit_Framework_TestCase
 *	@uses			Alg_SgmlTagReader
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			03.08.2008
 *	@version		0.1
 */
class Tests_Alg_SgmlTagReaderTest extends PHPUnit_Framework_TestCase
{
	public $tag1	= '<a href="http://google.com/" target="_blank" class=\'test-class other\'>Google</a>';
	public $tag2	= '<body font-color="#FF0000" onFocus="this.blur()">';

	/**
	 *	Constructor.
	 *	@access		public
	 *	@return		void
	 */
	public function __construct()
	{
		$reader	= new Alg_SgmlTagReader;
	}
	
	/**
	 *	Setup for every Test.
	 *	@access		public
	 *	@return		void
	 */
	public function setUp()
	{
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
	 *	Tests Method 'getNodeName'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetNodeName()
	{
		$assertion	= "a";
		$creation	= Alg_SgmlTagReader::getNodeName( $this->tag1 );
		$this->assertEquals( $assertion, $creation );

		$assertion	= "body";
		$creation	= Alg_SgmlTagReader::getNodeName( $this->tag2 );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'getAttributes'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetAttributes()
	{
		$assertion	= array(
			'href'		=> "http://google.com/",
			'target'	=> "_blank",
			'class'		=> "test-class other"
		);
		$creation	= Alg_SgmlTagReader::getAttributes( $this->tag1 );
		$this->assertEquals( $assertion, $creation );

		$assertion	= array(
			'font-color'	=> "#FF0000",
			'onFocus'		=> "this.blur()",
		);
		$creation	= Alg_SgmlTagReader::getAttributes( $this->tag2 );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'getContent'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetContent()
	{
		$assertion	= "Google";
		$creation	= Alg_SgmlTagReader::getContent( $this->tag1 );
		$this->assertEquals( $assertion, $creation );

		$assertion	= "";
		$creation	= Alg_SgmlTagReader::getContent( $this->tag2 );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'getTagData'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetTagData()
	{
		$assertion	= array(
			'nodename'		=> "a",
			'content'		=> "Google",
			'attributes'	=> array(
				'href'		=> "http://google.com/",
				'target'	=> "_blank",
				'class'		=> "test-class other"
			)
		);
		$creation	= Alg_SgmlTagReader::getTagData( $this->tag1 );
		$this->assertEquals( $assertion, $creation );
	}
}
?>