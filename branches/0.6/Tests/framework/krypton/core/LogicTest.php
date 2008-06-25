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


	public function testValidationSuccess()
	{
		$registry	= Framework_Krypton_Core_Registry::getInstance();
		import( 'de.ceus-media.framework.krypton.core.FormDefinitionReader' );
		$definition	= new Framework_Krypton_Core_FormDefinitionReader( "Tests/framework/krypton/core/" );
		$definition->setChannel( 'html' );
		$registry->set( 'definition', $definition, true );
		$data	= array(
			'relation_id'	=> 2,
			'title'			=> 'test',
		);
		try
		{
			Test_Logic::testValidateForm( "form", "addSomething", $data, "add_" );
		}
		catch( Framework_Krypton_Exception_Validation $e )
		{
			$this->fail( "An unexpected Exception has been thrown: ".$e->getMessage() );
		}
	}



	public function testValidationWrongPrefix()
	{
	
		$registry	= Framework_Krypton_Core_Registry::getInstance();
		import( 'de.ceus-media.framework.krypton.core.FormDefinitionReader' );
		$definition	= new Framework_Krypton_Core_FormDefinitionReader( "Tests/framework/krypton/core/" );
		$definition->setChannel( 'html' );
		$definition->setPrefix( 'wrong_prefix_' );
		$registry->set( 'definition', $definition, true );

		try
		{
			Test_Logic::testValidateForm( "form", "addSomething", array(), "add_" );
		}
		catch( Exception $e )
		{
			if( preg_match( "@not existing@i", $e->getMessage() ) )
				return true;
			$this->fail( 'An unexpected Exception has not been thrown.' );
		}
		$this->fail( 'An expected Exception has not been thrown.' );
	}

	public function testValidationFail()
	{
	
		$registry	= Framework_Krypton_Core_Registry::getInstance();
		import( 'de.ceus-media.framework.krypton.core.FormDefinitionReader' );
		$definition	= new Framework_Krypton_Core_FormDefinitionReader( "Tests/framework/krypton/core/" );
		$definition->setChannel( 'html' );
		$definition->setPrefix( '' );
		$registry->set( 'definition', $definition, true );

		try
		{
			Test_Logic::testValidateForm( "form", "addSomething", array(), "add_" );
		}
		catch( Framework_Krypton_Exception_Validation $e )
		{
			$errors	= $e->getErrors();

			$assertion	= 2;
			$creation	= count( $errors );
			$this->assertEquals( $assertion, $creation );

			$assertion	= true;
			$creation	= is_a( $errors[0], "Framework_Krypton_Logic_ValidationError" );
			$this->assertEquals( $assertion, $creation );
			return false;
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
			"prefix1_name1"	=> "value1",
			"prefix1_name2"	=> "value2",
			"prefix1_name3"	=> "value3",
			"prefix2_name4"	=> "value4",
		);

		$assertion	= array(
			"name1"			=> "value1",
			"name2"			=> "value2",
			"name3"			=> "value3",
			"prefix2_name4"	=> "value4",
		);
		$creation	= Framework_Krypton_Core_Logic::removePrefixFromFields( $source, "prefix1_", false );
		$this->assertEquals( $assertion, $creation );
	
		$assertion	= $source;
		$creation	= Framework_Krypton_Core_Logic::removePrefixFromFields( $source, "wrong_prefix", false );
		$this->assertEquals( $assertion, $creation );

		$assertion	= $source;
		$creation	= Framework_Krypton_Core_Logic::removePrefixFromFields( $source, "", false );
		$this->assertEquals( $assertion, $creation );

		$assertion	= array(
			"name1"	=> "value1",
			"name2"	=> "value2",
			"name3"	=> "value3",
		);
		$creation	= Framework_Krypton_Core_Logic::removePrefixFromFields( $source, "prefix1_", true );
		$this->assertEquals( $assertion, $creation );

		$assertion	= array(
			"name4"	=> "value4",
		);
		$creation	= Framework_Krypton_Core_Logic::removePrefixFromFields( $source, "prefix2_", true );
		$this->assertEquals( $assertion, $creation );
	
		$assertion	= array();
		$creation	= Framework_Krypton_Core_Logic::removePrefixFromFields( $source, "wrong_prefix", true );
		$this->assertEquals( $assertion, $creation );

		$assertion	= $source;
		$creation	= Framework_Krypton_Core_Logic::removePrefixFromFields( $source, "", true );
		$this->assertEquals( $assertion, $creation );
	}
}
class Test_Logic extends Framework_Krypton_Core_Logic
{ 
	public static function testValidateForm( $file, $form, $data, $prefix )
	{
		return self::validateForm( $file, $form, $data, $prefix );
	}
}
class Model_Test
{
	public function getColumns()
	{
		return array(
			'field1',
			'field2',
		);
	}
}
class Test_DefinitionDummy
{
	protected $form	= "[none]";

	public function setForm( $name )
	{
		$this->form	= $name;	
	}
	
	public function loadDefinition( $fileKey )
	{
		return $fileKey.":".$this->form;	
	}

}
?>