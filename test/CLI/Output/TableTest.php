<?php
/** @noinspection PhpIllegalPsrClassPathInspection */
/** @noinspection PhpMultipleClassDeclarationsInspection */
/** @noinspection PhpUnhandledExceptionInspection */
/** @noinspection PhpDocMissingThrowsInspection */

declare( strict_types = 1 );

/**
 *	TestUnit of CLI_Output_Table.
 *	@package		Tests.CLI.Output
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 */

namespace CeusMedia\CommonTest\CLI\Output;

use CeusMedia\Common\CLI\Output\Table;
use CeusMedia\CommonTest\BaseCase;

/**
 *	TestUnit of CLI_Output_Table.
 *	@package		Tests.CLI.Output
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 */
class TableTest extends BaseCase
{
	protected Table $table;

	/**
	 *    Setup for every Test.
	 * @access        public
	 * @return        void
	 */
	public function setUp(): void
	{
		$this->table	= new Table();
		$this->table->setData([[
			'a'		=> 1,
			'b'		=> 2,
		],[
			'a'		=> 3,
			'b'		=> 2,
		],
		]);
	}

	/**
	 *    Cleanup after every Test.
	 * @access        public
	 * @return        void
	 */
	public function tearDown(): void
	{
	}

	public function testRenderBorderSingle(): void
	{
		$actual		= $this->table->render();
		$expected	= <<<EOT
┌───┬───┐
│ a │ b │
├───┼───┤
│ 1 │ 2 │
├───┼───┤
│ 3 │ 2 │
└───┴───┘
EOT . PHP_EOL;
		$this->assertEquals( $expected, $actual );
	}

	public function testRenderBorderNone(): void
	{

		$this->table->setBorderStyle( Table::BORDER_STYLE_NONE );
		$actual		= $this->table->render();
		$expected	= <<<EOT
 a   b 
 1   2 
 3   2 
EOT . PHP_EOL;
		$this->assertEquals( $expected, $actual );
	}

	public function testRenderBorderDouble(): void
	{
		$this->table->setBorderStyle( Table::BORDER_STYLE_DOUBLE );
		$actual		= $this->table->render();
		$expected	= <<<EOT
╔═══╦═══╗
║ a ║ b ║
╠═══╬═══╣
║ 1 ║ 2 ║
╠═══╬═══╣
║ 3 ║ 2 ║
╚═══╩═══╝
EOT . PHP_EOL;
		$this->assertEquals( $expected, $actual );
	}

	public function testRenderBorderMixed(): void
	{
		$this->table->setBorderStyle( Table::BORDER_STYLE_MIXED );
		$actual		= $this->table->render();
		$expected	= <<<EOT
╔═══╤═══╗
║ a │ b ║
╟───┼───╢
║ 1 │ 2 ║
╟───┼───╢
║ 3 │ 2 ║
╚═══╧═══╝
EOT . PHP_EOL;
		$this->assertEquals( $expected, $actual );
	}

	public function testRenderBorderMixedSizeMax(): void
	{
		$this->table->setBorderStyle( Table::BORDER_STYLE_MIXED );
		$this->table->setSizeMode( Table::SIZE_MODE_MAX );
		$actual		= $this->table->render();
		$expected	= <<<EOT
╔════════════════════════════════════╤════════════════════════════════════╗
║                                  a │                                  b ║
╟────────────────────────────────────┼────────────────────────────────────╢
║                                  1 │                                  2 ║
╟────────────────────────────────────┼────────────────────────────────────╢
║                                  3 │                                  2 ║
╚════════════════════════════════════╧════════════════════════════════════╝
EOT . PHP_EOL;
		$this->assertEquals( $expected, $actual );
	}
}
