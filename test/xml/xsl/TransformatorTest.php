<?php
/**
 *	TestUnit of XSL Transformator.
 *	@package		Tests.xml.dom
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			13.12.2007
 *	@version		0.1
 */
require_once 'PHPUnit/Framework/TestCase.php'; 
require_once 'test/initLoaders.php5';
/**
 *	TestUnit of XSL Transformator.
 *	@package		Tests.xml.dom
 *	@extends		PHPUnit_Framework_TestCase
 *	@uses			XML_DOM_Node
 *	@uses			XML_DOM_Leaf
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			13.12.2007
 *	@version		0.1
 */
class Test_XML_XSL_TransformatorTest extends PHPUnit_Framework_TestCase
{
	/**
	 *	Sets up Node.
	 *	@access		public
	 *	@return		void
	 */
	public function setUp()
	{
		$this->path	= dirname( __FILE__ )."/";
		$this->transformator	= new XML_XSL_Transformator();
		$this->transformator->loadXmlFile( $this->path."collection.xml" );
		$this->transformator->loadXslFile( $this->path."collection.xsl" );
		$this->result	= file_get_contents( $this->path."result.html" );
	}

	/**
	 *	Tests Method 'addChild'.
	 *	@access		public
	 *	@return		void
	 */
	public function testTransform()
	{
		$assertion	= $this->result;
		$creation	= $this->transformator->transform();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'addChild'.
	 *	@access		public
	 *	@return		void
	 */
	public function testTransformToFile()
	{
		$this->transformator->transformToFile( $this->path."output.html" );
		$assertion	= $this->result;
		$creation	= file_get_contents( $this->path."output.html" );
		$this->assertEquals( $assertion, $creation );
	}
}
?>