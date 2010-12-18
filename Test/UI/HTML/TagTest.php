<?php
/**
 *	TestUnit of Tag.
 *	@package		Tests.ui.html
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			22.04.2008
 *	@version		0.1
 */
require_once 'PHPUnit/Framework/TestCase.php';
require_once 'Test/initLoaders.php5';
/**
 *	TestUnit of Gauss Blur.
 *	@package		Tests.ui.html
 *	@extends		PHPUnit_Framework_TestCase
 *	@uses			UI_HTML_Tag
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			22.04.2008
 *	@version		0.1
 */
class Test_UI_HTML_TagTest extends PHPUnit_Framework_TestCase
{
	/**
	 *	Constructor.
	 *	@access		public
	 *	@return		void
	 */
	public function setUp()
	{
		$name		= "Tag";
		$value		= "textContent";
		$attributes	= array( 'Key1' => 'Value1' );
		$this->tag	= new UI_HTML_Tag( $name, $value, $attributes );
	}

	/**
	 *	Tests Method '__construct'.
	 *	@access		public
	 *	@return		void
	 */
	public function testConstruct1()
	{
		$name		= "key";
		$value		= "value";
		$attributes	= array( 'key1' => 'value1' );
		$assertion	= '<key key1="value1">value</key>';
		$creation	= (string) new UI_HTML_Tag( $name, $value, $attributes );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method '__construct'.
	 *	@access		public
	 *	@return		void
	 */
/*	public function testConstruct2()
	{
		$name		= "key";
		$value		= "";
		$attributes	= array( 'key1' => "" );
		$assertion	= '<key></key>';
		$creation	= (string) new UI_HTML_Tag( $name, $value, $attributes );
		$this->assertEquals( $assertion, $creation );

	}
*/
	/**
	 *	Tests Method '__construct'.
	 *	@access		public
	 *	@return		void
	 */
	public function testConstruct3()
	{
		$name		= "key";
		$value		= NULL;
		$attributes	= array( 'key1' => NULL );
		$assertion	= '<key/>';
		$creation	= (string) new UI_HTML_Tag( $name, $value, $attributes );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method '__construct'.
	 *	@access		public
	 *	@return		void
	 */
	public function testConstruct4()
	{
		$name		= "key";
		$value		= FALSE;
		$attributes	= array( 'key1' => FALSE );
		$assertion	= '<key/>';
		$creation	= (string) new UI_HTML_Tag( $name, $value, $attributes );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'build'.
	 *	@access		public
	 *	@return		void
	 */
	public function testBuild()
	{
		$assertion	= '<tag key1="Value1">textContent</tag>';
		$creation	= (string) $this->tag->build();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'create'.
	 *	@access		public
	 *	@return		void
	 */
	public function testCreate1()
	{	
		$assertion	= '<tag key="value">content</tag>';
		$creation	= UI_HTML_Tag::create( "tag", "content", array( 'key' => 'value' ) );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'create'.
	 *	@access		public
	 *	@return		void
	 */
	public function testCreate2_1()
	{
		$assertion	= '<tag/>';
		$creation	= UI_HTML_Tag::create( "tag", NULL );
		$this->assertEquals( $assertion, $creation );
	}
	/**
	 *	Tests Method 'create'.
	 *	@access		public
	 *	@return		void
	 */
	public function testCreate2_2()
	{
		$assertion	= '<tag key="value"/>';
		$creation	= UI_HTML_Tag::create( "tag", NULL, array( 'key' => 'value' ) );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'create'.
	 *	@access		public
	 *	@return		void
	 */
	public function testCreate3_1()
	{
		$assertion	= '<style></style>';
		$creation	= UI_HTML_Tag::create( "style", NULL );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'create'.
	 *	@access		public
	 *	@return		void
	 */
	public function testCreate3_2()
	{
		$assertion	= '<script></script>';
		$creation	= UI_HTML_Tag::create( "script", NULL );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'create'.
	 *	@access		public
	 *	@return		void
	 */
	public function testCreate3_3()
	{
		$assertion	= '<div></div>';
		$creation	= UI_HTML_Tag::create( "div", NULL );
		$this->assertEquals( $assertion, $creation );
	}
	
	/**
	 *	Tests Method 'setAttribute'.
	 *	@access		public
	 *	@return		void
	 */
	public function testSetAttribute1()
	{
		$this->tag->setAttribute( "Key2", "Value2" );
		$assertion	= '<tag key1="Value1" key2="Value2">textContent</tag>';
		$creation	= (string) $this->tag;
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'setAttribute'.
	 *	@access		public
	 *	@return		void
	 */
	public function testSetAttribute2()
	{
		$this->tag->setAttribute( "Key2", "Value2" );
		$this->tag->setAttribute( "Key2", "Value2-2", FALSE );										//  override
		$assertion	= '<tag key1="Value1" key2="Value2-2">textContent</tag>';
		$creation	= (string) $this->tag;
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'setAttribute'.
	 *	@access		public
	 *	@return		void
	 */
	public function testSetAttribute3()
	{
		$this->tag->setAttribute( "xml:lang", "en" );
		$assertion	= '<tag key1="Value1" xml:lang="en">textContent</tag>';
		$creation	= (string) $this->tag;
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Exception of Method 'setAttribute'.
	 *	@access		public
	 *	@return		void
	 */
	public function testSetAttributeException1_1()
	{
		$this->setExpectedException( 'InvalidArgumentException' );
		$this->tag->setAttribute( NULL, 'value' );
	}

	/**
	 *	Tests Exception of Method 'setAttribute'.
	 *	@access		public
	 *	@return		void
	 */
	public function testSetAttributeException1_2()
	{
		$this->setExpectedException( 'InvalidArgumentException' );
		$this->tag->setAttribute( FALSE, 'value' );
	}

	/**
	 *	Tests Exception of Method 'setAttribute'.
	 *	@access		public
	 *	@return		void
	 */
	public function testSetAttributeException1_3()
	{
		$this->setExpectedException( 'InvalidArgumentException' );
		$this->tag->setAttribute( '', 'value' );
	}

	/**
	 *	Tests Exception of Method 'setAttribute'.
	 *	@access		public
	 *	@return		void
	 */
	public function testSetAttributeException2_1()
	{
		$this->setExpectedException( 'RuntimeException' );
		$this->tag->setAttribute( 'key1', 'value' );
		$this->tag->setAttribute( 'key1', 'value' );
	}

	/**
	 *	Tests Exception of Method 'setAttribute'.
	 *	@access		public
	 *	@return		void
	 */
	public function testSetAttributeException2_2()
	{
		$this->setExpectedException( 'RuntimeException' );
		$this->tag->setAttribute( 'KEY1', 'value' );
		$this->tag->setAttribute( 'key1', 'value' );
	}

	/**
	 *	Tests Exception of Method 'setAttribute'.
	 *	@access		public
	 *	@return		void
	 */
	public function testSetAttributeException3_1()
	{
		$this->setExpectedException( 'InvalidArgumentException' );
		$this->tag->setAttribute( 'invalid!', 'value' );
	}

	/**
	 *	Tests Exception of Method 'setAttribute'.
	 *	@access		public
	 *	@return		void
	 */
	public function testSetAttributeException3_2()
	{
		$this->setExpectedException( 'InvalidArgumentException' );
		$this->tag->setAttribute( 'with_space ', 'value' );
	}

	/**
	 *	Tests Exception of Method 'setAttribute'.
	 *	@access		public
	 *	@return		void
	 */
	public function testSetAttributeException4()
	{
		$this->setExpectedException( 'InvalidArgumentException' );
		$this->tag->setAttribute( 'key', 'value" inject="true' );
	}

	/**
	 *	Tests Method 'setContent'.
	 *	@access		public
	 *	@return		void
	 */
	public function testSetContent()
	{
		$this->tag->setContent( "textContent2" );		
		$assertion	= '<tag key1="Value1">textContent2</tag>';
		$creation	= (string) $this->tag;
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'toString'.
	 *	@access		public
	 *	@return		void
	 */
	public function testToString()
	{
		$assertion	= '<tag key1="Value1">textContent</tag>';
		$creation	= (string) $this->tag->__toString();
		$this->assertEquals( $assertion, $creation );
	}
}
?>