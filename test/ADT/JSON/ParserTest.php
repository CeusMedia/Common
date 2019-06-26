<?php
/**
 *	TestUnit of ADT_JSON_Parser
 *	@package		Tests.adt.json
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@version		0.1
 */
require_once dirname( dirname( __DIR__ ) ).'/initLoaders.php';
/**
 *	TestUnit of ADT_JSON_Parser
 *	@package		Tests.adt.json
 *	@extends		Test_Case
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@version		0.1
 */
class Test_ADT_JSON_ParserTest extends Test_Case
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

	public function testLoad(){
		$parser	= new ADT_JSON_Parser();
		$json	= '"a"';
		$this->assertEquals( $parser->parse( $json ), 'a' );
	}
}
?>
