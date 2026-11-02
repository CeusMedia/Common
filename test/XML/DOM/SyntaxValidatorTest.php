<?php
/** @noinspection PhpMultipleClassDeclarationsInspection */

declare( strict_types = 1 );

namespace CeusMedia\CommonTest\XML\DOM;

use CeusMedia\Common\XML\DOM\FeedIdentifier;
use CeusMedia\Common\XML\DOM\Formater;
use CeusMedia\Common\XML\DOM\SyntaxValidator;
use CeusMedia\CommonTest\BaseCase;

/**
 *	TestUnit of XML\DOM\SyntaxValidator, Formater and FeedIdentifier.
 *	@package		Tests.XML.DOM
 */
class SyntaxValidatorTest extends BaseCase
{
	public function testValidateIndependentOfErrorDisplay(): void
	{
		$display	= ini_get( 'display_errors' );
		ini_set( 'display_errors', '0' );
		$validator	= new SyntaxValidator();
		$invalid	= $validator->validate( '<a><b></a>' );
		ini_set( 'display_errors', (string) $display );
		self::assertFalse( $invalid );
		self::assertStringContainsString( 'mismatch', (string) $validator->getErrors() );

		self::assertTrue( $validator->validate( '<a><b/></a>' ) );
		self::assertNull( $validator->getErrors() );
		self::assertSame( 'a', $validator->getDocument()->documentElement->nodeName );
		self::assertFalse( $validator->validate( '' ) );
	}

	public function testValidateDoesNotNeedDtd(): void
	{
		$xml	= '<?xml version="1.0"?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://example.invalid/x.dtd"><html/>';
		self::assertTrue( ( new SyntaxValidator() )->validate( $xml ) );
	}

	public function testRecodeWithoutEncodingDeclaration(): void
	{
		self::assertSame( '<a>ä</a>', Formater::recode( '<a>ä</a>' ) );
	}

	public function testIdentifyAtom(): void
	{
		$identifier	= new FeedIdentifier();
		self::assertSame( 'ATOM/1.0', $identifier->identify( '<feed xmlns="http://www.w3.org/2005/Atom"><title>x</title></feed>' ) );
		self::assertSame( 'ATOM/0.3', $identifier->identify( '<feed version="0.3"/>' ) );
		self::assertSame( 'RSS/2.0', $identifier->identify( '<rss version="2.0"/>' ) );
	}
}
