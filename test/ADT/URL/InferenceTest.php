<?php
/** @noinspection PhpMultipleClassDeclarationsInspection */

declare( strict_types = 1 );

namespace CeusMedia\CommonTest\ADT\URL;

use CeusMedia\Common\ADT\URL;
use CeusMedia\Common\ADT\URL\Compare;
use CeusMedia\Common\ADT\URL\Inference;
use CeusMedia\CommonTest\BaseCase;
use RuntimeException;

/**
 *	TestUnit of ADT\URL\Inference, Compare and URL::setQuery().
 *	@package		Tests.ADT.URL
 */
class InferenceTest extends BaseCase
{
	protected array $get	= [];

	public function setUp(): void
	{
		$this->get	= $_GET;
		$_GET	= ['x' => '1', 'list' => ['a', 'b']];
	}

	public function tearDown(): void
	{
		$_GET	= $this->get;
	}

	public function testBuild(): void
	{
		$url	= new Inference( 'https://example.org/page?x=1#f' );
		self::assertSame( 'https://example.org/page?list%5B0%5D=a&list%5B1%5D=b&y=2', $url->build( ['y' => 2], ['x'] ) );
		self::assertSame( 'https://example.org/page?x=1&list%5B0%5D=a&list%5B1%5D=b#top', $url->build( [], [], 'top' ) );
	}

	public function testBuildStatic(): void
	{
		self::assertSame( './?x=1&a=1#top', Inference::buildStatic( ['a' => 1], ['list'], 'top' ) );
	}

	public function testSetQueryWithNumericKeys(): void
	{
		$url	= new URL( 'https://example.org/' );
		$url->setQuery( [1 => 'a', 'b' => 'c'] );
		self::assertSame( 'https://example.org/?1=a&b=c', $url->get() );
	}

	public function testCompareWithoutUrls(): void
	{
		self::assertTrue( Compare::sameBaseStatic( 'https://example.org/a', 'https://example.org/b?x' ) );
		$this->expectException( RuntimeException::class );
		( new Compare() )->sameBase();
	}
}
