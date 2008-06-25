<?php
/**
 *	TestUnit of CategoryFactory
 *	@package		tests.framework.krypton.core
 *	@extends		PHPUnit_Framework_TestCase
 *	@uses			Framework_Krypton_Core_CategoryFactory
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.1
 */
require_once 'PHPUnit/Framework/TestCase.php'; 
require_once 'Tests/initLoaders.php5' ;
import( 'de.ceus-media.framework.krypton.core.CategoryFactory' );
/**
 *	TestUnit of CategoryFactory
 *	@package		tests.framework.krypton.core
 *	@extends		PHPUnit_Framework_TestCase
 *	@uses			Framework_Krypton_Core_CategoryFactory
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.1
 */
class Tests_Framework_Krypton_Core_CategoryFactoryTest extends PHPUnit_Framework_TestCase
{
	public function setUp()
	{
		$this->factory	= new Framework_Krypton_Core_CategoryFactory();
		$this->types	= array( 'foo', 'bar', 'baz' );
	}

	public function testGetType()
	{
		try
		{
			$this->factory->getType();
			$this->fail( 'An expected Exception has not been thrown.' );
		}
		catch( Exception $e ){}

		$this->factory->setTypes( $this->types );

		$assertion	= "foo";
		$creation	= $this->factory->getType();
		$this->assertEquals( $assertion, $creation );


		$this->factory->setDefault( 'bar' );
		$assertion	= "bar";
		$creation	= $this->factory->getType();
		$this->assertEquals( $assertion, $creation );


		$this->factory->setType( 'baz' );
		$assertion	= "baz";
		$creation	= $this->factory->getType();
		$this->assertEquals( $assertion, $creation );
	}

	public function testGetTypes()
	{
		$assertion	= array();
		$creation	= $this->factory->getTypes();
		$this->assertEquals( $assertion, $creation );

		$this->factory->setTypes( $this->types );
		$assertion	= $this->types;
		$creation	= $this->factory->getTypes();
		$this->assertEquals( $assertion, $creation );

		$types	= array( 'test1', 'test2' );
		$this->factory->setTypes( $types );
		$assertion	= $types;
		$creation	= $this->factory->getTypes();
		$this->assertEquals( $assertion, $creation );
	}

	public function testSetDefault()
	{
		$this->factory->setTypes( $this->types );
		try
		{
			$this->factory->setDefault( 'test' );
			$this->fail( 'An expected Exception has not been thrown.' );
		}
		catch( Exception $e ) {}

		$this->factory->setDefault( 'bar' );
		$assertion	= "bar";
		$creation	= $this->factory->getType();
		$this->assertEquals( $assertion, $creation );

		$this->factory->setDefault( 'baz' );
		$assertion	= "baz";
		$creation	= $this->factory->getType();
		$this->assertEquals( $assertion, $creation );
	}

	public function testSetType()
	{
		try
		{
			$this->factory->setType( "test" );
			$this->fail( 'An expected Exception has not been thrown.' );
		}
		catch( Exception $e ) {}


		$this->factory->setTypes( $this->types );
		try
		{
			$this->factory->setType( "test" );
			$this->fail( 'An expected Exception has not been thrown.' );
		}
		catch( Exception $e ) {}
		
		$this->factory->setType( "bar" );
		$assertion	= "bar";
		$creation	= $this->factory->getType();
		$this->assertEquals( $assertion, $creation );
		
		$this->factory->setType( "baz" );
		$assertion	= "baz";
		$creation	= $this->factory->getType();
		$this->assertEquals( $assertion, $creation );
	}

	public function testSetTypes()
	{
		$this->factory->setTypes( $this->types );
		$assertion	= $this->types;
		$creation	= $this->factory->getTypes();
		$this->assertEquals( $assertion, $creation );
		
		$this->factory->setTypes( array( "test1", "test2" ) );
		$assertion	= array( "test1", "test2" );
		$creation	= $this->factory->getTypes();
		$this->assertEquals( $assertion, $creation );
	}
	
