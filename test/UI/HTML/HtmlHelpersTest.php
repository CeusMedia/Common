<?php
/** @noinspection PhpMultipleClassDeclarationsInspection */

declare( strict_types = 1 );

namespace CeusMedia\CommonTest\UI\HTML;

use CeusMedia\Common\UI\HTML\Elements;
use CeusMedia\Common\UI\HTML\Index;
use CeusMedia\Common\UI\HTML\JQuery;
use CeusMedia\Common\UI\HTML\PageFrame;
use CeusMedia\Common\UI\HTML\Pagination;
use CeusMedia\Common\UI\HTML\Table;
use CeusMedia\Common\UI\HTML\Tabs;
use CeusMedia\CommonTest\BaseCase;

/**
 *	TestUnit of several UI\HTML helpers.
 *	@package		Tests.UI.HTML
 */
class HtmlHelpersTest extends BaseCase
{
	public function testElementsEscapeAttributes(): void
	{
		$html	= Elements::CheckButton( 'name', 'a"b', 'Say "hi"' );
		self::assertStringContainsString( 'value="a&quot;b"', $html );
		self::assertStringContainsString( 'value="Say &quot;hi&quot;"', $html );

		$html	= Elements::Acronym( 'HTML', 'a "quoted" <b>' );
		self::assertStringContainsString( 'title="a &quot;quoted&quot; &lt;b&gt;"', $html );

		$html	= Elements::Link( 'page.php?a=1&b=2', 'Delete', NULL, NULL, "Don't do it" );
		self::assertStringContainsString( 'href="page.php?a=1&amp;b=2"', $html );
		self::assertStringNotContainsString( "confirm('Don't", $html );
		self::assertStringContainsString( 'return confirm(', $html );
	}

	public function testJQueryBuildPluginCall(): void
	{
		$script	= JQuery::buildPluginCall( 'plugin', 'a[name="x"]', ['text' => '</script><script>alert(1)'] );
		self::assertStringNotContainsString( '</script>', $script );
		self::assertStringContainsString( 'jQuery("a[name=\u0022x\u0022]").plugin(', $script );
	}

	public function testPagination(): void
	{
		$pagination	= new Pagination( ['uri' => 'list.php', 'param' => ['q' => 'a&b c'], 'showPrevNext' => FALSE] );
		$html	= $pagination->build( 100, 10, 20 );
		self::assertStringContainsString( 'q=a%26b%20c', $html );
		self::assertStringNotContainsString( '&lsaquo;', $html );
		self::assertStringContainsString( '&laquo;', $html );
		self::assertStringContainsString( '<span class="current">3</span>', $html );
	}

	public function testTableKeepsZero(): void
	{
		$table	= new Table();
		$table->addCell( '0' );
		$table->addCell( '' );
		$html	= $table->render( NULL );
		self::assertStringContainsString( '<td>0</td>', $html );
		self::assertStringContainsString( '<td>&#160;</td>', $html );
	}

	public function testTabsVersion(): void
	{
		$_SERVER['REDIRECT_URL']	= '/page';
		$tabs	= new Tabs( ['A' => 'a'] );
		$tabs->setVersion( 4 );
		self::assertStringContainsString( 'href="#0-container"', $tabs->buildTabs( 'tabs' ) );
		$tabs->setVersion( 2 );
		self::assertStringContainsString( 'href="/page#0-container"', $tabs->buildTabs( 'tabs' ) );
		unset( $_SERVER['REDIRECT_URL'] );
	}

	public function testIndexWithSpecialCharacters(): void
	{
		$content	= '<h1>What (and why)?</h1><p>x</p><h2>A <b>bold</b> one</h2><p>y</p>';
		$index	= new Index();
		$index->importFromHtml( $content );
		self::assertStringContainsString( '<h1 id="what-and-why">What (and why)?</h1>', $content );
		self::assertSame( 'A bold one', $index->tree[0]->children[0]->label );
	}

	public function testPageFrame(): void
	{
		$page	= new PageFrame();
		$page->setCanonicalLink( 'https://example.org/page' );
		$html	= $page->build();
		self::assertStringContainsString( 'xmlns="http://www.w3.org/1999/xhtml"', $html );
		self::assertStringContainsString( '"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd"', $html );
		self::assertStringContainsString( 'href="https://example.org/page"', $html );
		self::assertStringNotContainsString( 'uri=', $html );
	}
}
