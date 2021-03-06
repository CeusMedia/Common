<?php
/**
 *	TestUnit of XML_WDDX_Builder.
 *	@package		Tests.xml.wddx
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@since			02.05.2008
 *	@version		0.1
 */
declare( strict_types = 1 );

use PHPUnit\Framework\TestCase;

/**
 *	TestUnit of XML_WDDX_Builder.
 *	@package		Tests.xml.wddx
 *	@extends		Test_Case
 *	@uses			XML_WDDX_Builder
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@since			02.05.2008
 *	@version		0.1
 */
class Test_XML_WDDX_BuilderTest extends Test_Case
{
	public function setUp(): void
	{
		if( !extension_loaded( 'wddx' ) )
			$this->markTestSkipped( 'Missing WDDX support' );
		$this->builder	= new XML_WDDX_Builder( 'test' );
	}

	/**
	 *	Tests Method '__construct'.
	 *	@access		public
	 *	@return		void
	 */
	public function testConstruct()
	{
		$builder	= new XML_WDDX_Builder( 'constructorTest' );
		$assertion	= "<wddxPacket version='1.0'><header><comment>constructorTest</comment></header><data><struct></struct></data></wddxPacket>";
		$creation	= $builder->build();
		$this->assertEquals( $assertion, $creation );

		$builder	= new XML_WDDX_Builder();
		$assertion	= "<wddxPacket version='1.0'><header/><data><struct></struct></data></wddxPacket>";
		$creation	= $builder->build();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'add'.
	 *	@access		public
	 *	@return		void
	 */
	public function testAdd()
	{
		$assertion	= TRUE;
		$creation	= $this->builder->add( 'testKey1', 'testValue1' );
		$this->assertEquals( $assertion, $creation );

		$assertion	= "<wddxPacket version='1.0'><header><comment>test</comment></header><data><struct><var name='testKey1'><string>testValue1</string></var></struct></data></wddxPacket>";
		$creation	= $this->builder->build( 'testKey1', 'testValue1' );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'build'.
	 *	@access		public
	 *	@return		void
	 */
	public function testBuild()
	{
		$assertion	= "<wddxPacket version='1.0'><header><comment>test</comment></header><data><struct></struct></data></wddxPacket>";
		$creation	= $this->builder->build();
		$this->assertEquals( $assertion, $creation );
	}
}