	public function testGetClassName()
	{
		$this->factory->setTypes( $this->types );
		$this->factory->setDefault( "bar" );
		$this->factory->setType( "baz" );
		
		$assertion	= "Baz_Test";
		$creation	= $this->factory->getClassName( "Test" );
		$this->assertEquals( $assertion, $creation );
		
		$assertion	= "Baz_Test";
		$creation	= $this->factory->getClassName( "test" );
		$this->assertEquals( $assertion, $creation );
		
		$assertion	= "Baz_TEST";
		$creation	= $this->factory->getClassName( "tEST" );
		$this->assertEquals( $assertion, $creation );
		
		$assertion	= "Prefix_Baz_Test";
		$creation	= $this->factory->getClassName( "Test", "Prefix" );
		$this->assertEquals( $assertion, $creation );
		
		$assertion	= "Prefix_Baz_Test";
		$creation	= $this->factory->getClassName( "test", "prefix" );
		$this->assertEquals( $assertion, $creation );


		$assertion	= "Foo_Test";
		$creation	= $this->factory->getClassName( "Test", false, "foo" );
		$this->assertEquals( $assertion, $creation );
		
		$assertion	= "Foo_Test";
		$creation	= $this->factory->getClassName( "test", false, "foo" );
		$this->assertEquals( $assertion, $creation );
		
		$assertion	= "Foo_TEST";
		$creation	= $this->factory->getClassName( "tEST", false, "foo" );
		$this->assertEquals( $assertion, $creation );
		
		$assertion	= "Prefix_Foo_Test";
		$creation	= $this->factory->getClassName( "Test", "Prefix", "foo" );
		$this->assertEquals( $assertion, $creation );
		
		$assertion	= "Prefix_Foo_Test";
		$creation	= $this->factory->getClassName( "test", "prefix", "foo" );
		$this->assertEquals( $assertion, $creation );
	}

	public function testGetClassFileName()
	{
		$this->factory->setTypes( $this->types );
		$this->factory->setDefault( "bar" );
		$this->factory->setType( "baz" );
		
		$assertion	= "baz.Test";
		$creation	= $this->factory->getClassFileName( "Test" );
		$this->assertEquals( $assertion, $creation );
		
		$assertion	= "baz.Test";
		$creation	= $this->factory->getClassFileName( "test" );
		$this->assertEquals( $assertion, $creation );
		
		$assertion	= "baz.TEST";
		$creation	= $this->factory->getClassFileName( "tEST" );
		$this->assertEquals( $assertion, $creation );
		
		$assertion	= "prefix.baz.Test";
		$creation	= $this->factory->getClassFileName( "Test", "Prefix" );
		$this->assertEquals( $assertion, $creation );
		
		$assertion	= "prefix.baz.Test";
		$creation	= $this->factory->getClassFileName( "test", "prefix" );
		$this->assertEquals( $assertion, $creation );


		$assertion	= "foo.Test";
		$creation	= $this->factory->getClassFileName( "Test", false, "foo" );
		$this->assertEquals( $assertion, $creation );
		
		$assertion	= "foo.Test";
		$creation	= $this->factory->getClassFileName( "test", false, "foo" );
		$this->assertEquals( $assertion, $creation );
		
		$assertion	= "foo.TEST";
		$creation	= $this->factory->getClassFileName( "tEST", false, "Foo" );
		$this->assertEquals( $assertion, $creation );
		
		$assertion	= "prefix.foo.Test";
		$creation	= $this->factory->getClassFileName( "Test", "Prefix", "foo" );
		$this->assertEquals( $assertion, $creation );
		
		$assertion	= "prefix.foo.Test";
		$creation	= $this->factory->getClassFileName( "test", "prefix", "foo" );
		$this->assertEquals( $assertion, $creation );
	}
	
	public function testGetObject()
	{
		$this->factory->setTypes( $this->types );
	
		$object	= $this->factory->getObject( "Class" );
		$assertion	= true;
		$creation	= is_a( $object, 'Foo_Class' );
		$this->assertEquals( $assertion, $creation );
	
		$object	= $this->factory->getObject( "Class", "Test" );
		$assertion	= true;
		$creation	= is_a( $object, 'Test_Foo_Class' );
		$this->assertEquals( $assertion, $creation );
	
		$object	= $this->factory->getObject( "class", "test" );
		$assertion	= true;
		$creation	= is_a( $object, 'Test_Foo_Class' );
		$this->assertEquals( $assertion, $creation );
	}
}
class Foo_Class {}
class Test_Foo_Class {}
?>