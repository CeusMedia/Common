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
	public function testConstruct()
	{
		$name		= "key";
		$value		= "value";
		$attributes	= array( 'key1' => 'value1' );
		$assertion	= '<key key1="value1">value</key>';
		$creation	= (string) new UI_HTML_Tag( $name, $value, $attributes );
		$this->assertEquals( $assertion, $creation );

/*		XHTML 1.1
		$name		= "key";
		$value		= "";
		$attributes	= array( 'key1' => "" );
		$assertion	= '<key></key>';
		$creation	= (string) new UI_HTML_Tag( $name, $value, $attributes );
		$this->assertEquals( $assertion, $creation );
*/
		$name		= "key";
		$value		= NULL;
		$attributes	= array( 'key1' => NULL );
		$assertion	= '<key/>';
		$creation	= (string) new UI_HTML_Tag( $name, $value, $attributes );
		$this->assertEquals( $assertion, $creation );

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
	public function testCreate()
	{	
		$assertion	= '<tag key="value">content</tag>';
		$creation	= UI_HTML_Tag::create( "tag", "content", array( 'key' => 'value' ) );
		$this->assertEquals( $assertion, $creation );
	}
	
	/**
	 *	Tests Method 'setAttribute'.
	 *	@access		public
	 *	@return		void
	 */
	public function testSetAttribute()
	{
		$this->tag->setAttribute( "Key2", "Value2" );		
		$assertion	= '<tag key1="Value1" key2="Value2">textContent</tag>';
		$creation	= (string) $this->tag;
		$this->assertEquals( $assertion, $creation );

		$this->tag->setAttribute( "Key2", NULL );		
		$assertion	= '<tag key1="Value1">textContent</tag>';
		$creation	= (string) $this->tag;
		$this->assertEquals( $assertion, $creation );
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