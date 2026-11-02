<?php
/** @noinspection PhpMultipleClassDeclarationsInspection */

declare( strict_types = 1 );

namespace CeusMedia\CommonTest\Alg\Turing;

use CeusMedia\Common\Alg\Turing\Machine;
use CeusMedia\CommonTest\BaseCase;
use RuntimeException;

/**
 *	TestUnit of Alg\Turing\Machine.
 *	@package		Tests.Alg.Turing
 */
class MachineTest extends BaseCase
{
	public function testInvert(): void
	{
		$transitions	= [
			[['q0', '0'], ['q0', '1', 'r']],
			[['q0', '1'], ['q0', '0', 'r']],
			[['q0', '#'], ['f', '#', 'n']],
		];
		$machine	= new Machine( ['q0', 'f'], ['0', '1', '#'], $transitions, 'q0', '#', ['f'] );
		$this->expectOutputString( '' );
		self::assertSame( '1001', $machine->run( '0110' ) );
	}

	public function testMoveLeftExtendsTape(): void
	{
		$transitions	= [
			[['q0', '1'], ['q1', '1', 'l']],
			[['q1', '#'], ['f', '1', 'n']],
		];
		$machine	= new Machine( ['q0', 'q1', 'f'], ['1', '#'], $transitions, 'q0', '#', ['f'] );
		self::assertSame( '11', $machine->run( '1' ) );
	}

	public function testEndlessLoopException(): void
	{
		$transitions	= [[['q0', '#'], ['q0', '#', 'r']]];
		$machine	= new Machine( ['q0', 'f'], ['#'], $transitions, 'q0', '#', ['f'] );
		$this->expectException( RuntimeException::class );
		$machine->run( '', 50 );
	}
}
