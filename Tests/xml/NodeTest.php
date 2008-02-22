<?php
/**
 *	TestUnit of XML Node.
 *	@package		Tests.xml
 *	@extends		PHPUnit_Framework_TestCase
 *	@uses			XML_Node
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			20.02.2008
 *	@version		0.1
 */
require_once( 'PHPUnit/Framework/TestCase.php' ); 
require_once( 'Tests/initLoaders.php5' );
import( 'de.ceus-media.xml.Node' );
/**
 *	TestUnit of XML Node.
 *	@package		Tests.xml
 *	@extends		PHPUnit_Framework_TestCase
 *	@uses			XML_Node
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			20.02.2008
 *	@version		0.1
 */
class Tests_XML_NodeTest extends PHPUnit_Framework_TestCase
{
	protected $fileRead		= "Tests/xml/node_read.xml";
	protected $fileWrite	= "Tests/xml/node_write.xml";
	protected $fileSerial	= "Tests/xml/node_write_test.serial";

	public function setUp()
	{
		$this->xml	= file_get_contents( $this->fileRead );
	}
	
	public function testAddChild()
	{
		$node	= new XML_Node( $this->xml );
		$image	= $node->addChild( "image" );

		$assertion	= 5;
		$creation	= $node->countChildren();
		$this->assertEquals( $assertion, $creation );

		$image->addAttribute( "name", "Banner 5" );
		$image->addAttribute( "file", "pic5.jpg" );
		$assertion	= "Banner 5";
		$creation	= $node->image[4]->getAttribute( "name" );;
	}
	
	public function testAddAttribute()
	{
		$node	= new XML_Node( $this->xml );
		
		$node->image[3]->addAttribute( 'testKey', "testValue" );
		$assertion	= "testValue";
		$creation	= $node->image[3]->getAttribute( 'testKey' );
		$this->assertEquals( $assertion, $creation );
	}

	public function testAsFile()
	{
		$node	= new XML_Node( $this->xml );
		$node->asFile( $this->fileWrite);
		$assertion	= $this->xml;
		$creation	= file_get_contents( $this->fileWrite );
		$this->assertEquals( $assertion, $creation );
	}

	public function testAsXml()
	{
		$node	= new XML_Node( $this->xml );
		$assertion	= $this->xml;
		$creation	= $node->asXml();
		$this->assertEquals( $assertion, $creation );
	}

	public function testCountChildren()
	{
		$node	= new XML_Node( $this->xml );
		$assertion	= 4;
		$creation	= $node->countChildren();
		$this->assertEquals( $assertion, $creation );
	}

	public function testCountAttributes()
	{
		$node	= new XML_Node( $this->xml );
		$assertion	= 2;
		$creation	= $node->image[2]->countAttributes();
		$this->assertEquals( $assertion, $creation );
	}

	public function testGetAttribute()
	{
		$node	= new XML_Node( $this->xml );
		$assertion	= "pic3.jpg";
		$creation	= $node->image[2]->getAttribute( 'file' );
		$this->assertEquals( $assertion, $creation );
	}

	public function testGetAttributeKeys()
	{
		$node	= new XML_Node( $this->xml );
		$assertion	= array(
			'name',
			'file',
		);
		$creation	= $node->image[2]->getAttributeKeys();
		$this->assertEquals( $assertion, $creation );
	}
	
	public function testGetAttributes()
	{
		$node	= new XML_Node( $this->xml );
		$assertion	= array(
			'name'	=> "Banner 3",
			'file'	=> "pic3.jpg",
		);
		$creation	= $node->image[2]->getAttributes();
		$this->assertEquals( $assertion, $creation );
	}
	
	public function testHasAttribute()
	{
		$node	= new XML_Node( $this->xml );
		$assertion	= true;
		$creation	= $node->image[2]->hasAttribute( 'name' );
		$this->assertEquals( $assertion, $creation );

		$node	= new XML_Node( $this->xml );
		$assertion	= false;
		$creation	= $node->image[2]->hasAttribute( 'id' );
		$this->assertEquals( $assertion, $creation );
	}
}
?>