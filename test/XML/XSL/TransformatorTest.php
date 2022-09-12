<?php /** @noinspection PhpMultipleClassDeclarationsInspection */
/**
 *	TestUnit of XSL Transformator.
 *	@package		Tests.xml.dom
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 */
declare( strict_types = 1 );

namespace CeusMedia\Common\Test\XML\XSL;

use CeusMedia\Common\Test\BaseCase;
use CeusMedia\Common\XML\XSL\Transformator;

/**
 *	TestUnit of XSL Transformator.
 *	@package		Tests.xml.dom
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 */
class TransformatorTest extends BaseCase
{
	protected $transformator;
	protected $result;
	protected $path;

	/**
	 *	Sets up Node.
	 *	@access		public
	 *	@return		void
	 */
	public function setUp(): void
	{
		if( !class_exists( 'XSLTProcessor' ) )
			$this->markTestSkipped( 'Support for XSL is missing' );
		$this->path		= dirname( __FILE__ )."/";
		$this->transformator	= new Transformator();
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
