<?php
/**
 *	TestUnit of PageController
 *	@package		tests.framework.krypton.core
 *	@extends		PHPUnit_Framework_TestCase
 *	@uses			Framework_Krypton_Core_PageController
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.1
 */
require_once 'PHPUnit/Framework/TestCase.php'; 
require_once 'Tests/initLoaders.php5' ;
import( 'de.ceus-media.framework.krypton.core.CategoryFactory' );
import( 'de.ceus-media.framework.krypton.core.PageController' );
/**
 *	TestUnit of PageController
 *	@package		tests.framework.krypton.core
 *	@extends		PHPUnit_Framework_TestCase
 *	@uses			Framework_Krypton_Core_PageController
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.1
 */
class Tests_Framework_Krypton_Core_PageControllerTest extends PHPUnit_Framework_TestCase
{
	public function __construct()
	{
		$config		= array();
		$factory	= new Framework_Krypton_Core_CategoryFactory();
		$factory->setTypes( array( "alpha", "beta", "gamma" ) );
		$factory->setDefault( "gamma" );
		$factory->setType( "beta" );
		
		$this->registry		= Framework_Krypton_Core_Registry::getInstance();
		$this->registry->set( 'config', $config, true );
		$this->registry->set( 'factory', $factory, true );
		$this->controller	= new Framework_Krypton_Core_PageController( "Tests/framework/krypton/core/pages.xml" );
	}

	public function testGetDocument()
	{
		$document	= $this->controller->getDocument();	
		$assertion	= true;
		$creation	= is_a( $document, 'DOMDocument' );
		$this->assertEquals( $assertion, $creation );
	}
	
	public function testIsDisabled()
	{
		$assertion	= true;
		$creation	= $this->controller->isDisabled( 'help' );
		$this->assertEquals( $assertion, $creation );

		$assertion	= true;
		$creation	= $this->controller->isDisabled( 'shop' );
		$this->assertEquals( $assertion, $creation );

		$assertion	= false;
		$creation	= $this->controller->isDisabled( 'login' );
		$this->assertEquals( $assertion, $creation );

		$assertion	= false;
		$creation	= $this->controller->isDisabled( 'not_existing' );
		$this->assertEquals( $assertion, $creation );
	}
	
	public function testIsHidden()
	{
		$assertion	= true;
		$creation	= $this->controller->isHidden( 'contact' );
		$this->assertEquals( $assertion, $creation );

		$assertion	= true;
		$creation	= $this->controller->isHidden( 'shop' );
		$this->assertEquals( $assertion, $creation );

		$assertion	= false;
		$creation	= $this->controller->isHidden( 'login' );
		$this->assertEquals( $assertion, $creation );

		$assertion	= false;
		$creation	= $this->controller->isHidden( 'not_existing' );
		$this->assertEquals( $assertion, $creation );
	}
	
	public function testIsDynamic()
	{
		$assertion	= true;
		$creation	= $this->controller->isDynamic( 'contact' );
		$this->assertEquals( $assertion, $creation );

		$assertion	= true;
		$creation	= $this->controller->isDynamic( 'shop' );
		$this->assertEquals( $assertion, $creation );

		$assertion	= false;
		$creation	= $this->controller->isDynamic( 'home' );
		$this->assertEquals( $assertion, $creation );

		$assertion	= false;
		$creation	= $this->controller->isDynamic( 'imprint' );
		$this->assertEquals( $assertion, $creation );
	}
	
	public function testGetSource()
	{
		$assertion	= "home.html";
		$creation	= $this->controller->getSource( 'home' );
		$this->assertEquals( $assertion, $creation );

		$assertion	= "Contact";
		$creation	= $this->controller->getSource( 'contact' );
		$this->assertEquals( $assertion, $creation );

		$assertion	= "CatalogSearch";
		$creation	= $this->controller->getSource( 'search' );
		$this->assertEquals( $assertion, $creation );
		
		try
		{
			$this->controller->getSource( 'not_existing' );
			$this->fail( 'An expected Exception has not been thrown.' );
		}
		catch( Exception $e ){}
	}
	
	public function testGetDefaultPages()
	{
		$assertion	= array( "home", "blog" );
		$creation	= $this->controller->getDefaultPages();
		$this->assertEquals( $assertion, $creation );
	}

