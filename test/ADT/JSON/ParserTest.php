<?php
/** @noinspection PhpIllegalPsrClassPathInspection */
/** @noinspection PhpMultipleClassDeclarationsInspection */
/** @noinspection PhpUnhandledExceptionInspection */
/** @noinspection PhpDocMissingThrowsInspection */

declare( strict_types = 1 );

/**
 *	@package		Tests.adt.json
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 */

namespace CeusMedia\CommonTest\ADT\JSON;

use CeusMedia\Common\ADT\JSON\Parser;
use CeusMedia\CommonTest\BaseCase;
use Exception;

/**
 *	@package		Tests.adt.json
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 */
class ParserTest extends BaseCase
{
	public function testParse()
	{
		$parser	= new Parser();

		$info	= (object) array(
			'status'	=> Parser::STATUS_EMPTY,
			'code'		=> JSON_ERROR_NONE,
			'constant'	=> 'JSON_ERROR_NONE',
			'message'	=> 'No error',
		);
		$this->assertEquals( $info, $parser->getInfo() );

		$json	= '"a"';
		$info	= (object) array(
			'status'	=> Parser::STATUS_PARSED,
			'code'		=> JSON_ERROR_NONE,
			'constant'	=> 'JSON_ERROR_NONE',
			'message'	=> 'No error',
		);
		$this->assertEquals( 'a', $parser->parse( $json ) );
		$this->assertEquals( $info, $parser->getInfo() );
	}

	/**
	 */
	public function testParseException()
	{
		$this->expectException( 'RuntimeException' );
		$parser	= new Parser();
		$json	= '[a';
		$parser->parse( $json );
	}

	public function testParseWithError()
	{
		$parser	= new Parser();
		$json	= '[a';

		try{
			$parser->parse( $json );
		}
		catch( Exception $e ){
			$info	= (object) array(
				'status'	=> Parser::STATUS_ERROR,
				'code'		=> JSON_ERROR_SYNTAX,
				'constant'	=> 'JSON_ERROR_SYNTAX',
				'message'	=> 'Syntax error',
			);
			$this->assertEquals( $parser->getInfo(), $info );
		}
	}
}
