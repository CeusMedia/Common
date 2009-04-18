<?php
/**
 *	TestUnit of UI_Template
 *	@package		tests.ui
 *	@extends		PHPUnit_Framework_TestCase
 *	@uses			UI_Template
 *	@author			David Seebacher <dseebacher@gmail.com>
 *	@author			Christian W�rker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.2
 */

require_once 'PHPUnit/Framework/TestCase.php'; 
require_once 'Tests/initLoaders.php5' ;
import( 'de.ceus-media.ui.Template' );

/**
 *	TestUnit of UI_Template
 *	@package		tests.ui
 *	@extends		PHPUnit_Framework_TestCase
 *	@uses			UI_Template
 *	@author			David Seebacher <dseebacher@gmail.com>
 *	@author			Christian W�rker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.2
 */

class Tests_UI_TemplateTest extends PHPUnit_Framework_TestCase
{
	private $template;
	
	public function setUp()
	{
		$this->mock			= Tests_MockAntiProtection::getInstance( 'UI_Template' );
		$this->path			= dirname( __FILE__ )."/";
		$this->template		= new UI_Template( $this->path.'template_testcase1.html' );
		$this->mockElements	= array(
			'user'	=> "Welt",
			'list'	=> array(
				6, 5, 4
			),
			'map1'	=> array(
				'string1'	=> 'value1',
				'list1'	=> array(
					1, 2, 3
				),
				'map1'		=> array(
					'string1'	=> 'value2',
					'float1'		=> M_PI,
					'list1'	=> array(
						1, 2, 3
					),
				),
				'map2'		=> array(
					'string1'	=> 'value2',
					'float1'		=> M_PI,
					'list1'	=> array(
						1, 2, 3
					),
				),
			)
		);

	}
	
	public function testInitialyNoElements()
	{
		$size	= sizeof( $this->template->getElements() );
		$this->assertEquals( 0, $size );
	}

	public function testAdd()
	{
		$assertion	= 18;
		$creation	= $this->mock->add( $this->mockElements );
		$this->assertEquals( $assertion, $creation );
	}

	public function testGetElements()
	{
		$data		= array( 'key' => 'value' );
		$this->mock->setProtectedVar( 'elements', $data );
		$assertion	= $data;
		$creation	= $this->mock->getElements();
		$this->assertEquals( $assertion, $creation );
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
		$assertion	= file_get_contents( $this->path.'template_testcase1_result.html' );
		$creation	= $this->template->create();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Comments only
	 */
	public function testCreate2()
	{
		$this->template->setTemplate( $this->path.'template_testcase2.html' );
		$assertion	= file_get_contents( $this->path.'template_testcase2_result.html' );
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
		$assertion	= file_get_contents( $this->path.'template_testcase4_result.html' );
		$creation	= $this->template->create();
		$this->assertEquals( $assertion, $creation );
	}

	public function testRender()
	{
		$data		= array(
			'title'	=> 'das ist der titel',
			'text'	=> 'das ist der text',
		);
		$assertion	= file_get_contents( $this->path.'template_testcase4_result.html' );
		$creation	= UI_Template::render( $this->path.'template_testcase4.html', $data );
		$this->assertEquals( $assertion, $creation );
	}
}
?>