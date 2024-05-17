<?php
/** @noinspection PhpIllegalPsrClassPathInspection */
/** @noinspection PhpMultipleClassDeclarationsInspection */
/** @noinspection PhpUnhandledExceptionInspection */
/** @noinspection PhpDocMissingThrowsInspection */

declare( strict_types = 1 );

/**
 *	TestUnit of CLI_Output.
 *	@package		Tests.CLI
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 */

namespace CeusMedia\CommonTest\CLI;

use CeusMedia\Common\CLI\Output;
use CeusMedia\CommonTest\BaseCase;

/**
 *	TestUnit of CLI_Output.
 *	@package		Tests.CLI
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 */
class OutputTest extends BaseCase
{
	public function testAppend1(): void
	{
		$output	= new Output();
		$this->expectOutputString( "\rtest" );
		$output->append( 'test' );
	}

	public function testAppend2(): void
	{
		$output	= new Output();
		$this->expectOutputString( "\rabc\rabcXYZ" );
		$output->append( 'abc' );
		$output->append( 'XYZ' );
	}

	public function testNewLine1(): void
	{
		$output	= new Output();
		$this->expectOutputString( PHP_EOL.'test' );
		$output->newLine( 'test' );
	}

	public function testNewLine_withMaxLineLength1(): void
	{
		$output	= new Output();
		$output->setMaxLineLength( 20 );
		$this->expectOutputString( PHP_EOL."12345678901234567890" );
		$output->newLine( '12345678901234567890' );
	}

	public function testNewLine_withMaxLineLength2(): void
	{
		$output	= new Output();
		$output->setMaxLineLength( 20 );
		$this->expectOutputString( PHP_EOL."123456789...34567890" );
		$output->newLine( '1234567890ABC1234567890' );
	}

	public function testNewLine_withMaxLineLength3(): void
	{
		$output	= new Output();
		$output->setMaxLineLength( 20 );
		$this->expectOutputString( PHP_EOL."123456789...34567890".PHP_EOL."1234" );
		$output->newLine( '1234567890ABC1234567890' );
		$output->newLine( '1234' );
	}

	public function testSameLine1(): void
	{
		$output	= new Output();
		$this->expectOutputString( "\rtest" );
		$output->sameLine( 'test' );
	}

	public function testSameLine2(): void
	{
		$output	= new Output();
		$this->expectOutputString( "\rabc\rXYZ" );
		$output->sameLine( 'abc' );
		$output->sameLine( 'XYZ' );
	}

	public function testSameLine3(): void
	{
		$output	= new Output();
		$this->expectOutputString( "\r123456\rabc   " );
		$output->sameLine( '123456' );
		$output->sameLine( 'abc   ' );
	}

	public function testSameLine_withMaxLineLength1(): void
	{
		$output	= new Output();
		$output->setMaxLineLength( 20 );
		$this->expectOutputString( "\r12345678901234567890" );
		$output->sameLine( '12345678901234567890' );
	}

	public function testSameLine_withMaxLineLength2(): void
	{
		$output	= new Output();
		$output->setMaxLineLength( 20 );
		$this->expectOutputString( "\r123456789...34567890" );
		$output->sameLine( '1234567890ABC1234567890' );
	}

	public function testSameLine_withMaxLineLength3(): void
	{
		$output	= new Output();
		$output->setMaxLineLength( 20 );
		$this->expectOutputString( "\r123456789...34567890\r1234                " );
		$output->sameLine( '1234567890ABC1234567890' );
		$output->sameLine( '1234' );
	}
}
