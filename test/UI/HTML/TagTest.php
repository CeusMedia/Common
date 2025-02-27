<?php
/** @noinspection PhpIllegalPsrClassPathInspection */
/** @noinspection PhpMultipleClassDeclarationsInspection */
/** @noinspection PhpUnhandledExceptionInspection */
/** @noinspection PhpDocMissingThrowsInspection */

declare( strict_types = 1 );

/**
 *	TestUnit of Tag.
 *	@package		Tests.ui.html
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 */

namespace CeusMedia\CommonTest\UI\HTML;

use CeusMedia\Common\UI\HTML\Tag;
use CeusMedia\CommonTest\BaseCase;

/**
 *	TestUnit of Gauss Blur.
 *	@package		Tests.ui.html
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 */
class TagTest extends BaseCase
{
	protected $tag;

	/**
	 *	Constructor.
	 *	@access		public
	 *	@return		void
	 */
	public function setUp(): void
	{
		$name		= "Tag";
		$value		= "textContent";
		$attributes	= array( 'Key1' => 'Value1' );
		$this->tag	= new Tag( $name, $value, $attributes );
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
		$creation	= (string) new Tag( $name, $value, $attributes );
		self::assertEquals( $assertion, $creation );
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
		$creation	= (string) new Tag( $name, $value, $attributes );
		self::assertEquals( $assertion, $creation );

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
		$creation	= (string) new Tag( $name, $value, $attributes );
		self::assertEquals( $assertion, $creation );
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
		$creation	= (string) new Tag( $name, $value, $attributes );
		self::assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'build'.
	 *	@access		public
	 *	@return		void
	 */
	public function testBuild()
	{
		$assertion	= '<tag key1="Value1">textContent</tag>';
		$creation	= $this->tag->build();
		self::assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'create'.
	 *	@access		public
	 *	@return		void
	 */
	public function testCreate1()
	{
		$assertion	= '<tag key="value">content</tag>';
		$creation	= Tag::create( "tag", "content", array( 'key' => 'value' ) );
		self::assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'create'.
	 *	@access		public
	 *	@return		void
	 */
	public function testCreate2_1()
	{
		$assertion	= '<tag/>';
		$creation	= Tag::create( "tag", NULL );
		self::assertEquals( $assertion, $creation );
	}
	/**
	 *	Tests Method 'create'.
	 *	@access		public
	 *	@return		void
	 */
	public function testCreate2_2()
	{
		$assertion	= '<tag key="value"/>';
		$creation	= Tag::create( "tag", NULL, array( 'key' => 'value' ) );
		self::assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'create'.
	 *	@access		public
	 *	@return		void
	 */
	public function testCreate3_1()
	{
		$assertion	= '<style></style>';
		$creation	= Tag::create( "style", NULL );
		self::assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'create'.
	 *	@access		public
	 *	@return		void
	 */
	public function testCreate3_2()
	{
		$assertion	= '<script></script>';
		$creation	= Tag::create( "script", NULL );
		self::assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'create'.
	 *	@access		public
	 *	@return		void
	 */
	public function testCreate3_3()
	{
		$assertion	= '<div></div>';
		$creation	= Tag::create( "div", NULL );
		self::assertEquals( $assertion, $creation );
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
		self::assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'setAttribute'.
	 *	@access		public
	 *	@return		void
	 */
	public function testSetAttribute2()
	{
		$this->tag->setAttribute( "Key2", "Value2" );
		//  override
		$this->tag->setAttribute( "Key2", "Value2-2", FALSE );
		$assertion	= '<tag key1="Value1" key2="Value2-2">textContent</tag>';
		$creation	= (string) $this->tag;
		self::assertEquals( $assertion, $creation );
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
		self::assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'setAttribute'.
	 *	@access		public
	 *	@return		void
	 */
	public function testSetAttribute4_1()
	{
		$this->tag->setAttribute( "onclick", 'alert("Hello!")' );
		$assertion	= '<tag key1="Value1" onclick="alert(&quot;Hello!&quot;)">textContent</tag>';
		$creation	= (string) $this->tag;
		self::assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'setAttribute'.
	 *	@access		public
	 *	@return		void
	 */
	public function testSetAttribute4_2()
	{
		$this->tag->setAttribute( 'key', 'value" inject="true' );
		$assertion	= '<tag key1="Value1" key="value&quot; inject=&quot;true">textContent</tag>';
		$creation	= (string) $this->tag;
		self::assertEquals( $assertion, $creation );
	}


	/**
	 *	Tests Exception of Method 'setAttribute'.
	 *	@access		public
	 *	@return		void
	 */
	public function testSetAttributeException1_1()
	{
		$this->expectException( 'TypeError' );
		/** @noinspection PhpStrictTypeCheckingInspection */
		$this->tag->setAttribute( NULL, 'value' );
	}

	/**
	 *	Tests Exception of Method 'setAttribute'.
	 *	@access		public
	 *	@return		void
	 */
	public function testSetAttributeException1_2()
	{
		$this->expectException( 'TypeError' );
		/** @noinspection PhpStrictTypeCheckingInspection */
		$this->tag->setAttribute( FALSE, 'value' );
	}

	/**
	 *	Tests Exception of Method 'setAttribute'.
	 *	@access		public
	 *	@return		void
	 */
	public function testSetAttributeException1_3()
	{
		$this->expectException( 'InvalidArgumentException' );
		$this->tag->setAttribute( '', 'value' );
	}

	/**
	 *	Tests Exception of Method 'setAttribute'.
	 *	@access		public
	 *	@return		void
	 */
	public function testSetAttributeException2_1()
	{
		$this->expectException( 'RuntimeException' );
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
		$this->expectException( 'RuntimeException' );
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
		$this->expectException( 'InvalidArgumentException' );
		$this->tag->setAttribute( 'invalid!', 'value' );
	}

	/**
	 *	Tests Exception of Method 'setAttribute'.
	 *	@access		public
	 *	@return		void
	 */
	public function testSetAttributeException3_2()
	{
		$this->expectException( 'InvalidArgumentException' );
		$this->tag->setAttribute( 'with_space ', 'value' );
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
		self::assertEquals( $assertion, $creation );
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
		self::assertEquals( $assertion, $creation );
	}
}
