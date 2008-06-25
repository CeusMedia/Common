<?php
/**
 *	TestUnit of Action
 *	@package		tests.framework.krypton.core
 *	@extends		PHPUnit_Framework_TestCase
 *	@uses			Framework_Krypton_Core_Action
 *	@uses			Framework_Krypton_Core_Messenger
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.1
 */
require_once 'PHPUnit/Framework/TestCase.php'; 
require_once 'Tests/initLoaders.php5' ;
import( 'de.ceus-media.framework.krypton.core.Action' );
import( 'de.ceus-media.framework.krypton.core.Messenger' );
import( 'de.ceus-media.adt.list.Dictionary' );
/**
 *	TestUnit of Action
 *	@package		tests.framework.krypton.core
 *	@extends		PHPUnit_Framework_TestCase
 *	@uses			Framework_Krypton_Core_Action
 *	@uses			Framework_Krypton_Core_Messenger
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.1
 */
class TestAction extends Framework_Krypton_Core_Action
{
	public $var	= 0;
	public function __construct()
	{
		$this->addAction( 'testOne',			'returnOne' );
		$this->addAction( 'notExisting',		'notExistingMethod' );
		$this->addAction( 'removeThisAction',	'removeAction' );
	}

	public function addTestAction()
	{
		$this->addAction( 'testAction' );
	}

	public function returnOne()
	{
		$this->var	= 1;
	}
}
 
class Tests_Framework_Krypton_Core_ActionTest extends PHPUnit_Framework_TestCase
{
	public function __construct()
	{
		$language = $this->getMock( 'Language', array( 'getWords' ) );
		$language->expects( $this->any() )->method( 'getWords' )->will( $this->returnValue( array() ) );
		
		$registry	= Framework_Krypton_Core_Registry::getInstance();
		$registry->set( 'request', new ADT_List_Dictionary, true );
		$registry->set( 'session', new ADT_List_Dictionary, true );
		$registry->set( 'messenger', new Framework_Krypton_Core_Messenger, true );
		$registry->set( 'language', $language, true );
	}

	public function setUp()
	{
		$this->action	= new TestAction();
	}

	public function testAddAction()
	{
		$this->action->addTestAction();
		$assertion	= true;
		$creation	= $this->action->hasAction( 'testAction' );
		$this->assertEquals( $assertion, $creation );
	}

	public function testHasAction()
	{
		$assertion	= true;
		$creation	= $this->action->hasAction( 'testOne' );
		$this->assertEquals( $assertion, $creation );

		$assertion	= false;
		$creation	= $this->action->hasAction( 'wrong_name' );
		$this->assertEquals( $assertion, $creation );
	}
	
	public function testPerformActions()
	{
		$registry	= Framework_Krypton_Core_Registry::getInstance();
		$request	= $registry->get( 'request' );
		$request->set( 'testOne', '1' );
		
		$this->action->performActions();
		$assertion	= 1;
		$creation	= $this->action->var;
		$this->assertEquals( $assertion, $creation );
	}
	
	public function testPerformActionsException()
	{
		$registry	= Framework_Krypton_Core_Registry::getInstance();
		$request	= $registry->get( 'request' );
		$request->set( 'notExisting', 1 );

		try
		{
			$this->action->performActions();
		}
		catch( Exception $e )
		{
			return;
		}
		$this->fail( 'An expected Exception has not been thrown.' );
	}
	
	public function testRemoveAction()
	{
		$this->action->removeAction( "testOne" );
		$assertion	= false;
		$creation	= $this->action->hasAction( 'testOne' );
		$this->assertEquals( $assertion, $creation );
	}
}
?>