	public function testGetPageRoles()
	{
		$assertion	= array( "public" );
		$creation	= $this->controller->getPageRoles( 'blog' );
		$this->assertEquals( $assertion, $creation );

		$assertion	= array( "outside" );
		$creation	= $this->controller->getPageRoles( 'login' );
		$this->assertEquals( $assertion, $creation );

		$assertion	= array( "inside" );
		$creation	= $this->controller->getPageRoles( 'logout' );
		$this->assertEquals( $assertion, $creation );

		$assertion	= array( "inside", "outside" );
		$creation	= $this->controller->getPageRoles( 'search' );
		$this->assertEquals( $assertion, $creation );
	}


/*
	public function test()
	{
		$assertion	= true;
		$creation	= $this->controller->();
		$this->assertEquals( $assertion, $creation );
	}
*/	

	public function testConstruct()
	{
		
		$registry	= Framework_Krypton_Core_Registry::getInstance();
		$registry->set( 'config', $config = array(), true );
		$controller	= new Framework_Krypton_Core_PageController( "Tests/framework/krypton/core/pages.xml" );

		$pages		= $controller->getPages();

		$assertion	= true;
		$creation	= is_array( $pages );
		$this->assertEquals( $assertion, $creation );

		$assertion	= "static";
		$creation	= $pages['foot']['help']['type'];
		$this->assertEquals( $assertion, $creation );
	}

	public function testCheckPage()
	{
		$assertion	= true;
		$creation	= $this->controller->checkPage( 'help' );
		$this->assertEquals( $assertion, $creation );

		$assertion	= true;
		$creation	= $this->controller->checkPage( 'help', 'foot' );
		$this->assertEquals( $assertion, $creation );

		$assertion	= false;
		$creation	= $this->controller->checkPage( 'not_existing' );
		$this->assertEquals( $assertion, $creation );

		$assertion	= false;
		$creation	= $this->controller->checkPage( 'not_existing', 'foot' );
		$this->assertEquals( $assertion, $creation );

		$assertion	= false;
		$creation	= $this->controller->checkPage( 'help', 'main' );
		$this->assertEquals( $assertion, $creation );
	}

	public function testClearCache()
	{
		throw new PHPUnit_Framework_IncompleteTestError( 'Dieser Test ist noch nicht fertig ausprogrammiert.' );
	}
	public function testgetClassName()
	{
		$assertion	= "Beta_CatalogSearch";
		$creation	= $this->controller->getClassName( 'search' );
		$this->assertEquals( $assertion, $creation );

		$assertion	= "Prefix_Beta_CatalogSearch";
		$creation	= $this->controller->getClassName( 'search', "prefix" );
		$this->assertEquals( $assertion, $creation );

		$assertion	= "Prefix_Gamma_CatalogSearch";
		$creation	= $this->controller->getClassName( 'search', "prefix", "gamma" );
		$this->assertEquals( $assertion, $creation );

		$assertion	= "information/help.html";
		$creation	= $this->controller->getClassName( 'help' );
		$this->assertEquals( $assertion, $creation );

		try
		{
			$creation	= $this->controller->getClassName( 'not_existing' );
			$this->fail( 'An expected Exception has not been thrown.' );
		}
		catch( Exception $e ){}
	}

	public function testGetPages()
	{
		$assertion	= true;
		$creation	= array_key_exists( 'help', $this->controller->getPages( 'foot' ) );
		$this->assertEquals( $assertion, $creation );

		$assertion	= true;
		$creation	= array_key_exists( 'foot', $this->controller->getPages() );
		$this->assertEquals( $assertion, $creation );

		try
		{
			$creation	= array_key_exists( 'foot', $this->controller->getPages( 'not_existing' ) );
			$this->fail( 'An expected Exception has not been thrown.' );
		}
		catch( Exception $e ){}
	}

	public function testGetPageScope()
	{
		$assertion	= 'foot';
		$creation	= $this->controller->getPageScope( 'help' );
		$this->assertEquals( $assertion, $creation );

		$assertion	= 'main';
		$creation	= $this->controller->getPageScope( 'search' );
		$this->assertEquals( $assertion, $creation );

		try
		{
			$creation	= $this->controller->getPageScope( 'not_existing' );
			$this->fail( 'An expected Exception has not been thrown.' );
		}
		catch( Exception $e ){}
	}

/*
	public function clearCache()
	public function getClassName( $pageId, $prefix = "", $category = "" )
*/
}
?>