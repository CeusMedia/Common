<?php
/**
 *	TestUnit of CLI_Output_Table.
 *	@package		Tests.CLI.Output
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 */
declare( strict_types = 1 );

namespace CeusMedia\Common\Test\CLI\Output;

use CeusMedia\Common\CLI\Output\Table;
use CeusMedia\Common\Test\BaseCase;

/**
 *	TestUnit of CLI_Output_Table.
 *	@package		Tests.CLI.Output
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 */
class TableTest extends BaseCase
{
	protected $table;

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

	public function testRenderBorderSingle()
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

	public function testRenderBorderNone()
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

	public function testRenderBorderDouble()
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

	public function testRenderBorderMixed()
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

	public function testRenderBorderMixedSizeMax()
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
