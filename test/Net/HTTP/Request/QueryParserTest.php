<?php
/** @noinspection PhpIllegalPsrClassPathInspection */
/** @noinspection PhpMultipleClassDeclarationsInspection */
/** @noinspection PhpUnhandledExceptionInspection */
/** @noinspection PhpDocMissingThrowsInspection */

declare( strict_types = 1 );

/**
 *	TestUnit of Net_HTTP_Request_QueryParser.
 *	@package		Tests.net.http.request
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 */

namespace CeusMedia\CommonTest\Net\HTTP;

use CeusMedia\Common\Net\HTTP\Request\QueryParser;
use CeusMedia\CommonTest\BaseCase;

/**
 *	TestUnit of Net_HTTP_Request_QueryParser.
 *	@package		Tests.net.http.request
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 */
class QueryParserTest extends BaseCase
{
	/**
	 *	Setup for every Test.
	 *	@access		public
	 *	@return		void
	 */
	public function setUp(): void
	{
	}

	/**
	 *	Cleanup after every Test.
	 *	@access		public
	 *	@return		void
	 */
	public function tearDown(): void
	{
	}

	/**
	 *	Tests Exception of Method 'toArray'.
	 *	@access		public
	 *	@return		void
	 */
	public function test_toArray_withInvalid_expectInvalidArgumentException()
	{
		$this->expectException( 'InvalidArgumentException' );
		QueryParser::toArray( "=" );
	}

	/**
	 *	Tests Exception of Method 'toArray'.
	 *	@access		public
	 *	@return		void
	 */
	public function testToArrayException2()
	{
		$this->expectException( 'InvalidArgumentException' );
		QueryParser::toArray( "&a=123&=" );
	}

	/**
	 *	Tests Exception of Method 'toArray'.
	 *	@access		public
	 *	@return		void
	 */
	public function testToArrayException3()
	{
		$this->expectException( 'InvalidArgumentException' );
		QueryParser::toArray( "&a=321&=123" );
	}

	/**
	 *	Tests Exception of Method 'toArray'.
	 *	@access		public
	 *	@return		void
	 */
	public function testToArrayException4()
	{
		$this->expectException( 'InvalidArgumentException' );
		QueryParser::toArray( "a,321;,123", ";", "," );
	}

	/**
	 *	Tests Method 'toArray'.
	 *	@access		public
	 *	@return		void
	 */
	public function testToArray0()
	{
		$query		= "";
		$assertion	= array();
		$creation	= QueryParser::toArray( $query );
		$this->assertEquals( $assertion, $creation );

		$query		= "&&&";
		$assertion	= array();
		$creation	= QueryParser::toArray( $query );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'toArray'.
	 *	@access		public
	 *	@return		void
	 */
	public function testToArray1()
	{
		$query		= "a=word&b=123&c=&d";
		$assertion	= array(
			'a'	=> "word",
			'b'	=> 123,
			'c'	=> "",
			'd'	=> NULL,
		);
		$creation	= QueryParser::toArray( $query );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'toArray'.
	 *	@access		public
	 *	@return		void
	 */
	public function testToArray2()
	{
		$query		= "a,word;b,123;c,;d";
		$assertion	= array(
			'a'	=> "word",
			'b'	=> 123,
			'c'	=> "",
			'd'	=> NULL,
		);
		$creation	= QueryParser::toArray( $query, ";", "," );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'toArray'.
	 *	@access		public
	 *	@return		void
	 */
	public function testToArray3()
	{
		$query		= "none&short=*&long=a-z0-9._:#";
		$assertion	= array(
			'none'	=> NULL,
			'short'	=> "*",
			'long'	=> "a-z0-9._:#",
		);
		$creation	= QueryParser::toArray( $query );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'toArray'.
	 *	@access		public
	 *	@return		void
	 */
	public function testToArray4()
	{
		$query		= "array[]=1&array[]=2&array[]=";
		$assertion	= array(
			'array'	=> array(
				"1",
				"2",
				""
			),
		);
		$creation	= QueryParser::toArray( $query );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'toArray'.
	 *	@access		public
	 *	@return		void
	 */
	public function testToArray5()
	{
		$query		= "array[]=1&array[]=2&array[]";
		$assertion	= array(
			'array'	=> array(
				"1",
				"2",
				NULL,
			)
		);
		$creation	= QueryParser::toArray( $query );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'toArray'.
	 *	@access		public
	 *	@return		void
	 */
	public function testToArray6()
	{
		$query		= "array[];array[],1;array[],2;array[],";
		$assertion	= array(
			'array'	=> array(
				NULL,
				"1",
				"2",
				"",
			)
		);
		$creation	= QueryParser::toArray( $query, ";", "," );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'toArray'.
	 *	@access		public
	 *	@return		void
	 */
	public function testToArray7()
	{
		$query		= "__key1&..key2&--key3=test&::key4=123";
		$assertion	= array(
			'__key1'	=> NULL,
			'..key2'	=> NULL,
			'--key3'	=> "test",
			'::key4'	=> "123",
		);
		$creation	= QueryParser::toArray( $query );
		$this->assertEquals( $assertion, $creation );
	}
}
