<?php
/**
 *	TestUnit of XSL Transformator.
 *	@package		Tests.xml.dom
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@since			13.12.2007
 *
 */
declare( strict_types = 1 );

use CeusMedia\Common\Test\BaseCase;

/**
 *	TestUnit of XSL Transformator.
 *	@package		Tests.xml.dom
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@since			13.12.2007
 *
 */
class Test_XML_XSL_TransformatorTest extends BaseCase
{
	/**
	 *	Sets up Node.
	 *	@access		public
	 *	@return		void
	 */
	public function setUp(): void
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
