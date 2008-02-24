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
import( 'de.ceus-media.framework.krypton.core.Language' );
import( 'de.ceus-media.net.http.request.Receiver' );
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
	function returnOne()
	{
		return "1";
	}
}
 
class Tests_Framework_Krypton_Core_ActionTest extends PHPUnit_Framework_TestCase
{
/*	public function __construct()
	{
	
		$language = $this->getMock( 'Language' );
		$language->expects( $this->any() )->method( 'getWords' )->will( $this->returnValue( array() ) );
		
		die( $language->getWords() );
 
		$registry	= Framework_Krypton_Core_Registry::getInstance();
		$registry->set( 'request', new ADT_List_Dictionary );
		$registry->set( 'session', new ADT_List_Dictionary );
		$registry->set( 'messenger', new Framework_Krypton_Core_Messenger );
		$registry->set( 'language', $language );
	}

	public function setUp()
	{

		$this->action	= new TestAction();
		$this->action->addAction( 'testOne',			'returnOne' );
		$this->action->addAction( 'notExisting',		'notExistingMethod' );
		$this->action->addAction( 'removeThisAction',	'removeAction' );
	}

	public function testAddAction()
	{
		$this->action->addAction( 'testAction' );
		$assertion	= true;
		$creation	= $this->action->hasAction( 'testAction' );
		$this->assertEquals( $assertion, $creation );
	}
*/}
?>