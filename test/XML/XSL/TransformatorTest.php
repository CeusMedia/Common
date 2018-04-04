<?php
/**
 *	TestUnit of XSL Transformator.
 *	@package		Tests.xml.dom
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@since			13.12.2007
 *	@version		0.1
 */
require_once dirname( dirname( __DIR__ ) ).'/initLoaders.php';
/**
 *	TestUnit of XSL Transformator.
 *	@package		Tests.xml.dom
 *	@extends		Test_Case
 *	@uses			XML_DOM_Node
 *	@uses			XML_DOM_Leaf
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@since			13.12.2007
 *	@version		0.1
 */
class Test_XML_XSL_TransformatorTest extends Test_Case
{
	/**
	 *	Sets up Node.
	 *	@access		public
	 *	@return		void
	 */
	public function setUp()
	{
		if( !class_exists( 'XSLTProcessor' ) )
			$this->markTestSkipped( 'Support for XSL is missing' );
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
