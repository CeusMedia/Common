<?php /** @noinspection HtmlUnknownTarget */
/** @noinspection HtmlRequiredLangAttribute */
/** @noinspection PhpIllegalPsrClassPathInspection */
/** @noinspection PhpMultipleClassDeclarationsInspection */
/** @noinspection PhpUnhandledExceptionInspection */
/** @noinspection PhpDocMissingThrowsInspection */

declare( strict_types = 1 );

/**
 *	TestUnit of HtmlParser.
 *	@package		Tests.alg
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 */

namespace CeusMedia\CommonTest\Alg;

use CeusMedia\Common\Alg\HtmlParser;
use CeusMedia\CommonTest\BaseCase;
use DOMDocument;

/**
 *	TestUnit of HtmlParser.
 *	@package		Tests.alg
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 */
class HtmlParserTest extends BaseCase
{
	protected $fileName;
	protected $path;
	protected $parser;

	/**
	 *	Setup for every Test.
	 *	@access		public
	 *	@return		void
	 */
	public function setUp(): void
	{
		$this->path		= dirname( __FILE__ )."/";
		$this->fileName	= $this->path."html.html";
		$this->parser	= new HtmlParser();
		$this->parser->parseHtmlFile( $this->fileName );
	}

	/**
	 *	Cleanup after every Test.
	 *	@access		public
	 *	@return		void
	 */
	public function tearDown(): void
	{
	}

