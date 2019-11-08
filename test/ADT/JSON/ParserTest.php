<?php
declare(strict_types=1);

/**
 *	TestUnit of ADT_JSON_Parser
 *	@package		Tests.adt.json
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@version		0.1
 */
require_once dirname( dirname( __DIR__ ) ).'/Object.php';

use PHPUnit\Framework\TestCase;
use CeusMedia\Common\ADT\JSON\Parser;

/**
 *	TestUnit of ADT_JSON_Parser
 *	@package		Tests.adt.json
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@version		0.1
 */
final class Test_ADT_JSON_ParserTest extends TestCase
{
	/**
	 *	Constructor.
	 *	@access		public
	 *	@return		void
	 */
	public function __construct()
	{
		$this->object		= new Test_Object();
		$this->object->a	= "test";
	}

	public function testA(): void
	{
	}

	public function testLoad(): void
	{
		$parser	= new Parser();
		$json	= '"a"';
		$this->assertEquals( $parser->parse( $json ), 'a' );
	}
}
?>
