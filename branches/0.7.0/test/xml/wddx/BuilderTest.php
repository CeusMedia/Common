<?php
/**
 *	TestUnit of XML_WDDX_Builder.
 *	@package		Tests.xml.wddx
 *	@extends		PHPUnit_Framework_TestCase
 *	@uses			XML_WDDX_Builder
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			02.05.2008
 *	@version		0.1
 */
require_once( 'PHPUnit/Framework/TestCase.php' ); 
require_once '../autoload.php5';
import( 'de.ceus-media.xml.wddx.Builder' );
/**
 *	TestUnit of XML_WDDX_Builder.
 *	@package		Tests.xml.wddx
 *	@extends		PHPUnit_Framework_TestCase
 *	@uses			XML_WDDX_Builder
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			02.05.2008
 *	@version		0.1
 */
class XML_WDDX_BuilderTest extends PHPUnit_Framework_TestCase
{
	/**
	 *	Constructor.
	 *	@access		public
	 *	@return		void
	 */
	public function __construct()
	{
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
?>