	/**
	 *	Tests Method '__construct'.
	 *	@access		public
	 *	@return		void
	 */
	public function test__construct()
	{
		$document	= new HtmlParser();
		$creation	= $document->getDocument();

		self::assertInstanceOf( DOMDocument::class, $creation );
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
		self::assertEquals( $assertion, $creation );

		$parser		= new HtmlParser();
		$parser->parseHtml( '<html><meta name="DC.Description" content="Dublin Core Description">' );

		$assertion	= "Dublin Core Description";
		$creation	= $parser->getDescription( FALSE );
		self::assertEquals( $assertion, $creation );

		$parser		= new HtmlParser();
		$parser->parseHtml( '<html>' );

		$assertion	= "";
		$creation	= $parser->getDescription( FALSE );
		self::assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Exception of Method 'getDescription'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetDescriptionException()
	{
		$this->expectException( 'RuntimeException' );
		$parser	= new HtmlParser();
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
		self::assertEquals( $assertion, $creation );
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
		self::assertEquals( $assertion, $creation );

		$parser		= new HtmlParser();
		$parser->parseHtml( '<HTML><LINK REL="ICON" HREF="icon.png"></HTML>' );

		$assertion	= "icon.png";
		$creation	= $parser->getFavoriteIcon();
		self::assertEquals( $assertion, $creation );

		$parser		= new HtmlParser();
		$parser->parseHtml( '<link rel="shortcut icon" href="./images/favicon.ico" />' );

		$assertion	= "./images/favicon.ico";
		$creation	= $parser->getFavoriteIcon();
		self::assertEquals( $assertion, $creation );

		$parser		= new HtmlParser();
		$parser->parseHtml( '<html>' );

		$assertion	= "";
		$creation	= $parser->getFavoriteIcon( FALSE );
		self::assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Exception of Method 'getFavoriteIcon'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetFavoriteIconException()
	{
		$this->expectException( 'RuntimeException' );

		$parser		= new HtmlParser();
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
		self::assertEquals( $assertion, $creation );

		$assertion	= "alert('this is a test');";
		$creation	= trim( $blocks[0] );
		self::assertEquals( $assertion, $creation );
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
		self::assertEquals( $assertion, $creation );

		$assertion	= "script.js";
		$creation	= $urls[0];
		self::assertEquals( $assertion, $creation );

		$assertion	= "javascript.js";
		$creation	= $urls[1];
		self::assertEquals( $assertion, $creation );

		$parser	= new HtmlParser();
		$parser->parseHtml( "<html>" );

		$assertion	= [];
		$creation	= $parser->getJavaScriptUrls();
		self::assertEquals( $assertion, $creation );
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
		self::assertEquals( $assertion, $creation );

		$parser		= new HtmlParser();
		$parser->parseHtml( '<html>' );

		$assertion	= [];
		$creation	= $parser->getKeyWords( FALSE );
		self::assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Exception of Method 'getKeyWords'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetKeyWordsException()
	{
		$this->expectException( 'RuntimeException' );
		$parser		= new HtmlParser();
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
		self::assertEquals( $assertion, $creation );

		$parser		= new HtmlParser();
		$parser->parseHtml( '<html>' );

		$assertion	= "";
		$creation	= $parser->getLanguage( FALSE );
		self::assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Exception of Method 'getLanguage'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetLanguageException()
	{
		$this->expectException( 'RuntimeException' );
		$parser	= new HtmlParser();
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
		self::assertEquals( $assertion, $creation );

		$assertion	= array(
			'content-language'	=> "de",
			'description'		=> "Description Meta Tag",
			'keywords'			=> "test, 123",
			'author'			=> "Santa Claus",
			'expires'			=> "Sat, 01 Dec 2001 00:00:00 GMT",
		);
		$creation	= $tags;
		self::assertEquals( $assertion, $creation );

		$parser	= new HtmlParser();
		$parser->parseHtml( "<html>" );
		$assertion	= [];
		$creation	= $parser->getMetaTags();
		self::assertEquals( $assertion, $creation );
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
		self::assertEquals( $assertion, $creation );

		$assertion	= "#test {\n\tcolor: red;\n\t}";
		$creation	= trim( $blocks[0] );
		self::assertEquals( $assertion, $creation );
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
		self::assertEquals( $assertion, $creation );

		$assertion	= "style.css";
		$creation	= $urls[0];
		self::assertEquals( $assertion, $creation );

		$assertion	= "print.css";
		$creation	= $urls[1];
		self::assertEquals( $assertion, $creation );

		$parser	= new HtmlParser();
		$parser->parseHtml( "<html>" );

		$assertion	= [];
		$creation	= $parser->getStyleSheetUrls();
		self::assertEquals( $assertion, $creation );

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
		self::assertEquals( $assertion, $creation );

		$tags		= $this->parser->getTags( 'link' );

		$assertion	= 4;
		$creation	= count( $tags );
		self::assertEquals( $assertion, $creation );

		$assertion	= 'link';
		$creation	= $tags[0]->tagName;
		self::assertEquals( $assertion, $creation );

		$tags		= $this->parser->getTags( 'meta', 'name' );

		$assertion	= 2;
		$creation	= count( $tags );
		self::assertEquals( $assertion, $creation );

		$assertion	= 'meta';
		$creation	= $tags[0]->tagName;
		self::assertEquals( $assertion, $creation );

		$tags		= $this->parser->getTags( 'link', 'rel', 'icon' );

		$assertion	= 1;
		$creation	= count( $tags );
		self::assertEquals( $assertion, $creation );

		$assertion	= 'link';
		$creation	= $tags[0]->tagName;
		self::assertEquals( $assertion, $creation );

		$assertion	= "icon";
		$creation	= $tags[0]->getAttribute( 'rel' );
		self::assertEquals( $assertion, $creation );
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
		self::assertEquals( $assertion, $creation );

		$assertion	= 'meta';
		$creation	= $tags[0]->tagName;
		self::assertEquals( $assertion, $creation );

		$tags		= $this->parser->getTagsByAttribute( 'http-equiv', 'content-language' );

		$assertion	= 1;
		$creation	= count( $tags );
		self::assertEquals( $assertion, $creation );

		$assertion	= 'meta';
		$creation	= $tags[0]->tagName;
		self::assertEquals( $assertion, $creation );

		$assertion	= 'de';
		$creation	= $tags[0]->getAttribute( 'content' );
		self::assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'getTagById'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetTagById()
	{
		$tag		= $this->parser->getTagById( 'test' );

		$creation	= (bool) $tag;
		self::assertTrue( $creation );

		$assertion	= "ul";
		$creation	= $tag->tagName;
		self::assertEquals( $assertion, $creation );

		$assertion	= "id";
		$creation	= $tag->attributes->item(0)->name;
		self::assertEquals( $assertion, $creation );

		$assertion	= "test";
		$creation	= $tag->attributes->item(0)->value;
		self::assertEquals( $assertion, $creation );

		$assertion	= NULL;
		$creation	= $this->parser->getTagById( 'not_existing', FALSE );
		self::assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Exception of Method 'getTagById'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetTagByIdException()
	{
		$this->expectException( 'RuntimeException' );
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
		self::assertEquals( $assertion, $creation );

		$assertion	= 'meta';
		$creation	= $tags[0]->tagName;
		self::assertEquals( $assertion, $creation );
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
		self::assertEquals( $assertion, $creation );

		$assertion	= 'meta';
		$creation	= $tags[0]->tagName;
		self::assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'hasTagById'.
	 *	@access		public
	 *	@return		void
	 */
	public function testHasTagById()
	{
		$creation	= $this->parser->hasTagById( 'test' );
		self::assertTrue( $creation );

		$creation	= $this->parser->hasTagById( 'not_existing' );
		self::assertFalse( $creation );
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
		self::assertEquals( $assertion, $creation );

		$parser		= new HtmlParser();
		$parser->parseHtml( '<html><meta http-equiv="DC.Title" content="Dublin Core"></html>' );

		$assertion	= "Dublin Core";
		$creation	= $parser->getTitle( FALSE );
		self::assertEquals( $assertion, $creation );

		$parser		= new HtmlParser();
		$parser->parseHtml( '<html>' );

		$assertion	= "";
		$creation	= $parser->getTitle( FALSE );
		self::assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Exception of Method 'getTitle'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetTitleException()
	{
		$this->expectException( 'RuntimeException' );
		$parser	= new HtmlParser();
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

		$parser		= new HtmlParser();
		$parser->parseHtml( $html );
		$assertion	= $document;
		$creation	= $parser->getDocument();
		self::assertEquals( $assertion, $creation );
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

		$parser		= new HtmlParser();
		$parser->parseHtmlFile( $this->fileName );
		$assertion	= $document;
		$creation	= $parser->getDocument();
		self::assertEquals( $assertion, $creation );
	}
}
