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
	/**	@var		XML_Element		$element		XML element instance */
	protected $element;
	protected $fileRead;
	protected $fileWrite;
	protected $fileSerial;

	public function setUp()
	{
		$this->fileRead		= dirname( __FILE__ ).'/element_read.xml';
		$this->fileWrite	= dirname( __FILE__ ).'/element_write.xml';
		$this->fileSerial	= dirname( __FILE__ ).'/element_write_test.serial';
		$this->xml			= file_get_contents( $this->fileRead );
		$this->xmlNs		= str_replace( '<root>', '<root xmlns:my="http://my.image.ns/">', $this->xml );
	}
	
	public function testAddAttribute()
	{
		$element	= new XML_Element( $this->xml );
		
		$element->image[3]->addAttribute( 'testKey', "testValue" );
		$assertion	= "testValue";
		$creation	= $element->image[3]->getAttribute( 'testKey' );
		$this->assertEquals( $assertion, $creation );
		
		$assertion	= '<image name="Banner 4" file="pic4.jpg" testKey="testValue"/>';
		$creation	= $element->image[3]->asXml();
		$this->assertEquals( $assertion, $creation );
	}
	
	public function testAddAttributeWithNamespace()
	{
		$element	= new XML_Element( $this->xmlNs );
		
		$element->image[3]->addAttribute( 'testKey', "testValue", 'my' );

		$assertion	= "testValue";
		$creation	= $element->image[3]->getAttribute( 'testKey', 'my' );
		$this->assertEquals( $assertion, $creation );
		
		$assertion	= '<image name="Banner 4" file="pic4.jpg" my:testKey="testValue"/>';
		$creation	= $element->image[3]->asXml();
		$this->assertEquals( $assertion, $creation );
	}
	
	public function testAddAttributeWithUnregisteredNamespace()
	{
		$element	= new XML_Element( $this->xml );
		
		$element->image[3]->addAttribute( 'testKey', "testValue", 'my', 'http://my.image.ns/' );

		$assertion	= "testValue";
		$creation	= $element->image[3]->getAttribute( 'testKey', 'my' );
		$this->assertEquals( $assertion, $creation );
		
		$assertion	= '<image xmlns:my="http://my.image.ns/" name="Banner 4" file="pic4.jpg" my:testKey="testValue"/>';
		$creation	= $element->image[3]->asXml();
		$this->assertEquals( $assertion, $creation );
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
		$creation	= $element->image[4]->getAttribute( "name" );
		$this->assertEquals( $assertion, $creation );
	}
	
	public function testAddChildWithNamespace()
	{
		$element	= new XML_Element( $this->xmlNs );
		$image		= $element->addChild( "image", 'TestContent', 'my' );
		$image->setAttribute( 'name', 'TestAttribute' );

		$assertion	= 4;
		$creation	= $element->countChildren();
		$this->assertEquals( $assertion, $creation );

		$assertion	= 1;
		$creation	= $element->countChildren( 'my' );
		$this->assertEquals( $assertion, $creation );

		$assertion	= 'TestAttribute';
		$creation	= (string) $element->children( 'my', TRUE )->getAttribute( 'name' );
		$this->assertEquals( $assertion, $creation );

		$assertion	= 'TestContent';
		$creation	= (string) $element->children( 'my', TRUE )->getValue();
		$this->assertEquals( $assertion, $creation );

		$creation	= $element->children( 'my', TRUE )->asXml();
		$assertion	= '<my:image name="TestAttribute">TestContent</my:image>';
		$this->assertEquals( $assertion, $creation );
	}
	
	public function testAddChildWithUnregisteredNamespace()
	{
		$element	= new XML_Element( $this->xml );
		$image		= $element->addChild( "image", 'TestContent', 'my', 'http://my.image.ns/' );
		$image->setAttribute( 'name', 'TestAttribute' );

		$assertion	= 4;
		$creation	= $element->countChildren();
		$this->assertEquals( $assertion, $creation );

		$assertion	= 1;
		$creation	= $element->countChildren( 'my' );
		$this->assertEquals( $assertion, $creation );

		$assertion	= 'TestAttribute';
		$creation	= (string) $element->children( 'my', TRUE )->getAttribute( 'name' );
		$this->assertEquals( $assertion, $creation );

		$assertion	= 'TestContent';
		$creation	= (string) $element->children( 'my', TRUE )->getValue();
		$this->assertEquals( $assertion, $creation );

		$creation	= $element->children( 'my', TRUE )->asXml();
		$assertion	= '<my:image xmlns:my="http://my.image.ns/" name="TestAttribute">TestContent</my:image>';
		$this->assertEquals( $assertion, $creation );
	}
	
	public function testAddChildCData()
	{
		$element	= new XML_Element( $this->xml );
		$image		= $element->addChildCData( "image", 'äöü&' );

		$assertion	= 5;
		$creation	= $element->countChildren();
		$this->assertEquals( $assertion, $creation );

		$assertion	= '<image><![CDATA[äöü&]]></image>';
		$creation	= $element->image[4]->asXml();
		$this->assertEquals( $assertion, $creation );
	}
	
	public function testAddChildCDataWithNamespace()
	{
		$element	= new XML_Element( $this->xmlNs );
		$image		= $element->addChildCData( "image", 'äöü&', 'my' );

		$assertion	= 1;
		$creation	= $element->countChildren( 'my', TRUE );
		$this->assertEquals( $assertion, $creation );

		$assertion	= '<my:image><![CDATA[äöü&]]></my:image>';
		$creation	= $element->children( 'my', TRUE )->asXml();
		$this->assertEquals( $assertion, $creation );
	}
	
	public function testAddChildCDataWithUnregisteredNamespace()
	{
		$element	= new XML_Element( $this->xml );
		$image		= $element->addChildCData( "image", 'äöü&', 'my', 'http://my.image.ns/' );

		$assertion	= 1;
		$creation	= $element->countChildren( 'my', TRUE );
		$this->assertEquals( $assertion, $creation );

		$assertion	= '<my:image xmlns:my="http://my.image.ns/"><![CDATA[äöü&]]></my:image>';
		$creation	= $element->children( 'my', TRUE )->asXml();
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

	public function testCountAttributes()
	{
		$element	= new XML_Element( $this->xml );
		$assertion	= 2;
		$creation	= $element->image[2]->countAttributes();
		$this->assertEquals( $assertion, $creation );
	}

	public function testCountAttributesWithNamespace()
	{
		$element	= new XML_Element( $this->xmlNs );
		$child		= $element->addChild( 'image', NULL );
		$child->addAttribute( 'attr1', 'value1', 'my' );
		$child->addAttribute( 'attr2', 'value2', 'my' );
		$child->addAttribute( 'attr3', 'value3', 'my' );
		
		$assertion	= 0;
		$creation	= $element->image[4]->countAttributes();
		$this->assertEquals( $assertion, $creation );

		$assertion	= 3;
		$creation	= $element->image[4]->countAttributes( 'my' );
		$this->assertEquals( $assertion, $creation );
	}

	public function testCountChildren()
	{
		$element	= new XML_Element( $this->xml );
		$assertion	= 4;
		$creation	= $element->countChildren();
		$this->assertEquals( $assertion, $creation );
	}

	public function testCountChildrenWithNamespace()
	{
		$element	= new XML_Element( $this->xmlNs );
		$assertion	= 0;
		$creation	= $element->countChildren( 'my' );
		$this->assertEquals( $assertion, $creation );

		$image		= $element->addChild( "image", 'TestContent', 'my', 'http://my.image.ns/' );
		$assertion	= 1;
		$creation	= $element->countChildren( 'my' );
		$this->assertEquals( $assertion, $creation );
	}

	public function testGetAttribute()
	{
		$element	= new XML_Element( $this->xml );
		$assertion	= "pic3.jpg";
		$creation	= $element->image[2]->getAttribute( 'file' );
		$this->assertEquals( $assertion, $creation );
	}

	public function testGetAttributeWithNamespace()
	{
		$element	= new XML_Element( $this->xmlNs );
		$element->image[0]->addAttribute( 'lang', 'de', 'my' );
		
		$assertion	= FALSE;
		$creation	= $element->image[0]->hasAttribute( 'lang' );
		$this->assertEquals( $assertion, $creation );
		
		$assertion	= TRUE;
		$creation	= $element->image[0]->hasAttribute( 'lang', 'my' );
		$this->assertEquals( $assertion, $creation );
		
		$assertion	= "de";
		$creation	= $element->image[0]->getAttribute( 'lang', 'my' );
		$this->assertEquals( $assertion, $creation );
	}

	public function testGetAttributeNames()
	{
		$element	= new XML_Element( $this->xml );
		$assertion	= array(
			'name',
			'file',
		);
		$creation	= $element->image[2]->getAttributeNames();
		$this->assertEquals( $assertion, $creation );
	}

	public function testGetAttributeNamesWithNamespace()
	{
		$element	= new XML_Element( $this->xmlNs );
		$element->image[2]->addAttribute( 'attr1', 'value1', 'my' );
		$element->image[2]->addAttribute( 'attr2', 'value2', 'my' );
		$element->image[2]->addAttribute( 'attr3', 'value3', 'my' );
		$assertion	= array(
			'attr1',
			'attr2',
			'attr3',
		);
		$creation	= $element->image[2]->getAttributeNames( 'my' );
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
	
	public function testGetAttributesWithNamespace()
	{
		$element	= new XML_Element( $this->xmlNs );
		$element->image[2]->addAttribute( 'attr1', 'value1', 'my' );
		$element->image[2]->addAttribute( 'attr2', 'value2', 'my' );
		$element->image[2]->addAttribute( 'attr3', 'value3', 'my' );
		$assertion	= array(
			'attr1'	=> 'value1',
			'attr2'	=> 'value2',
			'attr3'	=> 'value3',
		);
		$creation	= $element->image[2]->getAttributes( 'my' );
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
	
	public function testHasAttributeWithNamespace()
	{
		$element	= new XML_Element( $this->xmlNs );
		$assertion	= FALSE;
		$creation	= $element->image[2]->hasAttribute( 'name', 'my' );
		$this->assertEquals( $assertion, $creation );

		$element->image[2]->addAttribute( 'name', 'me', 'my' );
		$assertion	= TRUE;
		$creation	= $element->image[2]->hasAttribute( 'name' );
		$this->assertEquals( $assertion, $creation );
	}
	
	public function testRemoveAttribute()
	{
		$element	= new XML_Element( $this->xml );
		$element->image[2]->removeAttribute( 'name' );

		$assertion	= FALSE;
		$creation	= $element->image[2]->hasAttribute( 'name' );
		$this->assertEquals( $assertion, $creation );
	}
	
	public function testRemoveAttributeWithNamespace()
	{
		$element	= new XML_Element( $this->xmlNs );
		$element->image[2]->addAttribute( 'name', 'me', 'my' );

		$assertion	= TRUE;
		$creation	= $element->image[2]->hasAttribute( 'name', 'my' );
		$this->assertEquals( $assertion, $creation );

		$element->image[2]->removeAttribute( 'name' );

		$assertion	= TRUE;
		$creation	= $element->image[2]->hasAttribute( 'name', 'my' );
		$this->assertEquals( $assertion, $creation );

		$element->image[2]->removeAttribute( 'name', 'my' );

		$assertion	= FALSE;
		$creation	= $element->image[2]->hasAttribute( 'name', 'my' );
		$this->assertEquals( $assertion, $creation );
	}

	public function testSetAttribute(){
		$element	= new XML_Element( $this->xml );

		$element->image[2]->setAttribute( 'name', 'test' );

		$assertion	= 'test';
		$creation	= $element->image[2]->getAttribute( 'name' );
		$this->assertEquals( $assertion, $creation );

		$element->image[2]->setAttribute( 'attr1', 'value1' );

		$assertion	= TRUE;
		$creation	= $element->image[2]->hasAttribute( 'attr1' );
		$this->assertEquals( $assertion, $creation );

		$assertion	= 'value1';
		$creation	= $element->image[2]->getAttribute( 'attr1' );
		$this->assertEquals( $assertion, $creation );

		$element->image[2]->setAttribute( 'attr1', NULL );

		$assertion	= FALSE;
		$creation	= $element->image[2]->hasAttribute( 'attr1' );
		$this->assertEquals( $assertion, $creation );
	}

	public function testSetAttributeWithNamespace(){
		$element	= new XML_Element( $this->xmlNs );

		$element->image[2]->addAttribute( 'attr1', 'value1', 'my' );

		$assertion	= 'value1';
		$creation	= $element->image[2]->getAttribute( 'attr1', 'my' );
		$this->assertEquals( $assertion, $creation );

		$element->image[2]->setAttribute( 'attr2', 'value2', 'my' );

		$assertion	= TRUE;
		$creation	= $element->image[2]->hasAttribute( 'attr1', 'my' );
		$this->assertEquals( $assertion, $creation );

		$assertion	= 'value2';
		$creation	= $element->image[2]->getAttribute( 'attr2', 'my' );
		$this->assertEquals( $assertion, $creation );

		$element->image[2]->setAttribute( 'attr1', 'value3', 'my');

		$assertion	= 'value3';
		$creation	= $element->image[2]->getAttribute( 'attr1', 'my' );
		$this->assertEquals( $assertion, $creation );
		
		$element->image[2]->setAttribute( 'attr2', NULL, 'my');

		$assertion	= FALSE;
		$creation	= $element->image[2]->hasAttribute( 'attr2' );
		$this->assertEquals( $assertion, $creation );
	}

	public function testSetValue(){
		$element	= new XML_Element( $this->xml );
		
		$element->image[2]->setValue( 'Nice one' );

		$assertion	= 'Nice one';
		$creation	= (string) $element->image[2];
		$this->assertEquals( $assertion, $creation );
		
		$element->image[2]->setValue( NULL );
		
		$assertion	= '';
		$creation	= (string) $element->image[2];
		$this->assertEquals( $assertion, $creation );
	}

	public function testSetValueWithCData(){
		$element	= new XML_Element( $this->xml );
		
		$element->image[2]->setValue( 'äöü&' );
		
		$assertion	= 'äöü&';
		$creation	= (string) $element->image[2];
		$this->assertEquals( $assertion, $creation );

		
		$assertion	= '<image name="Banner 3" file="pic3.jpg"><![CDATA[äöü&]]></image>';
		$creation	= (string) $element->image[2]->asXml();
		$this->assertEquals( $assertion, $creation );
	}
 }
?>