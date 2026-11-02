<?php
/** @noinspection PhpMultipleClassDeclarationsInspection */

declare( strict_types = 1 );

namespace CeusMedia\CommonTest\UI\HTML\Exception;

use CeusMedia\Common\Exception\Runtime as RuntimeException;
use CeusMedia\Common\UI\HTML\Exception\Trace;
use CeusMedia\Common\UI\HTML\Exception\TraceViewer;
use CeusMedia\Common\UI\HTML\Exception\View;
use CeusMedia\CommonTest\BaseCase;
use Exception;

/**
 *	TestUnit of UI\HTML\Exception views.
 *	@package		Tests.UI.HTML.Exception
 */
class ViewTest extends BaseCase
{
	public function testViewEscapesAndShowsFile(): void
	{
		$documentRoot	= $_SERVER['DOCUMENT_ROOT'] ?? NULL;
		unset( $_SERVER['DOCUMENT_ROOT'] );
		$exception	= RuntimeException::create( '<b>message</b>' )
			->setDescription( '<script>description</script>' )
			->setSuggestion( '<i>suggestion</i>' );
		$html	= View::render( $exception, FALSE );
		if( NULL !== $documentRoot )
			$_SERVER['DOCUMENT_ROOT']	= $documentRoot;
		self::assertStringNotContainsString( '<script>', $html );
		self::assertStringNotContainsString( '<i>suggestion', $html );
		self::assertStringContainsString( '&lt;script&gt;description', $html );
		//  without document root, the full path is shown (it was empty)
		self::assertStringContainsString( '<span class="path">/', $html );
	}

	public function testTraceViewerEscapesMessage(): void
	{
		$html	= TraceViewer::buildTrace( new Exception( '<script>alert(1)</script>' ) );
		self::assertStringNotContainsString( '<script>', $html );
		self::assertStringContainsString( 'ViewTest', $html );
	}

	public function testTraceEscapesArrayKeys(): void
	{
		$html	= Trace::renderFromTrace( [
			['function' => 'a'],
			['file' => __FILE__, 'line' => 1, 'function' => 'test', 'args' => [['<b>key</b>' => '<i>value</i>']]],
		] );
		self::assertStringNotContainsString( '<b>key', $html );
		self::assertStringNotContainsString( '<i>value', $html );
	}
}
