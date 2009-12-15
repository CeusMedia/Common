<?php
/**
 *	TestUnit of XML DOM XPath.
 *	@package		Tests.xml.dom
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			17.02.2008
 *	@version		0.1
 */
require_once 'PHPUnit/Framework/TestCase.php';
require_once 'test/initLoaders.php5';
/**
 *	TestUnit of XML DOM XPath.
 *	@package		Tests.xml.dom
 *	@extends		PHPUnit_Framework_TestCase
 *	@uses			XML_DOM_XPathQuery
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			17.02.2008
 *	@version		0.1
 */
class Test_XML_DOM_XPathQueryTest extends PHPUnit_Framework_TestCase
{
	protected $xmlUrl	= "http://www.w3schools.com/xquery/books.xml";

	/**
	 *	Sets up XPath Query.
	 *	@access		public
	 *	@return		void
	 */
	public function setUp()
	{
		$this->xmlFile	= dirname( __FILE__ ).'/books.xml';
		$this->xPath	= new XML_DOM_XPathQuery();
	}

	/**
	 *	Tests Method 'loadFile'.
	 *	@access		public
	 *	@return		void
	 */
	public function testLoadFile()
	{
		$this->xPath->loadFile( $this->xmlFile );

		$entries	= $this->xPath->query( "//book" );
		$assertion	= 4;
		$creation	= $entries->length;
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'loadFile'.
	 *	@access		public
	 *	@return		void
	 */
	public function testLoadFileException()
	{
		try
		{
			$entries	= $this->xPath->loadFile( "http://www.example.com/notexisting.xml" );
			$this->fail( 'An expected Exception has not been thrown.' );
		}
		catch( Exception $e )
		{
		}
	}

	/**
	 *	Tests Method 'loadXml'.
	 *	@access		public
	 *	@return		void
	 */
	public function testLoadXml()
	{
		$xml	= file_get_contents( $this->xmlFile );
		$this->xPath->loadXml( $xml );

		$entries	= $this->xPath->query( "//book" );
		$assertion	= 4;
		$creation	= $entries->length;
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'loadXml'.
	 *	@access		public
	 *	@return		void
	 */
	public function testLoadXmlException()
	{
		try
		{
			$entries	= $this->xPath->loadXml( "not_valid" );
			$this->fail( 'An expected Exception has not been thrown.' );
		}
		catch( Exception $e )
		{
		}
	}

	/**
	 *	Tests Method 'loadUrl'.
	 *	@access		public
	 *	@return		void
	 */
	public function testLoadUrl()
	{
		$this->xPath->loadUrl( $this->xmlUrl );
		
		$entries	= $this->xPath->query( "//book" );
		$assertion	= 4;
		$creation	= $entries->length;
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'loadUrl'.
	 *	@access		public
	 *	@return		void
	 */
	public function testLoadUrlException()
	{
		try
		{
			$entries	= $this->xPath->loadUrl( "notexisting.xml" );
			$this->fail( 'An expected Exception has not been thrown.' );
		}
		catch( Exception $e )
		{
		}
	}

	/**
	 *	Tests Method 'evaluate'.
	 *	@access		public
	 *	@return		void
	 */
	public function testEvaluate()
	{
		$this->xPath->loadFile( $this->xmlFile );

		$entries	= $this->xPath->evaluate( "//book" );
		$assertion	= 4;
		$creation	= $entries->length;
		$this->assertEquals( $assertion, $creation );

		$entries	= $this->xPath->evaluate( "//book[@category='COOKING']/title/text()" );
		$assertion	= "Everyday Italian";
		$creation	= $entries->item( 0 )->nodeValue;
		$this->assertEquals( $assertion, $creation );

		$entries	= $this->xPath->evaluate( "//book[@category='COOKING']/title" );
		$assertion	= "en";
		$creation	= $entries->item( 0 )->getAttribute( 'lang' );
		$this->assertEquals( $assertion, $creation );

		$entries	= $this->xPath->evaluate( "count(//book[@category='WEB']/author)" );
		$assertion	= 6;
		$creation	= $entries;
		$this->assertEquals( $assertion, $creation );
		
		$doc		= $this->xPath->getDocument();
		$book		= $doc->getElementsByTagName( "book" )->item( 2 );
		$entries	= $this->xPath->evaluate( "count(author)", $book );
		$assertion	= 5;
		$creation	= $entries;
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'evaluate'.
	 *	@access		public
	 *	@return		void
	 */
	public function testEvaluateException()
	{
		try
		{
			$entries	= $this->xPath->evaluate( "//book" );
			$this->fail( 'An expected Exception has not been thrown.' );
		}
		catch( Exception $e )
		{
		}
	}

	
	/**
	 *	Tests Method 'getDocument'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetDocument()
	{
		$this->xPath->loadFile( $this->xmlFile );
		
		$doc	= $this->xPath->getDocument();
		$assertion	= true;
		$creation	= is_a( $doc, 'DOMDocument' );
		$this->assertEquals( $assertion, $creation );

		$bookList	= $doc->getElementsByTagName( "book" );
		$assertion	= true;
		$creation	= is_a( $bookList, 'DOMNodeList' );
		$this->assertEquals( $assertion, $creation );
		
		$book		= $bookList->item( 0 );
		$assertion	= true;
		$creation	= is_a( $book, 'DOMNode' );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'getDocument'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetDocumetException()
	{
		try
		{
			$entries	= $this->xPath->getDocument();
			$this->fail( 'An expected Exception has not been thrown.' );
		}
		catch( Exception $e )
		{
		}
	}

	/**
	 *	Tests Method 'query'.
	 *	@access		public
	 *	@return		void
	 */
	public function testQuery()
	{
		$this->xPath->loadFile( $this->xmlFile );

		$entries	= $this->xPath->query( "//book" );
		$assertion	= 4;
		$creation	= $entries->length;
		$this->assertEquals( $assertion, $creation );

		$entries	= $this->xPath->query( "//book[@category='COOKING']/title/text()" );
		$assertion	= "Everyday Italian";
		$creation	= $entries->item( 0 )->nodeValue;
		$this->assertEquals( $assertion, $creation );

		$entries	= $this->xPath->query( "//book[@category='COOKING']/title" );
		$assertion	= "en";
		$creation	= $entries->item( 0 )->getAttribute( 'lang' );
		$this->assertEquals( $assertion, $creation );

		$entries	= $this->xPath->query( "//book[@category='WEB']/author" );
		$assertion	= 6;
		$creation	= $entries->length;
		$this->assertEquals( $assertion, $creation );
		
		$doc		= $this->xPath->getDocument();
		$book		= $doc->getElementsByTagName( "book" )->item( 2 );
		$entries	= $this->xPath->evaluate( "author[3]/text()", $book );
		$assertion	= "Kurt Cagle";
		$creation	= $entries->item( 0 )->nodeValue;
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'query'.
	 *	@access		public
	 *	@return		void
	 */
	public function testQueryException()
	{
		try
		{
			$entries	= $this->xPath->query( "//book" );
			$this->fail( 'An expected Exception has not been thrown.' );
		}
		catch( Exception $e )
		{
		}
	}
}
?>