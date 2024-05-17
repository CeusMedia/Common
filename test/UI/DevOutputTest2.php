<?php
declare(strict_types=1);

namespace CeusMedia\CommonTest\UI;

use CeusMedia\Common\UI\DevOutput;
use CeusMedia\CommonTest\BaseCase;

class Test_UI_DevOutput extends BaseCase
{
	/**
	 * @covers		::print_m
	 * @covers		UI_DevOutput::printM
	 */
	public function testPrintM1(){
		self::assertTrue( TRUE );

		new DevOutput();
		$r = print_m( 'a', NUll, NULL, TRUE );
		self::assertEquals( PHP_EOL.'[S] a'.PHP_EOL, $r );

		self::assertEquals( [], [] );

	}
}
