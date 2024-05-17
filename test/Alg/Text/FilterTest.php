<?php /** @noinspection HtmlUnknownAttribute */
/** @noinspection HtmlUnknownTarget */
/** @noinspection PhpIllegalPsrClassPathInspection */
/** @noinspection PhpMultipleClassDeclarationsInspection */
/** @noinspection PhpUnhandledExceptionInspection */
/** @noinspection PhpDocMissingThrowsInspection */

declare( strict_types = 1 );

/**
 *	TestUnit of Alg\Text\Filter.
 *	@package		Tests.Alg.Text
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 */

namespace CeusMedia\CommonTest\Alg\Text;

use CeusMedia\Common\Alg\Text\Filter;
use CeusMedia\CommonTest\BaseCase;

/**
 *	TestUnit of Alg\Text\Filter.
 *	@package		Tests.Alg.Text
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 */
class FilterTest extends BaseCase
{
	/**
	 *	Tests Method 'stripComments'.
	 *	@access		public
	 *	@return		void
	 */
	public function testStripComments()
	{
		$text	= "
			/** This is Comment 1. */
			/**
			 *	This is Comment 2.
			 */
			<!-- This is Comment 3 -->
			<!--
				This is Comment 3
			//-->
			This is plain Text.
			<!--/*Comment*/-->
			<!--/*Comment*///-->
			/*<!--Comment-->*/
			/*<!--Comment//-->*/";
		$assertion	= "This is plain Text.";
		$creation	= trim( Filter::stripComments( $text ) );
		self::assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'stripScripts'.
	 *	@access		public
	 *	@return		void
	 */
	public function testStripScripts()
	{
		$text	= '
			<script src="source.js"></script>
			This is plain Text.
			<script>alert("hello");</script>';
		$assertion	= "This is plain Text.";
		$creation	= trim( Filter::stripScripts( $text ) );
		self::assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'stripStyles'.
	 *	@access		public
	 *	@return		void
	 */
	public function testStripStyles()
	{
		$text	= '
			<link type="unknown" rel="stylesheet" src="source.css"></link>
			<link type="unknown" rel="stylesheet" src="source.css"/>
			<link type="unknown" rel="stylesheet" src="generate.php"/>
			This is plain Text.
			<style>h1{color:red}</script>
			<style>
			h2{
				color:green
			}
			</style>
			';
		$assertion	= "This is plain Text.";
		$creation	= trim( Filter::stripStyles( $text ) );
		self::assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'stripTags'.
	 *	@access		public
	 *	@return		void
	 */
	public function testStripTags()
	{
		$text	= '
<h1>Hello</h1>
This is plain Text.
<b><em>Test</b></em>
<br/>
';
		$assertion	= "Hello\nThis is plain Text.\nTest";
		$creation	= trim( Filter::stripTags( $text ) );
		self::assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'stripEventAttributes'.
	 *	@access		public
	 *	@return		void
	 */
	public function testStripEventAttributes()
	{
		$text	= '
			<tag onblur="alert(\'hello\');"/>
			<tag onblur=\'alert("hello");\'/>
			<tag name="test" onblur="alert(\'hello\');" attribute="value"></tag>
			<tag name="test" onblur="alert(\'hello\');" attribute="value">This is plain Text.</tag>';
		$assertion	= '
			<tag/>
			<tag/>
			<tag name="test" attribute="value"></tag>
			<tag name="test" attribute="value">This is plain Text.</tag>';
		$creation	= Filter::stripEventAttributes( $text );
		self::assertEquals( $assertion, $creation );
	}
}
