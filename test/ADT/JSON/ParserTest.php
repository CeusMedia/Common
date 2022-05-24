<?php
/**
 *	TestUnit of ADT_JSON_Parser
 *	@package		Tests.adt.json
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 */
declare( strict_types = 1 );

use PHPUnit\Framework\TestCase;
use CeusMedia\Common\ADT\JSON\Parser;

/**
 *	TestUnit of ADT_JSON_Parser
 *	@package		Tests.adt.json
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 */
class Test_ADT_JSON_ParserTest extends Test_Case
{
	public function testParse(){
		$parser	= new ADT_JSON_Parser();

		$info	= (object) array(
			'status'	=> ADT_JSON_Parser::STATUS_EMPTY,
			'code'		=> JSON_ERROR_NONE,
			'constant'	=> 'JSON_ERROR_NONE',
			'message'	=> 'No error',
		);
		$this->assertEquals( $parser->getInfo(), $info );

		$json	= '"a"';
		$info	= (object) array(
			'status'	=> ADT_JSON_Parser::STATUS_PARSED,
			'code'		=> JSON_ERROR_NONE,
			'constant'	=> 'JSON_ERROR_NONE',
			'message'	=> 'No error',
		);
		$this->assertEquals( $parser->parse( $json ), 'a' );
		$this->assertEquals( $parser->getInfo(), $info );
	}

	/**
	 */
	public function testParseException(){
		$this->expectException( 'RuntimeException' );
		$parser	= new ADT_JSON_Parser();
		$json	= '[a';
		$parser->parse( $json );
	}

	public function testParseWithError(){
		$parser	= new ADT_JSON_Parser();
		$json	= '[a';

		try{
			$parser->parse( $json );
		}
		catch( Exception $e ){
			$info	= (object) array(
				'status'	=> ADT_JSON_Parser::STATUS_ERROR,
				'code'		=> JSON_ERROR_SYNTAX,
				'constant'	=> 'JSON_ERROR_SYNTAX',
				'message'	=> 'Syntax error',
			);
			$this->assertEquals( $parser->getInfo(), $info );
		}
	}
}
