<?php
/**
 *	TestUnit of Core_Template
 *	@package		tests.prototype.core
 *	@extends		PHPUnit_Framework_TestCase
 *	@uses			Framework_Krypton_Core_Template
 *	@author			David Seebacher <dseebacher@gmail.com>
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.2
 */

require_once 'PHPUnit/Framework/TestCase.php'; 
require_once 'Tests/initLoaders.php5' ;
import( 'de.ceus-media.framework.krypton.core.Template' );

/**
 *	TestUnit of Core_Template
 *	@package		tests.prototype.core
 *	@extends		PHPUnit_Framework_TestCase
 *	@uses			Framework_Krypton_Core_Template
 *	@author			David Seebacher <dseebacher@gmail.com>
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.2
 */
class Tests_Framework_Krypton_Core_TemplateTest extends PHPUnit_Framework_TestCase
{
	private $template;
	
	public function setUp()
	{
		$this->path		= "Tests/framework/krypton/core/";
		$this->template = new Framework_Krypton_Core_Template( $this->path.'template_testcase1.html' );
	}
	
	public function testInitialyNoElements()
	{
		$size	= sizeof( $this->template->getElements() );
		$this->assertEquals( 0, $size );
	}
	
	public function testAddElement()
	{
		$this->template->addElement( 'tag', 'name' );
		$size	= sizeof( $this->template->getElements() );
		$this->assertEquals( 1, $size );
		$elements = $this->template->getElements();
		$this->assertEquals( 'name', $elements['tag'][0] );
	}
	
	/**
	 *	Tests Tags only
	 */
	public function testCreate1()
	{
		$this->template->setTemplate( $this->path.'template_testcase1.html' );
		$this->template->addElement( 'title', 'das ist der titel' );
		$this->template->addElement( 'text', 'das ist der text' );
		$assertion	= file_get_contents( $this->path.'result_testcase1.html' );
		$creation	= $this->template->create();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Comments only
	 */
	public function testCreate2()
	{
		$this->template->setTemplate( $this->path.'template_testcase2.html' );
		$assertion	= file_get_contents( $this->path.'result_testcase2.html' );
		$creation	= $this->template->create();
/*		var_dump( $assertion );
		var_dump( $creation );
*/		$this->assertEquals( $assertion, $creation );
	}

	public function testCreate4()
	{
		$this->template->setTemplate( $this->path.'template_testcase4.html' );
		$this->template->addElement( 'title', 'das ist der titel' );
		$this->template->addElement( 'text', 'das ist der text' );
		$assertion	= file_get_contents( $this->path.'result_testcase4.html' );
		$creation	= $this->template->create();
		$this->assertEquals( $assertion, $creation );
	}
}
?>