<?php
/**
 *	TestUnit of Alg_Text_Filter.
 *	@package		Tests.alg
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			07.07.2008
 *	@version		0.1
 */
require_once 'PHPUnit/Framework/TestCase.php';
require_once 'Test/initLoaders.php5';
/**
 *	TestUnit of Alg_Text_Filter.
 *	@package		Tests.alg
 *	@extends		PHPUnit_Framework_TestCase
 *	@uses			Alg_Text_Filter
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			07.07.2008
 *	@version		0.1
 */
class Test_Alg_Text_FilterTest extends PHPUnit_Framework_TestCase
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
		$creation	= trim( Alg_Text_Filter::stripComments( $text ) );
		$this->assertEquals( $assertion, $creation );
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
		$creation	= trim( Alg_Text_Filter::stripScripts( $text ) );
		$this->assertEquals( $assertion, $creation );
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
		$creation	= trim( Alg_Text_Filter::stripStyles( $text ) );
		$this->assertEquals( $assertion, $creation );
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
		$creation	= trim( Alg_Text_Filter::stripTags( $text ) );
		$this->assertEquals( $assertion, $creation );
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
		$creation	= Alg_Text_Filter::stripEventAttributes( $text );
		$this->assertEquals( $assertion, $creation );
	}
}
?>