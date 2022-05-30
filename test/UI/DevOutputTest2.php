<?php
declare(strict_types=1);

use CeusMedia\Common\Test\BaseCase;

class Test_UI_DevOutput extends TestCase
{
	/**
	 * @covers		::print_m
	 * @covers		UI_DevOutput::printM
	 */
	public function testPrintM1(){
		$this->assertTrue( TRUE );

		new UI_DevOutput();
		$r = print_m( 'a', NUll, NULL, TRUE );
		$this->assertEquals( PHP_EOL.'[S] a'.PHP_EOL, $r );

		$this->assertEquals( [], [] );

	}
}
