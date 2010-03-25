<?php
/**
 *	TestUnit of XML Element.
 *	@package		Tests.xml
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			20.02.2008
 *	@version		0.1
 */
require_once 'PHPUnit/Framework/TestCase.php';
require_once 'Test/initLoaders.php5';
/**
 *	TestUnit of XML Element.
 *	@package		Tests.xml
 *	@extends		PHPUnit_Framework_TestCase
 *	@uses			XML_Element
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			20.02.2008
 *	@version		0.1
 */
class Test_XML_ElementTest extends PHPUnit_Framework_TestCase
{
	protected $fileRead;
	protected $fileWrite;
	protected $fileSerial;

	public function setUp()
	{
		$this->fileRead		= dirname( __FILE__ ).'/element_read.xml';
		$this->fileWrite	= dirname( __FILE__ ).'/element_write.xml';
		$this->fileSerial	= dirname( __FILE__ ).'/element_write_test.serial';
		$this->xml			= file_get_contents( $this->fileRead );
	}
	
	public function testAddChild()
	{
		$element	= new XML_Element( $this->xml );
		$image		= $element->addChild( "image" );

		$assertion	= 5;
		$creation	= $element->countChildren();
		$this->assertEquals( $assertion, $creation );

		$image->addAttribute( "name", "Banner 5" );
		$image->addAttribute( "file", "pic5.jpg" );
		$assertion	= "Banner 5";
		$creation	= $element->image[4]->getAttribute( "name" );;
		$this->assertEquals( $assertion, $creation );
	}
	
	public function testAddAttribute()
	{
		$element	= new XML_Element( $this->xml );
		
		$element->image[3]->addAttribute( 'testKey', "testValue" );
		$assertion	= "testValue";
		$creation	= $element->image[3]->getAttribute( 'testKey' );
		$this->assertEquals( $assertion, $creation );
	}

	public function testAsFile()
	{
		$element	= new XML_Element( $this->xml );
		$element->asFile( $this->fileWrite);
		$assertion	= $this->xml;
		$creation	= file_get_contents( $this->fileWrite );
		$this->assertEquals( $assertion, $creation );
	}

	public function testAsXml()
	{
		$element	= new XML_Element( $this->xml );
		$assertion	= $this->xml;
		$creation	= $element->asXml();
		$this->assertEquals( $assertion, $creation );
	}

	public function testCountChildren()
	{
		$element	= new XML_Element( $this->xml );
		$assertion	= 4;
		$creation	= $element->countChildren();
		$this->assertEquals( $assertion, $creation );
	}

	public function testCountAttributes()
	{
		$element	= new XML_Element( $this->xml );
		$assertion	= 2;
		$creation	= $element->image[2]->countAttributes();
		$this->assertEquals( $assertion, $creation );
	}

	public function testGetAttribute()
	{
		$element	= new XML_Element( $this->xml );
		$assertion	= "pic3.jpg";
		$creation	= $element->image[2]->getAttribute( 'file' );
		$this->assertEquals( $assertion, $creation );
	}

	public function testGetAttributeKeys()
	{
		$element	= new XML_Element( $this->xml );
		$assertion	= array(
			'name',
			'file',
		);
		$creation	= $element->image[2]->getAttributeKeys();
		$this->assertEquals( $assertion, $creation );
	}
	
	public function testGetAttributes()
	{
		$element	= new XML_Element( $this->xml );
		$assertion	= array(
			'name'	=> "Banner 3",
			'file'	=> "pic3.jpg",
		);
		$creation	= $element->image[2]->getAttributes();
		$this->assertEquals( $assertion, $creation );
	}
	
	public function testHasAttribute()
	{
		$element	= new XML_Element( $this->xml );
		$assertion	= true;
		$creation	= $element->image[2]->hasAttribute( 'name' );
		$this->assertEquals( $assertion, $creation );

		$element	= new XML_Element( $this->xml );
		$assertion	= false;
		$creation	= $element->image[2]->hasAttribute( 'id' );
		$this->assertEquals( $assertion, $creation );
	}
}
?>