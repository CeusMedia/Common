<?php
/**
 *	TestUnit of Logic
 *	@package		tests.framework.krypton.core
 *	@extends		PHPUnit_Framework_TestCase
 *	@uses			Framework_Krypton_Core_Logic
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.1
 */
require_once 'PHPUnit/Framework/TestCase.php'; 
require_once 'Tests/initLoaders.php5' ;
import( 'de.ceus-media.framework.krypton.core.Logic' );
import( 'Tests.framework.krypton.core.logic.Test' );
import( 'Tests.framework.krypton.core.collection.Test' );
/**
 *	TestUnit of Logic
 *	@package		tests.framework.krypton.core
 *	@extends		PHPUnit_Framework_TestCase
 *	@uses			Framework_Krypton_Core_Logic
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.1
 */
class Tests_Framework_Krypton_Core_LogicTest extends PHPUnit_Framework_TestCase
{
	public function __construct()
	{
		Framework_Krypton_Core_Logic::$pathLogic		= "Tests.framework.krypton.core.logic.";
		Framework_Krypton_Core_Logic::$pathCollection	= "Tests.framework.krypton.core.collection.";
	}
	
	public function testGetCategoryLogic()
	{
		$assertion	= new Logic_Test;
		$creation	= Framework_Krypton_Core_Logic::getCategoryLogic( "test" );
		$this->assertEquals( $assertion, $creation );
	}

	public function testGetCategoryLogicException()
	{
		try
		{
			Framework_Krypton_Core_Logic::getCategoryLogic( "test1" );
		}
		catch( Exception $e )
		{
			return;
		}
		$this->fail( 'An expected Exception has not been thrown.' );
	}
	
	public function testGetCategoryCollection()
	{
		$assertion	= new Collection_Test;
		$creation	= Framework_Krypton_Core_Logic::getCategoryCollection( "test", "builder_dummy" );
		$this->assertEquals( $assertion, $creation );
	}

	public function testGetCategoryCollectionException()
	{
		try
		{
			Framework_Krypton_Core_Logic::getCategoryCollection( "test1", "builder_dummy" );
		}
		catch( Exception $e )
		{
			return;
		}
		$this->fail( 'An expected Exception has not been thrown.' );
	}
	
	public function testGetFieldsFromModel()
	{
		$assertion	= array( 'field1', 'field2' );
		$creation	= Framework_Krypton_Core_Logic::getFieldsFromModel( "Model_Test" );
		$this->assertEquals( $assertion, $creation );
	}

	public function testGetFieldsFromModelException()
	{
		try
		{
			Framework_Krypton_Core_Logic::getFieldsFromModel( "Not_Existing" );
		}
		catch( Exception $e )
		{
			return;
		}
		$this->fail( 'An expected Exception has not been thrown.' );
	}

	public function testRemovePrefixFromFieldName()
	{
		$assertion	= "name";
		$creation	= Framework_Krypton_Core_Logic::removePrefixFromFieldName( "prefix_name", "prefix_" );
		$this->assertEquals( $assertion, $creation );
	
		$assertion	= "prefix_name";
		$creation	= Framework_Krypton_Core_Logic::removePrefixFromFieldName( "prefix_name", "" );
		$this->assertEquals( $assertion, $creation );
	
		$assertion	= "prefix_name";
		$creation	= Framework_Krypton_Core_Logic::removePrefixFromFieldName( "prefix_name", "suffix_" );
		$this->assertEquals( $assertion, $creation );
	}
	
	public function testRemovePrefixFromFields()
	{
		$source	= array(
			"prefix_name1"	=> "value1",
			"prefix_name2"	=> "value2",
			"prefix_name3"	=> "value3",
		);

		$assertion	= array(
			"name1"	=> "value1",
			"name2"	=> "value2",
			"name3"	=> "value3",
		);
		$creation	= Framework_Krypton_Core_Logic::removePrefixFromFields( $source, "prefix_" );
		$this->assertEquals( $assertion, $creation );
	
		$assertion	= $source;
		$creation	= Framework_Krypton_Core_Logic::removePrefixFromFields( $source, "" );
		$this->assertEquals( $assertion, $creation );
	}
}
class Model_Test
{
	public function getFields()
	{
		return array(
			'field1',
			'field2',
		);
	}
}
?>