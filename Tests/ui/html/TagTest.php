<?php
/**
 *	TestUnit of Tag.
 *	@package		Tests.ui.html
 *	@extends		PHPUnit_Framework_TestCase
 *	@uses			UI_HTML_Tag
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			22.04.2008
 *	@version		0.1
 */
require_once( 'PHPUnit/Framework/TestCase.php' ); 
require_once( 'Tests/initLoaders.php5' );
import( 'de.ceus-media.ui.html.Tag' );
/**
 *	TestUnit of Gauss Blur.
 *	@package		Tests.ui.html
 *	@extends		PHPUnit_Framework_TestCase
 *	@uses			UI_HTML_Tag
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			22.04.2008
 *	@version		0.1
 */
class Tests_UI_HTML_TagTest extends PHPUnit_Framework_TestCase
{
	/**
	 *	Constructor.
	 *	@access		public
	 *	@return		void
	 */
	public function setUp()
	{
		$name		= "testTag";
		$value		= "testValue";
		$attributes	= array( 'testKey1' => 'testValue1' );
		$this->tag	= new UI_HTML_Tag( $name, $value, $attributes );
	}

	/**
	 *	Tests Method '__construct'.
	 *	@access		public
	 *	@return		void
	 */
	public function testConstruct()
	{
		$a	= array(
			'key1' => 'value1',
			'key2' => NULL,
		);
		$t	= new UI_HTML_Tag( "key", "value", $a );

		$assertion	= '<key key1="value1">value</key>';
		$creation	= (string) $t;
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'build'.
	 *	@access		public
	 *	@return		void
	 */
	public function testBuild()
	{
		$assertion	= '<testTag testKey1="testValue1">testValue</testTag>';
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
		$this->tag->setAttribute( "testKey2", "testValue2" );		
		$assertion	= '<testTag testKey1="testValue1" testKey2="testValue2">testValue</testTag>';
		$creation	= (string) $this->tag;
		$this->assertEquals( $assertion, $creation );

		$this->tag->setAttribute( "testKey2", NULL );		
		$assertion	= '<testTag testKey1="testValue1">testValue</testTag>';
		$creation	= (string) $this->tag;
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'setValue'.
	 *	@access		public
	 *	@return		void
	 */
	public function testSetValue()
	{
		$this->tag->setValue( "testValue2" );		
		$assertion	= '<testTag testKey1="testValue1">testValue2</testTag>';
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
		$assertion	= '<testTag testKey1="testValue1">testValue</testTag>';
		$creation	= (string) $this->tag->__toString();
		$this->assertEquals( $assertion, $creation );
	}
}
?>