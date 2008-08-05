<?php
/**
 *	TestUnit of Alg_HtmlParser.
 *	@package		Tests.alg
 *	@extends		PHPUnit_Framework_TestCase
 *	@uses			Alg_HtmlParser
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			04.08.2008
 *	@version		0.1
 */
require_once( 'PHPUnit/Framework/TestCase.php' ); 
require_once( 'Tests/initLoaders.php5' );
import( 'de.ceus-media.alg.HtmlParser' );
/**
 *	TestUnit of Alg_HtmlParser.
 *	@package		Tests.alg
 *	@extends		PHPUnit_Framework_TestCase
 *	@uses			Alg_HtmlParser
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			04.08.2008
 *	@version		0.1
 */
class Tests_Alg_HtmlParserTest extends PHPUnit_Framework_TestCase
{
	/**
	 *	Constructor.
	 *	@access		public
	 *	@return		void
	 */
	public function __construct()
	{
		$this->path		= dirname( __FILE__ )."/";
		$this->fileName	= $this->path."html.html";
		$this->parser	= new Alg_HtmlParser();
		$this->parser->parseHtmlFile( $this->fileName );
	}
	
	/**
	 *	Setup for every Test.
	 *	@access		public
	 *	@return		void
	 */
	public function setUp()
	{
	}
	
	/**
	 *	Cleanup after every Test.
	 *	@access		public
	 *	@return		void
	 */
	public function tearDown()
	{
	}

