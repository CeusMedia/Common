<?php
/**
 *	TestUnit of View
 *	@package		tests.framework.krypton.core
 *	@extends		PHPUnit_Framework_TestCase
 *	@uses			Framework_Krypton_Core_View
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.1
 */
require_once 'PHPUnit/Framework/TestCase.php'; 
require_once 'Tests/initLoaders.php5' ;
import( 'de.ceus-media.framework.krypton.core.View' );
import( 'de.ceus-media.framework.krypton.core.Messenger' );
import( 'de.ceus-media.adt.list.Dictionary' );
/**
 *	TestUnit of View
 *	@package		tests.framework.krypton.core
 *	@extends		PHPUnit_Framework_TestCase
 *	@uses			Framework_Krypton_Core_View
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.1
 */
class Tests_Framework_Krypton_Core_ViewTest extends PHPUnit_Framework_TestCase
{
	public function __construct()
	{
		$words	= array(
			'main'	=> array(
				'paging'	=> array(
					'next'		=> "[next]",
					'previous'	=> "[prev]",
					'last'		=> "[last]",
					'first'		=> "[first]",
					'more'		=> "[more]",
				),
			),
		);
					
		$language = $this->getMock( 'Language', array( 'getWords' ) );
		$language->expects( $this->any() )->method( 'getWords' )->will( $this->returnValue( $words ) );
		
		$registry	= Framework_Krypton_Core_Registry::getInstance();
		$registry->set( 'request', new ADT_List_Dictionary, true );
		$registry->set( 'session', new ADT_List_Dictionary, true );
		$registry->set( 'messenger', new Framework_Krypton_Core_Messenger, true );
		$registry->set( 'language', $language, true );
		
		$this->view	= new Framework_Krypton_Core_View;
	}

	public function testConstruct()
	{
		$view	= new Framework_Krypton_Core_View( true );
	}

	public function testBuildContent()
	{
		$assertion	= "";
		$creation	= $this->view->buildContent();
		$this->assertEquals( $assertion, $creation );
	}

	public function testBuildControl()
	{
		$assertion	= "";
		$creation	= $this->view->buildControl();
		$this->assertEquals( $assertion, $creation );
	}

	public function testBuildExtra()
	{
		$assertion	= "";
		$creation	= $this->view->buildExtra();
		$this->assertEquals( $assertion, $creation );
	}

	public function testBuildPaging()
	{
		$assertion	= file_get_contents( "Tests/framework/krypton/core/paging1.html" );
		$creation	= $this->view->buildPaging( 100, 10, 0 );
		$this->assertEquals( $assertion, $creation );

		$assertion	= file_get_contents( "Tests/framework/krypton/core/paging2.html" );
		$creation	= $this->view->buildPaging( 100, 10, 50 );
		$this->assertEquals( $assertion, $creation );

		$assertion	= file_get_contents( "Tests/framework/krypton/core/paging3.html" );
		$creation	= $this->view->buildPaging( 100, 10, 90 );
		$this->assertEquals( $assertion, $creation );
	}
	
	public function testBuildPadingOptions()
	{
		$options	= array(
			'coverage'	=> 10
		);

		$assertion	= file_get_contents( "Tests/framework/krypton/core/paging4.html" );
		$creation	= $this->view->buildPaging( 100, 10, 50, $options );
		$this->assertEquals( $assertion, $creation );
	}
}
?>