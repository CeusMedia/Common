<?php
/**
 *	TestUnit of XML DOM XPath.
 *	@package		Tests.xml.dom
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@since			17.02.2008
 *	@version		0.1
 */
declare( strict_types = 1 );

use PHPUnit\Framework\TestCase;

/**
 *	TestUnit of XML DOM XPath.
 *	@package		Tests.xml.dom
 *	@extends		Test_Case
 *	@uses			XML_DOM_XPathQuery
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@since			17.02.2008
 *	@version		0.1
 */
class Test_XML_DOM_XPathQueryTest extends Test_Case
{
	protected $xmlUrl	= "https://www.w3schools.com/xml/books.xml";
	protected $xmlFile;
	protected $xPath;

	/**
	 *	Sets up XPath Query.
	 *	@access		public
	 *	@return		void
	 */
	public function setUp(): void
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
		$this->expectException( 'RuntimeException' );
		$entries	= $this->xPath->loadFile( "http://www.example.com/notexisting.xml" );
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
		$this->expectException( 'InvalidArgumentException' );
		$this->xPath->loadXml( "not_valid" );
	}

	/**
	 *	Tests Method 'loadUrl'.
	 *	@access		public
	 *	@return		void
	 */
	public function testLoadUrl()
	{
		if( !extension_loaded( 'curl' ) )
			$this->markTestSkipped( 'The cURL extension is not available.' );
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
	public function testLoadUrlException1()
	{
		if( !extension_loaded( 'curl' ) )
			$this->markTestSkipped( 'The cURL extension is not available.' );
		$this->expectException( 'InvalidArgumentException' );
		$this->xPath->loadUrl( "notexisting.xml" );
	}

	/**
	 *	Tests Method 'loadUrl'.
	 *	@access		public
	 *	@return		void
	 */
	public function testLoadUrlException2()
	{
		if( !extension_loaded( 'curl' ) )
			$this->markTestSkipped( 'The cURL extension is not available.' );
		$this->expectException( 'Exception_IO' );
		$this->xPath->loadUrl( "http://example.org/notexisting.xml" );
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
		$this->expectException( 'RuntimeException' );
		$entries	= $this->xPath->evaluate( "//book" );
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
	public function testGetDocumentException()
	{
		$this->expectException( 'RuntimeException' );
		$entries	= $this->xPath->getDocument();
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
		$this->expectException( 'RuntimeException' );
		$entries	= $this->xPath->query( "//book" );
	}
}