	/**
	 *	Tests Method '__construct'.
	 *	@access		public
	 *	@return		void
	 */
	public function test__construct()
	{
		$document	= new Alg_HtmlParser();
		$assertion	= new DOMDocument();
		$creation	= $document->getDocument();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'getDescription'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetDescription()
	{
		$assertion	= "Description Meta Tag";
		$creation	= $this->parser->getDescription();
		$this->assertEquals( $assertion, $creation );

		$parser		= new Alg_HtmlParser();
		$parser->parseHtml( '<html><meta name="DC.Description" content="Dublin Core Description">' );

		$assertion	= "Dublin Core Description";
		$creation	= $parser->getDescription( FALSE );
		$this->assertEquals( $assertion, $creation );

		$parser		= new Alg_HtmlParser();
		$parser->parseHtml( '<html>' );

		$assertion	= "";
		$creation	= $parser->getDescription( FALSE );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Exception of Method 'getDescription'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetDescriptionException()
	{
		$this->setExpectedException( 'RuntimeException' );
		$parser	= new Alg_HtmlParser();
		$parser->parseHtml( "<html>" );
		$parser->getDescription();
	}

	/**
	 *	Tests Method 'getDocument'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetDocument()
	{
		$document	= new DOMDocument();
		$document->loadHtmlFile( $this->fileName );

		$assertion	= $document;
		$creation	= $this->parser->getDocument();
		$this->assertEquals( $assertion, $creation );
	}
	
	/**
	 *	Tests Method 'getFavoriteIcon'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetFavoriteIcon()
	{
		$assertion	= "icon.ico";
		$creation	= $this->parser->getFavoriteIcon();
		$this->assertEquals( $assertion, $creation );

		$parser		= new Alg_HtmlParser();
		$parser->parseHtml( '<HTML><LINK REL="ICON" HREF="icon.png"></HTML>' );

		$assertion	= "icon.png";
		$creation	= $parser->getFavoriteIcon();
		$this->assertEquals( $assertion, $creation );

		$parser		= new Alg_HtmlParser();
		$parser->parseHtml( '<link rel="shortcut icon" href="./images/favicon.ico" />' );

		$assertion	= "./images/favicon.ico";
		$creation	= $parser->getFavoriteIcon();
		$this->assertEquals( $assertion, $creation );

		$parser		= new Alg_HtmlParser();
		$parser->parseHtml( '<html>' );

		$assertion	= "";
		$creation	= $parser->getFavoriteIcon( FALSE );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Exception of Method 'getFavoriteIcon'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetFavoriteIconException()
	{
		$this->setExpectedException( 'RuntimeException' );

		$parser		= new Alg_HtmlParser();
		$parser->parseHtml( '<html>' );
		$parser->getFavoriteIcon();
	}

	/**
	 *	Tests Method 'getJavaScripts'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetJavaScripts()
	{
		$blocks		= $this->parser->getJavaScripts();

		$assertion	= 1;
		$creation	= count( $blocks );
		$this->assertEquals( $assertion, $creation );

		$assertion	= "alert('this is a test');";
		$creation	= trim( $blocks[0] );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'getJavaScriptUrls'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetJavaScriptUrls()
	{
		$urls		= $this->parser->getJavaScriptUrls();

		$assertion	= 2;
		$creation	= count( $urls );
		$this->assertEquals( $assertion, $creation );

		$assertion	= "script.js";
		$creation	= $urls[0];
		$this->assertEquals( $assertion, $creation );

		$assertion	= "javascript.js";
		$creation	= $urls[1];
		$this->assertEquals( $assertion, $creation );

		$parser	= new Alg_HtmlParser();
		$parser->parseHtml( "<html>" );

		$assertion	= array();
		$creation	= $parser->getJavaScriptUrls();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'getKeyWords'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetKeyWords()
	{
		$assertion	= array(
			'test',
			'123'
		);
		$creation	= $this->parser->getKeyWords();
		$this->assertEquals( $assertion, $creation );

		$parser		= new Alg_HtmlParser();
		$parser->parseHtml( '<html>' );

		$assertion	= array();
		$creation	= $parser->getKeyWords( FALSE );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Exception of Method 'getKeyWords'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetKeyWordsException()
	{
		$this->setExpectedException( 'RuntimeException' );
		$parser		= new Alg_HtmlParser();
		$parser->parseHtml( '<html>' );
		$parser->getKeyWords();
	}

	/**
	 *	Tests Method 'getLanguage'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetLanguage()
	{
		$assertion	= "de";
		$creation	= $this->parser->getLanguage();
		$this->assertEquals( $assertion, $creation );

		$parser		= new Alg_HtmlParser();
		$parser->parseHtml( '<html>' );

		$assertion	= "";
		$creation	= $parser->getLanguage( FALSE );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Exception of Method 'getLanguage'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetLanguageException()
	{
		$this->setExpectedException( 'RuntimeException' );
		$parser	= new Alg_HtmlParser();
		$parser->parseHtml( "<html>" );
		$parser->getLanguage();
	}

	/**
	 *	Tests Method 'getMetaTags'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetMetaTags()
	{
		$tags		= $this->parser->getMetaTags();
			
		$assertion	= 5;
		$creation	= count( $tags );
		$this->assertEquals( $assertion, $creation );

		$assertion	= array(
			'content-language'	=> "de",
			'description'		=> "Description Meta Tag",
			'keywords'			=> "test, 123",
			'author'			=> "Santa Claus",
			'expires'			=> "Sat, 01 Dec 2001 00:00:00 GMT",
		);
		$creation	= $tags;
		$this->assertEquals( $assertion, $creation );

		$parser	= new Alg_HtmlParser();
		$parser->parseHtml( "<html>" );
		$assertion	= array();
		$creation	= $parser->getMetaTags();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'getStyles'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetStyles()
	{
		$blocks		= $this->parser->getStyles();

		$assertion	= 2;
		$creation	= count( $blocks );
		$this->assertEquals( $assertion, $creation );

		$assertion	= "#test {\n\tcolor: red;\n\t}";
		$creation	= trim( $blocks[0] );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'getStyleSheetUrls'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetStyleSheetUrls()
	{
		$urls		= $this->parser->getStyleSheetUrls();

		$assertion	= 2;
		$creation	= count( $urls );
		$this->assertEquals( $assertion, $creation );

		$assertion	= "style.css";
		$creation	= $urls[0];
		$this->assertEquals( $assertion, $creation );

		$assertion	= "print.css";
		$creation	= $urls[1];
		$this->assertEquals( $assertion, $creation );

		$parser	= new Alg_HtmlParser();
		$parser->parseHtml( "<html>" );

		$assertion	= array();
		$creation	= $parser->getStyleSheetUrls();
		$this->assertEquals( $assertion, $creation );

	}

	/**
	 *	Tests Method 'getTags'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetTags()
	{
		$tags		= $this->parser->getTags();

		$assertion	= 20;
		$creation	= count( $tags );
		$this->assertEquals( $assertion, $creation );

		$tags		= $this->parser->getTags( 'link' );

		$assertion	= 4;
		$creation	= count( $tags );
		$this->assertEquals( $assertion, $creation );

		$assertion	= 'link';
		$creation	= $tags[0]->tagName;
		$this->assertEquals( $assertion, $creation );

		$tags		= $this->parser->getTags( 'meta', 'name' );

		$assertion	= 2;
		$creation	= count( $tags );
		$this->assertEquals( $assertion, $creation );

		$assertion	= 'meta';
		$creation	= $tags[0]->tagName;
		$this->assertEquals( $assertion, $creation );

		$tags		= $this->parser->getTags( 'link', 'rel', 'icon' );

		$assertion	= 1;
		$creation	= count( $tags );
		$this->assertEquals( $assertion, $creation );

		$assertion	= 'link';
		$creation	= $tags[0]->tagName;
		$this->assertEquals( $assertion, $creation );

		$assertion	= "icon";
		$creation	= $tags[0]->getAttribute( 'rel' );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'getTagsByAttribute'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetTagsByAttribute()
	{
		$tags		= $this->parser->getTagsByAttribute( 'http-equiv' );

		$assertion	= 3;
		$creation	= count( $tags );
		$this->assertEquals( $assertion, $creation );

		$assertion	= 'meta';
		$creation	= $tags[0]->tagName;
		$this->assertEquals( $assertion, $creation );

		$tags		= $this->parser->getTagsByAttribute( 'http-equiv', 'content-language' );

		$assertion	= 1;
		$creation	= count( $tags );
		$this->assertEquals( $assertion, $creation );

		$assertion	= 'meta';
		$creation	= $tags[0]->tagName;
		$this->assertEquals( $assertion, $creation );

		$assertion	= 'de';
		$creation	= $tags[0]->getAttribute( 'content' );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'getTagById'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetTagById()
	{
		$tag		= $this->parser->getTagById( 'test' );

		$assertion	= TRUE;
		$creation	= (bool) $tag;
		$this->assertEquals( $assertion, $creation );

		$assertion	= "ul";
		$creation	= $tag->tagName;
		$this->assertEquals( $assertion, $creation );

		$assertion	= "id";
		$creation	= $tag->attributes->item(0)->name;
		$this->assertEquals( $assertion, $creation );

		$assertion	= "test";
		$creation	= $tag->attributes->item(0)->value;
		$this->assertEquals( $assertion, $creation );

		$assertion	= NULL;
		$creation	= $this->parser->getTagById( 'not_existing', FALSE );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Exception of Method 'getTagById'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetTagByIdException()
	{
		$this->setExpectedException( 'RuntimeException' );
		$this->parser->getTagById( 'not_existing' );
	}

	/**
	 *	Tests Method 'getTagsByTagName'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetTagsByTagName()
	{
		$tags		= $this->parser->getTagsByTagName( 'meta' );

		$assertion	= 5;
		$creation	= count( $tags );
		$this->assertEquals( $assertion, $creation );

		$assertion	= 'meta';
		$creation	= $tags[0]->tagName;
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'getTagsByXPath'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetTagsByXPath()
	{
		$query		= "//meta[@name]";
		$tags		= $this->parser->getTagsByXPath( $query );
	
		$assertion	= 2;
		$creation	= count( $tags );
		$this->assertEquals( $assertion, $creation );

		$assertion	= 'meta';
		$creation	= $tags[0]->tagName;
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'hasTagById'.
	 *	@access		public
	 *	@return		void
	 */
	public function testHasTagById()
	{
		$assertion	= TRUE;
		$creation	= $this->parser->hasTagById( 'test' );
		$this->assertEquals( $assertion, $creation );

		$assertion	= FALSE;
		$creation	= $this->parser->hasTagById( 'not_existing' );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'getTitle'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetTitle()
	{
		$assertion	= "HTML Parser Test";
		$creation	= $this->parser->getTitle();
		$this->assertEquals( $assertion, $creation );

		$parser		= new Alg_HtmlParser();
		$parser->parseHtml( '<html><meta http-equiv="DC.Title" content="Dublin Core"></html>' );

		$assertion	= "Dublin Core";
		$creation	= $parser->getTitle( FALSE );
		$this->assertEquals( $assertion, $creation );

		$parser		= new Alg_HtmlParser();
		$parser->parseHtml( '<html>' );

		$assertion	= "";
		$creation	= $parser->getTitle( FALSE );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Exception of Method 'getTitle'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetTitleException()
	{
		$this->setExpectedException( 'RuntimeException' );
		$parser	= new Alg_HtmlParser();
		$parser->parseHtml( "<html>" );
		$parser->getTitle();
	}

	/**
	 *	Tests Method 'parseHtml'.
	 *	@access		public
	 *	@return		void
	 */
	public function testParseHtml()
	{
		$html		= file_get_contents( $this->fileName );
		$document	= new DOMDocument();
		$document->loadHtml( $html );
		
		$parser		= new Alg_HtmlParser();
		$parser->parseHtml( $html );
		$assertion	= $document;
		$creation	= $parser->getDocument();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'parseHtmlFile'.
	 *	@access		public
	 *	@return		void
	 */
	public function testParseHtmlFile()
	{
		$document	= new DOMDocument();
		$document->loadHtmlFile( $this->fileName );
		
		$parser		= new Alg_HtmlParser();
		$parser->parseHtmlFile( $this->fileName );
		$assertion	= $document;
		$creation	= $parser->getDocument();
		$this->assertEquals( $assertion, $creation );
	}
}
?>