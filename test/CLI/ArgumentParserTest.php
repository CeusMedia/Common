<?php
/** @noinspection PhpIllegalPsrClassPathInspection */
/** @noinspection PhpMultipleClassDeclarationsInspection */
/** @noinspection PhpUnhandledExceptionInspection */
/** @noinspection PhpDocMissingThrowsInspection */

declare( strict_types = 1 );

/**
 *	TestUnit of CLI_ArgumentParser.
 *	@package		Tests.CLI
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 */

namespace CeusMedia\CommonTest\CLI;

use CeusMedia\Common\CLI\ArgumentParser;
use CeusMedia\CommonTest\BaseCase;
use PhpParser\Node\Arg;

/**
 *	TestUnit of CLI_ArgumentParser.
 *	@package		Tests.CLI
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 */
class ArgumentParserTest extends BaseCase
{
	public function testParse1(): void
	{
		list( $arguments, $parameters ) = ArgumentParser::parse( 'a bc def opt1=val1 opt2=val2' );
		self::assertEquals( ['a', 'bc', 'def'], $arguments );
		self::assertEquals( ['opt1' => 'val1', 'opt2' => 'val2'], $parameters );
	}

	public function testParse2(): void
	{
		ArgumentParser::$delimiterAssign	= ':';
		list( $arguments, $parameters ) = ArgumentParser::parse( 'a bc def opt1:val1 opt2:val2' );
		ArgumentParser::$delimiterAssign	= '=';
		self::assertEquals( ['a', 'bc', 'def'], $arguments );
		self::assertEquals( ['opt1' => 'val1', 'opt2' => 'val2'], $parameters );
	}

	public function testParseFromCurrentCall(): void
	{
		global $argv;
		$argv = ['a', 'bc', 'def', 'opt1=val1', 'opt2=val2'];
		list( $arguments, $parameters ) = ArgumentParser::parseFromCurrentCall();
		self::assertEquals( ['bc', 'def'], $arguments );
		self::assertEquals( ['opt1' => 'val1', 'opt2' => 'val2'], $parameters );
	}

	public function testParse_withShortcut1(): void
	{
		ArgumentParser::$delimiterAssign	= ':';
		list( $arguments, $parameters ) = ArgumentParser::parse( 'a bc def o:val1 opt2:val2', ['o' => 'opt1'] );
		ArgumentParser::$delimiterAssign	= '=';
		self::assertEquals( ['a', 'bc', 'def'], $arguments );
		self::assertEquals( ['opt1' => 'val1', 'opt2' => 'val2'], $parameters );
	}

	public function testParseArguments1(): void
	{
		$parser	= new ArgumentParser();
		global $argv;
		$argv = ['a', 'bc', 'def', 'opt1=val1', 'opt2=val2'];
		$parser->parseArguments();
		self::assertEquals( ['bc', 'def'], $parser->get( 'commands' ) );
		self::assertEquals( ['opt1' => 'val1', 'opt2' => 'val2'], $parser->get( 'parameters' ) );
	}

	public function testParseArguments_withShortcut1(): void
	{
		$parser	= new ArgumentParser();
		$parser->addShortCut( 'o', 'opt1');
		global $argv;
		$argv = ['a', 'bc', 'def', 'o=val1', 'opt2=val2'];
		$parser->parseArguments();
		self::assertEquals( ['bc', 'def'], $parser->get( 'commands' ) );
		self::assertEquals( ['opt1' => 'val1', 'opt2' => 'val2'], $parser->get( 'parameters' ) );
	}
}